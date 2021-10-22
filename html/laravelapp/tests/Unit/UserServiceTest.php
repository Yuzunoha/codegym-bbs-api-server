<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\UserService;
use App\Services\UtilService;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Mockery;
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
}
