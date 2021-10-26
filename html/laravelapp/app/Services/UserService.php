<?php

namespace App\Services;

use App\Models\Reply;
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

    public function register($name, $email, $password)
    {
        if (User::where('email', $email)->count()) {
            /* emailが使われていた */
            $this->utilService->throwHttpResponseException("email ${email} は既に登録されています。");
        }

        /* 作成して返却する */
        return User::create([
            'name'     => $name,
            'email'    => $email,
            'password' => Hash::make($password),
        ]);
    }

    public function logout($loginUser)
    {
        /* 有効なトークンを全て削除する */
        $loginUser->tokens()->delete();
        return [
            'message' => 'ログアウトしました。既存のトークンは失効しました。',
        ];
    }

    public function deleteLoginUser($loginUser)
    {
        // 自分のリプライを全て削除する(スレッドは残る)
        Reply::where('user_id', $loginUser->id)->delete();

        // 自分のトークンを全て削除する
        $loginUser->tokens()->delete();

        // 自分のユーザ情報を削除する
        $loginUser->delete();

        return [
            'message' => 'ユーザ情報を削除しました。',
        ];
    }

    public function updateUser($loginUser, $name)
    {
        $loginUser->update([
            'name' => $name,
        ]);
        return User::find($loginUser->id);
    }

    public function select($per_page, $q = null)
    {
        $builder = $q
            ? User::where('name', 'LIKE', '%' . $q . '%')
            : User::query();
        return $builder->orderBy('id', 'desc')->paginate($per_page);
    }

    public function selectById($id)
    {
        return User::find($id);
    }
}
