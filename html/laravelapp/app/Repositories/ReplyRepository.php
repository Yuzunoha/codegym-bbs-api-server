<?php

namespace App\Repositories;

use App\Models\Reply;
use Illuminate\Database\Eloquent\Collection;

class ReplyRepository implements ReplyRepositoryInterface
{
    public function insert($thread_id, $number, $user_id, $text, $ip_address)
    {
        $model = new Reply;
        $model->thread_id = $thread_id;
        $model->number = $number;
        $model->user_id = $user_id;
        $model->text = $text;
        $model->ip_address = $ip_address;
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
