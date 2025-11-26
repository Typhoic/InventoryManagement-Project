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
        // Sales table (orders/transactions)
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->decimal('total_amount', 10, 2); // total order amount
            $table->string('payment_method')->nullable(); // cash, card, etc.
            $table->string('order_type')->default('dine_in'); // dine_in, takeout, delivery
            $table->date('sale_date');
            $table->timestamps();
        });

        // Sale items table (individual bowls/items in a sale)
        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained('sales')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('restrict');
            $table->integer('quantity'); // how many bowls
            $table->decimal('unit_price', 10, 2); // price per unit
            $table->json('selected_toppings')->nullable(); // JSON array of topping IDs: [1, 3]
            $table->decimal('toppings_price', 10, 2)->default(0); // total topping price
            $table->decimal('subtotal', 10, 2); // (unit_price + toppings_price) * quantity
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_items');
        Schema::dropIfExists('sales');
    }
};
