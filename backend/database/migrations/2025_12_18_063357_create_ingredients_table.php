<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Chicken", "Rice", "Salt"
            $table->decimal('initial_stock', 10, 2); // Initial quantity (e.g., 500 for 500g)
            $table->decimal('current_stock', 10, 2); // Current remaining quantity
            $table->string('unit'); // e.g., "gram", "kg", "liter"
            $table->decimal('low_stock_threshold', 5, 2)->default(30); // % threshold for low stock
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredients');
    }
};
