<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SalesController;
use App\Http\Controllers\Api\InventoryController;
use App\Http\Controllers\Api\ItemController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Sales endpoints
Route::apiResource('sales', SalesController::class);
Route::get('sales/chart/data', [SalesController::class, 'chartData']);

// Inventory endpoints
Route::apiResource('inventory', InventoryController::class);

// Items endpoints
Route::apiResource('items', ItemController::class);
Route::get('items/low-stock', [ItemController::class, 'lowStock']);

// Health check
Route::get('health', function () {
    return response()->json(['status' => 'ok', 'message' => 'Backend is running']);
});

// Weekly reports: update stock and sold counts
Route::post('reports/weekly', [\App\Http\Controllers\Api\WeeklyReportController::class, 'store']);
