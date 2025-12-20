<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\IngredientGroup;
use App\Models\Ingredient;

class IngredientGroupSeeder extends Seeder
{
    public function run()
    {
        // Create groups
        $spices = IngredientGroup::create([
            'name' => 'Spices',
            'description' => 'All spices and seasonings'
        ]);

        $mainIngredients = IngredientGroup::create([
            'name' => 'Main Ingredients',
            'description' => 'Primary ingredients for dishes'
        ]);

        // Attach ingredients to groups
        $salt = Ingredient::where('name', 'Salt')->first();
        $pepper = Ingredient::where('name', 'Pepper')->first();
        $paprika = Ingredient::where('name', 'Paprika Powder')->first();

        $chicken = Ingredient::where('name', 'Chicken')->first();
        $rice = Ingredient::where('name', 'Rice')->first();
        $cheese = Ingredient::where('name', 'Cheese')->first();

        $spices->ingredients()->attach([$salt->id, $pepper->id, $paprika->id]);
        $mainIngredients->ingredients()->attach([$chicken->id, $rice->id, $cheese->id]);
    }
}
