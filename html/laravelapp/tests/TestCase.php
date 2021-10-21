<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

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
}
