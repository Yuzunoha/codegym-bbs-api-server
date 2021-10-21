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
}
