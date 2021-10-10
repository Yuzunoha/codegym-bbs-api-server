<?php

namespace App\Repositories;

use App\Models\User;

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
}
