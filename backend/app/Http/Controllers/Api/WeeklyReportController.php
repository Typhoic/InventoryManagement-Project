<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use App\Models\Product;
use App\Models\WeeklyReport;
use App\Models\WeeklyReportItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WeeklyReportController extends Controller
{
    /**
     * Store a weekly report: sold products, total income, and ingredient stock updates.
     *
     * Expected payload:
     * {
     *   "week_start": "2025-11-23",
     *   "sold_counts": [{"product_id":1, "quantity": 100}, ...],
     *   "total_income": 375000,
     *   "ingredient_stock": [{"ingredient_id":1, "quantity_on_hand": 150000}, ...]
     * }
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'week_start' => 'required|date',
            'sold_counts' => 'required|array',
            'sold_counts.*.product_id' => 'required|integer|exists:products,id',
            'sold_counts.*.quantity' => 'required|integer|min:0',
            'total_income' => 'required|numeric|min:0',
            'ingredient_stock' => 'nullable|array',
            'ingredient_stock.*.ingredient_id' => 'required_with:ingredient_stock|integer|exists:ingredients,id',
            'ingredient_stock.*.quantity_on_hand' => 'required_with:ingredient_stock|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $report = WeeklyReport::create([
                'week_start' => $data['week_start'],
                'total_income' => $data['total_income'],
            ]);

            // Update ingredient stock if provided
            $updatedIngredients = [];
            if (!empty($data['ingredient_stock'])) {
                foreach ($data['ingredient_stock'] as $is) {
                    $ing = Ingredient::find($is['ingredient_id']);
                    if ($ing) {
                        $ing->quantity_on_hand = $is['quantity_on_hand'];
                        $ing->save();
                        $updatedIngredients[$ing->id] = $ing->quantity_on_hand;
                    }
                }
            }

            // Create weekly report items and compute required ingredient totals
            $requiredPerIngredient = [];

            foreach ($data['sold_counts'] as $sold) {
                $product = Product::with('ingredients')->find($sold['product_id']);
                $qtySold = (int) $sold['quantity'];

                WeeklyReportItem::create([
                    'weekly_report_id' => $report->id,
                    'product_id' => $product->id,
                    'quantity' => $qtySold,
                    'unit_price' => $product->base_price,
                ]);

                foreach ($product->ingredients as $ingredient) {
                    $reqQty = $ingredient->pivot->quantity_required * $qtySold; // in base unit
                    if (!isset($requiredPerIngredient[$ingredient->id])) {
                        $requiredPerIngredient[$ingredient->id] = 0;
                    }
                    $requiredPerIngredient[$ingredient->id] += $reqQty;
                }
            }

            // Build response: check for shortages using current ingredient quantities
            $shortages = [];
            foreach ($requiredPerIngredient as $ingId => $required) {
                $ing = Ingredient::find($ingId);
                $available = $ing->quantity_on_hand;
                $need = max(0, $required - $available);
                $shortages[] = [
                    'ingredient_id' => $ing->id,
                    'name' => $ing->name,
                    'unit' => $ing->unit,
                    'required' => (float) $required,
                    'available' => (float) $available,
                    'need_to_buy' => (float) $need,
                    'will_suffice' => $need <= 0,
                ];
            }

            DB::commit();

            return response()->json([
                'report_id' => $report->id,
                'shortages' => $shortages,
                'updated_ingredients' => $updatedIngredients,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
