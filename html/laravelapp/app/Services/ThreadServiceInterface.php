<?php

namespace App\Services;

use App\Models\Thread;

interface ThreadServiceInterface
{
    public function create($user_id, $title, $ip_address);
    public function selectById(int $id): ?Thread;
}
