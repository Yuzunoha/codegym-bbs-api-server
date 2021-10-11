<?php

namespace App\Services;

use App\Models\Reply;
use Illuminate\Database\Eloquent\Collection;

interface ReplyServiceInterface
{
    public function create(int $thread_id, int $user_id, string $text, string $ip_address): Reply;
    public function selectAll();
    public function selectByThreadId(int $thread_id): Collection;
}
