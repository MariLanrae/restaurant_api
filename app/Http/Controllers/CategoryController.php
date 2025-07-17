<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\File;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\CategoryResource;
use App\Http\Requests\CategoryRequest;
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
        $category = $category->paginate(perPage: $validated['perPage'], page:  $validated['page'])->withQueryString();


        return CategoryResource::collection($category);
    }

    public function store(CategoryRequest $request): CategoryResource
    {
        $validated = $request->validated();
        $path = Storage::disk('public')->putFile('uploads', $validated['file']);
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
        $category = Category::findOrFail($id);

        return new CategoryResource($category);
    }

    public function update(UpdateCategoryRequest $category, CategoryRequest $request): CategoryResource
    {
        $validated = $request->validated();
        $category->update($validated);
        $category->save();

        if (isset($validated['file'])) {
            $path = Storage::disk('public')->putFile('uploads', $validated['file']);
            Storage::disk('public')->delete($category->file->path);
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
