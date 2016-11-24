<?php
namespace App\Http\Controllers\Insite;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventLocation;
use Illuminate\Http\Request;

class EventsController extends Controller {

    public function show(Request $request, $eventId)
    {
        // setup page
        config(['app.name' => 'Events | Insite Dashboard']);

        // get data
        $event = Event::where('id', $eventId)->first();
        $locations = EventLocation::where('event_id', $eventId)->get();

        return view('insite.event-locations')
            ->with('locations', $locations)
            ->with('event', $event);
    }
}