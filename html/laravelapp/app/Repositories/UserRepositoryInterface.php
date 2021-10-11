<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface
{
    public function create(string $name, string $email, string $password): ?User;
    public function selectByEmail(string $email): Collection;
}
