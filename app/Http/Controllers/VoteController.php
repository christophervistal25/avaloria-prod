<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Account;
use App\Models\VoteLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class VoteController extends Controller
{
    // Add new method to initiate voting process
    public function initiateVote(Request $request)
    {
        $request->validate([
            'account' => 'required|string|exists:ACCOUNT_DBF.dbo.ACCOUNT_TBL,account',
        ]);

        $account = $request->input('account');
        $voterIP = $request->ip();

        // Check if the user or IP has voted within the cooldown period
        $lastVote = VoteLog::where(function ($query) use ($account, $voterIP) {
            $query->where('account', $account)
                ->orWhere('ip_address', $voterIP);
        })
            ->where('voted_at', '>=', now()->subSeconds(config('voting.vote_interval')))
            ->first();

        if ($lastVote) {
            $nextVoteTime = $lastVote->voted_at->addSeconds(config('voting.vote_interval'));
            $timeRemaining = now()->diffForHumans($nextVoteTime, ['parts' => 2]);

            return response()->json([
                'status' => 'cooldown',
                'message' => 'You can vote again in ' . $timeRemaining,
                'next_vote' => $nextVoteTime
            ], 429);
        }

        // Generate a unique vote token and store in session
        $voteToken = md5($account . time());
        Session::put('vote_token', $voteToken);
        Session::put('voting_account', $account);
        Session::put('vote_initiated_at', now()->timestamp);

        // Get GTOP vote URL with the username
        $voteUrl = config('voting.gtop_url') . 'pingUsername=' . $account;

        return response()->json([
            'status' => 'success',
            'message' => 'Vote initiated',
            'vote_url' => $voteUrl,
            'token' => $voteToken
        ]);
    }

    // Add a method to check vote status
    public function checkVoteStatus(Request $request)
    {
        $account = Session::get('voting_account');
        if (!$account) {
            return response()->json(['status' => 'not_initiated']);
        }

        // Check if a vote has been processed recently for this account
        $recentVote = VoteLog::where('account', $account)
            ->where('voted_at', '>=', now()->subMinutes(5))
            ->first();

        if ($recentVote) {
            // Clear session data as vote is complete
            Session::forget(['vote_token', 'voting_account', 'vote_initiated_at']);

            return response()->json([
                'status' => 'completed',
                'message' => 'Vote successful! You earned ' . config('voting.vote_increment') . ' vote points!'
            ]);
        }

        // Check if vote has timed out
        $initiatedAt = Session::get('vote_initiated_at');
        if ($initiatedAt && (now()->timestamp - $initiatedAt) > 300) { // 5 minute timeout
            Session::forget(['vote_token', 'voting_account', 'vote_initiated_at']);
            return response()->json([
                'status' => 'timeout',
                'message' => 'Vote session timed out. Please try again.'
            ]);
        }

        return response()->json(['status' => 'pending']);
    }

    public function processVote(Request $request)
    {
        try {
            $request->validate([
                'pingbackkey' => 'required|string',
                'pingUsername' => 'required|string|alpha_num|max:50',
                'Successful' => 'required|integer|in:0,1',
            ]);

            // Retrieve pingback key from environment
            $pingbackkey = 'base64:M3T0OqEz3qqgMp68ZrKnCbp2lxYDjWI9XXJSSi7QHl';

            // Verify pingback key
            if ($request->input('pingbackkey') !== $pingbackkey) {
                Log::error("Invalid pingback key provided.");
                return response()->json(['error' => 'Invalid pingback key.'], 403);
            }

            // Retrieve the username
            $pingUsername = $request->input('pingUsername');

            // Find the user account
            $account = Account::where('account', $pingUsername)->first();

            if (!$account) {
                // Log::error("User not found: " . substr($pingUsername, 0, 3) . "***");
                Log::error("User not found: " . $pingUsername);
                return response()->json(['error' => 'User not found.'], 404);
            }

            // Retrieve the voter's IP address
            $voterIP = $request->ip();

            // Check if the user or IP has voted within the last 5 hours
            $lastVote = VoteLog::where(function ($query) use ($pingUsername, $voterIP) {
                $query->where('account', $pingUsername)
                    ->orWhere('ip_address', $voterIP);
            })
                ->where('voted_at', '>=', now()->subSeconds(config('voting.vote_interval')))
                ->first();

            if ($lastVote) {
                Log::warning("User or IP has already voted within the last 12 hours: " . substr($pingUsername, 0, 3) . "***" . " - " . $voterIP);
                return response()->json(['error' => 'You can only vote once every 12 hours.'], 429); // 429 Too Many Requests
            }

            // Check if the vote was successful
            $success = (int)$request->input('Successful');
            if ($success === 0) {
                // Increment votepoint using the value from the config file
                $account->increment('votepoint', config('voting.vote_increment'));

                // Log the vote
                VoteLog::create([
                    'account' => $pingUsername,
                    'ip_address' => $voterIP,
                    'voted_at' => now(),
                ]);

                // Log the successful vote
                Log::info("Vote processed successfully for account: " . substr($pingUsername, 0, 3) . "***" . " - " . $voterIP);

                return response()->json(['message' => 'Vote processed successfully.'], 200);
            } else {
                // Log the failed vote
                Log::warning("Vote failed for account: " . substr($pingUsername, 0, 3) . "***");

                return response()->json(['error' => 'Vote failed.'], 400);
            }
        } catch (Exception $e) {
            dd($e->getMessage());
            // Log the exception
            Log::error("An error occurred while processing the vote: " . $e->getMessage());

            // Return a generic error response
            return response()->json(['error' => 'An internal server error occurred.'], 500);
        }
    }
}
