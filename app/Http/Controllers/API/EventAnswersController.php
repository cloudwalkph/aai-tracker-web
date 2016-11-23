<?php
namespace App\Http\Controllers\API;

use App\AAI\Services\EventAnswersService;
use App\AAI\Services\EventsService;
use App\AAI\Services\ImageToS3Service;
use App\Http\Controllers\Controller;
use App\Http\Requests\SaveAnswerRequest;
use App\Http\Requests\UploadImageRequest;

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
}