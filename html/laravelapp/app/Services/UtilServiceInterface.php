<?php

namespace App\Services;

interface UtilServiceInterface
{
    public function getIp(): string;
    public function throwHttpResponseException($message, int $status = 400): void;
}
