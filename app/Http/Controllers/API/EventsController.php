<?php
namespace App\Http\Controllers\API;

use App\AAI\Services\EventsService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EventsController extends Controller {
    protected $eventService;

    public function __construct(EventsService $eventService)
    {
        $this->eventService = $eventService;
    }

    public function getEvents(Request $request)
    {
        $response = [
            'data'      => $this->eventService->getFullEvents(),
            'status'    => 200
        ];

        return response()->json($response, 200);
    }
}