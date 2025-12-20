<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            IngredientSeeder::class,
            IngredientGroupSeeder::class,
            MenuItemSeeder::class,
            // OrderSeeder will be added later after we create the input form
        ]);
    }
}
