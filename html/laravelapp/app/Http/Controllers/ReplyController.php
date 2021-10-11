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

    public function __construct(
        ReplyService $replyService,
        UtilService  $utilService
    ) {
        $this->replyService = $replyService;
        $this->utilService  = $utilService;
    }

    public function create(ReplyCreatePost $request)
    {
        return $this->replyService->create(
            $request->thread_id,
            Auth::id(),
            $request->text,
            $this->utilService->getIp()
        );
    }

    public function selectAll()
    {
        return Reply::all();
    }
}
