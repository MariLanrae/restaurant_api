<?php

namespace App\Actions;

use App\Models\Dish;
use App\Models\Order;
use Carbon\Carbon;

class OrderAction
{
    /**
     * Create a new class instance.
     */
    public function orderIndex($validated)
    {
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
        $order->paginate(perPage: $validated['perPage'], page:  $validated['page'])->withQueryString();

        return $order;
    }

    public function orderStore($validated)
    {
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
        return $order;
    }

    public function orderShow($id)
    {
        return Order::findOrFail($id);
    }

    public function orderUpdate($validated, $order)
    {
        return $order->update($validated);
    }

    public function orderDelete($order)
    {
        $order->delete();

        return $order;
    }
}
