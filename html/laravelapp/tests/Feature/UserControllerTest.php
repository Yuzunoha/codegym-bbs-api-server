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
        $this->post('/register', [
            'name' => 'a',
            'email' => 'a@a.com',
            'password' => 'a',
        ]);
        $this->post('/register', [
            'name' => 'b',
            'email' => 'b@b.com',
            'password' => 'b',
        ]);
        $this->post('/register', [
            'name' => 'c',
            'email' => 'c@c.com',
            'password' => 'c',
        ]);
        $response = $this->get('/users', $this->getAuthorizationHeader());
        $this->p($response->getData());
        $this->assertTrue(true);
    }
}
