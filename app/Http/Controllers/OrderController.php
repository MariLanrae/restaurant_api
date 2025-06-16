<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    public function index(): JsonResponse
    {
        $order = Order::all();
        return response()->json($order);
    }

    public function index_sort(Request $request): JsonResponse
    {
        $users = Order::all();
        if ($request->has('number')){
            $users = $users->sortBy('number');
        }
        elseif ($request->has('closing_date')){
            $users = $users->sortBy('closing_date');
        }
        elseif ($request->has('user_id')){
            $users = $users->sortBy('user_id');
        }

        return response()->json($users);
    }

    public function index_search(Request $request): JsonResponse
    {
        $order = Order::all();
        if ($request->has('number')) {
            $search = $request->input('number');
            $order->where('number', 'like', $search);
        }
        elseif ($request->has('closing_date')) {
            $search = $request->input('closing_date');
            $order->where('closing_date', 'like', $search);
        }
        elseif ($request->has('user_id')) {
            $search = $request->input('user_id');
            $order->where('user_id', 'like', $search);
        }

        return response()->json($order);
    }

    public function store(Request $request): JsonResponse
    {
        $order = Order::create($request->all());

        return response()->json($order);
    }

    public function show(Order $order): JsonResponse
    {
        return response()->json($order);
    }

    public function update(Request $request, Order $order): JsonResponse
    {
        $order->update($request->all());

        return response()->json($order);
    }

    public function destroy(Order $order): JsonResponse
    {
        $order->delete();

        return response()->json($order->delete_at);
    }
}
