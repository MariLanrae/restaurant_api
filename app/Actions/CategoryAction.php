<?php

namespace App\Actions;

use App\Models\Category;

class CategoryAction extends BasicAction
{
    /**
     * Create a new class instance.
     */
    public function getModel()
    {
        return Category::class;
    }

}
