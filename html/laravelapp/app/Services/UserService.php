<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\NewAccessToken;

class UserService
{
    protected $utilService;

    public function __construct(UtilService $utilService)
    {
        $this->utilService = $utilService;
    }

    public function create(string $name, string $email, string $passwordPlain): User
    {
        if (User::where('email', $email)->count()) {
            /* emailが使われていた */
            $this->utilService->throwHttpResponseException("email ${email} は既に登録されています。");
        }

        /* 作成して返却する */
        return User::create([
            'name'     => $name,
            'email'    => $email,
            'password' => Hash::make($passwordPlain),
        ]);
    }

    public function login(string $email, string $passwordPlain): array
    {
        $fnThrow = fn () => $this->utilService->throwHttpResponseException('emailとpasswordの組み合わせが不正です。');
        $user = User::where('email', $email)->first();

        if (!$user) {
            /* emailが存在しなかった */
            $fnThrow();
        }
        if (!Hash::check($passwordPlain, $user->password)) {
            /* emailとpasswordが一致しなかった */
            $fnThrow();
        }

        /* 1ユーザにつき有効なトークンは1つだけにする */
        $user->tokens()->delete();

        /* トークンを発行する */
        $token = $user->createToken('token-name');

        /* トークンを返却する */
        return [
            'token' => $token->plainTextToken,
        ];
    }

    public function logout(User $user): array
    {
        /* 有効なトークンを全て削除する */
        $user->tokens()->delete();
        return [
            'message' => 'ログアウトしました。既存のトークンは失効しました。',
        ];
    }
}
