<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginPost;
use App\Http\Requests\UserPatch;
use App\Http\Requests\UserRegisterPost;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected $userService;

    public function __construct(
        UserService $userService
    ) {
        $this->userService = $userService;
    }

    public function login(UserLoginPost $request)
    {
        return $this->userService->login(
            $request->name,
            $request->password
        );
    }

    public function register(UserRegisterPost $request)
    {
        return $this->userService->register(
            $request->name,
            $request->bio,
            $request->password
        );
    }

    public function logout()
    {
        return $this->userService->logout(Auth::user());
    }

    public function deleteLoginUser()
    {
        return $this->userService->deleteLoginUser(Auth::user());
    }

    public function updateUser(UserPatch $request)
    {
        return $this->userService->updateUser(
            Auth::user(),
            $request->bio
        );
    }

    public function select(Request $request)
    {
        return $this->userService->select(
            $request->per_page,
            $request->q
        );
    }

    public function selectById($id)
    {
        return $this->userService->selectById($id);
    }
}
