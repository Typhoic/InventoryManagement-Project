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
        // Toppings table (optional add-ons like sausage, eggs)
        Schema::create('toppings', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Sausage", "Egg"
            $table->decimal('price', 10, 2); // additional price
            $table->decimal('quantity_on_hand', 10, 2)->default(0); // current stock
            $table->decimal('reorder_level', 10, 2)->default(10);
            $table->string('unit')->nullable(); // e.g., "pcs", "kg"
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('toppings');
    }
};
