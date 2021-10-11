<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;
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

    public function create(string $name, string $email, string $passwordPlain): ?User
    {
        /* emailチェック */
        if ($this->userRepository->selectByEmail($email)->count()) {
            $this->utilService->throwHttpResponseException("email ${email} は既に登録されています。");
        }

        return $this->userRepository->create(
            $name,
            $email,
            Hash::make($passwordPlain)
        );
    }

    public function createToken(User $user, $tokenName = 'token-name'): NewAccessToken
    {
        return $this->userRepository->createToken($user, $tokenName);
    }

    public function deleteAllTokens(User $user): void
    {
        $this->userRepository->deleteAllTokens($user);
    }

    public function login(string $email, string $passwordPlain): array
    {
        $fnThrow = fn () => $this->utilService->throwHttpResponseException('emailとpasswordの組み合わせが不正です。');
        $user = $this->userRepository->selectByEmail($email)->first();

        if (!$user) {
            /* emailが存在しなかった */
            $fnThrow();
        }
        if (!Hash::check($passwordPlain, $user->password)) {
            /* emailとpasswordが一致しなかった */
            $fnThrow();
        }

        /* 1ユーザにつき有効なトークンは1つだけにする */
        $this->deleteAllTokens($user);

        /* トークンを発行する */
        $token = $this->createToken($user);

        /* トークンを返却する */
        return [
            'token' => $token->plainTextToken,
        ];
    }

    public function logout(User $user): array
    {
        /* 有効なトークンを全て削除する */
        $this->deleteAllTokens($user);
        return [
            'message' => 'ログアウトしました。既存のトークンは失効しました。',
        ];
    }

    public function selectAll(): Collection
    {
        return $this->userRepository->selectAll();
    }
}
