<?php

namespace App\Http\Controllers;

use App\Actions\OrderAction;
use App\Models\Order;
use App\Http\Resources\OrderResource;
use App\Http\Requests\OrderRequest;

class OrderController extends Controller
{

    public function index(OrderRequest $request, OrderAction $action)
    {
        $validated = $request->validated();

        $order = $action->index($validated);


        return OrderResource::collection($order);
    }

    public function store(OrderRequest $request, OrderAction $action): OrderResource
    {
        $validated = $request->validated();

        $order = $action->store($validated);

        return new OrderResource($order);
    }

    public function show($id, OrderAction $action): OrderResource
    {
        $order = $action->show($id);

        return new OrderResource($order);
    }

    public function update(OrderRequest $request, Order $order, OrderAction $action): OrderResource
    {
        $validated = $request->validated();

        $order = $action->update($validated, $order);

        return new OrderResource($order);
    }

    public function destroy(Order $order, OrderAction $action): OrderResource
    {
        $order = $action->destroy($order);

        return new OrderResource($order);
    }
}
