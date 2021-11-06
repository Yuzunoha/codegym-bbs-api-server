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

    public function test_異常_login_name不正()
    {
        $userService = new UserService(new UtilService);
        ['name' => $name, 'password' => $password] = $this->getTestUserData();
        try {
            $userService->login($name . 'a', $password);
        } catch (HttpResponseException $e) {
            $expected = json_encode([
                'status' => 400,
                'message' => 'nameとpasswordの組み合わせが不正です。',
            ]);
            $actual = json_encode($e->getResponse()->original);
            $this->assertEquals($expected, $actual);
        }
    }

    public function test_異常_login_password不正()
    {
        $userService = new UserService(new UtilService);
        ['name' => $name, 'password' => $password] = $this->getTestUserData();
        try {
            $userService->login($name, $password . 'a');
        } catch (HttpResponseException $e) {
            $expected = json_encode([
                'status' => 400,
                'message' => 'nameとpasswordの組み合わせが不正です。',
            ]);
            $actual = json_encode($e->getResponse()->original);
            $this->assertEquals($expected, $actual);
        }
    }

    public function test_正常_login()
    {
        $userService = new UserService(new UtilService);
        ['name' => $name, 'password' => $password] = $this->getTestUserData();
        $token = $userService->login($name, $password)['token'];
        $response = $this->get('/users', ['Authorization' => "Bearer {$token}"]);
        $response->assertOk();
    }

    public function test_正常_login_自分の古いtokenを失効させる()
    {
        $userService = new UserService(new UtilService);
        ['name' => $name, 'password' => $password] = $this->getTestUserData();
        $userService->login($name, $password);
        $response = $this->get('/users', ['Authorization' => "Bearer {$this->token}"]);
        $response->assertStatus(401);
    }

    public function test_正常_register()
    {
        $userService = new UserService(new UtilService);
        $user = $userService->register($name = 'a', $bio = 'aの自己紹介', $password = Str::random());
        $this->assertEquals($name, $user->name);
        $this->assertEquals($bio, $user->bio);
        $response = $this->post('/login', ['name' => $name, 'password' => $password]);
        $response->assertOk();
    }

    public function test_異常_name重複()
    {
        $userService = new UserService(new UtilService);
        ['name' => $name] = $this->getTestUserData();
        try {
            $userService->register($name, '適当な自己紹介', Str::random());
        } catch (HttpResponseException $e) {
            $expected = json_encode([
                'status' => 400,
                'message' => "name {$name} は既に登録されています。",
            ]);
            $actual = json_encode($e->getResponse()->original);
            $this->assertEquals($expected, $actual);
        }
    }

    public function test_正常_logout()
    {
        $userService = new UserService(new UtilService);
        ['name' => $name] = $this->getTestUserData();
        $loginUser = User::where('name', $name)->first();
        $userService->logout($loginUser);
        // 認証に失敗すること
        $this->get('/users', $this->getAuthorizationHeader())->assertStatus(401);
    }

    public function test_正常_delete()
    {
        $userService = new UserService(new UtilService);
        ['name' => $name] = $this->getTestUserData();
        $loginUser = User::where('name', $name)->first();
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
        ['name' => $name, 'bio' => $bio] = $this->getTestUserData();
        $loginUser = User::where('name', $name)->first();
        $bioUpdated = $bio . '編集しました';
        $userService->updateUser($loginUser, $bioUpdated);
        $this->assertEquals($bioUpdated, User::find($loginUser->id)->bio);
    }

    public function test_正常_select_name()
    {
        $userService = new UserService(new UtilService);
        User::create([
            'name' => 'a',
            'bio' => 'A自己紹介',
            'password' => 'pass'
        ]);
        User::create([
            'name' => 'B',
            'bio' => 'b自己紹介',
            'password' => 'pass'
        ]);
        User::create([
            'name' => 'ccccacccc',
            'bio' => 'c自己紹介a',
            'password' => 'pass'
        ]);
        $this->assertEquals(4, count($userService->select(20)->toArray()['data']));
        $this->assertEquals('B', $userService->select(20, 'B')->toArray()['data'][0]['name']);
        $this->assertEquals(0, count($userService->select(20, 'pass')->toArray()['data'])); // passwordは検索しない
        $expected = [User::find(4)->toArray(), User::find(2)->toArray(),]; // nameとbioをそれぞれ検索する
        $actual = $userService->select(20, 'a')->toArray()['data'];
        $this->assertEquals($expected, $actual);
    }

    public function test_正常_selectById()
    {
        $userService = new UserService(new UtilService);
        User::create([
            'name' => 'a',
            'bio' => 'A@gmail.com自己紹介',
            'password' => 'pass'
        ]);
        $user = User::create([
            'name' => 'B',
            'bio' => 'b@mymail.net自己紹介',
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
