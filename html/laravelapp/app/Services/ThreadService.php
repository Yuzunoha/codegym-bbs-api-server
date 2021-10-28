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

    public function updateOwnThread($user_id, $thread_id, $title)
    {
        $thread = Thread::find($thread_id);
        if (!$thread) {
            /* thread_id が存在しない */
            $this->utilService->throwHttpResponseException("thread_id {$thread_id} は存在しません。");
        }
        if (intval($user_id) !== intval($thread->user_id)) {
            /* 自分のスレッドではない */
            $this->utilService->throwHttpResponseException("他のユーザのスレッドは編集できません。");
        }
        $thread->update([
            'title' => $title,
        ]);
        return Thread::with('user')->find($thread_id);
    }
}
