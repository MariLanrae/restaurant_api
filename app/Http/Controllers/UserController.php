<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function index_sort(Request $request): JsonResponse
    {
        $users = User::all();
        if ($request->has('name')){
            $users = $users->sortBy('name');
        }
        elseif ($request->has('role_id')){
            $users = $users->sortBy('role_id');
        }

        return response()->json($users);
    }

    public function index_search(Request $request): JsonResponse
    {
        $user = User::all();
        if ($request->has('name')) {
            $search = $request->input('name');
            $user->where('name', 'like', $search);
        }
        elseif ($request->has('email')) {
            $search = $request->input('email');
            $user->where('email', 'like', $search);
        }
        elseif ($request->has('role')) {
            $search = $request->input('role');
            $user->where('role', 'like', $search);
        }
        return response()->json($user);
    }

    public function store(Request $request): JsonResponse
    {
        $user = User::create($request->all());

        return response()->json($user);
    }

    public function show(User $user): JsonResponse
    {
        return response()->json($user);
    }

    public function update(Request $request, User $user): JsonResponse
    {
        $user->update($request->all());

        return response()->json($user);
    }

    public function destroy(User $user): JsonResponse
    {
        $user->delete();

        return response()->json($user->delete_at);
    }
}
