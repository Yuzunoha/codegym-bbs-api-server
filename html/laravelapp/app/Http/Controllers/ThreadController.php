<?php

namespace App\Http\Controllers;

use App\Http\Requests\ThreadCreatePost;
use App\Models\Thread;
use App\Services\UtilService;
use Illuminate\Support\Facades\Auth;

class ThreadController extends Controller
{
    protected $utilService;

    public function __construct(UtilService $utilService)
    {
        $this->utilService = $utilService;
    }

    public function create(ThreadCreatePost $request)
    {
        return Thread::create([
            'user_id'    => Auth::id(),
            'title'      => $request->title,
            'ip_address' => $this->utilService->getIp(),
        ]);
    }

    public function selectAll()
    {
        return Thread::all();
    }
}
