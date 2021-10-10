<?php

namespace App\Http\Controllers;

use App\Http\Requests\ThreadCreatePost;
use App\Services\ThreadServiceInterface;
use App\Services\UtilServiceInterface;
use Illuminate\Support\Facades\Auth;

class ThreadController extends Controller
{
    protected $threadService;
    protected $utilService;

    public function __construct(
        ThreadServiceInterface    $threadService,
        UtilServiceInterface      $utilService
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

    public function getAll()
    {
        return $this->threadService->selectAll();
    }
}
