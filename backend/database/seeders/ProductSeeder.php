<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Ingredient;
use App\Models\Topping;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create products (menu items)
        $chickenBowl = Product::create([
            'name' => 'Chicken Bowl',
            'description' => 'Grilled chicken with rice',
            'base_price' => 25000,
            'category' => 'Bowl',
            'image_url' => '/images/chicken-bowl.jpg',
            'is_active' => true,
        ]);

        $riceBowl = Product::create([
            'name' => 'Rice Bowl',
            'description' => 'Rice with vegetables',
            'base_price' => 23000,
            'category' => 'Bowl',
            'image_url' => '/images/rice-bowl.jpg',
            'is_active' => true,
        ]);

        // Get ingredients
        $chicken = Ingredient::where('name', 'Chicken Breast')->first();
        $rice = Ingredient::where('name', 'Rice')->first();
        $spicySauce = Ingredient::where('name', 'Spicy Sauce')->first();

        // Add ingredients to products (recipe)
        if ($chicken) {
            // store recipe quantities in grams (base unit)
            $chickenBowl->ingredients()->attach($chicken->id, [
                'quantity_required' => 200, // 200 g of chicken per bowl
                'unit' => 'g',
            ]);
        }

        if ($rice) {
            $chickenBowl->ingredients()->attach($rice->id, [
                'quantity_required' => 300, // 300 g of rice per bowl
                'unit' => 'g',
            ]);
            
            $riceBowl->ingredients()->attach($rice->id, [
                'quantity_required' => 400, // 400 g of rice per rice bowl
                'unit' => 'g',
            ]);
        }
    }
}
