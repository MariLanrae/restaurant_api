<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use App\Models\File;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class DishController extends Controller
{

    public function index_sort(Request $request): JsonResponse
    {
        $dish = Dish::all();
        if ($request->has('title')){
            $dish = $dish->sortBy('title');
        }
        elseif ($request->has('compound')){
            $dish = $dish->sortBy('compound');
        }
        elseif ($request->has('price')){
            $dish = $dish->sortBy('price');
        }
        elseif ($request->has('calories')){
            $dish = $dish->sortBy('calories');
        }

        return response()->json($dish);
    }

    public function index_search(Request $request): JsonResponse
    {
        $dish = Dish::all();
        if ($request->has('title')) {
            $search = $request->input('title');
            $dish->where('title', 'like', $search);
        }
        elseif ($request->has('compound')) {
            $search = $request->input('compound');
            $dish->where('compound', 'like', $search);
        }
        return response()->json($dish);
    }

    public function store(Request $request): JsonResponse
    {
        $path = $request->file('file')->store('uploads', 'public');

        File::create(['path' => $path]);

        $dish = Dish::create($request->all());

        return response()->json($dish);
    }

    public function show(Dish $dish): JsonResponse
    {
        return response()->json($dish);
    }

    public function update(Request $request, Dish $dish): JsonResponse
    {
        Storage::disk('public')->delete($dish->file->path);

        $path = $request->file('file')->store('uploads', 'public');

        $dish->update($request->all());
        $dish->update([$dish->file->path =  $path]);

        return response()->json($dish);
    }

    public function destroy(Dish $dish): JsonResponse
    {
        $file = $dish->file;
        Storage::disk('public')->delete($file->path);
        $file->delete();
        $dish->delete();

        return response()->json($dish->delete_at);
    }
}
