<?php

namespace App\Services;

use App\Models\Thread;
use Illuminate\Database\Eloquent\Collection;

interface ThreadServiceInterface
{
    public function create($user_id, $title, $ip_address);
    public function selectAll(): Collection;
    public function selectById(int $id): ?Thread;
}
