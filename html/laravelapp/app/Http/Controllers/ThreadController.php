<?php

namespace App\Http\Controllers;

use App\Http\Requests\ThreadCreatePost;
use App\Models\Thread;
use App\Services\UtilService;
use Illuminate\Http\Request;
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
        $thread = Thread::create([
            'user_id'    => Auth::id(),
            'title'      => $request->title,
            'ip_address' => $this->utilService->getIp(),
        ]);
        return Thread::with('user')->find($thread->id);
    }

    public function select(Request $request)
    {
        $builder = Thread::with('user');
        if ($request->q) {
            $builder = $builder->where('title', 'LIKE', '%' . $request->q . '%');
        }
        return $builder->paginate($request->per_page);
    }
}
