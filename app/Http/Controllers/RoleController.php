<?php

namespace App\Http\Controllers;

use App\Actions\RoleAction;
use App\Models\Role;
use App\Http\Resources\RoleResource;
use  App\Http\Requests\RoleRequest;

class RoleController extends Controller
{

    public function index(RoleRequest $request, RoleAction $action)
    {
        $validated = $request->validated();
        $role = $action->roleIndex($validated);

        return RoleResource::collection($role);
    }

    public function store(RoleRequest $request, RoleAction $action): RoleResource
    {
        $validated = $request->validated();
        $role = $action->roleStore($validated);

        return new RoleResource($role);
    }

    public function show($id, RoleAction $action)
    {
        $role = $action->roleShow($id);

        return new RoleResource($role);
    }

    public function update(RoleRequest $request, Role $role, RoleAction $action): RoleResource
    {
        $validated = $request->validated();

        $role = $action->roleUpdate($validated, $role);

        return new RoleResource($role);
    }

    public function destroy(Role $role, RoleAction $action): RoleResource
    {
        $role = $action->roleDelete($role);

        return new RoleResource($role);
    }
}
