<?php

namespace App\Http\Requests;

class UserUpdatePut extends FormRequestBase
{
    public function rules()
    {
        return [
            'name' => 'required|string|max:' . config('const')['NAME_MAX_LENGTH'],
        ];
    }
}
