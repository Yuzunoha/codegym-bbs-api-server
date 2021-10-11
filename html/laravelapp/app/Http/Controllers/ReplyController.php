<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReplyCreatePost;
use App\Services\ReplyService;
use App\Services\UtilService;
use Illuminate\Support\Facades\Auth;

class ReplyController extends Controller
{
    protected $replyService;
    protected $utilService;

    public function __construct(UtilService  $utilService)
    {
        $this->replyService = $replyService;
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

    public function selectAll()
    {
        return Reply::all();
    }
}
