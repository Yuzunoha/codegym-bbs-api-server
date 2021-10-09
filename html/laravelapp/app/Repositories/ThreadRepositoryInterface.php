<?php

namespace App\Repositories;

use App\Models\Thread;

interface ThreadRepositoryInterface
{
    public function insert($user_id, $title, $ip_address);
    public function selectAll();
    public function selectById(int $id): ?Thread;
}
