<?php

namespace App\Services;

use App\Models\User;
use Laravel\Sanctum\NewAccessToken;

interface UserServiceInterface
{
    public function create(string $name, string $email, string $passwordPlain): ?User;
    public function createToken(User $user, string $tokenName): NewAccessToken;
    public function login(string $email, string $passwordPlain): array;
    public function deleteAllTokens(User $user): void;
}
