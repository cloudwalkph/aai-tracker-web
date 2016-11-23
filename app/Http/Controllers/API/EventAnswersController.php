<?php
namespace App\Http\Controllers\API;

use App\AAI\Services\EventAnswersService;
use App\AAI\Services\EventsService;
use App\AAI\Services\ImageToS3Service;
use App\Http\Controllers\Controller;
use App\Http\Requests\SaveAnswerRequest;

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

    public function saveAnswer(SaveAnswerRequest $request, $eventId)
    {
        $input = $request->all();
        $input['event_id'] = $eventId;
        $input['image'] = $this->imageService->processImage($request->file('image'));

        $answer = $this->eventAnswerService->saveAnswer($input);

        return response()->json(['status' => 200, 'data' => $answer], 200);
    }
}