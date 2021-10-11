<?php

namespace App\Services;

use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class ReplyService implements ReplyServiceInterface
{
    protected $threadService;
    protected $utilService;

    public function __construct(
        ThreadServiceInterface $threadService,
        UtilServiceInterface   $utilService
    ) {
        $this->threadService = $threadService;
        $this->utilService   = $utilService;
    }

    public function create(int $thread_id, int $user_id, string $text, string $ip_address): Reply
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
        $model = new Reply([
            'thread_id'  => $thread_id,
            'number'     => $number,
            'user_id'    => $user_id,
            'text'       => $text,
            'ip_address' => $ip_address,
        ]);
        $model->save();
        return $model;
    }

    public function selectAll()
    {
        return Reply::all();
    }

    public function selectByThreadId(int $thread_id): Collection
    {
        return Reply::where('thread_id', $thread_id)->get();
    }
}
