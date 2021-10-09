<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReplyCreatePost;
use App\Models\Reply;
use App\Services\ReplyServiceInterface;
use App\Services\UtilService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReplyController extends Controller
{
    protected $replyService;

    public function __construct(
        ReplyServiceInterface $replyService
    ) {
        $this->replyService = $replyService;
    }

    public function create(ReplyCreatePost $request)
    {
        $user_id    = Auth::id();
        $ip_address = UtilService::getIp();

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
