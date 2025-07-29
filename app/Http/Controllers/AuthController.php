<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthLoginRequest;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use App\Actions\LoginAction;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    function login(AuthLoginRequest $req, LoginAction $action)
        {
            $validated = $req->validated();

            $token = $action->login($validated);

            if (!isset($token)) {
                throw new AuthenticationException();
            }
            return ['token' => $token];
        }

        function logout(): JsonResponse
        {
            Auth::guard('sanctum')->user()->currentAccessToken()->delete();

            return response()->json(['message' => 'Logged out']);
        }
}
