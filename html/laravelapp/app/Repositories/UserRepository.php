<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Laravel\Sanctum\NewAccessToken;

class UserRepository implements UserRepositoryInterface
{
    public function create(string $name, string $email, string $passwordHash): ?User
    {
        return User::create([
            'name'     => $name,
            'email'    => $email,
            'password' => $passwordHash,
        ]);
    }

    public function selectByEmail(string $email): Collection
    {
        return User::where('email', $email)->get();
    }

    public function createToken(User $user, string $tokenName): NewAccessToken
    {
        return $user->createToken($tokenName);
    }

    public function deleteAllTokens(User $user): void
    {
        $user->tokens()->delete();
    }
}
