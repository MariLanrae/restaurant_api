<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Resources\UserResource;
use App\Http\Requests\UserRequest;
use App\Actions\UserAction;

class UserController extends Controller
{

    public function index(UserRequest $request, UserAction $action)
    {
        $validated = $request->validated();

        $user = $action->userIndex($validated);

        return UserResource::collection($user);
    }

    public function store(UserRequest $request, UserAction $action): UserResource
    {
        $validated = $request->validated();

        $user = $action->userStore($validated);

        return new UserResource($user);
    }

    public function show($id, UserAction $action): UserResource
    {
        $user = $action->userShow($id);

        return new UserResource($user);
    }

    public function update(User $user, UserRequest $request, UserAction $action): UserResource
    {
        $validated = $request->validated();

        $user = $action->userUpdate($user, $validated);

        return new UserResource($user);
    }

    public function destroy(User $user, UserAction $action): UserResource
    {
        $user = $action->userDelete($user);

        return new UserResource($user);
    }
}
