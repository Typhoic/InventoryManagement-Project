<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ingredient;

class IngredientSeeder extends Seeder
{
    public function run()
    {
        $ingredients = [
            ['name' => 'Chicken', 'initial_stock' => 5000, 'current_stock' => 5000, 'unit' => 'gram', 'low_stock_threshold' => 30],
            ['name' => 'Rice', 'initial_stock' => 10000, 'current_stock' => 10000, 'unit' => 'gram', 'low_stock_threshold' => 30],
            ['name' => 'Cheese', 'initial_stock' => 2000, 'current_stock' => 2000, 'unit' => 'gram', 'low_stock_threshold' => 30],
            ['name' => 'Salt', 'initial_stock' => 1000, 'current_stock' => 1000, 'unit' => 'gram', 'low_stock_threshold' => 30],
            ['name' => 'Pepper', 'initial_stock' => 500, 'current_stock' => 500, 'unit' => 'gram', 'low_stock_threshold' => 30],
            ['name' => 'Paprika Powder', 'initial_stock' => 500, 'current_stock' => 500, 'unit' => 'gram', 'low_stock_threshold' => 30],
        ];

        foreach ($ingredients as $ingredient) {
            Ingredient::create($ingredient);
        }
    }
}
