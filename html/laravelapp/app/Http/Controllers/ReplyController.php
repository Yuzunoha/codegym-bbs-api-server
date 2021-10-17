<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReplyCreatePost;
use App\Http\Requests\ReplyDelete;
use App\Http\Requests\ReplySelectGet;
use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;
use App\Services\UtilService;
use Illuminate\Support\Facades\Auth;

class ReplyController extends Controller
{
    protected $utilService;

    public function __construct(UtilService  $utilService)
    {
        $this->utilService = $utilService;
    }

    public function create(ReplyCreatePost $request)
    {
        $thread_id = $request->thread_id;
        $user_id   = Auth::id();

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
        return Reply::create([
            'thread_id'  => $thread_id,
            'number'     => $number,
            'user_id'    => $user_id,
            'text'       => $request->text,
            'ip_address' => $this->utilService->getIp(),
        ]);
    }

    public function selectByThreadId(ReplySelectGet $request)
    {
        $thread_id = $request->thread_id;
        if (!Thread::find($thread_id)) {
            /* thread_id が存在しない */
            $this->utilService->throwHttpResponseException("thread_id ${thread_id} は存在しません。");
        }
        return Reply::where('thread_id', $thread_id)
            ->orderBy('number')
            ->paginate($request->per_page);
    }

    public function deleteOwnReply(ReplyDelete $request)
    {
        $reply_id = $request->id;
        $reply = Reply::find($reply_id);

        if (!$reply) {
            /* 投稿が存在しない */
            $this->utilService->throwHttpResponseException("reply_id ${reply_id} は存在しません。");
        }
        if (Auth::id() !== intval($reply->user_id)) {
            /* 自分の投稿でない */
            $this->utilService->throwHttpResponseException("他のユーザの投稿は削除できません。");
        }

        Reply::find($reply_id)->delete();
        return [
            'message' => "reply_id ${reply_id} のリプライを削除しました。",
        ];
    }
}
