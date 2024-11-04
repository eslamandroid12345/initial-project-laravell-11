<?php

namespace App\Http\Controllers\Dashboard\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Auth\LoginRequest;
use App\Http\Services\Dashboard\Auth\AuthService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AuthController extends Controller implements HasMiddleware
{
    public function __construct(
        private readonly AuthService $auth,
    )
    {
    }

    public static function middleware(): array
    {
        return [
            new Middleware('auth', only: ['logout']),
            new Middleware('guest', except: ['logout']),
        ];
    }

    public function _login() {
        return view('dashboard.site.auth.login');
    }

    public function login(LoginRequest $request) {
        return $this->auth->login($request);
    }

    public function logout() {
        return $this->auth->logout();
    }
}
