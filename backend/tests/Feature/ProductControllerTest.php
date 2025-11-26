<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;
use App\Models\Ingredient;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_make_endpoint_returns_shortage_when_insufficient()
    {
        $ing = Ingredient::create([
            'name' => 'Rice',
            'quantity_on_hand' => 300, // grams
            'unit' => 'g',
            'unit_price' => 1,
        ]);

        $product = Product::create([
            'name' => 'Rice Bowl',
            'description' => 'Test',
            'base_price' => 5000,
            'category' => 'Bowl',
        ]);

        // require 200 g per bowl
        $product->ingredients()->attach($ing->id, ['quantity_required' => 200, 'unit' => 'g']);

        // request quantity 2 -> requires 400g, available 300g -> shortage 100g
        $res = $this->getJson("/api/products/{$product->id}/can-make?quantity=2");
        $res->assertStatus(200);
        $json = $res->json();

        $this->assertArrayHasKey('can_make', $json);
        $this->assertFalse($json['can_make']);
        $this->assertArrayHasKey('shortages', $json);
        $this->assertCount(1, $json['shortages']);
        $this->assertEquals('g', $json['shortages'][0]['unit']);
    }
}
