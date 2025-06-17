<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Http\Resources\OrderResource;
use  App\Http\Requests\OrderRequest;

class OrderController extends Controller
{

    public function index_sort(OrderRequest $request)
    {
        $users = Order::all();
        if (isset ($request['number'])){
            $users = $users->sortBy('number', $request['sort_order']);
        }
        elseif (isset ($request['closing_date'])){
            $users = $users->sortBy('closing_date', $request['sort_order']);
        }
        elseif (isset ($request['user_id'])){
            $users = $users->sortBy('user_id', $request['sort_order']);
        }

        return OrderResource::collection($users);
    }

    public function index_search(OrderRequest $request)
    {
        $order = Order::all();
        if (isset ($request['number'])) {
            $order->where('number', 'like', $request['number']);
        }
        elseif (isset ($request['closing_date'])) {
            $order->where('closing_date', 'like', $request['closing_date']);
        }
        elseif (isset ($request['user_id'])) {
            $order->where('user_id', 'like', $request['user_id']);
        }

        return OrderResource::collection($order);
    }

    public function store(OrderRequest $request): OrderResource
    {
        $order = Order::create($request->all());

        return new OrderResource($order);
    }

    public function show(Order $order): OrderResource
    {
        return new OrderResource($order);
    }

    public function update(OrderRequest $request, Order $order): OrderResource
    {
        $order->update($request->all());

        return new OrderResource($order);
    }

    public function destroy(Order $order): OrderResource
    {
        $order->delete();

        return new OrderResource($order['deleted_at']);
    }
}
