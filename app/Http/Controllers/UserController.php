<?php

namespace App\Http\Controllers;

use App\Actions\UserAction;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Http\Requests\UserRequest;

class UserController extends Controller
{
    public function index(UserRequest $request, UserAction $action)
    {
        $validated = $request->validated();
        $user = $action->index($validated);


        return UserResource::collection($user);
    }

    public function store(UserRequest $request, UserAction $action): UserResource
    {
        $validated = $request->validated();

        $user = $action->store($validated);

        return new UserResource($user);
    }

    public function show($id, UserAction $action): UserResource
    {
        $user = $action->show($id);

        return new UserResource($user);
    }

    public function update(User $user, UserRequest $request, UserAction $action): UserResource
    {
        $validated = $request->validated();

        $user = $action->update($user, $validated);

        return new UserResource($user);
    }

    public function destroy(User $user, UserAction $action): UserResource
    {
        $user = $action->destroy($user);

        return new UserResource($user);
    }
}
