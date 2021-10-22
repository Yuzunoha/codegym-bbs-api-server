<?php

namespace App\Services;

use App\Models\User;
use App\Services\UtilService;
use Illuminate\Support\Facades\Hash;

class UserService
{
    protected $utilService;

    public function __construct(UtilService $utilService)
    {
        $this->utilService = $utilService;
    }

    public function login($email, $password)
    {
        $fnThrow = fn () => $this->utilService->throwHttpResponseException('emailとpasswordの組み合わせが不正です。');
        $user = User::where('email', $email)->first();

        if (!$user) {
            /* emailが存在しなかった */
            $fnThrow();
        }
        if (!Hash::check($password, $user->password)) {
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
}
