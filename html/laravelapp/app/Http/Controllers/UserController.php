<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginPost;
use App\Http\Requests\UserRegisterPost;
use App\Models\User;
use App\Services\UserServiceInterface;
use App\Services\UtilServiceInterface;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    protected $utilService;
    protected $userService;

    public function __construct(
        UtilServiceInterface $utilService,
        UserServiceInterface $userService
    ) {
        $this->utilService = $utilService;
        $this->userService = $userService;
    }

    public function login(UserLoginPost $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            /* emailが存在しなかった */
            $this->utilService->throwHttpResponseException('emailとpasswordの組み合わせが不正です。');
        }

        /* emailが存在した */
        if (!Hash::check($request->password, $user->password)) {
            /* emailとpasswordが一致しなかった */
            $this->utilService->throwHttpResponseException('emailとpasswordの組み合わせが不正です。');
        }

        /* emailとpasswordが一致した */
        $user->tokens()->delete();
        $token = $user->createToken("login:user{$user->id}")->plainTextToken;
        return ['token' => $token];
    }

    public function register(UserRegisterPost $request)
    {
        return $this->userService->create(
            $request->name,
            $request->email,
            Hash::make($request->password)
        );
    }
}
