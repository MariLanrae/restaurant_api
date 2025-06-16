<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\File;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{

    public function index(Request $request): JsonResponse
    {
        $category = Category::all();
        if ($request->has('sort')) {
            $category = $category->sortBy('title');
        }
        elseif ($request->has('search')){
            $search = $request->input('title');
            $category->where('title', 'like', $search);
        }
        return response()->json($category);
    }

    public function store(Request $request): JsonResponse
    {
        $path = $request->file('file')->store('uploads', 'public');

        File::create(['path' => $path]);

        $category = Category::create($request->all());

        return response()->json($category);
    }

    public function show(Category $category): JsonResponse
    {
        return response()->json($category);
    }

    public function update(Request $request, Category $category): JsonResponse
    {
        $category->title = 'title';
        Storage::disk('public')->delete($category->file->path);

        $path = $request->file('file')->store('uploads', 'public');

        $category->file->path = $path;

        return response()->json($category);
    }

    public function destroy(Category $category): JsonResponse
    {
        $file = $category->file;
        Storage::disk('public')->delete($file->path);
        $file->delete();
        $category->delete();

        return response()->json($category->delete_at);
    }
}
