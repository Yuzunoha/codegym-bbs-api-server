<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRegisterPost;
use App\Models\User;
use App\Services\UtilService;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login()
    {
        return 'loginです';
    }

    public function register(AuthRegisterPost $request)
    {
        /* emailチェック */
        $email = $request->email;
        if (User::where('email', $email)->first()) {
            UtilService::throwHttpResponseException("email ${email} は既に登録されています。");
        }

        return User::create([
            'name'     => $request->name,
            'email'    => $email,
            'password' => Hash::make($request->password),
        ]);
    }
}
