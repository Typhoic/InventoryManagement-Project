<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Ingredient;

class WeeklyReportNormalizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_weekly_report_normalizes_kg_to_grams_when_updating_stock()
    {
        // create an ingredient with initial stock 0 (stored in grams)
        $ingredient = Ingredient::create([
            'name' => 'Test Ingredient',
            'quantity_on_hand' => 0,
            'unit' => 'g',
            'unit_price' => 1,
        ]);

        $payload = [
            'week_start' => now()->toDateString(),
            'sold_counts' => [],
            'total_income' => 0,
            'ingredient_stock' => [
                [
                    'ingredient_id' => $ingredient->id,
                    'quantity_on_hand' => 5, // manager provides 5 kg
                    'unit' => 'kg',
                ]
            ]
        ];

        $res = $this->postJson('/api/reports/weekly', $payload);
        $res->assertStatus(201);

        $ingredient->refresh();
        // 5 kg -> 5000 g
        $this->assertEquals(5000, (int) $ingredient->quantity_on_hand);
        $this->assertEquals('g', $ingredient->unit);
    }
}
