<?php

namespace App\Services;

use App\Models\Thread;
use App\Services\UtilService;

class ThreadService
{
    protected $utilService;

    public function __construct(UtilService $utilService)
    {
        $this->utilService = $utilService;
    }

    public function create($loginUserId, $title)
    {
        $thread = Thread::create([
            'user_id'    => $loginUserId,
            'title'      => $title,
            'ip_address' => $this->utilService->getIp(),
        ]);
        return Thread::with('user')->find($thread->id);
    }

    public function select($per_page, $q = null)
    {
        $builder = Thread::with('user');
        if ($q) {
            $builder = $builder->where('title', 'LIKE', '%' . $q . '%');
        }
        return $builder->paginate($per_page);
    }

    public function selectById($id)
    {
        return Thread::with('user')->find($id);
    }
}
