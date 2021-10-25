<?php

namespace Tests\Unit;

use App\Models\Reply;
use App\Models\Thread;
use App\Services\ReplyService;
use App\Services\UtilService;
use Illuminate\Http\Exceptions\HttpResponseException;
use Tests\TestCase;

class ReplyServiceTest extends TestCase
{
    protected function insertTestData()
    {
        Thread::create(['user_id' => 1, 'title' => 'thread1', 'ip_address' => '123',]);
        Thread::create(['user_id' => 1, 'title' => 'thread2', 'ip_address' => '345',]);
        Thread::create(['user_id' => 1, 'title' => 'thread3', 'ip_address' => '567',]);
    }

    public function test_正常系_create()
    {
        $replyService = new ReplyService(new UtilService);
        $this->insertTestData();
        $actual = $replyService->create(2, 1, 'テキスト');
        $expected = Reply::with('user')->find(1);
        $this->assertEquals($expected, $actual);
    }

    public function test_異常系_create_スレッドが無い()
    {
        $replyService = new ReplyService(new UtilService);
        $this->insertTestData();
        $thread_id = 4;
        try {
            $replyService->create($thread_id, 1, 'テキスト');
        } catch (HttpResponseException $e) {
            $expected = json_encode([
                'status' => 400,
                'message' => "thread_id {$thread_id} は存在しません。",
            ]);
            $actual = json_encode($e->getResponse()->original);
            $this->assertEquals($expected, $actual);
        }
    }

    public function test_異常系_create_ユーザが無い()
    {
        $replyService = new ReplyService(new UtilService);
        $this->insertTestData();
        $user_id = 2;
        try {
            $replyService->create(1, $user_id, 'テキスト');
        } catch (HttpResponseException $e) {
            $expected = json_encode([
                'status' => 400,
                'message' => "user_id {$user_id} は存在しません。",
            ]);
            $actual = json_encode($e->getResponse()->original);
            $this->assertEquals($expected, $actual);
        }
    }
}
