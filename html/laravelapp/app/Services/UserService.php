<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use Laravel\Sanctum\NewAccessToken;

class UserService implements UserServiceInterface
{
    protected $userRepository;
    protected $utilService;

    public function __construct(
        UserRepositoryInterface $userRepository,
        UtilServiceInterface    $utilService
    ) {
        $this->userRepository = $userRepository;
        $this->utilService    = $utilService;
    }

    public function create(string $name, string $email, string $passwordHash): ?User
    {
        /* emailチェック */
        if ($this->userRepository->selectByEmail($email)->count()) {
            $this->utilService->throwHttpResponseException("email ${email} は既に登録されています。");
        }

        return $this->userRepository->create($name, $email, $passwordHash);
    }

    public function createToken(User $user, $tokenName = 'token-name'): NewAccessToken
    {
        return $this->userRepository->createToken($user, $tokenName);
    }

    public function deleteAllTokens(User $user): void
    {
        $this->userRepository->deleteAllTokens($user);
    }
}
