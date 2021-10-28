<?php

namespace App\Http\Requests;

class ThreadEditPatch extends FormRequestBase
{
    public function rules()
    {
        return [
            'title' => 'required|string|max:' . config('const')['TITLE_MAX_LENGTH'],
        ];
    }
}
