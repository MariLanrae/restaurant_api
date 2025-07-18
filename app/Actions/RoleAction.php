<?php

namespace App\Actions;

use App\Models\Role;

class RoleAction extends BasicAction
{
    /**
     * Create a new class instance.
     */
    public function getModel()
    {
        return Role::class;
    }
}
