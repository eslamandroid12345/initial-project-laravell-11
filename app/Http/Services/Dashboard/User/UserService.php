<?php

namespace App\Http\Services\Dashboard\User;

use App\Repository\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;

class UserService
{
    public function __construct(private readonly UserRepositoryInterface $userRepository)
    {
    }

    public function index()
    {
        $users = $this->userRepository->paginate(25);
        return view('dashboard.site.users.index', compact('users'));
    }

    public function create()
    {
        return view('dashboard.site.users.create');
    }

    public function store($request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();

            $user = $this->userRepository->create($data);
            DB::commit();
            return redirect()->route('users.index', $user->id)->with(['success' => __('messages.created_successfully')]);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with(['error' => __('messages.Something went wrong')]);
        }
    }
    public function show($id)
    {
        $user = $this->userRepository->getById($id);
        return view('dashboard.site.users.show', compact('user'));
    }
    public function edit($id)
    {
        $user = $this->userRepository->getById($id);
        return view('dashboard.site.users.edit', compact('user'));
    }

    public function update($request, $id)
    {
        try {
            $user=$this->userRepository->getById($id);
            $data = $request->validated();
            if ($data['password'] == null) {
                unset($data['password']);
            }
            $this->userRepository->update($id, $data);
            return redirect()->route('users.update', $user->id)->with(['success' => __('messages.updated_successfully')]);
        } catch (\Exception $e) {
            return back()->with(['error' => __('messages.Something went wrong')]);
        }
    }

    public function destroy($id)
    {
        try {
            $this->userRepository->delete($id);
            return redirect()->back()->with(['success' => __('messages.deleted_successfully')]);
        } catch (\Exception $e) {
            return back()->with(['error' => __('messages.Something went wrong')]);
        }
    }

    public function showActiveUsers()
    {
        $users = $this->userRepository->getActiveUsers();

        // do something with $users
        // ...
        // ...
        // return ...
    }
}
