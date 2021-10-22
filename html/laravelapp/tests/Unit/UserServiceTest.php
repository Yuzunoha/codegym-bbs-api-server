<?php

namespace Tests\Unit;

use App\Services\UserService;
use App\Services\UtilService;
use Illuminate\Http\Exceptions\HttpResponseException;
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
            $a = $e->getResponse()->original;
            $this->assertEquals(400, $a['status']);
            $this->assertEquals('emailとpasswordの組み合わせが不正です。', $a['message']);
        }
    }

    public function test_異常_login_password不正()
    {
        $userService = new UserService(new UtilService);
        ['email' => $email, 'password' => $password] = $this->getTestUserData();
        try {
            $userService->login($email, $password . 'a');
        } catch (HttpResponseException $e) {
            $a = $e->getResponse()->original;
            $this->assertEquals(400, $a['status']);
            $this->assertEquals('emailとpasswordの組み合わせが不正です。', $a['message']);
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
}
