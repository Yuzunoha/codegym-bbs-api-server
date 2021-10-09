<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class UserTest extends TestCase
{
    use RefreshDatabase;

    private $attributes;

    protected function setUp(): void
    {
        parent::setUp();

        $this->attributes = [
            'name'     => 'テスト太郎',
            'email'    => 'hoge@example.com',
            'password' => bcrypt('test'),
        ];
    }

    public function test_登録できる()
    {
        User::create($this->attributes);
        print_r(User::get()->toArray());
        $this->assertDatabaseHas('users', $this->attributes);
    }

    public function test_確認()
    {
        print_r(User::get()->toArray());
        $this->assertTrue(1 === 1);
    }
}
