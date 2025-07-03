<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table='categories';
    protected $fillable=[
        'name',
        'slug',
        'image_path',
        'parent_category',
        'description',
        'status'

    ];
    public function products()
    {
        return $this->hasMany(Product::class,'primary_category_name');
    }
    public function collection(){
        return $this->hasMany(Collection::class);
    }
    public function shop(){
        return $this->belongsTo(Shop::class);
    }
    public function productDetail(){
        return $this->belongsTo(ProductDetail::class);
    }
}
