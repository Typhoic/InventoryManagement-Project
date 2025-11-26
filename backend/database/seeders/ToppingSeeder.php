<?php

namespace Database\Seeders;

use App\Models\Topping;
use Illuminate\Database\Seeder;

class ToppingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $toppings = [
            ['name' => 'Sausage', 'price' => 5000, 'quantity_on_hand' => 20, 'reorder_level' => 5, 'unit' => 'pcs', 'is_active' => true],
            ['name' => 'Boiled Egg', 'price' => 3000, 'quantity_on_hand' => 30, 'reorder_level' => 10, 'unit' => 'pcs', 'is_active' => true],
        ];

        foreach ($toppings as $topping) {
            Topping::create($topping);
        }
    }
}
