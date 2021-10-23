<?php

namespace App\Http\Requests;

class UserRegisterPost extends FormRequestBase
{
    public function rules()
    {
        return [
            'name'     => 'required|string|max:' . config('const')['NAME_MAX_LENGTH'],
            'email'    => 'required|email',
            'password' => 'required',
        ];
    }
}
