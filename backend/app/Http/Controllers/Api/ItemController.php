<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Display a listing of all items.
     */
    public function index()
    {
        return response()->json([
            'data' => Inventory::all(),
        ]);
    }

    /**
     * Store a newly created item in storage.
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

        $item = Inventory::create($validated);

        return response()->json($item, 201);
    }

    /**
     * Display the specified item.
     */
    public function show(Inventory $item)
    {
        return response()->json($item);
    }

    /**
     * Update the specified item in storage.
     */
    public function update(Request $request, Inventory $item)
    {
        $validated = $request->validate([
            'item_name' => 'sometimes|string',
            'quantity_on_hand' => 'sometimes|integer|min:0',
            'reorder_level' => 'sometimes|integer|min:0',
            'unit_price' => 'sometimes|numeric|min:0',
            'category' => 'sometimes|string',
        ]);

        $item->update($validated);

        return response()->json($item);
    }

    /**
     * Remove the specified item from storage.
     */
    public function destroy(Inventory $item)
    {
        $item->delete();
        return response()->json(null, 204);
    }

    /**
     * Get low-stock items (items where quantity_on_hand < reorder_level).
     */
    public function lowStock()
    {
        $lowStockItems = Inventory::whereRaw('quantity_on_hand < reorder_level')->get();

        return response()->json([
            'data' => $lowStockItems,
            'count' => $lowStockItems->count(),
        ]);
    }
}
