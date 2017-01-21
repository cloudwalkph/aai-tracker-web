<?php
namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventLocation;

class EventsController extends Controller {
    public function index() {
        config(['app.name' => 'Events | Insite Management']);

        $events = Event::all();

        return view('management.events.index')->with('events', $events);
    }

    public function showLocations($eventId) {
        config(['app.name' => 'Locations | Insite Management']);

        $locations = EventLocation::where('event_id', $eventId)->get();

        return view('management.events.view-event-locations')
            ->with('locations', $locations);
    }
}