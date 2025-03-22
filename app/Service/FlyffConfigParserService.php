<?php

namespace App\Service;

use Carbon\Carbon;

class FlyffConfigParserService
{
    /**
     * Parse all events from Flyff config files.
     *
     * @return array
     */
    public function getAllEvents(): array
    {
        $events = [];

        // Parse all event types
        $events = array_merge(
            $events,
            $this->getGuildSiegeEvents(),
            $this->getTDMEvents(),
            $this->getFFAEvents(),
            $this->getSecretRoomEvents()
        );

        // Sort all events by next occurrence
        usort($events, function ($a, $b) {
            return strcmp($a['next'], $b['next']);
        });

        return [
            'events' => $events,
            'pkSettings' => $this->getPKSettings()
        ];
    }

    public function getGuildSiegeEvents(): array
    {
        $events = [];
        $guildConfig = file_get_contents(storage_path('flyff/GuildCombat.txt'));
        
        // Parse basic requirements as before...
        
        // Set game server timezone (adjust this to match your game server)
        $gameServerTimezone = 'Europe/Paris'; // or 'Europe/Paris' for France
        
        // Parse event schedule
        preg_match_all('/AUTO_OPEN_IDC\s*{([^}]+)}/s', $guildConfig, $matches);
        if (isset($matches[1][0])) {
            $lines = explode("\n", trim($matches[1][0]));
            foreach ($lines as $line) {
                $parts = preg_split('/\s+/', trim($line));
                if (count($parts) >= 3 && is_numeric($parts[0])) {
                    $day = (int)$parts[0];
                    $hour = (int)$parts[1];
                    $minute = (int)$parts[2];
                    
                    // Create the base event time
                    $registrationTime = Carbon::now()->startOfWeek()
                        ->addDays($day - 1)
                        ->setHour($hour)
                        ->setMinute($minute)
                        ->setSecond(0);
                    
                    // Add preparation time (5 min) and maintenance time (1 min)
                    $battleStartTime = $registrationTime->copy()->addMinutes(6);
                    
                    // Convert to game server timezone 
                    $gameServerTime = $battleStartTime->copy()->setTimezone($gameServerTimezone);
                    
                    // Get next occurrence
                    $nextOccurrence = $this->getNextOccurrence([
                        'day' => $day,
                        'hour' => $hour,
                        'minute' => $minute
                    ]);
                    
                    // Add preparation + maintenance time and convert to game server timezone
                    $nextBattleStart = $nextOccurrence->copy()
                        ->addHours(2);
                    
                    $events[] = [
                        'type' => 'Guild Siege',
                        'time' => sprintf('%02d:%02d', $hour, $minute),
                        'battleTime' => sprintf('%02d:%02d', $battleStartTime->hour, $battleStartTime->minute),
                        'gameServerTime' => $gameServerTime->format('H:i'),
                        'next' => $nextBattleStart->toIso8601String(),
                        'day' => $this->getDayName($day),
                        'eventType' => 'siege',
                        'requirements' => [
                            'level' => 'Guild Lv.' . ($levelMatch[1] ?? '10') . '+',
                            'fee' => $this->formatPenya((int)($penyaMatch[1] ?? 100000000)),
                            'players' => ($minSizeMatch[1] ?? '2') . '-' . ($sizeMatch[1] ?? '15') . ' guilds',
                            'memberSize' => 'Max ' . ($memberSizeMatch[1] ?? '10') . ' members per guild',
                            'warPlayers' => 'Max ' . ($warPlayerMatch[1] ?? '10') . ' active players',
                            'lives' => ($livesMatch[1] ?? '10') . ' lives',
                            'respawn' => ($respawnMatch[1] ?? '15') . ' seconds',
                        ]
                    ];
                }
            }
        }
    
        return $events;
    }

/**
 * Get the next occurrence of a specific weekday and time.
 *
 * @param int $day Weekday (1-7 for Monday-Sunday)
 * @param int $hour Hour (0-23)
 * @param int $minute Minute (0-59)
 * @return Carbon
 */
protected function getNextWeekdayOccurrence(int $day, int $hour, int $minute): Carbon
{
    $now = Carbon::now();
    $date = $now->copy()->startOfWeek()->addDays($day - 1)
        ->setHour($hour)->setMinute($minute)->setSecond(0);
    
    if ($date->lte($now)) {
        $date->addWeek();
    }
    
    return $date;
}


    public function getPKSettings(): array
{
    $pkConfig = file_get_contents(storage_path('flyff/PKSetting.txt'));

    // Parse PK settings by directly finding the values in the config file
    preg_match('/PKTIMEOUT\s+(\d+)/i', $pkConfig, $timeoutMatch);
    preg_match('/PKDURATION\s+(\d+)/i', $pkConfig, $durationMatch);
    preg_match('/PKEXPRATEMULTIPLIER\s+(\d+\.?\d*)/i', $pkConfig, $expMatch);
    preg_match('/PKDROPRATEMULTIPLIER\s+(\d+\.?\d*)/i', $pkConfig, $dropMatch);

    $timeout = (int)($timeoutMatch[1] ?? 7200);
    $duration = (int)($durationMatch[1] ?? 3600);

    // Calculate next available PK time
    $now = now();
    $nextPK = $this->calculateNextPKTime($now, $timeout, $duration);

    return [
        'type' => 'PK Mode',
        'eventType' => 'pk',
        'settings' => [
            'timeout' => $this->formatDuration($timeout),
            'duration' => $this->formatDuration($duration),
            'expMultiplier' => number_format((float)($expMatch[1] ?? 2.5), 1) . 'x',
            'dropMultiplier' => number_format((float)($dropMatch[1] ?? 1.5), 1) . 'x'
        ],
        'next' => $nextPK->toIso8601String(),
        'isActive' => $this->isPKActive($now, $timeout, $duration),
        'timeoutSeconds' => $timeout,
        'durationSeconds' => $duration
    ];
}



    public function getSecretRoomEvents(): array
    {
        $events = [];
        $srConfig = file_get_contents(storage_path('flyff/SecretRoom.txt'));

        // Parse basic requirements
        preg_match('/MinGuildLevel=(\d+)/', $srConfig, $levelMatch);
        preg_match('/MinGuildMemberLevel=(\d+)/', $srConfig, $memberLevelMatch);
        preg_match('/MinPenya=\s*(\d+)/', $srConfig, $penyaMatch);
        preg_match('/MinGuildMemberNum=(\d+)/', $srConfig, $minMembersMatch);
        preg_match('/MaxGuildMemberNum=(\d+)/', $srConfig, $maxMembersMatch);

        // Parse time settings
        preg_match('/OpenTime=\s*"([A-Za-z]+)\s+(\d+):(\d+)"/', $srConfig, $timeMatch);

        if (isset($timeMatch[1], $timeMatch[2], $timeMatch[3])) {
            $dayMap = [
                'Sun' => 7,
                'Mon' => 1,
                'Tue' => 2,
                'Wed' => 3,
                'Thu' => 4,
                'Fri' => 5,
                'Sat' => 6
            ];

            $day = $dayMap[$timeMatch[1]] ?? 1;
            $nextOccurrence = $this->getNextOccurrence([
                'day' => $day,
                'hour' => (int)$timeMatch[2],
                'minute' => (int)$timeMatch[3]
            ]);

            $events[] = [
                'type' => 'Secret Room',
                'time' => sprintf('%02d:%02d', $timeMatch[2], $timeMatch[3]),
                'next' => $nextOccurrence->toIso8601String(),
                'day' => $this->getDayName($day),
                'eventType' => 'secretroom',
                'requirements' => [
                    'level' => 'Guild Lv.' . ($levelMatch[1] ?? '4') . '+',
                    'memberLevel' => 'Lv.' . ($memberLevelMatch[1] ?? '30') . '+',
                    'fee' => $this->formatPenya((int)($penyaMatch[1] ?? 100000000)),
                    'players' => sprintf(
                        '%d-%d members',
                        $minMembersMatch[1] ?? 1,
                        $maxMembersMatch[1] ?? 11
                    )
                ],
                'boss' => 'Lucifer',
                'duration' => '60 minutes'
            ];
        }

        return $events;
    }

 /**
 * Get TDM events from config file.
 *
 * @return array
 */
public function getTDMEvents(): array
{
    $events = [];
    $tdmConfig = file_get_contents(storage_path('flyff/TDMManager.inc'));

    // Parse TDM configuration values
    preg_match('/TDM_COST\s+(\d+)/', $tdmConfig, $costMatch);
    preg_match('/TDM_MIN_LEVEL\s+(\d+)/', $tdmConfig, $levelMatch);
    preg_match('/TDM_MAX_MEMBERS\s+(\d+)/', $tdmConfig, $maxMembersMatch);
    preg_match('/TDM_MIN_MEMBERS\s+(\d+)/', $tdmConfig, $minMembersMatch);
    preg_match('/TDM_ANNOUNCE_TIME\s+(\d+)/', $tdmConfig, $announceTimeMatch);
    preg_match('/TDM_WAR_TIME\s+(\d+)/', $tdmConfig, $warTimeMatch);
    preg_match('/TDM_LIFES\s+(\d+)/', $tdmConfig, $livesMatch);

    // Get the announcement time (default to 5 minutes if not found)
    $announceTime = (int)($announceTimeMatch[1] ?? 5);

    // Parse TDM event schedule
    preg_match_all('/TDM_BEGIN\s+(\d+)\s+(\d+)\s+(\d+)/', $tdmConfig, $matches, PREG_SET_ORDER);

    foreach ($matches as $match) {
        // Get basic event time
        $day = (int)$match[1];
        $hour = (int)$match[2];
        $minute = (int)$match[3];

        // Create Carbon instance for manipulation
        $eventTime = Carbon::now()->startOfWeek()->addDays($day - 1);
        $eventTime->setHour($hour);
        $eventTime->setMinute($minute);
        
        // Add the announcement time to get the actual start time
        $actualEventTime = $eventTime->copy()->addMinutes($announceTime);
        
        // Get the next occurrence of this event
        $nextOccurrence = $this->getNextOccurrence([
            'day' => $day,
            'hour' => (int)$actualEventTime->format('H'),
            'minute' => (int)$actualEventTime->format('i')
        ]);

        $events[] = [
            'type' => 'Team Death Match',
            'time' => sprintf('%02d:%02d', $actualEventTime->format('H'), $actualEventTime->format('i')),
            'registrationTime' => sprintf('%02d:%02d', $hour, $minute),
            'next' => $nextOccurrence->toIso8601String(),
            'day' => $this->getDayName($day),
            'eventType' => 'tdm',
            'requirements' => [
                'level' => 'Lv.' . ($levelMatch[1] ?? '120') . '+',
                'fee' => $this->formatPenya((int)($costMatch[1] ?? 10000000)),
                'players' => sprintf(
                    '%d-%d players',
                    $minMembersMatch[1] ?? 2,
                    $maxMembersMatch[1] ?? 30
                ),
                'duration' => ($warTimeMatch[1] ?? 10) . ' minutes',
                'lives' => ($livesMatch[1] ?? 10) . ' lives'
            ]
        ];
    }

    return $events;
}

    public function getFFAEvents(): array
    {
        $events = [];
        $ffaConfig = file_get_contents(storage_path('flyff/FFAManager.inc'));
    
        // Parse FFA configuration values
        preg_match('/FFA_COST\s+(\d+)/', $ffaConfig, $costMatch);
        preg_match('/FFA_MIN_LEVEL\s+(\d+)/', $ffaConfig, $levelMatch);
        preg_match('/FFA_MAX_MEMBERS\s+(\d+)/', $ffaConfig, $maxMembersMatch);
        preg_match('/FFA_MIN_MEMBERS\s+(\d+)/', $ffaConfig, $minMembersMatch);
        preg_match('/FFA_WAR_TIME\s+(\d+)/', $ffaConfig, $warTimeMatch);
        preg_match('/FFA_LIFES\s+(\d+)/', $ffaConfig, $livesMatch);
        preg_match('/FFA_ANNOUNCE_TIME\s+(\d+)/', $ffaConfig, $announceTimeMatch);
        
        $announceTime = (int)($announceTimeMatch[1] ?? 5);
    
        // Parse FFA event schedule
        preg_match_all('/FFA_BEGIN\s+(\d+)\s+(\d+)\s+(\d+)/', $ffaConfig, $matches, PREG_SET_ORDER);
    
        foreach ($matches as $match) {
            // Create a date object for the event time
            $eventTime = Carbon::now()->startOfWeek()->addDays((int)$match[1] - 1);
            $eventTime->setHour((int)$match[2]);
            $eventTime->setMinute((int)$match[3]);
            
            // Add the announcement time to get the actual start time
            $eventTime->addMinutes($announceTime);
            
            $nextOccurrence = $this->getNextOccurrence([
                'day' => (int)$match[1],
                'hour' => (int)$eventTime->format('H'),
                'minute' => (int)$eventTime->format('i')
            ]);
    
            $events[] = [
                'type' => 'Free For All',
                'time' => sprintf('%02d:%02d', $eventTime->format('H'), $eventTime->format('i')),
                'next' => $nextOccurrence->toIso8601String(),
                'day' => $this->getDayName((int)$match[1]),
                'eventType' => 'ffa',
                'requirements' => [
                    'level' => 'Lv.' . ($levelMatch[1] ?? '120') . '+',
                    'fee' => $this->formatPenya((int)($costMatch[1] ?? 10000000)),
                    'players' => sprintf(
                        '%d-%d players',
                        $minMembersMatch[1] ?? 2,
                        $maxMembersMatch[1] ?? 30
                    ),
                    'duration' => ($warTimeMatch[1] ?? 10) . ' minutes',
                    'lives' => ($livesMatch[1] ?? 10) . ' lives'
                ]
            ];
        }
    
        return $events;
    }



    /**
     * Calculate the next occurrence of an event.
     *
     * @param array $event
     * @return Carbon
     */
    protected function getNextOccurrence(array $event): Carbon
    {
        $now = now();
        $eventDay = $event['day']; // 1-7 (Monday-Sunday)

        $eventDate = $now->copy()->startOfWeek()
            ->addDays($eventDay - 1)
            ->setTime($event['hour'], $event['minute']);

        if ($eventDate->lt($now)) {
            $eventDate->addWeek();
        }

        return $eventDate;
    }

    /**
     * Get day name from number.
     *
     * @param int $day
     * @return string
     */
    protected function getDayName(int $day): string
    {
        $days = [
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
            7 => 'Sunday'
        ];

        return $days[$day] ?? '';
    }



    protected function formatDuration(int $seconds): string
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);

        if ($hours > 0) {
            return "{$hours}h" . ($minutes > 0 ? " {$minutes}m" : '');
        }
        return "{$minutes}m";
    }

    /**
     * Format penya amount to readable string.
     *
     * @param int $amount
     * @return string
     */
    protected function formatPenya(int $amount): string
    {
        if ($amount >= 1000000) {
            return ($amount / 1000000) . 'M Penya';
        } elseif ($amount >= 1000) {
            return ($amount / 1000) . 'K Penya';
        }
        return $amount . ' Penya';
    }




    protected function calculateNextPKTime(Carbon $now, int $timeout, int $duration): Carbon
    {
        $startOfDay = $now->copy()->startOfDay();
        $currentTime = $now->copy();

        // Find the next available PK time
        while ($startOfDay->lte($now->copy()->endOfDay())) {
            if ($startOfDay->gt($currentTime)) {
                return $startOfDay;
            }
            $startOfDay->addSeconds($timeout + $duration);
        }

        // If no time found today, get first slot tomorrow
        return $now->copy()->addDay()->startOfDay();
    }

    protected function isPKActive(Carbon $now, int $timeout, int $duration): bool
    {
        $startOfDay = $now->copy()->startOfDay();
        $currentTime = $now->copy();

        while ($startOfDay->lte($now->copy()->endOfDay())) {
            $pkEndTime = $startOfDay->copy()->addSeconds($duration);

            if ($currentTime->between($startOfDay, $pkEndTime)) {
                return true;
            }

            $startOfDay->addSeconds($timeout + $duration);
        }

        return false;
    }
}
