<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use App\Models\Order;
use App\Http\Resources\OrderResource;
use  App\Http\Requests\OrderRequest;
use Carbon\Carbon;

class OrderController extends Controller
{

    public function index(OrderRequest $request)
    {
        $validated = $request->validated();

        $order = Order::query();

        if (array_key_exists('number', $validated)) {
            $order->where('number', 'iLike', '%' . $validated['number'] . '%');
        }
        elseif (array_key_exists('closing_date', $validated)) {
            $order->where('closing_date', 'iLike','%' . $validated['closing_date'] . '%');
        }
        elseif (array_key_exists('user_id', $validated)) {
            $order->where('user_id', 'iLike', '%' . $validated['user_id'] . '%');
        }
        if (array_key_exists('sort',  $validated)) {
            $order->orderBy($validated['sort'], $validated['sort_order' ?? 'asc']);
        }
        $order = $order->paginate(5);


        return OrderResource::collection($order);
    }

    public function store(OrderRequest $request): OrderResource
    {

        $validated = $request->validated();

        $number = $validated['number'].'--'.$validated['user_id'].'-'.uniqid();
        if ($validated['status'] == 'open') {
            $closing_date = null;
        }
        else {
            $closing_date = Carbon::now()->format('Y-m-d H:i:s');
        }
        $order = Order::create([
            'number' => $number,
            'closing_date' => $closing_date,
            'user_id' => $validated['user_id'],
            'status' => $validated['status'],
            'creation_date' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        foreach ($validated['dishes'] as $dishes) {
            $dish = Dish::where('title', $dishes['title'])->first();
            $order->dishes()->attach($dish->id, ['quantity' => $dishes['quantity']]);
        }

        return new OrderResource($order);
    }

    public function show(Order $order): OrderResource
    {
        return new OrderResource($order);
    }

    public function update(OrderRequest $request, Order $order): OrderResource
    {
        $validated = $request->validated();

        if ($validated['creation_date']) {
            $order->creation_date = $validated['creation_date'];
        }
        if ($validated['closing_date']) {
            $order->closing_date = $validated['closing_date'];
        }
        if ($validated['user_id']) {
            $order->user_id = $validated['user_id'];
        }
        if ($validated['status']) {
            $order->status = $validated['status'];
        }
        $order->save();

        return new OrderResource($order);
    }

    public function destroy(Order $order): OrderResource
    {
        $order->delete();

        return new OrderResource($order);
    }
}
