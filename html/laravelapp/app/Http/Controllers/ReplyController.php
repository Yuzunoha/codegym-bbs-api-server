<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReplyCreatePost;
use App\Models\Reply;
use App\Services\UtilService;
use Illuminate\Http\Request;
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

    public function selectAll(Request $request)
    {
        return Reply::paginate($request->per_page);
    }
}
