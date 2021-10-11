<?php

namespace App\Http\Requests;

class UserLoginPost extends FormRequestBase
{
    public function rules()
    {
        return [
            'email'    => 'required|email',
            'password' => 'required',
        ];
    }
}
