<?php

namespace App\Http\Requests;

class UserLoginPost extends FormRequestBase
{
    public function rules()
    {
        return [
            'name'     => 'required',
            'password' => 'required',
        ];
    }
}
