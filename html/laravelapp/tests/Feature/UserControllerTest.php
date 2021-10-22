<?php

namespace Tests\Feature;

use Tests\TestCase;

class UserControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->新規登録してトークンを取得する();
    }

    public function test_正常_新規登録とloginと1件取得()
    {
        $input = ['name' => 'a', 'email' => 'a@a.com', 'password' => 'a',];
        $expected = $this->post('/register', $input)->getData();
        $response = $this->post('/login', $input);
        $response->assertOk();
        $token = $response->getData()->token;
        $actual = $this->get("/users/{$expected->id}", [
            'Authorization' => "Bearer {$token}"
        ])->getData();
        foreach (['id', 'name', 'email'] as $key) {
            $this->assertEquals($expected->$key, $actual->$key);
        }
    }

    public function test_異常_login_emailがDBに無い()
    {
        $input = ['name' => 'a', 'email' => 'a@a.com', 'password' => 'a',];
        $this->post('/register', $input);
        $response = $this->post('/login', [
            'email' => 'a@A.com',
            'password' => 'a',
        ]);
        $response->assertStatus(400);
        $actual = $response->getData();
        $this->assertEquals($actual->status, 400);
        $this->assertEquals($actual->message, 'emailとpasswordの組み合わせが不正です。');
    }

    public function test_異常_register_email重複()
    {
        $input = ['name' => 'a', 'email' => 'a@a.com', 'password' => 'a',];
        $this->post('/register', $input);
        $response = $this->post('/register', $input);
        $response->assertStatus(400);
        $actual = $response->getData();
        $this->assertEquals($actual->status, 400);
        $this->assertEquals($actual->message, "email {$input['email']} は既に登録されています。");
    }

    public function test_一覧取得()
    {
        $input = [
            ['name' => 'a', 'email' => 'a@a.com', 'password' => 'a',],
            ['name' => 'b', 'email' => 'b@b.com', 'password' => 'b',],
            ['name' => 'c', 'email' => 'c@c.com', 'password' => 'c',],
        ];
        foreach ($input as $e) {
            $this->post('/register', $e);
        }
        $expected = array_reverse(array_merge([$this->getTestUserData()], $input));
        $actual = $this->get('/users', $this->getAuthorizationHeader())->getData()->data;
        for ($i = 0; $i < count($expected); $i++) {
            foreach (['name', 'email'] as $key) {
                $this->assertEquals($expected[$i][$key], $actual[$i]->$key);
            }
        }
    }
}
