<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReplyCreatePost;
use App\Services\ReplyServiceInterface;
use App\Services\UtilServiceInterface;
use Illuminate\Http\Request;
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
        $user_id    = Auth::id();
        $ip_address = $this->utilService->getIp();

        return $this->replyService->create(
            $request->thread_id,
            $user_id,
            $request->text,
            $ip_address
        );
    }

    public function selectAll(Request $request)
    {
        return $this->replyService->selectAll();
    }
}
