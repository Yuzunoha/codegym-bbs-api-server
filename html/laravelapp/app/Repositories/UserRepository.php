<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserRepository implements UserRepositoryInterface
{
    public function create(string $name, string $email, string $password): ?User
    {
        return User::create([
            'name'     => $name,
            'email'    => $email,
            'password' => $password,
        ]);
    }

    public function selectByEmail(string $email): Collection
    {
        return User::where('email', $email)->get();
    }
}
