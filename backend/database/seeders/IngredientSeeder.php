<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use Illuminate\Database\Seeder;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ingredients = [
            // Main proteins/grains for bowls
            ['name' => 'Chicken Breast', 'quantity_on_hand' => 15, 'reorder_level' => 5, 'unit_price' => 8000, 'unit' => 'kg', 'category' => 'Protein'],
            ['name' => 'Rice', 'quantity_on_hand' => 20, 'reorder_level' => 8, 'unit_price' => 5000, 'unit' => 'kg', 'category' => 'Grain'],
            
            // Sauces (will add more variants later)
            ['name' => 'Spicy Sauce', 'quantity_on_hand' => 3, 'reorder_level' => 1.5, 'unit_price' => 2500, 'unit' => 'bottle', 'category' => 'Sauce'],
            ['name' => 'Sweet Sauce', 'quantity_on_hand' => 2.5, 'reorder_level' => 1, 'unit_price' => 2500, 'unit' => 'bottle', 'category' => 'Sauce'],
        ];

        foreach ($ingredients as $ingredient) {
            Ingredient::create($ingredient);
        }
    }
}
