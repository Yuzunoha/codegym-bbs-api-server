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

    public function login($name, $password)
    {
        $fnThrow = fn () => $this->utilService->throwHttpResponseException('nameとpasswordの組み合わせが不正です。');
        $user = User::where('name', $name)->first();

        if (!$user) {
            /* nameが存在しなかった */
            $fnThrow();
        }
        if (!Hash::check($password, $user->password)) {
            /* nameとpasswordが一致しなかった */
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

    public function register($name, $bio, $password)
    {
        if (User::where('name', $name)->count()) {
            /* nameが使われていた */
            $this->utilService->throwHttpResponseException("name ${name} は既に登録されています。");
        }

        /* 作成して返却する */
        return User::create([
            'name'     => $name,
            'bio'      => $bio,
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

    public function updateUser($loginUser, $bio)
    {
        $loginUser->update([
            'bio' => $bio,
        ]);
        return User::find($loginUser->id);
    }

    public function select($per_page, $q = null)
    {
        $builder = User::query();
        if ($q) {
            $builder = $builder
                ->where('name', 'LIKE', '%' . $q . '%')
                ->orWhere('bio', 'LIKE', '%' . $q . '%');
        }
        return $builder->orderBy('id', 'desc')->paginate($per_page);
    }

    public function selectById($id)
    {
        return User::find($id);
    }
}
