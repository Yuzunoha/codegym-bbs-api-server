<?php

namespace Tests\Unit;

use App\Models\Thread;
use App\Services\ThreadService;
use App\Services\UtilService;
use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Tests\TestCase;

class ThreadServiceTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    protected function insertTestData()
    {
        Thread::create(['user_id' => 1, 'title' => 'thread1', 'ip_address' => '123',]);
        Thread::create(['user_id' => 1, 'title' => 'thread2', 'ip_address' => '345',]);
        Thread::create(['user_id' => 1, 'title' => 'thread3', 'ip_address' => '567',]);
    }

    public function test_正常_create()
    {
        $threadService = new ThreadService(new UtilService);

        // 空であること
        $this->assertEquals(0, count(Thread::all()));

        // スレッド作成
        $expected = $threadService->create(1, 'テスト');

        // スレッド取得
        $actual = Thread::with('user')->find(1);

        // モデルのあいまい比較
        $this->assertEquals($expected, $actual);
    }

    public function test_異常_create()
    {
        $threadService = new ThreadService(new UtilService);
        $userId = 123;
        try {
            $threadService->create($userId, '作成失敗するかな');
        } catch (Exception $e) {
            $this->assertTrue($e instanceof HttpResponseException);
            $expected = json_encode([
                'status' => 400,
                'message' => "user_id {$userId} は存在しません。",
            ]);
            $actual = json_encode($e->getResponse()->original);
            $this->assertEquals($expected, $actual);
        }
    }

    public function test_正常_select()
    {
        $threadService = new ThreadService(new UtilService);
        $this->insertTestData();
        $actual = $threadService->select(20)->toArray()['data'];
        $expected = array_reverse(Thread::with('user')->get()->toArray());
        $len = count($actual);
        for ($i = 0; $i < $len; $i++) {
            $this->assertEquals($expected[$i], $actual[$i]);
        }
    }

    public function test_正常_select_検索ip()
    {
        $threadService = new ThreadService(new UtilService);
        $this->insertTestData();
        $actual = $threadService->select(20, '5')->toArray()['data'];
        $expected = array_reverse(Thread::with('user')->where('ip_address', 'LIKE', "%5%")->get()->toArray());
        $len = count($actual);
        for ($i = 0; $i < $len; $i++) {
            $this->assertEquals($expected[$i], $actual[$i]);
        }
    }

    public function test_正常_select_検索title()
    {
        $threadService = new ThreadService(new UtilService);
        $this->insertTestData();
        $actual = $threadService->select(20, 'd2')->toArray()['data'][0];
        $expected = Thread::with('user')->find(2)->toArray();
        $this->assertEquals($expected, $actual);
    }

    public function test_正常_selectById()
    {
        $threadService = new ThreadService(new UtilService);
        $this->insertTestData();
        $expected = Thread::with('user')->find(2)->toArray();
        $actual = $threadService->selectById(2)->toArray();
        $this->assertEquals($expected, $actual);
    }

    public function test_異常_updateOwnThread_スレッドが無い()
    {
        $threadService = new ThreadService(new UtilService);
        $thread_id = 123;
        try {
            $threadService->updateOwnThread(1, $thread_id, 'タイトル');
        } catch (HttpResponseException $e) {
            $expected = json_encode([
                'status' => 400,
                'message' => "thread_id {$thread_id} は存在しません。",
            ]);
            $actual = json_encode($e->getResponse()->original);
            $this->assertEquals($expected, $actual);
        }
    }

    public function test_異常_updateOwnThread_自分のスレッドではない()
    {
        $threadService = new ThreadService(new UtilService);
        $this->insertTestData();
        try {
            $threadService->updateOwnThread(2, 2, 'タイトル');
        } catch (HttpResponseException $e) {
            $expected = json_encode([
                'status' => 400,
                'message' => "他のユーザのスレッドは編集できません。",
            ]);
            $actual = json_encode($e->getResponse()->original);
            $this->assertEquals($expected, $actual);
        }
    }

    public function test_正常_updateOwnThread()
    {
        $threadService = new ThreadService(new UtilService);
        $this->insertTestData();
        $title = '編集後のタイトル!?';
        $thread_id = 2;
        $actual = $threadService->updateOwnThread(1, $thread_id, $title)->toArray();
        $expected = Thread::with('user')->find($thread_id)->toArray();
        $this->assertEquals($title, $actual['title']);
        $this->assertEquals($expected, $actual);
    }
}
