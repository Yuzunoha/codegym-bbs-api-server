<?php

namespace Tests\Unit;

use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;
use App\Services\ThreadService;
use App\Services\UserService;
use App\Services\UtilService;
use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

class ThreadServiceTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
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
        Thread::create(['user_id' => 1, 'title' => 'thread1', 'ip_address' => 'ip無し',]);
        Thread::create(['user_id' => 1, 'title' => 'thread2', 'ip_address' => 'ip無し',]);
        Thread::create(['user_id' => 1, 'title' => 'thread3', 'ip_address' => 'ip無し',]);
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
        Thread::create(['user_id' => 1, 'title' => 'thread1', 'ip_address' => '123',]);
        Thread::create(['user_id' => 1, 'title' => 'thread2', 'ip_address' => '345',]);
        Thread::create(['user_id' => 1, 'title' => 'thread3', 'ip_address' => '567',]);
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
        Thread::create(['user_id' => 1, 'title' => 'thread1', 'ip_address' => '123',]);
        Thread::create(['user_id' => 1, 'title' => 'thread2', 'ip_address' => '345',]);
        Thread::create(['user_id' => 1, 'title' => 'thread3', 'ip_address' => '567',]);
        $actual = $threadService->select(20, 'd2')->toArray()['data'][0];
        $expected = Thread::with('user')->find(2)->toArray();
        $this->assertEquals($expected, $actual);
    }

    // 個々から下はUserServiceのテストのコピー

    public function test_異常_login_email不正()
    {
        $userService = new UserService(new UtilService);
        ['email' => $email, 'password' => $password] = $this->getTestUserData();
        try {
            $userService->login($email . 'a', $password);
        } catch (HttpResponseException $e) {
            $expected = json_encode([
                'status' => 400,
                'message' => 'emailとpasswordの組み合わせが不正です。',
            ]);
            $actual = json_encode($e->getResponse()->original);
            $this->assertEquals($expected, $actual);
        }
    }

    public function test_異常_login_password不正()
    {
        $userService = new UserService(new UtilService);
        ['email' => $email, 'password' => $password] = $this->getTestUserData();
        try {
            $userService->login($email, $password . 'a');
        } catch (HttpResponseException $e) {
            $expected = json_encode([
                'status' => 400,
                'message' => 'emailとpasswordの組み合わせが不正です。',
            ]);
            $actual = json_encode($e->getResponse()->original);
            $this->assertEquals($expected, $actual);
        }
    }

    public function test_正常_login()
    {
        $userService = new UserService(new UtilService);
        ['email' => $email, 'password' => $password] = $this->getTestUserData();
        $token = $userService->login($email, $password)['token'];
        $response = $this->get('/users', ['Authorization' => "Bearer {$token}"]);
        $response->assertOk();
    }

    public function test_正常_login_自分の古いtokenを失効させる()
    {
        $userService = new UserService(new UtilService);
        ['email' => $email, 'password' => $password] = $this->getTestUserData();
        $userService->login($email, $password);
        $response = $this->get('/users', ['Authorization' => "Bearer {$this->token}"]);
        $response->assertStatus(401);
    }

    public function test_正常_register()
    {
        $userService = new UserService(new UtilService);
        $user = $userService->register($name = 'a', $email = 'a@a.com', $password = Str::random());
        $this->assertEquals($name, $user->name);
        $this->assertEquals($email, $user->email);
        $response = $this->post('/login', ['email' => $email, 'password' => $password]);
        $response->assertOk();
    }

    public function test_異常_email重複()
    {
        $userService = new UserService(new UtilService);
        ['email' => $email] = $this->getTestUserData();
        try {
            $userService->register('a', $email, Str::random());
        } catch (HttpResponseException $e) {
            $expected = json_encode([
                'status' => 400,
                'message' => "email {$email} は既に登録されています。",
            ]);
            $actual = json_encode($e->getResponse()->original);
            $this->assertEquals($expected, $actual);
        }
    }

    public function test_正常_logout()
    {
        $userService = new UserService(new UtilService);
        ['email' => $email] = $this->getTestUserData();
        $loginUser = User::where('email', $email)->first();
        $userService->logout($loginUser);
        // 認証に失敗すること
        $this->get('/users', $this->getAuthorizationHeader())->assertStatus(401);
    }

    public function test_正常_delete()
    {
        $userService = new UserService(new UtilService);
        ['email' => $email] = $this->getTestUserData();
        $loginUser = User::where('email', $email)->first();
        $thread = Thread::create([
            'user_id'    => $loginUser->id,
            'title'      => 'ダミータイトル',
            'ip_address' => 'ダミーIPアドレス',
        ]);
        Reply::create([
            'thread_id'  => $thread->id,
            'number'     => 1,
            'user_id'    => $loginUser->id,
            'text'       => 'ダミー投稿',
            'ip_address' => 'ダミーIPアドレス',
        ]);
        Reply::create([
            'thread_id'  => $thread->id,
            'number'     => 1,
            'user_id'    => $loginUser->id + 1,
            'text'       => 'ダミー投稿',
            'ip_address' => 'ダミーIPアドレス',
        ]);
        // テスト対象のメソッドを実行する
        $userService->deleteLoginUser($loginUser);

        // 確認:自分のリプライを全て削除する(スレッドは残る)
        $this->assertEquals(1, Reply::count());

        // 確認:自分のトークンを全て削除する
        $this->assertEquals(0, count(DB::select('select * from personal_access_tokens')));

        // 確認:自分のユーザ情報を削除する
        $this->assertEquals(null, User::find($loginUser->id));
    }

    public function test_正常_updateUser()
    {
        $userService = new UserService(new UtilService);
        ['name' => $name, 'email' => $email] = $this->getTestUserData();
        $loginUser = User::where('email', $email)->first();
        $nameUpdated = $name . '編集しました';
        $userService->updateUser($loginUser, $nameUpdated);
        $this->assertEquals($nameUpdated, User::find($loginUser->id)->name);
    }

    public function test_正常_select_name()
    {
        $userService = new UserService(new UtilService);
        User::create([
            'name' => 'a',
            'email' => 'A@gmail.com',
            'password' => 'pass'
        ]);
        User::create([
            'name' => 'B',
            'email' => 'b@mymail.net',
            'password' => 'pass'
        ]);
        $this->assertEquals(3, count($userService->select(20)->toArray()['data']));
        $this->assertEquals('B', $userService->select(20, 'B')->toArray()['data'][0]['name']);
        $this->assertEquals('a', $userService->select(20, 'A@gmail')->toArray()['data'][0]['name']);
        $this->assertEquals(1, count($userService->select(20, 'mymail')->toArray()['data']));
        $this->assertEquals(3, count($userService->select(20, 'mail')->toArray()['data']));
    }

    public function test_正常_selectById()
    {
        $userService = new UserService(new UtilService);
        User::create([
            'name' => 'a',
            'email' => 'A@gmail.com',
            'password' => 'pass'
        ]);
        $user = User::create([
            'name' => 'B',
            'email' => 'b@mymail.net',
            'password' => 'pass'
        ]);
        $ret = $userService->selectById($user->id);
        $expected = $user->toArray();
        $actual = $ret->toArray();
        $this->assertEquals(count($expected), count($actual));
        foreach ($expected as $key => $expectedValue) {
            $this->assertEquals($expectedValue, $actual[$key]);
        }
    }
}
