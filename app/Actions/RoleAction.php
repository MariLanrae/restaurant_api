<?php

namespace App\Actions;

use App\Models\Role;

class RoleAction
{
    /**
     * Create a new class instance.
     */
    public function roleIndex($validated)
    {
        $role = Role::query();

        $role->paginate(perPage: $validated['perPage'], page:  $validated['page'])->withQueryString();

        return $role;
    }

    public function roleStore($validated)
    {
        return Role::create($validated);
    }

    public function roleShow($id)
    {
        return Role::findOrFail($id);
    }

    public function roleUpdate($validated, $role)
    {
        return $role->update($validated);
    }

    public function roleDelete($role)
    {
        $role->delete();
        return $role;
    }
}
