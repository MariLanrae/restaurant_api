<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DishController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
Route::post('roles', [RoleController::class, 'store']);
Route::get('roles/{role}', [RoleController::class, 'show'])->name('roles.show');
Route::put('roles/{role}', [RoleController::class, 'update']);
Route::delete('roles/{role}', [RoleController::class, 'destroy']);

Route::get('users/{user}', [UserController::class, 'show'])->name('users.show');
Route::post('users', [UserController::class, 'store'])->name('users.store');
Route::delete('users/{user}', [UserController::class, 'destroy']);
Route::put('users/{user}', [UserController::class, 'update']);
Route::get('users', [UserController::class, 'index'])->name('users.index');

Route::delete('categories/{category}', [CategoryController::class, 'destroy']);
Route::post('categories/{category}', [CategoryController::class, 'update']);
Route::post('categories', [CategoryController::class, 'store']);
Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

Route::post('dishes/{dish}', [DishController::class, 'update']);
Route::delete('dishes/{dish}', [DishController::class, 'destroy']);
Route::post('dishes', [DishController::class, 'store']);
Route::get('dishes/{dishes}', [DishController::class, 'show'])->name('dishes.show');
Route::get('dishes', [DishController::class, 'index'])->name('dishes.index');

Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
Route::put('orders/{order}', [OrderController::class, 'update']);
Route::delete('orders/{order}', [OrderController::class, 'destroy']);
Route::post('orders', [OrderController::class, 'store']);
