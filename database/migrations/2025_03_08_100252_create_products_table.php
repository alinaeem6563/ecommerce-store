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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->string('product_slug')->unique();
            $table->foreignId('vendor_id')->constrained('vendors')->onDelete('cascade');
            $table->string('brand_name');
            $table->string('product_type');
            $table->enum('status', ['active', 'inactive', 'draft', 'archived']);
            $table->string('primary_category_name');
            $table->json('categories');
            $table->json('collections');
            $table->json('tags');
            $table->decimal('base_price', 10, 2);
            $table->decimal('current_price', 10, 2);
            $table->decimal('cost_price', 10, 2);
            $table->string('currency');
            $table->string('tax_class');
            $table->decimal('tax_rate', 5, 2);
            $table->boolean('price_includes_tax');
            $table->string('discount_type')->nullable();
            $table->decimal('discount_value', 10, 2)->nullable();
            $table->date('discount_start_date')->nullable();
            $table->date('discount_end_date')->nullable();
            $table->string('sku');
            $table->string('barcode')->nullable()->unique();
            $table->boolean('inventory_tracking');
            $table->integer('stock_quantity');
            $table->string('stock_status');
            $table->integer('low_stock_threshold');
            $table->boolean('allow_backorders');
            $table->integer('min_order_quantity');
            $table->integer('max_order_quantity');
            $table->text('short_description');
            $table->longText('full_description');
            $table->json('features');
            $table->json('benefits');
            $table->string('meta_title');
            $table->text('meta_description');
            $table->json('meta_keywords');
            $table->string('main_image');
            $table->json('image_gallery')->nullable();
            $table->json('video_urls');
            $table->json('document_urls');
            $table->string('thumbnail_image');
            $table->string('alt_text');
            $table->boolean('has_variants');
            $table->json('variant_attributes');
            $table->json('variants');
            $table->json('attributes');
            $table->json('specifications');
            $table->decimal('weight', 10, 2);
            $table->string('weight_unit');
            $table->json('dimensions');
            $table->string('shipping_class');
            $table->boolean('free_shipping');
            $table->json('shipping_dimensions');
            $table->decimal('shipping_weight', 10, 2);
            $table->decimal('additional_shipping_fee', 10, 2);
            $table->json('related_products');
            $table->json('custom_fields');
            $table->boolean('is_visible');
            $table->json('visibility_in');
            $table->date('available_from')->nullable();
            $table->date('available_to')->nullable();
            $table->json('available_in_locations');
            $table->boolean('is_digital');
            $table->integer('download_limit')->nullable();
            $table->integer('download_expiry')->nullable();
            $table->string('download_file')->nullable();
            $table->integer('download_file_size')->nullable();
            $table->string('download_file_type')->nullable();
            $table->boolean('is_subscription');
            $table->string('subscription_period')->nullable();
            $table->integer('subscription_period_interval')->nullable();
            $table->integer('subscription_length')->nullable();
            $table->integer('trial_period')->nullable();
            $table->decimal('sign_up_fee', 10, 2)->nullable();
            $table->json('badges');
            $table->json('labels');
            $table->text('warranty_information')->nullable();
            $table->text('return_policy')->nullable();
            $table->text('safety_warnings')->nullable();
            $table->string('country_of_origin')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
