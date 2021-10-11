<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReplyCreatePost;
use App\Services\ReplyServiceInterface;
use App\Services\UtilServiceInterface;
use Illuminate\Support\Facades\Auth;

class ReplyController extends Controller
{
    protected $replyService;
    protected $utilService;

    public function __construct(
        ReplyServiceInterface $replyService,
        UtilServiceInterface  $utilService

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
        return $this->replyService->selectAll();
    }
}
