<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $token;

    public function setUp(): void
    {
        parent::setUp();

        $this->新規登録してトークンを取得する();
    }

    /**
     * 降順で取得できるか
     */
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
        $expected = array_merge([$this->getTestUserData()], $input);
        $len = count($expected);
        $response = $this->get('/users', $this->getAuthorizationHeader());
        $actual = $response->getData()->data;
        $keys = ['name', 'email',];
        for ($i = 0; $i < $len; $i++) {
            foreach ($keys as $key) {
                $this->assertEquals($expected[$len - 1 - $i][$key], $actual[$i]->$key);
            }
        }
    }
}
