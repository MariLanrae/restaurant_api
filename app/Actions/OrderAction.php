<?php

namespace App\Actions;

use App\Enums\OrderEnum;
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

    public function store($validated)
    {
        $number = $validated['number'].'--'.$validated['user_id'].'-'.uniqid();
        if ($validated['status'] == OrderEnum::OPEN) {
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
        $order = Order::create($valid);

        $id = array_column($validated['dishes'], 'id');
        $quantity = array_column($validated['dishes'], 'quantity', 'quantity');
        $dishes = array_combine($id, array_map(function($quan) {
            return ['quantity' => $quan];
        }, $quantity));

        $order->dishes()->sync($dishes);

        return $order;
    }
}
