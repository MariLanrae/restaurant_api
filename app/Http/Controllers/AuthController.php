<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthLoginRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    function login(AuthLoginRequest $req): JsonResponse
        {
            $validated = $req->validated();

            if(!isset($validated['email'])) {
                $role = Role::where('name', 'waiter')->firstOrFail();
                $users = User::where('role_id', $role['id'])->get();
                foreach ($users as $user) {
                    if (Hash::check($validated['pincode'], $user['pincode'])) {
                        $token = $user->createToken($user->name)->plainTextToken;
                        return response()->json([$token]);
                    }
                }
            }
            else {
                $user = User::where('email', $validated['email'])->firstOrFail();
                if (isset($validated['password'])) {
                    if (Hash::check($validated['password'], $user->password)) {
                        $token = $user->createToken($user->name)->plainTextToken;
                        return response()->json([$token]);
                    }
                } elseif (isset($validated['pincode'])) {
                    if (Hash::check($validated['pincode'], $user->pincode)) {
                        $token = $user->createToken($user->name)->plainTextToken;
                        return response()->json([$token]);
                    }
                }
            }
            return response()->json(['error' => 'Unauthorised'], 401);
        }

        function logout(Request $request): JsonResponse
        {
            $request->user()->currentAccessToken()->delete();

            return response()->json(['message' => 'Logged out']);
        }
}
