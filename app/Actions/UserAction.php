<?php

namespace App\Actions;

use App\Models\Role;
use App\Models\User;

class UserAction
{
    /**
     * Create a new class instance.
     */
    public function userIndex($validated)
    {
        $user = User::query();

        if (isset($validated['search'])) {
            if ($validated['search'] == 'name') {
                $user = $user->where('name', 'iLike', '%' . $validated['search_order'] . '%');
            }
            elseif ($validated['search'] == 'role_id') {
                $user = $user->where('role_id', 'iLike', '%' . $validated['search_order'] . '%');
            }
            elseif ($validated['search'] == 'email') {
                $user = $user->where('email', 'iLike', '%' . $validated['search_order'] . '%');
            }
        }
        if (isset($validated['sort'])) {
            $user = $user->orderBy($validated['sort'], ($validated['sort_order'] ?? 'asc'));
        }
        $user->paginate(perPage: $validated['perPage'], page:  $validated['page'])->withQueryString();

        return $user;
    }

    public function userStore($validated)
    {
        $role = Role::where('id', $validated['role_id'])->firstOrFail();
        $validated['role_id'] = $role->id;
        return User::create($validated);
    }

    public function userShow($id)
    {
        return User::findOrFail($id);
    }

    public function userUpdate($user, $validated)
    {
        return $user->update($validated);
    }

    public function userDelete($user)
    {
        $user->delete();

        return $user;

    }

}
