<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Http\Resources\RoleResource;
use  App\Http\Requests\RoleRequest;

class RoleController extends Controller
{

    public function index()
    {
        $role = Role::all();

        return RoleResource::collection($role);
    }

    public function store(RoleRequest $request): RoleResource
    {
        $role = Role::create($request->all());

        return new RoleResource($role);
    }

    public function show(Role $role): RoleResource
    {
        return new RoleResource($role);
    }

    public function update(RoleRequest $request, Role $role): RoleResource
    {
        $role->update($request->all());

        return new RoleResource($role);
    }

    public function destroy(Role $role): RoleResource
    {
        $role->delete();

        return new RoleResource($role);
    }
}
