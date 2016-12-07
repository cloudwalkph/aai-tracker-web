<?php
namespace App\AAI\Services;

use App\AAI\Modules\EventAnswers\Repositories\EventAnswersRepository;
use App\AAI\Modules\EventLocationAnswers\Repositories\EventLocationAnswersRepository;
use App\Models\Event;
use App\Models\EventAnswer;
use App\Models\EventLocation;
use App\Models\EventLocationAnswer;
use App\Models\EventPoll;
use App\Models\Poll;
use Carbon\Carbon;

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
        $eventLocationAnswers = $this->eventLocationAnswer->findByKey('event_id',$eventId)->get();
        $eventPolls = EventPoll::where('event_id', $eventId)->get();

        $result = [];
        foreach ($eventPolls as $poll) {
            $pollInfo = Poll::where('id', $poll->poll_id)->first();

            // set key for poll
            foreach ($eventLocationAnswers as $locationAnswer) {
                $eventAnswers = EventAnswer::where('event_location_answer_id', $locationAnswer->id)
                    ->where('poll_id', $pollInfo->id)
                    ->get();

                foreach ($eventAnswers as $answer) {
                    $result[$pollInfo->name][] = [
                        'answer' => 1,
                        'label'  => ucwords($answer->value)
                    ];
                }
            }
        }

        return $result;
    }

    public function getAnswersByEventOfUser($userId, $eventId)
    {
        $eventLocationAnswers = EventLocationAnswer::where('event_id', $eventId)
            ->where('user_id', $userId)->get();

        $eventPolls = EventPoll::where('event_id', $eventId)->get();

        $result = [];
        foreach ($eventPolls as $poll) {
            $pollInfo = Poll::where('id', $poll->poll_id)->first();

            // set key for poll
            foreach ($eventLocationAnswers as $locationAnswer) {
                $eventAnswers = EventAnswer::where('event_location_answer_id', $locationAnswer->id)
                    ->where('poll_id', $pollInfo->id)
                    ->get();

                foreach ($eventAnswers as $answer) {
                    $result[$pollInfo->name][] = [
                        'answer' => 1,
                        'label'  => ucwords($answer->value)
                    ];
                }
            }
        }

        return $result;
    }

    public function getAnswersCountForAllEvents()
    {
        $result = [];
        $today = Carbon::now('Asia/Manila');
        $date = $today->toDateString();

        $events = Event::whereDate('start_date', '<=', $date)
            ->whereDate('end_date', '>=', $date)
            ->get();

        foreach ($events as $event) {
            $count = $this->getAnswersCountByEvent($event->id);

            $event['total_hits'] = $count;

            $result[] = $event;
        }

        return $result;
    }

    public function getAnswersCountForLocationOfEvent($eventId)
    {
        $result = [];
        $today = Carbon::now('Asia/Manila');
        $date = $today->toDateString();

        $eventLocations = EventLocation::where('event_id', $eventId)->get();

        foreach ($eventLocations as $location) {
            $count = $this->getAnswersCountByLocationId($location->id);

            $location['total_hits'] = $count;

            $result[] = $location;
        }

        return $result;
    }

    public function getAnswersCountForLocationWithTimestamp($eventId, $locationId)
    {
        $today = Carbon::now('Asia/Manila');

        $count = $this->getAnswersCountByLocationIdWithTimestamp($locationId);

        $result = $count;

        return $result;
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
    public function getAnswersByLocationId($eventId, $locationId)
    {
        $eventLocationAnswer = $this->eventLocationAnswer->findByKey('event_location_id', $locationId)->get();
        $eventPolls = EventPoll::where('event_id', $eventId)->get();

        if (! $eventLocationAnswer) {
            return [];
        }

        $result = [];
        foreach ($eventPolls as $poll) {
            $pollInfo = Poll::where('id', $poll->poll_id)->first();

            foreach ($eventLocationAnswer as $eventLocation) {
                // set key for poll
                $eventAnswers = EventAnswer::where('event_location_answer_id', $eventLocation->id)
                    ->where('poll_id', $pollInfo->id)
                    ->get();


                foreach ($eventAnswers as $answer) {
                    $result[$pollInfo->name][] = [
                        'answer' => 1,
                        'label'  => ucwords($answer->value)
                    ];
                }
            }
        }

        return $result;
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

    public function getAnswersCountByLocationIdWithTimestamp($locationId)
    {
        $hits = EventLocationAnswer::select(\DB::raw('DATE_FORMAT(hit_date, \'%Y-%m-%d %H:%i\') as date_group'), \DB::raw('count(id) as hits'))
            ->where('event_location_id', $locationId)
            ->groupBy('date_group')
            ->get();

        $result = ['key' => 'Hits'];
        foreach ($hits as $hit) {
            $timestamp = strtotime($hit->date_group);
            \Log::info($timestamp);

            $result['values'][] = [
                'x' => $timestamp,
                'y' => $hit->hits
            ];
        }

        return [$result];
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
                'image'         => $input['image'],
                'hit_date'      => $input['hit_date'],
                'name'          => isset($input['name']) ? $input['name'] : '',
                'contact_number'    => isset($input['contact_number']) ? $input['contact_number'] : '',
                'email'         => isset($input['email']) ? $input['email'] : '',
            ];

            // Create event location answer
            $result = EventLocationAnswer::create($eventLocationAnswerData)->toArray();
            // Create answers
            $result['answers'] = $this->createAnswers($result['id'], $input['answers']);
        });

        return $result;
    }

    private function createAnswers($locationAnswerId, $answers)
    {
        $result = [];

        foreach ($answers as $answer) {
            $eventAnswer = EventAnswer::create([
                'poll_id'                   => $answer['poll_id'],
                'event_location_answer_id'  => $locationAnswerId,
                'value'                     => $answer['value']
            ]);

            $result[] = $eventAnswer;
        }

        return $result;
    }
}