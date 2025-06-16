<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoleController extends Controller
{

    public function index(): JsonResponse
    {
        $role = Role::all();
        return response()->json($role);
    }

    public function store(Request $request): JsonResponse
    {
        $role = Role::create($request->all());

        return response()->json($role);
    }

    public function show(Role $role): JsonResponse
    {
        return response()->json($role);
    }

    public function update(Request $request, Role $role): JsonResponse
    {
        $role->update($request->all());

        return response()->json($role);
    }

    public function destroy(Role $role): JsonResponse
    {
        $role->delete();

        return response()->json($role);
    }
}
