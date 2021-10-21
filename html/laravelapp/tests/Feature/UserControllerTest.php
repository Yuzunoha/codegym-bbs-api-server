<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp(): void
    {
        parent::setUp();

        // token
        $this->accessToken = 'token!!!!';
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(400);
    }

    public function test_認証失敗()
    {
        $response = $this->get('/users', [
            'Authorization' => 'aaa',
        ]);

        // これでレスポンスデータが取れる
        // $this->p($response->decodeResponseJson());

        $response->assertUnauthorized();
    }

    public function test_ユーザ1件取得()
    {
        // TODO
        $this->assertTrue(true);
    }
}
