<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    
    protected $fillable=['collection'];
   

    public function products(){
        return $this->hasMany(Product::class);
    }
    public function category(){
        return $this->hasMany(Category::class);
    }
}
