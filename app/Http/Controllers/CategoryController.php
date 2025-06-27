<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\File;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\CategoryResource;
use  App\Http\Requests\CategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
{

    public function index(CategoryRequest $request)
    {
        $validated = $request->validated();

        $category = Category::query();


        if (isset($validated['sort_order'])) {
            $category->orderBy('title', $validated['sort_order']);
        }
        if (isset($validated['search'])) {

            $category = $category->where('title', 'iLike', $validated['title']);
        }
        $category = $category->paginate(1);


        return CategoryResource::collection($category);
    }

    public function store(CategoryRequest $request): CategoryResource
    {
        $validated = $request->validated();

        $path =$validated['file']->store('uploads', 'public');
        $file = File::create(['path' => $path]);
        $validated['file_id'] = $file->id;
        $category = Category::create([
            'title' => $validated['title'],
            'file_id' => $validated['file_id'],
        ]);

        return new CategoryResource($category);
    }

    public function show($id): CategoryResource
    {
        $category = Category::find($id);

        return new CategoryResource($category);
    }

    public function update(UpdateCategoryRequest $category, CategoryRequest $request): CategoryResource
    {
        $validated = $request->validated();
        $category->update($validated);
        $category->save();

        if (array_key_exists('file', $validated)) {
            Storage::disk('public')->delete($category->file->path);
            $path = $validated['file']->store('uploads', 'public');
            $category->file->path = $path;
            $category->file->save();
        }

        return new CategoryResource($category);
    }

    public function destroy(Category $category): CategoryResource
    {
        $file = $category->file;
        Storage::disk('public')->delete($file->path);
        $file->delete();
        $category->delete();

        return new CategoryResource($category);
    }
}
