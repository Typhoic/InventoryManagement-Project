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
        // Ingredients table (chicken, rice, sauce, etc.)
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Chicken Breast", "Rice", "Spicy Sauce"
            $table->decimal('quantity_on_hand', 10, 2)->default(0); // current stock
            $table->decimal('reorder_level', 10, 2)->default(10); // alert when below this
            $table->decimal('unit_price', 10, 2)->default(0); // cost per unit
            $table->string('unit'); // e.g., "kg", "liter", "pcs", "bottle"
            $table->string('category')->nullable(); // e.g., "Protein", "Grain", "Sauce", "Seasoning"
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
