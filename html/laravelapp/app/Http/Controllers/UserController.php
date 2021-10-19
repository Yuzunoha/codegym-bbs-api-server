<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginPost;
use App\Http\Requests\UserPatch;
use App\Http\Requests\UserRegisterPost;
use App\Http\Requests\UserUpdatePut;
use App\Models\Reply;
use App\Models\User;
use App\Services\UtilService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    protected $utilService;

    public function __construct(UtilService $utilService)
    {
        $this->utilService = $utilService;
    }

    public function login(UserLoginPost $request)
    {
        $fnThrow = fn () => $this->utilService->throwHttpResponseException('emailとpasswordの組み合わせが不正です。');
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            /* emailが存在しなかった */
            $fnThrow();
        }
        if (!Hash::check($request->password, $user->password)) {
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

    public function register(UserRegisterPost $request)
    {
        $email = $request->email;
        if (User::where('email', $email)->count()) {
            /* emailが使われていた */
            $this->utilService->throwHttpResponseException("email ${email} は既に登録されています。");
        }

        /* 作成して返却する */
        return User::create([
            'name'     => $request->name,
            'email'    => $email,
            'password' => Hash::make($request->password),
        ]);
    }

    public function logout()
    {
        /* 有効なトークンを全て削除する */
        Auth::user()->tokens()->delete();
        return [
            'message' => 'ログアウトしました。既存のトークンは失効しました。',
        ];
    }

    public function selectAll(Request $request)
    {
        return User::paginate($request->per_page);
    }

    public function deleteLoginUser()
    {
        // 自分のリプライを全て削除する(スレッドは残る)
        Reply::where('user_id', Auth::id())->delete();

        // 自分のトークンを全て削除する
        Auth::user()->tokens()->delete();

        // 自分のユーザ情報を削除する
        Auth::user()->delete();

        return [
            'message' => 'ユーザ情報を削除しました。',
        ];
    }

    public function updateUser(UserPatch $request)
    {
        Auth::user()->update([
            'name' => $request->name,
        ]);
        return Auth::user();
    }

    public function select(Request $request)
    {
        $builder = User::query();
        if ($request->q) {
            $builder = $builder->where('name', 'LIKE', '%' . $request->q . '%');
        }
        return $builder->paginate($request->per_page);
    }

    public function selectAuth()
    {
        return Auth::user();
    }

    public function selectById($id)
    {
        return User::find($id);
    }
}
