<?php

namespace App\Http\Controllers;

use App\Http\Requests\ThreadCreatePost;
use App\Services\ThreadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ThreadController extends Controller
{
    protected $utilService;
    protected $threadService;

    public function __construct(
        ThreadService $threadService
    ) {
        $this->threadService = $threadService;
    }

    public function create(ThreadCreatePost $request)
    {
        return $this->threadService->create(
            Auth::id(),
            $request->title
        );
    }

    public function select(Request $request)
    {
        return $this->threadService->select(
            $request->per_page,
            $request->q
        );
    }

    public function selectById($id)
    {
        return $this->threadService->selectById(
            $id
        );
    }
}
