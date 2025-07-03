<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $categories = Category::all();
        $vendors = Vendor::all();
        $products = Product::all()->map(function ($product) {
            return [
                'id' => $product->id,
                'product_name' => $product->product_name,
                'short_description' => $product->short_description,
                'price' => $product->base_price,
                'current_price' => $product->current_price,
                'discount_value'=>$product->discount_value,
                'category' => $product->primary_category_name ? $product->category->name : 'Unknown',
                'main_image' => asset('storage/' . $product->main_image) // Ensure correct path
            ];
        })->toArray();
        return view('content.apps.app-ecommerce-shop', compact('products', 'categories', 'vendors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::findOrFail($id);
        $suggestedProducts=Product::all();
        if (!$product) {
            return abort(404, 'Product not found');
        }
        $product->variants = is_string($product->variants) ? json_decode($product->variants, true) : $product->variants;

        return view('content.apps.app-ecommerce-details', compact('product', 'suggestedProducts'));
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
