<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Http\Resources\UserResource;
use  App\Http\Requests\UserRequest;

class UserController extends Controller
{
    public function index_sort(UserRequest $request)
    {
        $users = User::all();

        if (isset ($request['name'])){
            $users = $users->sortBy('name', $request['sort_order']);
        }
        elseif (isset ($request['role_id'])){
            $users = $users->sortBy('role_id',  $request['sort_order']);
        }

        return UserResource::collection($users);
    }

    public function index_search(UserRequest $request)
    {
        $user = User::all();

        if (isset ($request['name'])) {
            $user->where('name', 'like', $request['name']);
        }
        elseif (isset ($request['email'])) {
            $user->where('email', 'like', $request['email']);
        }
        elseif (isset ($request['role_id'])) {
            $user->where('role_id', 'like', $request['role_id']);
        }

        return UserResource::collection($user);
    }

    public function store(UserRequest $request): UserResource
    {
        $user = User::create($request->all());

        return new UserResource($user);
    }

    public function show(User $user): UserResource
    {
        return new UserResource($user);
    }

    public function update(UserRequest $request, User $user): UserResource
    {
        $user->update($request->all());

        return new UserResource($user);
    }

    public function destroy(User $user): UserResource
    {
        $user->delete();

        return new UserResource($user['deleted_at']);
    }
}
