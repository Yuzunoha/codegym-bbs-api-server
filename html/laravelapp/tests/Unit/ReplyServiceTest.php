<?php

namespace Tests\Unit;

use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;
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

        $thread_id = 2;
        $number = 1;
        $user_id = 1;
        $text = 'テキストナンバー';
        Reply::create([
            'thread_id'  => $thread_id,
            'number'     => $number,
            'user_id'    => $user_id,
            'text'       => $text . $number,
            'ip_address' => '123',
        ]);
        $number++;
        Reply::create([
            'thread_id'  => $thread_id,
            'number'     => $number,
            'user_id'    => $user_id,
            'text'       => $text . $number,
            'ip_address' => '345',
        ]);
        $number++;
        Reply::create([
            'thread_id'  => $thread_id,
            'number'     => $number,
            'user_id'    => $user_id,
            'text'       => $text . $number,
            'ip_address' => '567',
        ]);
    }

    public function test_正常系_create()
    {
        $replyService = new ReplyService(new UtilService);
        $this->insertTestData();
        $actual = $replyService->create(2, 1, 'テキスト');
        $expected = Reply::with('user')->find($actual->id);
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

    public function selectByThreadId($per_page, $thread_id, $q = null)
    {
        if (!Thread::find($thread_id)) {
            /* thread_id が存在しない */
            $this->utilService->throwHttpResponseException("thread_id ${thread_id} は存在しません。");
        }
        $builder = Reply::with('user')->where('thread_id', $thread_id);
        if ($q) {
            $builder = $builder
                ->where('text', 'LIKE', '%' . $q . '%')
                ->orWhere('ip_address', 'LIKE', '%' . $q . '%');
        }
        return $builder->orderBy('id', 'desc')->paginate($per_page);
    }

    public function test_selectByThreadId_異常系_存在しないスレッド()
    {
        $replyService = new ReplyService(new UtilService);
        $thread_id = 1;
        try {
            $replyService->selectByThreadId(20, $thread_id);
        } catch (HttpResponseException $e) {
            $expected = json_encode([
                'status' => 400,
                'message' => "thread_id {$thread_id} は存在しません。",
            ]);
            $actual = json_encode($e->getResponse()->original);
            $this->assertEquals($expected, $actual);
        }
    }

    public function test_selectByThreadId_正常系()
    {
        $replyService = new ReplyService(new UtilService);
        $this->insertTestData();
        $actual = $replyService->selectByThreadId(20, 2)->toArray()['data'];
        $expected = array_reverse(Reply::with('user')->get()->toArray());
        $len = count($expected);
        $this->assertEquals($len, count($actual));
        for ($i = 0; $i < $len; $i++) {
            $this->assertEquals($expected[$i], $actual[$i]);
        }
    }

    public function test_selectByThreadId_正常系_テキストあいまい検索1件()
    {
        $replyService = new ReplyService(new UtilService);
        $this->insertTestData();
        $actual = $replyService->selectByThreadId(20, 2, 'ナンバー2')->toArray()['data'][0];
        $expected = Reply::with('user')->find(2)->toArray();
        $this->assertEquals($expected, $actual);
    }
}
