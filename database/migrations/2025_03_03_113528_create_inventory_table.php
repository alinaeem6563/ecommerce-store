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
        Schema::create('inventory', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('product_id')->constrained('products')->onDelete('cascade');

            // // Shipping Options
            // $table->enum('shipping_fulfillment', ['seller', 'company'])->default('seller');
            // $table->enum('global_delivery', ['worldwide', 'selected_countries', 'local'])->default('local');

            // // Attributes (JSON format)
            // $table->json('attributes')->nullable();
            /* Example:
       {
           "fragile": true,
           "biodegradable": false,
           "frozen_product": {
               "max_time_allowed": "24 hours"
           },
           "expiry_date": "2025-12-31"
       }
    */

            // Advanced Options
            // $table->string('product_id_type')->nullable(); // Example: "UPC", "EAN"
            // $table->string('product_id')->nullable(); // Example: "1234567890"

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory');
    }
};
