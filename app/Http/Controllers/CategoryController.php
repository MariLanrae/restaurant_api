<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\File;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\CategoryResource;
use  App\Http\Requests\CategoryRequest;

class CategoryController extends Controller
{

    public function index(CategoryRequest $request)
    {
        $category = Category::all();

        if (isset ($request['sort'])) {
            $category = $category->sortBy('title',  $request['sort_order']);
        }
        elseif (isset($val['search'])) {
            $category->where('title', 'like', $request['title']);
        }

        return CategoryResource::collection($category);
    }

    public function store(CategoryRequest $request): CategoryResource
    {
        $path = $request->file('file')->store('uploads', 'public');
        File::create(['path' => $path]);
        $category = Category::create($request->all());

        return new CategoryResource($category);
    }

    public function show($id): CategoryResource
    {
        $category = Category::find($id);

        return new CategoryResource($category);
    }

    public function update(CategoryRequest $request, Category $category): CategoryResource
    {
        $category->title = 'title';
        Storage::disk('public')->delete($category->file->path);
        $path = $request->file('file')->store('uploads', 'public');
        $category->file->path = $path;

        return new CategoryResource($category);
    }

    public function destroy(Category $category): CategoryResource
    {
        $file = $category->file;
        Storage::disk('public')->delete($file->path);
        $file->delete();
        $category->delete();

        return new CategoryResource($category['deleted_at']);
    }
}
