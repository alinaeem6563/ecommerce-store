<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    public function products(){
        return $this->hasMany(Product::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'primary_category_id'); // Ensure correct foreign key
    }

    public function vendors(){
        return $this->hasMany(Vendor::class);
    }
    public function productDetail()
    {
        return $this->hasMany(ProductDetail::class);
    }
}
