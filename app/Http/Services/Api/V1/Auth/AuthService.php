<?php

namespace App\Http\Services\Api\V1\Auth;

use App\Http\Helpers\Get;
use App\Http\Helpers\Response;
use App\Http\Requests\Api\V1\Auth\SignInRequest;
use App\Http\Requests\Api\V1\Auth\SignUpRequest;
use App\Http\Resources\V1\User\UserResource;
use App\Http\Services\PlatformService;
use App\Repository\UserRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;

abstract class AuthService extends PlatformService
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    )
    {
    }

    public function signUp(SignUpRequest $request) {
        DB::beginTransaction();
        try {
            $data = $request->validated();

            $user = $this->userRepository->create($data);

            DB::commit();
            return Response::success(message: __('messages.created successfully'), data: new UserResource($user, false));
        } catch (Exception $e) {
            DB::rollBack();
//            dd($e);
            return Response::fail(message: __('messages.Something went wrong'));
        }
    }

    public function signIn(SignInRequest $request) {
        $credentials = $request->only('email', 'password');
        $token = auth('api')->attempt($credentials);
        if ($token) {
            return Response::success(message: __('messages.Successfully authenticated'), data: new UserResource(auth('api')->user(), true));
        }

        return Response::fail(status: 401, message: __('messages.wrong credentials'));
    }

    public function signOut() {
        auth('api')->logout();
        return Response::success(message: __('messages.Successfully loggedOut'));
    }

}
