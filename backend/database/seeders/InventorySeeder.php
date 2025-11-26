<?php

namespace Database\Seeders;

use App\Models\Inventory;
use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            ['item_name' => 'Chicken Breast', 'quantity_on_hand' => 150, 'reorder_level' => 50, 'unit_price' => 8000, 'category' => 'Poultry'],
            ['item_name' => 'Rice (Kg)', 'quantity_on_hand' => 200, 'reorder_level' => 100, 'unit_price' => 5000, 'category' => 'Grains'],
            ['item_name' => 'Vegetables Mix', 'quantity_on_hand' => 75, 'reorder_level' => 30, 'unit_price' => 3000, 'category' => 'Vegetables'],
            ['item_name' => 'Sauce', 'quantity_on_hand' => 12, 'reorder_level' => 20, 'unit_price' => 2500, 'category' => 'Condiments'],
            ['item_name' => 'Packaging Boxes', 'quantity_on_hand' => 200, 'reorder_level' => 100, 'unit_price' => 500, 'category' => 'Packaging'],
            ['item_name' => 'Seasoning', 'quantity_on_hand' => 8, 'reorder_level' => 15, 'unit_price' => 1500, 'category' => 'Seasonings'],
            ['item_name' => 'Oil (Liter)', 'quantity_on_hand' => 50, 'reorder_level' => 20, 'unit_price' => 12000, 'category' => 'Oils'],
            ['item_name' => 'Plastic Wrap', 'quantity_on_hand' => 10, 'reorder_level' => 25, 'unit_price' => 1000, 'category' => 'Packaging'],
            ['item_name' => 'Utensils', 'quantity_on_hand' => 150, 'reorder_level' => 50, 'unit_price' => 800, 'category' => 'Tools'],
            ['item_name' => 'Napkins', 'quantity_on_hand' => 500, 'reorder_level' => 200, 'unit_price' => 200, 'category' => 'Supplies'],
        ];

        foreach ($items as $item) {
            Inventory::create($item);
        }
    }
}
