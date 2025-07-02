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
                $user = $user->where('name', 'iLike', '%' . $validated['search_order'] . '%');
            }
            elseif ($validated['search'] == 'role_id') {
                $user = $user->where('role_id', 'iLike', '%' . $validated['search_order'] . '%');
            }
            elseif ($validated['search'] == 'email') {
                $user = $user->where('email', 'iLike', '%' . $validated['search_order'] . '%');
        }
        }
        if (isset($validated['sort'])) {
             $user = $user->orderBy($validated['sort'], ($validated['sort_order'] ?? 'asc'));
        }
        $user = $user->paginate(perPage: $validated['perPage'], page:  $validated['page'])->withQueryString();

        return UserResource::collection($user);
    }

    public function store(UserRequest $request): UserResource
    {
        $validated = $request->validated();

        $role = Role::where('id', $validated['role_id'])->firstOrFail();
        $validated['role_id'] = $role->id;
        $user = User::create($validated);

        return new UserResource($user);
    }

    public function show($id): UserResource
    {
        $user = User::findOrFail($id);

        return new UserResource($user);
    }

    public function update(User $user, UserRequest $request): UserResource
    {
        $validated = $request->validated();

        $user->update($validated);

        return new UserResource($user);
    }

    public function destroy(User $user): UserResource
    {
        $user->delete();

        return new UserResource($user);
    }
}
