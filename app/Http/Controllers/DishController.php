<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use App\Http\Resources\DishResource;
use App\Http\Requests\DishRequest;
use App\Http\Requests\UpdateDishRequest;
use App\Actions\DishAction;


class DishController extends Controller
{

    public function index(DishRequest $request, DishAction $action)
    {
        $validated = $request->validated();

        $dish = $action->dishIndex($validated);

        return DishResource::collection($dish);
    }

    public function store(DishRequest $request, DishAction $action): DishResource
    {
        $validated = $request->validated();

        $dish = $action->dishStore($validated);

        return new DishResource($dish);
    }

    public function show($id, DishAction $action): DishResource
    {
        $dish = $action->dishShow($id);

        return new DishResource($dish);
    }

    public function update(UpdateDishRequest $request, Dish $dish, DishAction $action): DishResource
    {
        $validated = $request->validated();

        $dish = $action->dishUpdate($validated, $dish);

        return new DishResource($dish);
    }

    public function destroy(Dish $dish, DishAction $action): DishResource
    {
        $dish = $action->dishDelete($dish);

        return new DishResource($dish);
    }
}
