<?php
namespace App\Http\Controllers\Insite;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventUser;
use Illuminate\Http\Request;

class HomeController extends Controller {

    public function index(Request $request)
    {
        // setup page
        config(['app.name' => 'Insite Dashboard']);

        // get data
        $user = $request->user();
        $eventsForUser = EventUser::where('user_id', $user['id'])->get();

        $events = [];
        foreach ($eventsForUser as $userEvent) {
            $event = Event::where('id', $userEvent->event_id)->first();
            $events[] = $event->toArray();
        }

        return view('insite.home')->with('events', $events);
    }
}