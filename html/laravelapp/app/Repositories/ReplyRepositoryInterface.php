<?php

namespace App\Repositories;

use App\Models\Reply;
use Illuminate\Database\Eloquent\Collection;

interface ReplyRepositoryInterface
{
    public function insert(int $thread_id, int $number, int $user_id, string $text, string $ip_address): Reply;
    public function selectAll();
    public function selectByThreadId(int $thread_id): Collection;
}
