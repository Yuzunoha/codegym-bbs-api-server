<?php

namespace Tests\Unit;

use App\Models\Thread;
use App\Services\ReplyService;
use App\Services\UtilService;
use Tests\TestCase;

class ReplyServiceTest extends TestCase
{
    protected function insertTestData()
    {
        Thread::create(['user_id' => 1, 'title' => 'thread1', 'ip_address' => '123',]);
    }

    /*
    public function create($thread_id, $user_id, $text)
    {
        if (!Thread::find($thread_id)) {
            $this->utilService->throwHttpResponseException("thread_id ${thread_id} は存在しません。");
        }
        if (!User::find($user_id)) {
            $this->utilService->throwHttpResponseException("user_id ${user_id} は存在しません。");
        }

        $number = Reply::where('thread_id', $thread_id)->count() + 1;

        $reply = Reply::create([
            'thread_id'  => $thread_id,
            'number'     => $number,
            'user_id'    => $user_id,
            'text'       => $text,
            'ip_address' => $this->utilService->getIp(),
        ]);
        return Reply::with('user')->find($reply->id);
    }
    */

    public function test_正常系_create()
    {
        $replyService = new ReplyService(new UtilService);
        $this->insertTestData();
        $actual = $replyService->create(1, 1, 'テキスト');
        $this->p($actual->toArray());
        $this->assertTrue(true);
    }
}
