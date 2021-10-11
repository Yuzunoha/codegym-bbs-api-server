<?php

namespace App\Repositories;

use App\Models\Reply;
use Illuminate\Database\Eloquent\Collection;

class ReplyRepository
{
    public function insert(int $thread_id, int $number, int $user_id, string $text, string $ip_address): Reply
    {
        $model = new Reply([
            'thread_id'  => $thread_id,
            'number'     => $number,
            'user_id'    => $user_id,
            'text'       => $text,
            'ip_address' => $ip_address,
        ]);
        $model->save();
        return $model;
    }

    public function selectAll()
    {
        return Reply::all();
    }

    public function selectByThreadId(int $thread_id): Collection
    {
        return Reply::where('thread_id', $thread_id)->get();
    }
}
