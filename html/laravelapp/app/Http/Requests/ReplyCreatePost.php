<?php

namespace App\Http\Requests;

use App\Services\UtilServiceInterface;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class ReplyCreatePost extends FormRequest
{
    protected $utilService;

    public function __construct(UtilServiceInterface $utilService)
    {
        $this->utilService = $utilService;
    }

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
            'thread_id'  => 'required|integer',
            'text'       => 'required|string|max:' . $const['TEXT_MAX_LENGTH'],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $this->utilService->throwHttpResponseException($validator->errors());
    }
}
