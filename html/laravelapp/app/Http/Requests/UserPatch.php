<?php

namespace App\Http\Requests;

class UserPatch extends FormRequestBase
{
    public function rules()
    {
        return [
            'name' => 'required|string|max:' . config('const')['NAME_MAX_LENGTH'],
        ];
    }
}
