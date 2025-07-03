<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class EcommerceProductList extends Controller
{
  public function index()
  {
    $products = Product::with('category')->paginate(10);
    $categories=Category::all();
    return view('content.apps.product-list',compact('products', 'categories'));
    // return view('content.apps.app-ecommerce-product-list',compact('products'));
  }
}
