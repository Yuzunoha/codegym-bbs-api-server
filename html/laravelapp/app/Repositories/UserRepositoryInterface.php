<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Laravel\Sanctum\NewAccessToken;

interface UserRepositoryInterface
{
    public function create(string $name, string $email, string $password): ?User;
    public function selectByEmail(string $email): Collection;
    public function createToken(User $user, string $tokenName): NewAccessToken;
}
