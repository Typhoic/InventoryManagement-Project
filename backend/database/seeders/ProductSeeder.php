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
            $chickenBowl->ingredients()->attach($chicken->id, [
                'quantity_required' => 0.2, // 200g of chicken per bowl
                'unit' => 'kg',
            ]);
        }

        if ($rice) {
            $chickenBowl->ingredients()->attach($rice->id, [
                'quantity_required' => 0.3, // 300g of rice per bowl
                'unit' => 'kg',
            ]);
            
            $riceBowl->ingredients()->attach($rice->id, [
                'quantity_required' => 0.4, // 400g of rice per rice bowl
                'unit' => 'kg',
            ]);
        }
    }
}
