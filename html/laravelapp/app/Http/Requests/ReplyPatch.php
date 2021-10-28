<?php

namespace App\Http\Requests;

class ReplyPatch extends FormRequestBase
{
    public function rules()
    {
        return [
            'text' => 'required|string|max:' . config('const')['TEXT_MAX_LENGTH'],
        ];
    }
}
