<?php

namespace App\Services;

use App\Models\User;
use Laravel\Sanctum\NewAccessToken;

interface UserServiceInterface
{
    public function create(string $name, string $email, string $password): ?User;
    public function createToken(User $user, string $tokenName): NewAccessToken;
}
