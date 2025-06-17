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

Route::apiResource('categories', CategoryController::class)->except('index');
Route::get('/categories/parameter/{parameter}', [CategoryController::class, 'index'])->name('categories.index');

Route::apiResource('users', UserController::class)->except('index');
Route::get('/users/search/{search}', [UserController::class, 'index_search'])->name('users.search');
Route::get('/users/sort/{sort}', [UserController::class, 'index_sort'])->name('users.sort');

Route::apiResource('roles', RoleController::class);

Route::apiResource('orders', OrderController::class)->except('index');
Route::get('/orders/search/{search}', [OrderController::class, 'index_search'])->name('orders.search');
Route::get('/orders/sort/{sort}', [OrderController::class, 'index_sort'])->name('orders.sort');

Route::apiResource('dishes', DishController::class)->except('index');
Route::get('/dishes/search/{search}', [DishController::class, 'index_search'])->name('dishes.search');
Route::get('/dishes/sort/{sort}', [DishController::class, 'index_sort'])->name('dishes.sort');



