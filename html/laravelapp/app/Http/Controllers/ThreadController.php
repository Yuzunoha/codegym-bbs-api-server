<?php

namespace App\Http\Controllers;

use App\Http\Requests\ThreadCreatePost;
use App\Models\Thread;
use App\Services\ThreadService;
use App\Services\UtilService;
use Illuminate\Support\Facades\Auth;

class ThreadController extends Controller
{
    protected $threadService;
    protected $utilService;

    public function __construct(
        ThreadService    $threadService,
        UtilService      $utilService
    ) {
        $this->threadService    = $threadService;
        $this->utilService      = $utilService;
    }

    public function create(ThreadCreatePost $request)
    {
        $user_id    = Auth::id();
        $title      = $request->title;
        $ip_address = $this->utilService->getIp();
        return $this->threadService->create($user_id, $title, $ip_address);
    }

    public function selectAll()
    {
        return Thread::all();
    }
}
