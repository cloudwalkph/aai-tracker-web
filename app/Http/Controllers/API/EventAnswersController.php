<?php
namespace App\Http\Controllers\API;

use App\AAI\Services\EventAnswersService;
use App\AAI\Services\EventsService;
use App\AAI\Services\ImageToS3Service;
use App\Http\Controllers\Controller;
use App\Http\Requests\SaveAnswerRequest;
use App\Http\Requests\UploadImageRequest;
use App\Models\EventAnswer;
use App\Models\EventLocationAnswer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EventAnswersController extends Controller {
    protected $eventService;
    protected $eventAnswerService;
    protected $imageService;

    public function __construct(EventsService $eventService, EventAnswersService $eventAnswersService,
                ImageToS3Service $imageService)
    {
        $this->eventService = $eventService;
        $this->eventAnswerService = $eventAnswersService;
        $this->imageService = $imageService;
    }

    public function uploadImage(UploadImageRequest $request)
    {
        $file = $request->file('image');

        $fileName = $this->imageService->processImage($file);

        return response()->json(['status' => 201, 'data' => ['file_name' => $fileName]]);
    }

    public function saveAnswer(SaveAnswerRequest $request, $eventId, $locationId)
    {
        $input = $request->all();
        $input['event_id'] = $eventId;
        $input['event_location_id'] = $locationId;
        $input['hit_date'] = Carbon::createFromTimestamp(strtotime($input['hit_date']))->toDateTimeString();

        // Save the answer
        $answer = $this->eventAnswerService->saveAnswer($input);

        return response()->json(['status' => 201, 'data' => $answer], 200);
    }

    public function getHits($eventId, $locationId, $criteria)
    {
        $result = [];

        $eventLocationAnswer = EventLocationAnswer::where('event_id', $eventId)
            ->where('event_location_id', $locationId)->get();

        foreach ($eventLocationAnswer as $answer) {
            $eventAnswer = EventAnswer::where('event_location_answer_id', $answer->id)
                ->where('value', strtolower($criteria))->first();

            if (! $eventAnswer) {
                continue;
            }

            $result[] = [
                'uuid'      => $answer->uuid,
//                'user_id'   => $answer->user_id,
                'image'     => url('/images/uploads/'.$answer->image),
                'name'      => $answer->name,
                'email'     => $answer->email,
                'contact_number'    => $answer->contact_number,
                'hit_date'  => Carbon::createFromTimestamp(strtotime($answer->hit_date))->format('F d Y h:i:s A')

            ];
        }

        return response()->json(['data' => $result, 'status' => 200]);
    }

    public function getAnswers($eventId)
    {
        $response = new StreamedResponse(function() use ($eventId) {
            $answersCount = 0;
            $newAnswersCount = $this->eventAnswerService->getAnswersCountByEvent($eventId);

            if ($newAnswersCount != $answersCount) {
                $answers = $this->eventAnswerService->getAnswersByEvent($eventId);
                $json = [
                    'data'      => $answers,
                    'status'    => 200
                ];

                echo 'data: ' . json_encode($json) . "\n\n";
                ob_flush();
                flush();
            }

            sleep(3);
            $answersCount = $newAnswersCount;
        }, 200);

        $response->headers->set('Content-Type', 'text/event-stream');

        return $response;
    }

    public function getAnswersCreatedByUser(Request $request, $eventId)
    {
        $user = $request->user();
        $answers = $this->eventAnswerService->getAnswersByEventOfUser($user->id, $eventId);

        return response()->json([
            'data' => $answers,
            'status' => 200
        ], 200);
    }

    public function getAnswerByLocation($eventId, $locationId)
    {
        $response = new StreamedResponse(function() use ($eventId, $locationId) {
            $answersCount = 0;
            $newAnswersCount = $this->eventAnswerService->getAnswersCountByLocationId($locationId);

            if ($newAnswersCount != $answersCount) {
                $answers = $this->eventAnswerService->getAnswersByLocationId($eventId, $locationId);

                $json = [
                    'data'      => $answers,
                    'status'    => 200
                ];

                echo 'data: ' . json_encode($json) . "\n\n";
                ob_flush();
                flush();
            }

            sleep(3);
            $answersCount = $newAnswersCount;
        });

        $response->headers->set('Content-Type', 'text/event-stream');

        return $response;
    }

    public function getEventsHitCount()
    {
        $response = new StreamedResponse(function() {
            $eventsHitCount = $this->eventAnswerService->getAnswersCountForAllEvents();

            $json = [
                'data'      => $eventsHitCount,
                'status'    => 200
            ];

            echo 'data: ' . json_encode($json) . "\n\n";
            ob_flush();
            flush();

            sleep(3);
        }, 200);

        $response->headers->set('Content-Type', 'text/event-stream');

        return $response;
    }

    public function getEventsHitCountByLocation($eventId)
    {
        $response = new StreamedResponse(function() use ($eventId) {
            $eventsHitCount = $this->eventAnswerService->getAnswersCountForLocationOfEvent($eventId);

            $json = [
                'data'      => $eventsHitCount,
                'status'    => 200
            ];

            echo 'data: ' . json_encode($json) . "\n\n";
            ob_flush();
            flush();

            sleep(3);
        }, 200);

        $response->headers->set('Content-Type', 'text/event-stream');

        return $response;
    }

    public function getLocationHits($eventId, $locationId)
    {
        $response = new StreamedResponse(function() use ($eventId, $locationId) {
            $eventsHitCount = $this->eventAnswerService->getAnswersCountForLocationWithTimestamp($eventId, $locationId);

            $json = [
                'data'      => $eventsHitCount,
                'status'    => 200
            ];

            echo 'data: ' . json_encode($json) . "\n\n";
            ob_flush();
            flush();

            sleep(3);
        }, 200);

        $response->headers->set('Content-Type', 'text/event-stream');

        return $response;
    }
}