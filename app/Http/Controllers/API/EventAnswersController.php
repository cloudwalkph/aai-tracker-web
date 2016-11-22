<?php
namespace App\Http\Controllers\API;

use App\AAI\Services\EventsService;
use App\Http\Controllers\Controller;
use App\Http\Requests\SaveAnswerRequest;

class EventAnswersController extends Controller {
    protected $eventService;

    public function __construct(EventsService $eventService)
    {
        $this->eventService = $eventService;
    }

    public function saveAnswer(SaveAnswerRequest $request, $eventId)
    {
        $input = $request->all();
        $input['event_id'] = $eventId;
        $answer = $this->eventService->saveAnswer($input);

        return response()->json(['status' => 200, 'data' => $answer], 200);
    }
}