<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReplyCreatePost;
use App\Http\Requests\ReplyDelete;
use App\Http\Requests\ReplyPatch;
use App\Http\Requests\ReplySelectGet;
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

    public function deleteOwnReply(ReplyDelete $request)
    {
        return $this->replyService->deleteOwnReply(
            Auth::id(),
            $request->id
        );
    }

    public function updateOwnReply(ReplyPatch $request)
    {
        return $this->replyService->updateOwnReply(
            Auth::id(),
            $request->id,
            $request->text
        );
    }

    public function selectById($id)
    {
        return $this->replyService->selectById($id);
    }
}
