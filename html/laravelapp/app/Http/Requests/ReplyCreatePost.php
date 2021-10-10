<?php

namespace App\Http\Requests;

class ReplyCreatePost extends FormRequestBase
{
    public function rules()
    {
        $const = config('const');
        return [
            'thread_id'  => 'required|integer',
            'text'       => 'required|string|max:' . $const['TEXT_MAX_LENGTH'],
        ];
    }
}
