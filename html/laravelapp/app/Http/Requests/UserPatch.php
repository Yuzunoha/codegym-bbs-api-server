<?php

namespace App\Http\Requests;

class UserPatch extends FormRequestBase
{
    public function rules()
    {
        return [
            'bio' => 'required|string|max:' . config('const')['BIO_MAX_LENGTH'],
        ];
    }
}
