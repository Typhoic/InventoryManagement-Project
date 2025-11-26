<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    /**
     * Display a listing of all sales.
     */
    public function index()
    {
        return response()->json([
            'data' => Sale::orderBy('sale_date', 'desc')->get(),
        ]);
    }

    /**
     * Store a newly created sale in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_name' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'sale_date' => 'required|date',
        ]);

        $sale = Sale::create($validated);

        return response()->json($sale, 201);
    }

    /**
     * Display the specified sale.
     */
    public function show(Sale $sale)
    {
        return response()->json($sale);
    }

    /**
     * Update the specified sale in storage.
     */
    public function update(Request $request, Sale $sale)
    {
        $validated = $request->validate([
            'product_name' => 'sometimes|string',
            'quantity' => 'sometimes|integer|min:1',
            'price' => 'sometimes|numeric|min:0',
            'sale_date' => 'sometimes|date',
        ]);

        $sale->update($validated);

        return response()->json($sale);
    }

    /**
     * Remove the specified sale from storage.
     */
    public function destroy(Sale $sale)
    {
        $sale->delete();
        return response()->json(null, 204);
    }

    /**
     * Get chart data for sales (for dashboard graphs).
     */
    public function chartData()
    {
        $sales = Sale::selectRaw('DATE(sale_date) as date, SUM(quantity * price) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->limit(30)
            ->get();

        return response()->json([
            'labels' => $sales->pluck('date'),
            'values' => $sales->pluck('total'),
        ]);
    }
}
