<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
    protected $table= 'product_variants';
    protected $fillable=['name'];
    public function products(){
        return $this->hasMany(Product::class);
    }
}
