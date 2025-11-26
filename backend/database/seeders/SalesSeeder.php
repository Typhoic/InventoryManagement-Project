<?php

namespace Database\Seeders;

use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SalesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dates = [
            Carbon::now()->subDays(25),
            Carbon::now()->subDays(18),
            Carbon::now()->subDays(10),
            Carbon::now()->subDays(2),
            Carbon::now(),
        ];

        $sales = [
            ['product_name' => 'Chicken Bowl', 'quantity' => 55, 'price' => 25000, 'sale_date' => $dates[0]],
            ['product_name' => 'Rice Bowl', 'quantity' => 80, 'price' => 23000, 'sale_date' => $dates[0]],
            ['product_name' => 'Chicken Bowl', 'quantity' => 45, 'price' => 25000, 'sale_date' => $dates[1]],
            ['product_name' => 'Rice Bowl', 'quantity' => 60, 'price' => 23000, 'sale_date' => $dates[1]],
            ['product_name' => 'Chicken Bowl', 'quantity' => 70, 'price' => 25000, 'sale_date' => $dates[2]],
            ['product_name' => 'Rice Bowl', 'quantity' => 90, 'price' => 23000, 'sale_date' => $dates[2]],
            ['product_name' => 'Chicken Bowl', 'quantity' => 100, 'price' => 25000, 'sale_date' => $dates[3]],
            ['product_name' => 'Rice Bowl', 'quantity' => 110, 'price' => 23000, 'sale_date' => $dates[3]],
            ['product_name' => 'Chicken Bowl', 'quantity' => 115, 'price' => 25000, 'sale_date' => $dates[4]],
            ['product_name' => 'Rice Bowl', 'quantity' => 135, 'price' => 23000, 'sale_date' => $dates[4]],
        ];

        foreach ($sales as $sale) {
            Sale::create($sale);
        }
    }
}
