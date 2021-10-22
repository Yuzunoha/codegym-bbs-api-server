<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginPost;
use App\Http\Requests\UserPatch;
use App\Http\Requests\UserRegisterPost;
use App\Services\UserService;
use App\Services\UtilService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected $utilService;
    protected $userService;

    public function __construct(
        UtilService $utilService,
        UserService $userService
    ) {
        $this->utilService = $utilService;
        $this->userService = $userService;
    }

    public function login(UserLoginPost $request)
    {
        return $this->userService->login(
            $request->email,
            $request->password
        );
    }

    public function register(UserRegisterPost $request)
    {
        return $this->userService->register(
            $request->name,
            $request->email,
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
            $request->name
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
