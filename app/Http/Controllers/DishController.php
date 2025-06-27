<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Dish;
use App\Models\File;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\DishResource;
use  App\Http\Requests\DishRequest;
use App\Http\Requests\UpdateDishRequest;


class DishController extends Controller
{

    public function index(DishRequest $request)
    {
        $validated = $request->validated();

        $dish = Dish::query();

        if (array_key_exists('sort', $validated)) {
            $dish->orderBy($validated['sort'], ($validated['sort_order'] ?? 'asc'));
        }
        if (array_key_exists('title', $validated)) {
            $dish->where('title', 'iLike', '%' . $validated['title'] . '%');
        }
        if (array_key_exists('compound', $validated)) {
            $dish->where('compound', 'iLike', '%' . $validated['compound'] . '%');
        }

        $dishes= $dish->paginate(5);

        return DishResource::collection($dishes);
    }

    public function store(DishRequest $request): DishResource
    {
        $validated = $request->validated();

        $category = Category::where('id', $validated['category_id'])->firstOrFail();
        $path = $validated['file']->store('uploads', 'public');

        $file = File::create(['path' => $path]);
        $dish = Dish::create([
            'title' => $validated['title'],
            'compound' => $validated['compound'],
            'price' => $validated['price'],
            'calories' => $validated['calories'],
            'category_id' => $category->id,
            'file_id' => $file->id,
        ]);

        return new DishResource($dish);
    }

    public function show($id): DishResource
    {
        $dish = Dish::findOrFail($id);

        return new DishResource($dish);
    }

    public function update(UpdateDishRequest $request, Dish $dish): DishResource
    {
        $validated = $request->validated();

        if ($validated['title']) {
            $dish->title = $validated['title'];
        }

        if ($validated['file']) {
            Storage::disk('public')->delete($dish->file->path);
            $path = $validated['file']->store('uploads', 'public');
            $dish->file->path = $path;
            $dish->file->save();
        }
        $dish->save();

        return new DishResource($dish);
    }

    public function destroy(Dish $dish): DishResource
    {
        $file = $dish->file;
        Storage::disk('public')->delete($file->path);
        $file->delete();
        $dish->delete();

        return new DishResource($dish);
    }
}
