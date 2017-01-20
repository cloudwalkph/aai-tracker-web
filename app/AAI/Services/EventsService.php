<?php
namespace App\AAI\Services;

use App\AAI\Modules\EventAnswers\Repositories\EventAnswersRepository;
use App\AAI\Modules\EventLocations\Repositories\EventLocationsRepository;
use App\AAI\Modules\EventPolls\Repositories\EventPollsRepository;
use App\AAI\Modules\Events\Repositories\EventsRepository;
use App\AAI\Modules\Polls\Repositories\PollsRepository;
use App\Models\EventAnswer;
use App\Models\EventUser;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class EventsService {
    protected $events;
    protected $eventLocations;
    protected $eventPolls;
    protected $polls;

    public function __construct(EventsRepository $events,
                                PollsRepository $polls,
                                EventLocationsRepository $eventLocations,
                                EventPollsRepository $eventPolls)
    {
        $this->events = $events;
        $this->polls = $polls;
        $this->eventLocations = $eventLocations;
        $this->eventPolls = $eventPolls;
    }

    public function getFullEvents($user)
    {
        $userEvents = EventUser::where('user_id', $user['id'])->get();
        \Log::info($user['id']);

        $result = [];
        foreach ($userEvents as $userEvent) {
            \Log::info($userEvent);
            $event = $this->events->findById($userEvent['event_id']);

            $polls = $this->getPolls($event->id);
            $locations = $this->getEventLocations($event->id);

            $result[] = [
                'event_id'      => $event->id,
                'name'          => $event->name,
                'description'   => $event->description,
                'start_date'    => $event->start_date,
                'end_date'      => $event->end_date,
                'polls'         => $polls,
                'locations'     => $locations,
                'status'        => $this->getStatus($event)
            ];
        }

        return $result;
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

    private function getPolls($eventId)
    {
        $eventPolls = $this->eventPolls->findByKey('event_id', $eventId)->get();

        $result = [];
        foreach ($eventPolls as $eventPoll) {
            $poll = $this->polls->findById($eventPoll->id);

            $result[] = [
                'id'        => $poll->id,
                'name'      => $poll->name,
                'type'      => $poll->type,
                'choices'   => json_decode($poll->choices)
            ];
        }

        return $result;
    }

    private function getEventLocations($eventId)
    {
        $locations = $this->eventLocations->findByKey('event_id', $eventId)->get();

        $result = [];
        foreach ($locations as $location) {
            $result[] = [
                'id'    => $location->id,
                'name'  => $location->name
            ];
        }

        return $result;
    }
}