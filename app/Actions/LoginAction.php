<?php

namespace App\Actions;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Enums\RoleEnum;

class LoginAction
{
    /**
     * Create a new class instance.
     */
   public function createToken($user)
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
        $role = Role::where('name', RoleEnum::Waiter)->firstOrFail();
        $users = User::where('role_id', $role['id'])->get();
        foreach ($users as $user) {
            if (Hash::check($validated['pincode'], $user['pincode'])) {
                $token = $this->createToken($user);
            }
        }
        return $token;
    }

    public function authLogin($validated)
    {
        $token = null;

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
        return $token;
    }
}
