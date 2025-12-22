<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use Illuminate\Http\Request;

class IngredientController extends Controller
{
    /**
     * GET /api/ingredients
     * List all ingredients with stock info
     */
    public function index()
    {
        $ingredients = Ingredient::all()->map(function ($ingredient) {
            return [
                'id' => $ingredient->id,
                'name' => $ingredient->name,
                'initial_stock' => $ingredient->initial_stock,
                'current_stock' => $ingredient->current_stock,
                'unit' => $ingredient->unit,
                'low_stock_threshold' => $ingredient->low_stock_threshold,
                'stock_percentage' => $ingredient->getStockPercentage(),
                'is_low_stock' => $ingredient->isLowStock(),
                'created_at' => $ingredient->created_at,
                'updated_at' => $ingredient->updated_at
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $ingredients,
            'count' => $ingredients->count()
        ]);
    }

    /**
     * GET /api/ingredients/{id}
     * Get a single ingredient with its menu items
     */
    public function show($id)
    {
        $ingredient = Ingredient::with('menuItems', 'groups')->find($id);

        if (!$ingredient) {
            return response()->json([
                'success' => false,
                'message' => 'Ingredient not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => array_merge(
                $ingredient->toArray(),
                [
                    'stock_percentage' => $ingredient->getStockPercentage(),
                    'is_low_stock' => $ingredient->isLowStock()
                ]
            )
        ]);
    }

    /**
     * POST /api/ingredients
     * Create a new ingredient
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'initial_stock' => 'required|numeric|min:0',
            'current_stock' => 'required|numeric|min:0',
            'unit' => 'required|string|in:g,ml,pcs',
            'low_stock_threshold' => 'required|numeric|min:0|max:100'
        ]);

        $ingredient = Ingredient::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Ingredient created successfully',
            'data' => array_merge(
                $ingredient->toArray(),
                [
                    'stock_percentage' => $ingredient->getStockPercentage(),
                    'is_low_stock' => $ingredient->isLowStock()
                ]
            )
        ], 201);
    }

    /**
     * PUT /api/ingredients/{id}
     * Update ingredient stock or details
     */
    public function update(Request $request, $id)
    {
        $ingredient = Ingredient::find($id);

        if (!$ingredient) {
            return response()->json([
                'success' => false,
                'message' => 'Ingredient not found'
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'initial_stock' => 'sometimes|numeric|min:0',
            'current_stock' => 'sometimes|numeric|min:0',
            'unit' => 'sometimes|string|in:g,ml,pcs',
            'low_stock_threshold' => 'sometimes|numeric|min:0|max:100'
        ]);

        $ingredient->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Ingredient updated successfully',
            'data' => array_merge(
                $ingredient->toArray(),
                [
                    'stock_percentage' => $ingredient->getStockPercentage(),
                    'is_low_stock' => $ingredient->isLowStock()
                ]
            )
        ]);
    }

    /**
     * DELETE /api/ingredients/{id}
     * Delete an ingredient
     */
    public function destroy($id)
    {
        $ingredient = Ingredient::find($id);

        if (!$ingredient) {
            return response()->json([
                'success' => false,
                'message' => 'Ingredient not found'
            ], 404);
        }

        $ingredient->delete();

        return response()->json([
            'success' => true,
            'message' => 'Ingredient deleted successfully'
        ]);
    }

    /**
     * GET /api/ingredients/low-stock
     * Get all ingredients that are running low on stock
     */
    public function lowStock()
    {
        $lowStockIngredients = Ingredient::all()->filter(function ($ingredient) {
            return $ingredient->isLowStock();
        })->values();

        return response()->json([
            'success' => true,
            'data' => $lowStockIngredients->map(function ($ingredient) {
                return array_merge(
                    $ingredient->toArray(),
                    [
                        'stock_percentage' => $ingredient->getStockPercentage(),
                        'is_low_stock' => $ingredient->isLowStock()
                    ]
                );
            }),
            'count' => $lowStockIngredients->count()
        ]);
    }
}
