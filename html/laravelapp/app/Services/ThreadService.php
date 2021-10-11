<?php

namespace App\Services;

use App\Models\Thread;
use Illuminate\Database\Eloquent\Collection;

class ThreadService
{
    public function create($user_id, $title, $ip_address)
    {
        return Thread::create([
            'user_id'    => $user_id,
            'title'      => $title,
            'ip_address' => $ip_address,
        ]);
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
