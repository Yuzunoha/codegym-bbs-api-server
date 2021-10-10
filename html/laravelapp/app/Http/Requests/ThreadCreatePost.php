<?php

namespace App\Http\Requests;

class ThreadCreatePost extends FormRequestBase
{
    public function rules()
    {
        $const = config('const');
        return [
            'title' => 'required|string|max:' . $const['TITLE_MAX_LENGTH'],
        ];
    }
}
