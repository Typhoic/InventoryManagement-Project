<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MenuItemController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\IngredientController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Health check
Route::get('health', function () {
    return response()->json(['status' => 'ok', 'message' => 'Backend is running']);
});

// Menu Items endpoints
Route::apiResource('menu-items', MenuItemController::class);

// Orders endpoints
Route::apiResource('orders', OrderController::class);

// Ingredients endpoints
Route::apiResource('ingredients', IngredientController::class);
Route::get('ingredients/low-stock', [IngredientController::class, 'lowStock']);

// Optional: Auth endpoint (if User model is added later)
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
