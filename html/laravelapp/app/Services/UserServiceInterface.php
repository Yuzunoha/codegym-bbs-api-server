<?php

namespace App\Services;

use App\Models\User;

interface UserServiceInterface
{
    public function create(string $name, string $email, string $password): ?User;
}
