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
        /* TODO: チェックする */
        return $this->threadRepository->insert($user_id, $title, $ip_address);
    }

    public function selectAll(): Collection
    {
        return $this->threadRepository->selectAll();
    }

    public function selectById(int $id): ?Thread
    {
        return $this->threadRepository->selectById($id);
    }
}
