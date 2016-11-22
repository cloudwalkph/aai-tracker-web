<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveAnswerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'event_id'  => 'required|exists:events,id',
            'poll_id'   => 'required|exists:polls,id',
            'event_location_id' => 'required|exists:event_locations,id',
            'value'     => 'required'
        ];
    }

    public function response(array $errors)
    {
        return response()->json(['status' => 422, 'errors' => $errors], 422);
    }


}
