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

    public function test_ユーザ1件取得()
    {
        $this->p('test_ユーザ1件取得');
        $this->p('test_ユーザ1件取得');

        $this->assertTrue(true);
    }
}
