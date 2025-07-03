<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductDetail extends Model
{
   public function products(){
    return $this->hasMany(Product::class);
   }
   public function category(){
    return $this->hasMany(Category::class);
   }
   public function shop(){
    return $this->belongsTo(Shop::class);
   }
}
