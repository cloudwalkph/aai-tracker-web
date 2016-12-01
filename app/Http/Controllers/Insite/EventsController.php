<?php
namespace App\Http\Controllers\Insite;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventLocation;
use Illuminate\Http\Request;
use Hashids\Hashids;

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

    public function showByLocation(Request $request, $eventId, $locationId)
    {
        // setup page
        config(['app.name' => 'Events | Insite Dashboard']);

        // get data
        $event = Event::where('id', $eventId)->first();
        $location = EventLocation::where('id', $locationId)->first();

        $hashIds = new Hashids('activations', 5);
        $hash = $hashIds->encode($location->id);

        return view('insite.event-location')
            ->with('location', $location)
            ->with('event', $event)
            ->with('hash', $hash);
    }
}