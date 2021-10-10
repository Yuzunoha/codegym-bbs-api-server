<?php

namespace App\Http\Controllers;

use App\Http\Requests\ThreadCreatePost;
use App\Repositories\ThreadRepositoryInterface;
use App\Services\ThreadServiceInterface;
use App\Services\UtilServiceInterface;
use Illuminate\Support\Facades\Auth;

class ThreadController extends Controller
{
    protected $threadRepository;
    protected $threadService;
    protected $utilService;

    public function __construct(
        ThreadServiceInterface    $threadService,
        ThreadRepositoryInterface $threadRepository,
        UtilServiceInterface      $utilService
    ) {
        $this->threadService    = $threadService;
        $this->threadRepository = $threadRepository;
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
        // TODO: 直す
        return $this->threadRepository->selectAll();
    }
}
