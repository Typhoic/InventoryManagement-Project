<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * Display a listing of all inventory items.
     */
    public function index()
    {
        return response()->json([
            'data' => Inventory::all(),
        ]);
    }

    /**
     * Store a newly created inventory item in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_name' => 'required|string',
            'quantity_on_hand' => 'required|integer|min:0',
            'reorder_level' => 'required|integer|min:0',
            'unit_price' => 'required|numeric|min:0',
            'category' => 'nullable|string',
        ]);

        $inventory = Inventory::create($validated);

        return response()->json($inventory, 201);
    }

    /**
     * Display the specified inventory item.
     */
    public function show(Inventory $inventory)
    {
        return response()->json($inventory);
    }

    /**
     * Update the specified inventory item in storage.
     */
    public function update(Request $request, Inventory $inventory)
    {
        $validated = $request->validate([
            'item_name' => 'sometimes|string',
            'quantity_on_hand' => 'sometimes|integer|min:0',
            'reorder_level' => 'sometimes|integer|min:0',
            'unit_price' => 'sometimes|numeric|min:0',
            'category' => 'sometimes|string',
        ]);

        $inventory->update($validated);

        return response()->json($inventory);
    }

    /**
     * Remove the specified inventory item from storage.
     */
    public function destroy(Inventory $inventory)
    {
        $inventory->delete();
        return response()->json(null, 204);
    }
}
