<?php

namespace App\Actions;

use App\Models\User;

class UserAction extends BasicAction
{
    /**
     * Create a new class instance.
     */
    public function getModel()
    {
        return User::class;
    }
}
