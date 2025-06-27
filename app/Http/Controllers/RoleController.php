<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Http\Resources\RoleResource;
use  App\Http\Requests\RoleRequest;

class RoleController extends Controller
{

    public function index()
    {
        $role = Role::query();
        $role = $role->paginate(3);

        return RoleResource::collection($role);
    }

    public function store(RoleRequest $request): RoleResource
    {
        $validated = $request->validated();
        $role = Role::create($validated);

        return new RoleResource($role);
    }

    public function show($id) //RoleResource
    {
        $role = Role::findOrFail($id);

        return new RoleResource($role);
    }

    public function update(RoleRequest $request, Role $role): RoleResource
    {
        $validated = $request->validated();

        $role->update($validated);

        return new RoleResource($role);
    }

    public function destroy(Role $role): RoleResource
    {
        $role->delete();

        return new RoleResource($role);
    }
}
