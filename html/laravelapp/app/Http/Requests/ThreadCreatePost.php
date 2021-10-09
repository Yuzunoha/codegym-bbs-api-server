<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class ThreadCreatePost extends FormRequest
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
        $const = config('const');
        return [
            'title' => 'required|string|max:' . $const['TITLE_MAX_LENGTH'],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        UtilService::throwHttpResponseException($validator->errors());
    }
}
