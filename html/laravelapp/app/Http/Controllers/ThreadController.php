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
        return Thread::create([
            'user_id'    => Auth::id(),
            'title'      => $request->title,
            'ip_address' => $this->utilService->getIp(),
        ]);
    }

    public function select(Request $request)
    {
        $builder = Thread::with('user');
        if ($request->search) {
            $builder = $builder->where('title', 'LIKE', '%' . $request->search . '%');
        }
        return $builder->paginate($request->per_page);
    }
}
