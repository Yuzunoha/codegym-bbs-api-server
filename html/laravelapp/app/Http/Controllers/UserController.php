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
        return $this->userService->login(
            $request->email,
            Hash::make($request->password)
        );
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
