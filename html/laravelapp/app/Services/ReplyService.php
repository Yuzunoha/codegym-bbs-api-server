<?php

namespace App\Services;

use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;
use App\Services\UtilService;

class ReplyService
{
    protected $utilService;

    public function __construct(UtilService $utilService)
    {
        $this->utilService = $utilService;
    }

    public function create($thread_id, $user_id, $text)
    {
        if (!Thread::find($thread_id)) {
            /* thread_id が存在しない */
            $this->utilService->throwHttpResponseException("thread_id ${thread_id} は存在しません。");
        }
        if (!User::find($user_id)) {
            /* user_id が存在しない */
            $this->utilService->throwHttpResponseException("user_id ${user_id} は存在しません。");
        }

        /* number を取得する */
        $number = Reply::where('thread_id', $thread_id)->count() + 1;

        /* 作成して返却する */
        $reply = Reply::create([
            'thread_id'  => $thread_id,
            'number'     => $number,
            'user_id'    => $user_id,
            'text'       => $text,
            'ip_address' => $this->utilService->getIp(),
        ]);
        return Reply::with('user')->find($reply->id);
    }
}
