<?php

namespace App\Actions;

use App\Models\Dish;
use App\Models\Order;
use Carbon\Carbon;

class OrderAction extends BasicAction
{
    /**
     * Create a new class instance.
     */
    public function getModel()
    {
        return Order::class;
    }

    public function store($validated, $model = Order::class)
    {
        $number = $validated['number'].'--'.$validated['user_id'].'-'.uniqid();
        if ($validated['status'] == 'open') {
            $closing_date = null;
        }
        else {
            $closing_date = Carbon::now()->format('Y-m-d H:i:s');
        }
        $valid = [
            'number' => $number,
            'closing_date' => $closing_date,
            'user_id' => $validated['user_id'],
            'status' => $validated['status'],
            'creation_date' => Carbon::now()->format('Y-m-d H:i:s'),
        ];
        $order = parent::store($valid, $model);

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
}
