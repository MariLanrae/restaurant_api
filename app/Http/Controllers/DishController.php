<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use App\Models\File;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\DishResource;
use  App\Http\Requests\DishRequest;


class DishController extends Controller
{

    public function index_sort(DishRequest $request)
    {
        $dish = Dish::all();

        if (isset ($request['title'])){
            $dish = $dish->sortBy('title', $request['sort_order']);
        }
        elseif (isset ($request['compound'])){
            $dish = $dish->sortBy('compound', $request['sort_order']);
        }
        elseif (isset ($request['price'])){
            $dish = $dish->sortBy('price', $request['sort_order']);
        }
        elseif (isset ($request['calories'])){
            $dish = $dish->sortBy('calories', $request['sort_order']);
        }

        return DishResource::collection($dish);
    }

    public function index_search(DishRequest $request)
    {
        $dish = Dish::all();

        if (isset ($request['title'])) {
            $dish->where('title', 'like', $request['title']);
        }
        elseif (isset ($request['compound'])) {
            $dish->where('compound', 'like', $request['compound']);
        }

        return DishResource::collection($dish);
    }

    public function store(DishRequest $request): DishResource
    {
        $path = $request->file('file')->store('uploads', 'public');
        File::create(['path' => $path]);
        $dish = Dish::create($request->all());

        return new DishResource($dish);
    }

    public function show(Dish $dish): DishResource
    {
        return new DishResource($dish);
    }

    public function update(DishRequest $request, Dish $dish): DishResource
    {
        Storage::disk('public')->delete($dish->file->path);
        $path = $request->file('file')->store('uploads', 'public');
        $dish->update($request->all());
        $dish->update([$dish->file->path =  $path]);

        return new DishResource($dish);
    }

    public function destroy(Dish $dish): DishResource
    {
        $file = $dish->file;
        Storage::disk('public')->delete($file->path);
        $file->delete();
        $dish->delete();

        return new DishResource($dish['deleted_at']);
    }
}
