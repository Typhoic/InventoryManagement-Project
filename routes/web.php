<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\IngredientController;


// Dashboard - Homepage
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Low Stock Items
Route::get('/items/low-stock', [MenuItemController::class, 'lowStockItems'])->name('lowstockitems');

// Items
Route::get('/items', [MenuItemController::class, 'allItems'])->name('itemdetailsclicked');
Route::get('/items/create', [MenuItemController::class, 'createItem'])->name('items.create');
Route::post('/items', [MenuItemController::class, 'storeItem'])->name('items.store');
Route::get('/items/{id}/edit', [MenuItemController::class, 'editItem'])->name('items.edit');
Route::put('/items/{id}', [MenuItemController::class, 'updateItem'])->name('items.update');
Route::delete('/items/{id}', [MenuItemController::class, 'destroyItem'])->name('items.destroy');

// Orders
Route::get('/orders', [OrderController::class, 'index'])->name('salesorderclicked');
Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');

// Ingredients
Route::resource('ingredients', IngredientController::class);
Route::post('ingredients/{ingredient}/restock', [IngredientController::class, 'restock'])->name('ingredients.restock');
Route::get('/ingredient-groups', [IngredientController::class, 'groups'])->name('ingredient-groups.index');

/*
Route::get('/', function () {
    return view('welcome');
});
*/
