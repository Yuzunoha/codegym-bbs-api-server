<?php

namespace App\Http\Requests;

class ReplySelectGet extends FormRequestBase
{
    public function rules()
    {
        return [
            'thread_id'  => 'required|integer',
        ];
    }
}
