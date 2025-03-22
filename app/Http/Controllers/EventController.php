<?php

namespace App\Http\Controllers;

use App\Service\FlyffConfigParserService;
use Illuminate\Http\JsonResponse;

class EventController extends Controller
{
    protected $configParser;

    public function __construct(FlyffConfigParserService $configParser)
    {
        $this->configParser = $configParser;
    }

    public function getEvents(): JsonResponse
    {
        $events = $this->configParser->getAllEvents();
        return response()->json($events);
    }
}
