<?php
namespace App\Http\Controllers\API;

use App\AAI\Services\EventAnswersService;
use App\AAI\Services\EventsService;
use App\AAI\Services\ImageToS3Service;
use App\Http\Controllers\Controller;
use App\Http\Requests\SaveAnswerRequest;
use App\Http\Requests\UploadImageRequest;
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

        // Save the answer
        $answer = $this->eventAnswerService->saveAnswer($input);

        return response()->json(['status' => 201, 'data' => $answer], 200);
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

    public function getEventsHitCount($eventId)
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
}