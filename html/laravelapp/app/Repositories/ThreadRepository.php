<?php

namespace App\Repositories;

use App\Models\Thread;
use App\Repositories\ThreadRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ThreadRepository implements ThreadRepositoryInterface
{
    public function insert($user_id, $title, $ip_address)
    {
        $thread = new Thread([
            'user_id'    => $user_id,
            'title'      => $title,
            'ip_address' => $ip_address,
        ]);
        $thread->save();
        return $thread;
    }

    public function selectAll(): Collection
    {
        return Thread::all();
    }

    public function selectById(int $id): ?Thread
    {
        return Thread::find($id);
    }
}
