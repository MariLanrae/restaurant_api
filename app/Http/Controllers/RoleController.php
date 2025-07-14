<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Http\Resources\RoleResource;
use App\Http\Requests\RoleRequest;

class RoleController extends Controller
{

    public function index(RoleRequest $request)
    {
        $validated = $request->validated();
        $role = Role::query();
        $role = $role->paginate(perPage: $validated['perPage'], page:  $validated['page'])->withQueryString();

        return RoleResource::collection($role);
    }

    public function store(RoleRequest $request): RoleResource
    {
        $validated = $request->validated();
        $role = Role::create($validated);

        return new RoleResource($role);
    }

    public function show($id)
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
