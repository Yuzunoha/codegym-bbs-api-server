<?php

namespace App\Repositories;

use App\Models\Thread;
use Illuminate\Database\Eloquent\Collection;

interface ThreadRepositoryInterface
{
    public function insert($user_id, $title, $ip_address);
    public function selectAll(): Collection;
    public function selectById(int $id): ?Thread;
}
