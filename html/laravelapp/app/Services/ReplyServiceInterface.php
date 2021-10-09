<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;

interface ReplyServiceInterface
{
    public function create($thread_id, $user_id, $text, $ip_address);
    public function selectAll();
    public function selectByThreadId(int $thread_id): Collection;
}
