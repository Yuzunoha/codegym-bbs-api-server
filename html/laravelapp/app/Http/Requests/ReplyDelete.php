<?php

namespace App\Http\Requests;

class ReplyDelete extends FormRequestBase
{
    public function rules()
    {
        return [
            'id'  => 'required|integer',
        ];
    }
}
