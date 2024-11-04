<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\SignInRequest;
use App\Http\Requests\Api\V1\Auth\SignUpRequest;
use App\Http\Services\Api\V1\Auth\AuthService;
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
            new Middleware('auth:api', only: ['signOut']),
        ];
    }

    public function signUp(SignUpRequest $request) {
        return $this->auth->signUp($request);
    }

    public function signIn(SignInRequest $request) {
        return $this->auth->signIn($request);
    }

    public function signOut()
    {
        return $this->auth->signOut();
    }

    public function whatIsMyPlatform()
    {
        return $this->auth->whatIsMyPlatform();
    }
}
