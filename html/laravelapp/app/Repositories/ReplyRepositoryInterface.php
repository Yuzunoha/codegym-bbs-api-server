<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;

interface ReplyRepositoryInterface
{
    public function insert($thread_id, $number, $user_id, $text, $ip_address);
    public function selectAll();
    public function selectByThreadId(int $thread_id): Collection;
}
