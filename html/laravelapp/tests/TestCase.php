<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $token;

    protected function p($a = '')
    {
        echo PHP_EOL;
        print_r($a);
        echo PHP_EOL;
    }

    protected function getTestUserData()
    {
        return [
            'name'     => 'テスト太郎',
            'email'    => 'test_taro@gmail.com',
            'password' => 'test_taro_123',
        ];
    }

    protected function 新規登録してトークンを取得する()
    {
        // テストユーザの情報
        $testUserData = $this->getTestUserData();

        // 新規登録
        $this->post('/register', $testUserData);

        // ログイン
        $this->token = $this->post('/login', $testUserData)->getData()->token;
    }

    protected function getAuthorizationHeader()
    {
        return [
            'Authorization' => "Bearer {$this->token}",
        ];
    }
}
