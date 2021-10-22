<?php

namespace Tests\Unit;

use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;
use App\Services\UserService;
use App\Services\UtilService;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

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
}
