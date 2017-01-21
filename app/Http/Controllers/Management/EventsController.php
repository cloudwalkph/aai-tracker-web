<?php
namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventLocation;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class EventsController extends Controller {
    public function index()
    {
        config(['app.name' => 'Events | Insite Management']);

        $events = Event::all();

        return view('management.events.index')->with('events', $events);
    }

    public function showLocations($eventId)
    {
        config(['app.name' => 'Locations | Insite Management']);

        $locations = EventLocation::where('event_id', $eventId)->get();

        return view('management.events.view-event-locations')
            ->with('eventId', $eventId)
            ->with('locations', $locations);
    }

    public function uploadPlaybackFootage(Request $request, $eventId, $locationId)
    {

        if (! $request->hasFile('video_playback')) {
            // Get the locations again
            $locations = EventLocation::where('event_id', $eventId)->get();

            return view('management.events.view-event-locations')
                ->with('eventId', $eventId)
                ->with('locations', $locations);
        }

        $videoPlayback = $request->file('video_playback')->store('videos');

        // Update the channel of location
        EventLocation::where('id', $locationId)->update([
            'channel'   => $videoPlayback
        ]);

        // Get the locations again
        $locations = EventLocation::where('event_id', $eventId)->get();

        return view('management.events.view-event-locations')
            ->with('eventId', $eventId)
            ->with('locations', $locations);
    }

}