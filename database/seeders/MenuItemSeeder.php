<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MenuItem;
use App\Models\Ingredient;

class MenuItemSeeder extends Seeder
{
    public function run()
    {
        // Create menu items
        $chickenBowlKayu = MenuItem::create([
            'name' => 'Chicken Bowl Kayu',
            'price' => 25000,
            'image_url' => '/images/chicken-bowl-kayu.jpg',
            'description' => 'Chicken Bowl with Rice and...',
            'is_active' => true
        ]);

        $chickenBowlMayo = MenuItem::create([
            'name' => 'Chicken Bowl Mayo',
            'price' => 23000,
            'image_url' => '/images/chicken-bowl-mayo.jpg',
            'description' => 'Chicken Bowl with Mayo',
            'is_active' => true
        ]);

        $chickenBowlBlackPepper = MenuItem::create([
            'name' => 'Chicken Bowl Black Pepper',
            'price' => 26000,
            'image_url' => '/images/chicken-bowl-black-pepper.jpg',
            'description' => 'Chicken Bowl with Black Pepper',
            'is_active' => true
        ]);

        // Define ingredient usage per menu item
        $chicken = Ingredient::where('name', 'Chicken')->first();
        $rice = Ingredient::where('name', 'Rice')->first();
        $cheese = Ingredient::where('name', 'Cheese')->first();
        $salt = Ingredient::where('name', 'Salt')->first();
        $pepper = Ingredient::where('name', 'Pepper')->first();

        // Chicken Bowl Kayu uses: 150g chicken, 200g rice, 5g salt
        $chickenBowlKayu->ingredients()->attach([
            $chicken->id => ['quantity_used' => 150],
            $rice->id => ['quantity_used' => 200],
            $salt->id => ['quantity_used' => 5]
        ]);

        // Chicken Bowl Mayo uses: 150g chicken, 200g rice, 50g cheese
        $chickenBowlMayo->ingredients()->attach([
            $chicken->id => ['quantity_used' => 150],
            $rice->id => ['quantity_used' => 200],
            $cheese->id => ['quantity_used' => 50]
        ]);

        // Chicken Bowl Black Pepper uses: 150g chicken, 200g rice, 10g pepper
        $chickenBowlBlackPepper->ingredients()->attach([
            $chicken->id => ['quantity_used' => 150],
            $rice->id => ['quantity_used' => 200],
            $pepper->id => ['quantity_used' => 10]
        ]);
    }
}
