<?php

namespace App\Services;

use App\Models\Thread;
use App\Models\User;
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
        if (!User::find($loginUserId)) {
            /* ユーザが存在しない */
            $this->utilService->throwHttpResponseException("user_id {$loginUserId} は存在しません。");
        }
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
            $builder = $builder
                ->where('title', 'LIKE', '%' . $q . '%')
                ->orWhere('ip_address', 'LIKE', '%' . $q . '%');
        }
        return $builder
            ->orderBy('id', 'desc')
            ->paginate($per_page);
    }

    public function selectById($id)
    {
        return Thread::with('user')->find($id);
    }
}
