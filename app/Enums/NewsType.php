<?php

namespace App\Enums;

enum NewsType: string
{
    case News = '1';
    case Event = '2';
    case Patch = '3';
    case Promotion = '4';
    case Announcement = '5';
    case Maintenance = '6';
}
