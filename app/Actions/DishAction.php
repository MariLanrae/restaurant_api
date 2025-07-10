<?php

namespace App\Actions;

use App\Models\Dish;

class DishAction extends BasicAction
{
    /**
     * Create a new class instance.
     */
    public function getModel()
    {
        return Dish::class;
    }
}
