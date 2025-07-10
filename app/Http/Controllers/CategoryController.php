<?php

namespace App\Http\Controllers;

use App\Actions\CategoryAction;
use App\Models\Category;
use App\Http\Resources\CategoryResource;
use  App\Http\Requests\CategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
{

    public function index(CategoryRequest $request, CategoryAction $action)
    {
        $validated = $request->validated();
        $category = $action->index($validated);

        return CategoryResource::collection($category);
    }

    public function store(CategoryRequest $request, CategoryAction $action): CategoryResource
    {
        $validated = $request->validated();
        $category = $action->store($validated);

        return new CategoryResource($category);
    }

    public function show($id, CategoryAction $action): CategoryResource
    {
        $category = $action->show($id);

        return new CategoryResource($category);
    }

    public function update(UpdateCategoryRequest $request, Category $category, CategoryAction $action): CategoryResource
    {
        $validated = $request->validated();
        $category = $action->update($category, $validated);

        return new CategoryResource($category);
    }

    public function destroy(Category $category, CategoryAction $action): CategoryResource
    {
        $category = $action->destroy($category);

        return new CategoryResource($category);
    }
}
