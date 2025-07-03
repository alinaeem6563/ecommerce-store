<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
        protected $fillable = ['name', 'description', 'status'];
        public function products()
        {
                return $this->hasMany(Product::class);
        }
        public function shop()
        {
                return $this->belongsTo(Shop::class);
        }
}
