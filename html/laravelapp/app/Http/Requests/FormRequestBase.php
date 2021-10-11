<?php

namespace App\Http\Requests;

use App\Services\UtilService;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class FormRequestBase extends FormRequest
{
    protected $utilService;

    public function __construct(UtilService $utilService)
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
     * バリデーション失敗したときに例外を投げる
     */
    protected function failedValidation(Validator $validator)
    {
        $this->utilService->throwHttpResponseException($validator->errors());
    }
}
