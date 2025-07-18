<?php

namespace App\Http\Controllers;

use App\Actions\DishAction;
use App\Models\Dish;
use App\Http\Resources\DishResource;
use App\Http\Requests\DishRequest;
use App\Http\Requests\UpdateDishRequest;


class DishController extends Controller
{

    public function index(DishRequest $request, DishAction $action)
    {
        $validated = $request->validated();
        $dish = $action->index($validated, Dish::class);

        return DishResource::collection($dish);
    }

    public function store(DishRequest $request, DishAction $action): DishResource
    {
        $validated = $request->validated();
        $dish = $action->store($validated, Dish::class);

        return new DishResource($dish);
    }

    public function show($id, DishAction $action): DishResource
    {
        $dish = $action->show($id, Dish::class);

        return new DishResource($dish);
    }

    public function update(UpdateDishRequest $request, Dish $dish, DishAction $action): DishResource
    {
        $validated = $request->validated();

        $dish = $action->update($validated, $dish);

        return new DishResource($dish);
    }

    public function destroy(Dish $dish, DishAction $action): DishResource
    {
        $dish = $action->destroy($dish);

        return new DishResource($dish);
    }
}
