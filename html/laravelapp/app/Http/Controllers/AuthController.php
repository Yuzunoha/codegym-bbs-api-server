<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRegisterPost;
use App\Models\User;
use App\Services\UtilServiceInterface;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    protected $utilService;

    public function __construct(UtilServiceInterface $utilService)
    {
        $this->utilService = $utilService;
    }

    public function login()
    {
        return 'loginです';
    }

    public function register(AuthRegisterPost $request)
    {
        /* emailチェック */
        $email = $request->email;
        if (User::where('email', $email)->first()) {
            $this->utilService->throwHttpResponseException("email ${email} は既に登録されています。");
        }

        return User::create([
            'name'     => $request->name,
            'email'    => $email,
            'password' => Hash::make($request->password),
        ]);
    }
}
