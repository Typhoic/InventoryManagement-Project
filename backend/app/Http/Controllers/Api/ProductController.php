<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Check if a product can be made for the requested quantity.
     * GET /api/products/{id}/can-make?quantity=2
     */
    public function canMake(Request $request, $id)
    {
        $quantity = max(1, (int) $request->query('quantity', 1));

        $product = Product::with('ingredients')->findOrFail($id);

        $shortages = [];
        $allGood = true;

        foreach ($product->ingredients as $ingredient) {
            $pivot = $ingredient->pivot;
            $reqPerUnit = (float) $pivot->quantity_required; // as stored (unit may vary)
            $reqUnit = $pivot->unit ?? $ingredient->unit; // unit used in recipe

            // normalize to a base unit for comparison
            $required = $this->convertToBase($reqPerUnit * $quantity, $reqUnit);
            $available = $this->convertToBase((float) $ingredient->quantity_on_hand, $ingredient->unit);

            if ($available < $required) {
                $allGood = false;
                $shortages[] = [
                    'ingredient_id' => $ingredient->id,
                    'name' => $ingredient->name,
                    'required' => $this->formatUnit($required, $reqUnit),
                    'available' => $this->formatUnit($available, $ingredient->unit),
                    'need_to_buy' => $this->formatUnit(max(0, $required - $available), $reqUnit),
                    'unit' => $reqUnit,
                ];
            }
        }

        return response()->json([
            'product_id' => $product->id,
            'product_name' => $product->name,
            'requested_quantity' => $quantity,
            'can_make' => $allGood,
            'shortages' => $shortages,
        ]);
    }

    /**
     * Convert a value and unit to a canonical base number for comparison.
     * Base units: grams for weight, milliliters for volume, pieces for count.
     */
    private function convertToBase(float $value, ?string $unit): float
    {
        if (!$unit) return $value;

        $u = strtolower($unit);
        switch ($u) {
            case 'kg':
                return $value * 1000.0; // kg -> g
            case 'g':
            case 'gram':
            case 'grams':
                return $value;
            case 'l':
            case 'liter':
            case 'litre':
                return $value * 1000.0; // L -> ml
            case 'ml':
            case 'milliliter':
                return $value;
            case 'pcs':
            case 'pc':
            case 'piece':
            case 'pieces':
                return $value; // count-based
            case 'bottle':
                // ambiguous: treat as unit count
                return $value;
            default:
                // if unknown unit, assume same-scale numeric comparison
                return $value;
        }
    }

    private function formatUnit(float $baseValue, ?string $unit): float
    {
        if (!$unit) return $baseValue;
        $u = strtolower($unit);
        switch ($u) {
            case 'kg':
                return round($baseValue / 1000.0, 3); // return in kg
            case 'g':
            case 'gram':
            case 'grams':
                return round($baseValue, 2);
            case 'l':
            case 'liter':
            case 'litre':
                return round($baseValue / 1000.0, 3);
            case 'ml':
            case 'milliliter':
                return round($baseValue, 2);
            default:
                return round($baseValue, 2);
        }
    }
}
