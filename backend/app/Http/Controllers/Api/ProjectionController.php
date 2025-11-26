<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use App\Models\Product;
use App\Models\WeeklyReportItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectionController extends Controller
{
    /**
     * Return weekly projection: average weekly sales and weeks remaining per product
     */
    public function weekly()
    {
        // average weekly sold per product (from weekly_report_items)
        $averages = WeeklyReportItem::select('product_id', DB::raw('AVG(quantity) as avg_qty'))
            ->groupBy('product_id')
            ->pluck('avg_qty', 'product_id')
            ->toArray();

        $products = Product::with('ingredients')->get();

        $result = [];
        foreach ($products as $p) {
            $avg = isset($averages[$p->id]) ? (float) $averages[$p->id] : 0.0;

            // compute how many units can be produced with current stock
            $availableUnits = PHP_INT_MAX;
            foreach ($p->ingredients as $ing) {
                $reqPerUnit = (float) $ing->pivot->quantity_required;
                $ingAvailable = (float) $ing->quantity_on_hand;
                if ($reqPerUnit <= 0) continue;
                $unitsByThisIng = floor($ingAvailable / $reqPerUnit);
                if ($unitsByThisIng < $availableUnits) $availableUnits = $unitsByThisIng;
            }
            if ($availableUnits === PHP_INT_MAX) $availableUnits = 0;

            $weeksRemaining = $avg > 0 ? floor($availableUnits / $avg) : null;

            $result[] = [
                'product_id' => $p->id,
                'name' => $p->name,
                'avg_weekly_sold' => $avg,
                'available_units' => (int) $availableUnits,
                'weeks_remaining' => $weeksRemaining,
            ];
        }

        return response()->json(['projections' => $result]);
    }

    /**
     * Generate a shopping list to cover expected demand for target_weeks.
     * Request: { target_weeks: 1 }
     */
    public function shoppingList(Request $request)
    {
        $data = $request->validate([
            'target_weeks' => 'nullable|integer|min:1',
        ]);

        $targetWeeks = $data['target_weeks'] ?? 1;

        // compute average weekly sales per product
        $averages = WeeklyReportItem::select('product_id', DB::raw('AVG(quantity) as avg_qty'))
            ->groupBy('product_id')
            ->pluck('avg_qty', 'product_id')
            ->toArray();

        $products = Product::with('ingredients')->get();

        // total ingredient needed across products
        $neededByIngredient = [];

        foreach ($products as $p) {
            $avg = isset($averages[$p->id]) ? (float) $averages[$p->id] : 0.0;
            $expectedUnits = ceil($avg * $targetWeeks);
            if ($expectedUnits <= 0) continue;

            foreach ($p->ingredients as $ing) {
                $reqPerUnit = (float) $ing->pivot->quantity_required;
                $totalReq = $reqPerUnit * $expectedUnits;
                if (!isset($neededByIngredient[$ing->id])) {
                    $neededByIngredient[$ing->id] = [
                        'ingredient_id' => $ing->id,
                        'name' => $ing->name,
                        'required_total' => 0.0,
                        'available' => (float) $ing->quantity_on_hand,
                        'unit' => $ing->unit,
                    ];
                }
                $neededByIngredient[$ing->id]['required_total'] += $totalReq;
            }
        }

        // compute need_to_buy per ingredient
        $shoppingList = [];
        foreach ($neededByIngredient as $ing) {
            $needToBuy = max(0, $ing['required_total'] - $ing['available']);
            if ($needToBuy <= 0) continue;
            $shoppingList[] = [
                'ingredient_id' => $ing['ingredient_id'],
                'name' => $ing['name'],
                'need_to_buy' => $needToBuy,
                'unit' => $ing['unit'],
                'required_total' => $ing['required_total'],
                'available' => $ing['available'],
            ];
        }

        return response()->json(['target_weeks' => $targetWeeks, 'shopping_list' => $shoppingList]);
    }
}
