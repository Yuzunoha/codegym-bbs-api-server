<?php

namespace App\Http\Requests;

class UserRegisterPost extends FormRequestBase
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
