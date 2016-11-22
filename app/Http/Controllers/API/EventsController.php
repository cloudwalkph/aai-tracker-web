<?php
namespace App\Http\Controllers\API;

use App\AAI\Services\EventsService;
use App\Http\Controllers\Controller;

class EventsController extends Controller {
    protected $eventService;

    public function __construct(EventsService $eventService)
    {
        $this->eventService = $eventService;
    }

    public function getEvents()
    {
        return $this->eventService->getFullEvents();
    }
}