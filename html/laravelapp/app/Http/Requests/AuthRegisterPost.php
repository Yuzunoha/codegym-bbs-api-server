<?php

namespace App\Http\Requests;

class AuthRegisterPost extends FormRequestBase
{
    public function rules()
    {
        return [
            'name'     => 'required',
            'email'    => 'required|email',
            'password' => 'required',
        ];
    }
}
