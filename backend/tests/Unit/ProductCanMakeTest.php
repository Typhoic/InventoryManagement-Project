<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;
use App\Models\Ingredient;

class ProductCanMakeTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_can_be_made_when_ingredients_sufficient()
    {
        // create ingredient with 1000 g available
        $ing = Ingredient::create([
            'name' => 'Chicken',
            'quantity_on_hand' => 1000,
            'unit' => 'g',
            'unit_price' => 1,
        ]);

        $product = Product::create([
            'name' => 'Mini Bowl',
            'description' => 'Test',
            'base_price' => 10000,
            'category' => 'Test',
        ]);

        // attach pivot: require 200 g per product
        $product->ingredients()->attach($ing->id, ['quantity_required' => 200, 'unit' => 'g']);

        $this->assertTrue($product->canBeMade(3)); // needs 600g
        $this->assertFalse($product->canBeMade(6)); // needs 1200g > 1000g
    }
}
