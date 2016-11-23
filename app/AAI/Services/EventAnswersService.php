<?php
namespace App\AAI\Services;

use App\AAI\Modules\EventAnswers\Repositories\EventAnswersRepository;
use App\AAI\Modules\EventLocationAnswers\Repositories\EventLocationAnswersRepository;
use App\Models\EventAnswer;
use App\Models\EventLocationAnswer;

class EventAnswersService {

    protected $eventAnswer;
    protected $eventLocationAnswer;

    public function __construct(EventAnswersRepository $eventAnswer,
                                EventLocationAnswersRepository $eventLocationAnswer)
    {
        $this->eventAnswer = $eventAnswer;
        $this->eventLocationAnswer = $eventLocationAnswer;
    }


    /**
     * Save Answer
     *
     * @param $input
     * @return null
     * @throws \Exception
     */
    public function saveAnswer($input)
    {
        if (! isset($input['event_id'])) {
            throw new \Exception("Missing Event Id");
        }

        if (! isset($input['event_location_id'])) {
            throw new \Exception("Missing Event Location Id");
        }

        if (! isset($input['answers'])) {
            throw new \Exception("Answers Missing");
        }

        // Create the location answer
        $result = $this->createLocationAnswer($input);

        return $result;
    }

    /**
     * Get Answers By Event Id
     *
     * @param $eventId
     * @return mixed
     */
    public function getAnswersByEvent($eventId)
    {
        return $this->eventLocationAnswer->findByKey('event_id',$eventId)->get();
    }

    /**
     * Get Answers Count By Event Id
     *
     * @param $eventId
     * @return mixed
     */
    public function getAnswersCountByEvent($eventId)
    {
        return $this->eventLocationAnswer->findByKey('event_id',$eventId)->count();
    }

    /**
     * Get Answers By Location Id
     *
     * @param $locationId
     * @return mixed
     */
    public function getAnswersByLocationId($locationId)
    {
        return $this->eventLocationAnswer->findByKey('event_location_id',$locationId)->get();
    }

    /**
     * Get Answers Count By Location Id
     *
     * @param $locationId
     * @return mixed
     */
    public function getAnswersCountByLocationId($locationId)
    {
        return $this->eventLocationAnswer->findByKey('event_location_id',$locationId)->count();
    }

    private function createLocationAnswer($input)
    {
        $result = null;

        \DB::transaction(function() use ($input, &$result) {
            $eventLocationAnswerData = [
                'uuid'          => $input['uuid'],
                'event_id'      => $input['event_id'],
                'user_id'       => $input['user_id'],
                'event_location_id' => $input['event_location_id'],
                'image'         => $input['image']
            ];

            // Create event location answer
            $result = EventLocationAnswer::create($eventLocationAnswerData)->toArray();
            // Create answers
            $result['answers'] = $this->createAnswers($input['answers']);
        });

        return $result;
    }

    private function createAnswers($answers)
    {
        $result = [];

        foreach ($answers as $answer) {
            $eventAnswer = EventAnswer::create([
                'poll_id'                   => $answer['poll_id'],
                'event_location_answer_id'  => $result['id'],
                'value'                     => $answer['value']
            ]);

            $result[] = $eventAnswer;
        }

        return $result;
    }
}