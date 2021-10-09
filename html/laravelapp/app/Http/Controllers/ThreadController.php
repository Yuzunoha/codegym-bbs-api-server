<?php

namespace App\Http\Controllers;

use App\Http\Requests\ThreadCreatePost;
use App\Models\Thread;
use App\Repositories\ThreadRepositoryInterface;
use App\Services\ThreadServiceInterface;
use App\Services\UtilService;
use Illuminate\Support\Facades\Auth;

class ThreadController extends Controller
{
    protected $threadRepository;
    protected $threadService;

    public function __construct(
        ThreadServiceInterface $threadService,
        ThreadRepositoryInterface $threadRepository
    ) {
        $this->threadService = $threadService;
        $this->threadRepository = $threadRepository;
    }

    public function create(ThreadCreatePost $request)
    {
        $user_id    = Auth::id();
        $title      = $request->title;
        $ip_address = UtilService::getIp();
        return $this->threadService->create($user_id, $title, $ip_address);
    }

    public function getAll()
    {
        return $this->threadRepository->selectAll();
    }
}
