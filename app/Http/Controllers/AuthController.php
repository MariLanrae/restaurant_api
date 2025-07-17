<?php

namespace App\Http\Controllers;

use App\Enums\RoleEnum;
use App\Http\Requests\AuthLoginRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    private function createToken($user)
    {
        return $user->createToken($user->name)->plainTextToken;
    }

    public function pincodeLogin($user, $validated, $token)
    {
        if (Hash::check($validated['pincode'], $user->pincode)) {
            $token = $this->createToken($user);
        }
        return $token;
    }

    public function passwordLogin($user, $validated, $token)
    {
        if (Hash::check($validated['password'], $user->password)) {
            $token = $this->createToken($user);
        }
        return $token;
    }

    public function waiterLogin($validated, $token)
    {
        $role = Role::where('name', RoleEnum::WAITER)->firstOrFail();
        $users = User::where('role_id', $role['id'])->get();
        foreach ($users as $user) {
            if (Hash::check($validated['pincode'], $user['pincode'])) {
                $token = $this->createToken($user);
            }
        }
        return $token;
    }

    public function login(AuthLoginRequest $req)
        {
            $token = null;

            $validated = $req->validated();

            if(!isset($validated['email'])) {
                $token = $this->waiterLogin($validated, $token);
            }
            else {
                $user = User::where('email', $validated['email'])->firstOrFail();
                if (isset($validated['password'])){
                    $token = $this->passwordLogin($user, $validated, $token);

                } elseif (isset($validated['pincode'])) {
                    $token = $this->pincodeLogin($user, $validated, $token);
                }
            }

            if (!isset($token)) {
                throw new AuthenticationException();
            }
            else{
                return ['token' => $token];
            }
        }

        public function logout(): JsonResponse
        {
            Auth::guard('sanctum')->user()->currentAccessToken()->delete();

            return response()->json(['message' => 'Logged out']);
        }
}
