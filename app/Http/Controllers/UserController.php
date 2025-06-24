<?php

namespace App\Http\Controllers;
use App\Models\Role;
use App\Models\User;
use App\Http\Resources\UserResource;
use  App\Http\Requests\UserRequest;

class UserController extends Controller
{

    public function index(UserRequest $request)
    {
        $validated = $request->validated();

        $user = User::query();

        if (isset($validated['search'])) {
            if ($validated['search'] == 'name') {
                $user = $user->where('name', 'like', '%' . $validated['search_order'] . '%');
            }
            elseif ($validated['search'] == 'role_id') {
                $user = $user->where('role_id', 'like', '%' . $validated['search_order'] . '%');
            }
            elseif ($validated['search'] == 'email') {
                $user = $user->where('email', 'like', '%' . $validated['search_order'] . '%');
        }
        }
        if (isset($validated['sort'])) {
             $user = $user->orderBy($validated['sort'], ($validated['sort_order'] ?? 'asc'));
        }
        $val = $user->get();
        return UserResource::collection($val);
    }

    public function store(UserRequest $request): UserResource
    {
        $validated = $request->validated();

        $role = Role::where('id', $validated['role_id'])->firstOrFail();
        $validated['role_id'] = $role->id;
        $user = User::create($validated->all());

        return new UserResource($user);
    }

    public function show(User $user): UserResource
    {
        return new UserResource($user);
    }

    public function update(User $user, UserRequest $request): UserResource
    {
        $validated = $request->validated();

        if ($validated['name']) {
            $user->name = $validated['name'];
        }
        if ($validated['email']) {
            $user->email = $validated['email'];
        }
        if ($validated['role_id']) {
            $user->role_id = $validated['role_id'];
        }
        $user->save();

        return new UserResource($user);
    }

    public function destroy(User $user): UserResource
    {
        $user->delete();

        return new UserResource($user);
    }
}
