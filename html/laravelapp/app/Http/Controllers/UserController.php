<?php

namespace App\Http\Controllers;

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

    public function login()
    {
        return 'loginです';
    }

    public function register(UserRegisterPost $request)
    {
        /* emailチェック */
        $email = $request->email;
        if (User::where('email', $email)->first()) {
            $this->utilService->throwHttpResponseException("email ${email} は既に登録されています。");
        }

        return $this->userService->create(
            $request->name,
            $email,
            Hash::make($request->password)
        );
    }
}
