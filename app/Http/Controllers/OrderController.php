<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use App\Models\Order;
use App\Http\Resources\OrderResource;
use App\Http\Requests\OrderRequest;
use Carbon\Carbon;

class OrderController extends Controller
{

    public function index(OrderRequest $request)
    {
        $validated = $request->validated();

        $order = Order::query();

        if (isset($validated['number'])) {
            $order->where('number', 'iLike', '%' . $validated['number'] . '%');
        }
        if (isset($validated['closing_date'])) {
            $order->where('closing_date', 'iLike','%' . $validated['closing_date'] . '%');
        }
        if (isset($validated['user_id'])) {
            $order->where('user_id', 'iLike', '%' . $validated['user_id'] . '%');
        }
        if (isset($validated['sort'])) {
            $order->orderBy($validated['sort'], $validated['sort_order' ?? 'asc']);
        }
        $order = $order->paginate(perPage: $validated['perPage'], page:  $validated['page'])->withQueryString();


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

        $titles = array_column($validated['dishes'], 'title');
        $dishes = Dish::whereIn('title', $titles)->get()->keyBy('title');
        foreach ($validated['dishes'] as $dishesData) {
            $dish = $dishes->get($dishesData['title']);
            if ($dish) {
                $order->dishes()->attach($dish->id, ['quantity' => $dishesData['quantity']]);
            }
        }

        return new OrderResource($order);
    }

    public function show($id): OrderResource
    {
        $order = Order::findOrFail($id);

        return new OrderResource($order);
    }

    public function update(OrderRequest $request, Order $order): OrderResource
    {
        $validated = $request->validated();

        $order->update($validated);

        return new OrderResource($order);
    }

    public function destroy(Order $order): OrderResource
    {
        $order->delete();

        return new OrderResource($order);
    }
}
