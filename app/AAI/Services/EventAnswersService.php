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

        $result = null;
        \DB::transaction(function() use ($input) {
            $eventLocationAnswerData = [
                'uuid'          => $input['uuid'],
                'event_id'      => $input['event_id'],
                'user_id'       => $input['user_id'],
                'event_location_id' => $input['event_location_id'],
                'image'         => $input['image']
            ];

            $result = EventLocationAnswer::create($eventLocationAnswerData);

            // Create answers
            foreach ($result as $answer) {
                $eventAnswer = EventAnswer::create([
                    'poll_id'                   => $answer['poll_id'],
                    'event_location_answer_id'  => $result['id'],
                    'value'                     => $answer['value']
                ]);

                $result['answers'][] = $eventAnswer;
            }
        });

        return $result;
    }
}