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

    public function selectByThreadId($per_page, $thread_id, $q = null)
    {
        if (!Thread::find($thread_id)) {
            /* thread_id が存在しない */
            $this->utilService->throwHttpResponseException("thread_id ${thread_id} は存在しません。");
        }
        $builder = Reply::with('user')->where('thread_id', $thread_id);
        if ($q) {
            $builder = $builder
                ->where('text', 'LIKE', '%' . $q . '%')
                ->orWhere('ip_address', 'LIKE', '%' . $q . '%');
        }
        return $builder->orderBy('id', 'desc')->paginate($per_page);
    }

    /**
     * リプライの存在と、投稿主を調べる。
     * エラーであれば例外を投げる。例外を投げなければvalidということ。
     */
    public function checkExistAndOwnReply(int $reply_id, int $user_id): void
    {
        $reply = Reply::find($reply_id);
        if (!$reply) {
            /* 投稿が存在しない */
            $this->utilService->throwHttpResponseException("reply_id ${reply_id} は存在しません。");
        }
        if (intval($user_id) !== intval($reply->user_id)) {
            /* 自分の投稿でない */
            $this->utilService->throwHttpResponseException("他のユーザの投稿は編集できません。");
        }
    }

    public function deleteOwnReply($user_id, $reply_id)
    {
        $this->checkExistAndOwnReply($reply_id, $user_id);
        Reply::find($reply_id)->delete();
        return [
            'message' => "reply_id ${reply_id} のリプライを削除しました。",
        ];
    }

    public function updateOwnReply($user_id, $reply_id, $text)
    {
        $this->checkExistAndOwnReply($reply_id, $user_id);
        Reply::find($reply_id)->update([
            'text' => $text,
        ]);
        return Reply::with('user')->find($reply_id);
    }

    public function selectById($id)
    {
        return Reply::with('user')->find($id);
    }
}
