<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Resources\CategoryResource;
use  App\Http\Requests\CategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Actions\CategoryAction;

class CategoryController extends Controller
{

    public function index(CategoryRequest $request, CategoryAction $action)
    {
        $validated = $request->validated();

        $category = $action->categoryIndex($validated);

        return CategoryResource::collection($category);
    }

    public function store(CategoryRequest $request, CategoryAction $action): CategoryResource
    {
        $validated = $request->validated();

        $category = $action->categoryStore($validated);

        return new CategoryResource($category);
    }

    public function show($id, CategoryAction $action): CategoryResource
    {
        $category = $action->categoryShow($id);

        return new CategoryResource($category);
    }

    public function update(UpdateCategoryRequest $request, Category $category, CategoryAction $action): CategoryResource
    {
        $validated = $request->validated();

        $category = $action->categoryUpdate($category, $validated);

        return new CategoryResource($category);
    }

    public function destroy(Category $category, CategoryAction $action): CategoryResource
    {
        $category = $action->categoryDelete($category);

        return new CategoryResource($category);
    }
}
