<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthLoginRequest;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use App\Actions\LoginAction;
class AuthController extends Controller
{
    function login(AuthLoginRequest $req, LoginAction $action)
        {
            $validated = $req->validated();

            $token = $action->login($validated);

            if (!isset($token)) {
                throw new AuthenticationException();
            }
            else{
                return ['token' => $token];
            }
        }

        function logout(): JsonResponse
        {
            auth('api')->user()->currentAccessToken()->delete();

            return response()->json(['message' => 'Logged out']);
        }
}
