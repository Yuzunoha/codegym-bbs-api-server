<?php

namespace App\Services;

use App\Models\Thread;
use App\Repositories\ThreadRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ThreadService implements ThreadServiceInterface
{
    protected $threadRepository;

    public function __construct(ThreadRepositoryInterface $threadRepository)
    {
        $this->threadRepository = $threadRepository;
    }

    public function create($user_id, $title, $ip_address)
    {
        $model = new Thread([
            'user_id'    => $user_id,
            'title'      => $title,
            'ip_address' => $ip_address,
        ]);
        $model->save();
        return $model;
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
