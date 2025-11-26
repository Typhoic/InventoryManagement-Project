<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Basic smoke test for projections endpoint
     */
    public function test_weekly_projections_endpoint_returns_expected_shape()
    {
        $response = $this->getJson('/api/projections/weekly');
        $response->assertStatus(200);
        $response->assertJsonStructure(['projections']);
    }

    /**
     * Basic smoke test for shopping list endpoint
     */
    public function test_shopping_list_endpoint_requires_valid_input_and_returns_list()
    {
        $response = $this->postJson('/api/shopping-list', ['target_weeks' => 1]);
        $response->assertStatus(200);
        $response->assertJsonStructure(['target_weeks', 'shopping_list']);
    }
}
