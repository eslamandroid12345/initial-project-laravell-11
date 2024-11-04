<?php

namespace App\Http\Controllers\Dashboard\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\User\UserRequest;
use App\Http\Services\Dashboard\user\UserService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;

class UserController extends Controller implements HasMiddleware
{
    public function __construct(private readonly UserService $user)
    {
    }

    public static function middleware(): array
    {
        return [
            'auth'
        ];
    }

    public function index()
    {
        return $this->user->index();
    }

    public function show($id)
    {
        return $this->user->show($id);
    }

    public function create()
    {
        return $this->user->create();
    }

    public function store(UserRequest $request)
    {
        return $this->user->store($request);
    }

    public function edit(string $id)
    {
        return $this->user->edit($id);
    }

    public function update(UserRequest $request, string $id)
    {
        return $this->user->update($request, $id);
    }

    public function destroy(string $id)
    {
        return $this->user->destroy($id);
    }
}
