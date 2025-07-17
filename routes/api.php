<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DishController;
use App\Http\Middleware\AuthMiddleware;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware(AuthMiddleware::class);

Route::group(['prefix' => 'roles', 'middleware' => [AuthMiddleware::class],], function () {
    Route::get('/{id}', [RoleController::class, 'show']);
    Route::get('/', [RoleController::class, 'index']);
    Route::post('/', [RoleController::class, 'store']);
    Route::put('/{role}', [RoleController::class, 'update']);
    Route::delete('/{role}', [RoleController::class, 'destroy']);
});

Route::group(['prefix' => 'users', 'middleware' => [AuthMiddleware::class],], function () {
    Route::get('/{id}', [UserController::class, 'show']);
    Route::get('/', [UserController::class, 'index']);
    Route::post('/', [UserController::class, 'store']);
    Route::put('/{user}', [UserController::class, 'update']);
    Route::delete('/{user}', [UserController::class, 'destroy']);
});

Route::group(['prefix' => 'categories', 'middleware' => [AuthMiddleware::class],], function () {
    Route::get('/{category}', [CategoryController::class, 'show']);
    Route::get('/', [CategoryController::class, 'index']);
    Route::post('/', [CategoryController::class, 'store']);
    Route::post('/{category}', [CategoryController::class, 'update']);
    Route::delete('/{category}', [CategoryController::class, 'destroy']);
});

Route::group(['prefix' => 'dishes', 'middleware' => [AuthMiddleware::class],], function () {
    Route::get('/{id}', [DishController::class, 'show']);
    Route::get('/', [DishController::class, 'index']);
    Route::post('/', [DishController::class, 'store']);
    Route::post('/{dish}', [DishController::class, 'update']);
    Route::delete('/{dish}', [DishController::class, 'destroy']);
});

Route::group(['prefix' => 'orders', 'middleware' => [AuthMiddleware::class],], function () {
    Route::get('/{id}', [OrderController::class, 'show']);
    Route::get('/', [OrderController::class, 'index']);
    Route::post('/', [OrderController::class, 'store']);
    Route::put('/{order}', [OrderController::class, 'update']);
    Route::delete('/{order}', [OrderController::class, 'destroy']);
});
