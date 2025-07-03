<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;
protected $table='products';
    protected $fillable = [
        'product_id',
        'product_name',
        'brand_name',
        'product_slug',
        'vendor_id',
        'product_type',
        'status',
        'primary_category_name',
        'categories',
        'collections',
        'tags',
        'base_price',
        'current_price',
        'cost_price',
        'currency',
        'tax_class',
        'tax_rate',
        'price_includes_tax',
        'discount_type',
        'discount_value',
        'discount_start_date',
        'discount_end_date',
        'sku',
        'barcode',
        'inventory_tracking',
        'stock_quantity',
        'stock_status',
        'low_stock_threshold',
        'allow_backorders',
        'min_order_quantity',
        'max_order_quantity',
        'short_description',
        'full_description',
        'features',
        'benefits',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'main_image',
        'image_gallery',
        'video_urls',
        'document_urls',
        'thumbnail_image',
        'alt_text',
        'has_variants',
        'variant_attributes',
        'variants',
        'attributes',
        'specifications',
        'weight',
        'weight_unit',
        'dimensions',
        'shipping_class',
        'free_shipping',
        'shipping_dimensions',
        'shipping_weight',
        'additional_shipping_fee',
        'related_products',
        'custom_fields',
        'is_visible',
        'visibility_in',
        'available_from',
        'available_to',
        'available_in_locations',
        'is_digital',
        'download_limit',
        'download_expiry',
        'download_file',
        'download_file_size',
        'download_file_type',
        'is_subscription',
        'subscription_period',
        'subscription_length',
        'trial_period',
        'sign_up_fee',
        'badges',
        'labels',
        'warranty_information',
        'return_policy',
        'safety_warnings',
        'country_of_origin',
    ];

    protected $casts = [
        'features' => 'array',
        'benefits' => 'array',
        'tags' => 'array',
        'categories' => 'array',
        'collections' => 'array',
        'video_urls' => 'array',
        'variant_attributes' => 'array',
        'specifications' => 'array',
        'attributes' => 'array',
        'meta_keywords' => 'array',
        'custom_fields' => 'array',
        'visibility_in' => 'array',
        'available_in_locations' => 'array',
        'badges' => 'array',
        'labels' => 'array',
        'document_urls' => 'array',
        'variants' => 'array',
        'dimensions' => 'array',
        'shipping_dimensions' => 'array',
        'related_products' => 'array',
        
 
    ];

    public function categoryNames()
    {
        return Category::whereIn('id', $this->categories)->pluck('name')->toArray();
    }
    public function collectionNames()
    {
        return Collection::whereIn('id', $this->collections)->pluck('collection')->toArray();
    }
    public function vendor(){
        return $this->belongsTo(Vendor::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'primary_category_name');
    }

    public function collection(){
        return $this->belongsToMany(Collection::class);
    }
    public function variant(){
        return $this->belongsTo(Variant::class);
    }
    public function productDetail(){
        return $this->belongsTo(ProductDetail::class);
    }
    public function shop(){
        return $this->belongsTo(Shop::class);
    }
    public function countries(){
        return $this->belongsTo(Country::class);
    }
}
