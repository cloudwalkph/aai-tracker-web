<?php
namespace App\AAI\Services;

use App\AAI\Modules\EventAnswers\Repositories\EventAnswersRepository;
use App\AAI\Modules\EventLocations\Repositories\EventLocationsRepository;
use App\AAI\Modules\EventPolls\Repositories\EventPollsRepository;
use App\AAI\Modules\Events\Repositories\EventsRepository;
use App\AAI\Modules\Polls\Repositories\PollsRepository;
use App\Models\EventAnswer;

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

    public function getFullEvents()
    {
        $events = $this->events->all();

        $result = [];
        foreach ($events as $event) {
            $polls = $this->getPolls($event->id);
            $locations = $this->getEventLocations($event->id);

            $result[] = [
                'event_id'      => $event->id,
                'name'          => $event->name,
                'description'   => $event->description,
                'start_date'    => $event->start_date,
                'end_date'      => $event->end_date,
                'polls'         => $polls,
                'locations'     => $locations
            ];
        }

        return $result;
    }

    public function saveAnswer($input)
    {
        if (! isset($input['event_id'])) {
            throw new \Exception("Missing Event Id");
        }

        if (! isset($input['poll_id'])) {
            throw new \Exception("Missing Poll Id");
        }

        if (! isset($input['event_location_id'])) {
            throw new \Exception("Missing Event Location Id");
        }

        if (! isset($input['value'])) {
            throw new \Exception("Missing Answer Value");
        }

        return EventAnswer::create($input);
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