<?php
namespace App\Http\Controllers\Insite;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventUser;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
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
            $event['status'] = $this->getStatus($event);
            $events[] = $event->toArray();
        }

        return view('insite.home')->with('events', $events);
    }

    private function getStatus(Model $event)
    {
        $startDate = Carbon::createFromTimestamp(strtotime($event->start_date));
        $endDate = Carbon::createFromTimestamp(strtotime($event->end_date));
        $today = Carbon::today('Asia/Manila');


        if ($startDate->isFuture()) {
            return 'Not Started';
        }

        if ($endDate->isPast()) {
            return 'Finished';
        }

        if ($startDate->isToday()) {
            return 'On Going';
        }

        if ($today->diffInDays($endDate) > 0) {
            return 'Finished';
        }

        if ($today->between($startDate, $endDate) ) {
            return 'On Going';
        }

        return 'Not Started';
    }
}