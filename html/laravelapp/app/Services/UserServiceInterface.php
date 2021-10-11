<?php

namespace App\Services;

use App\Models\User;
use Laravel\Sanctum\NewAccessToken;

interface UserServiceInterface
{
    public function create(string $name, string $email, string $passwordHash): ?User;
    public function createToken(User $user, string $tokenName): NewAccessToken;
    public function login(string $email, string $passwordHash): array;
    public function deleteAllTokens(User $user): void;
}
