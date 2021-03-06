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
            'user_id'           => 'required',
            'uuid'              => 'required',
            'image'             => 'required',
            'answers'           => 'required',
            'hit_date'          => 'required',
            'answers.*.poll_id' => 'required|exists:polls,id',
            'answers.*.value'   => 'required'
        ];
    }

    public function response(array $errors)
    {
        return response()->json(['status' => 422, 'errors' => $errors], 422);
    }


}
