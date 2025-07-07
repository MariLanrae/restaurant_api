<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Http\Resources\OrderResource;
use App\Http\Requests\OrderRequest;
use App\Actions\OrderAction;

class OrderController extends Controller
{

    public function index(OrderRequest $request, OrderAction $action)
    {
        $validated = $request->validated();

        $order = $action->orderIndex($validated);

        return OrderResource::collection($order);
    }

    public function store(OrderRequest $request, OrderAction $action): OrderResource
    {
        $validated = $request->validated();

        $order = $action->orderStore($validated);

        return new OrderResource($order);
    }

    public function show($id, OrderAction $action): OrderResource
    {
        $order = $action->orderShow($id);

        return new OrderResource($order);
    }

    public function update(OrderRequest $request, Order $order, OrderAction $action): OrderResource
    {
        $validated = $request->validated();

        $order = $action->orderUpdate($validated, $order);

        return new OrderResource($order);
    }

    public function destroy(Order $order, OrderAction $action): OrderResource
    {
        $order = $action->orderDelete($order);

        return new OrderResource($order);
    }
}
