<?php
namespace App\AAI\Services;

use App\AAI\Modules\EventAnswers\Repositories\EventAnswersRepository;
use App\Models\EventAnswer;

class EventAnswersService {

    protected $eventAnswer;

    public function __construct(EventAnswersRepository $eventAnswer)
    {
        $this->eventAnswer = $eventAnswer;
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
}