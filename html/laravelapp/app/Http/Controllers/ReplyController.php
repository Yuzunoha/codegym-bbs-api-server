<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReplyCreatePost;
use App\Http\Requests\ReplyDelete;
use App\Http\Requests\ReplyPatch;
use App\Http\Requests\ReplySelectGet;
use App\Models\Reply;
use App\Models\Thread;
use App\Services\ReplyService;
use App\Services\UtilService;
use Illuminate\Support\Facades\Auth;

class ReplyController extends Controller
{
    protected $utilService;
    protected $replyService;

    public function __construct(
        UtilService  $utilService,
        ReplyService $replyService
    ) {
        $this->utilService  = $utilService;
        $this->replyService = $replyService;
    }

    public function create(ReplyCreatePost $request)
    {
        return $this->replyService->create(
            $request->thread_id,
            Auth::id(),
            $request->text
        );
    }

    public function selectByThreadId(ReplySelectGet $request)
    {
        return $this->replyService->selectByThreadId(
            $request->per_page,
            $request->thread_id
        );
    }

    /**
     * リプライの存在と、投稿主を調べる。
     * エラーであれば例外を投げる。例外を投げなければvalidということ。
     */
    protected function checkExistAndOwnReply(int $reply_id, int $user_id): void
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

    public function deleteOwnReply(ReplyDelete $request)
    {
        $reply_id = $request->id;
        $this->checkExistAndOwnReply($reply_id, Auth::id());

        Reply::find($reply_id)->delete();
        return [
            'message' => "reply_id ${reply_id} のリプライを削除しました。",
        ];
    }

    public function updateOwnReply(ReplyPatch $request)
    {
        $reply_id = $request->id;
        $this->checkExistAndOwnReply($reply_id, Auth::id());

        Reply::find($reply_id)->update([
            'text' => $request->text,
        ]);
        return Reply::with('user')->find($reply_id);
    }

    public function selectById($id)
    {
        return Reply::with('user')->find($id);
    }
}
