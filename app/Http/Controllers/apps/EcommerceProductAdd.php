<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Country;
use App\Models\Product;
use App\Models\Variant;
use App\Models\Vendor;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class EcommerceProductAdd extends Controller
{
  public function index(Request $request)
  {
    $products = Product::all();

    $categories = Category::all();

    return view('content.apps.product-list', compact('categories', 'products'));
  }






  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    $products = Product::all();
    $vendors = Vendor::all();
    $categories = Category::all();
    $collections = Collection::all();
    $variants = Variant::all();
    $countries = Country::all();
    return view(
      'content.apps.app-ecommerce-product-add',
      compact('vendors', 'categories', 'collections', 'variants', 'products', 'countries')
    );
  }

  /**
   * Store a newly created resource in storage.
   */








  public function store(Request $request)
  {
    try {
      // Validate the request
      $validator = Validator::make($request->all(), [
        'product_name' => 'required|string|max:255',
        'brand_name' => 'required|string|max:255',
        'vendor_id' => 'required|exists:vendors,id',
        'product_type' => 'required|string',
        'status' => 'required|in:active,inactive,draft,archived',
        'primary_category_name' => 'required|string',
        'categories' => 'required|array',
        'collections' => 'nullable|array',
        'tags' => 'nullable|array',
        'tags.*' => 'string|max:255',
        'short_description' => 'required|string',
        'full_description' => 'required|string',
        'features' => 'nullable|array',
        'benefits' => 'nullable|array',
        'base_price' => 'required|numeric|min:0',
        'current_price' => 'required|numeric|min:0',
        'cost_price' => 'required|numeric|min:0',
        'currency' => 'required|string|max:10',
        'tax_class' => 'required|string|max:50',
        'tax_rate' => 'required|numeric|min:0|max:100',
        'price_includes_tax' => 'boolean',
        'discount_type' => 'nullable|string|max:50',
        'discount_value' => 'nullable|numeric|min:0',
        'discount_start_date' => 'nullable|date',
        'discount_end_date' => 'nullable|date|after_or_equal:discount_start_date',
        'sku' => [
          'required',
          'string',
          'max:255',
          Rule::unique('products', 'sku')
        ],
        'barcode' => 'nullable|unique:products,barcode|max:255',
        'inventory_tracking' => 'required|boolean',
        'stock_quantity' => 'required|integer|min:0',
        'stock_status' => 'required|string',
        'low_stock_threshold' => 'required|integer|min:0',
        'allow_backorders' => 'boolean',
        'min_order_quantity' => 'nullable|integer|min:1',
        'max_order_quantity' => 'nullable|integer|min:1',
        'meta_title' => 'nullable|string|max:255',
        'meta_description' => 'nullable|string',
        'meta_keywords' => 'nullable|array',
        'meta_keywords.*' => 'string|max:255',
        'main_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        'image_gallery.*' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        'video_urls' => 'nullable|array',
        'document_urls' => 'nullable|array',
        'thumbnail_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:1024',
        'alt_text' => 'nullable|string|max:255',
        'has_variants' => 'boolean',
        'variant_attributes' => 'nullable|array',
        'variants' => 'nullable|array',
        'attributes' => 'nullable|array',
        'specifications' => 'nullable|array',
        'weight' => 'nullable|numeric|min:0',
        'weight_unit' => 'nullable|string|max:10',
        'dimensions' => 'nullable|array',
        'shipping_class' => 'nullable|string|max:50',
        'free_shipping' => 'boolean',
        'shipping_dimensions' => 'nullable|array',
        'shipping_weight' => 'nullable|numeric|min:0',
        'additional_shipping_fee' => 'nullable|numeric|min:0',
        'warranty_information' => 'nullable|string',
        'return_policy' => 'nullable|string',
        'safety_warnings' => 'nullable|string',
        'country_of_origin' => 'nullable|string',

      ]);

      if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
      }

      // Generate a unique slug for the product
      $slug = Str::slug($request->product_name);
      $count = Product::where('product_slug', $slug)->count();
      if ($count > 0) {
        $slug .= '-' . ($count + 1);
      }

      // Prepare product data according to the model
      $productData = [
        'product_name' => $request->product_name,
        'brand_name' => $request->brand_name,
        'product_slug' => $slug,
        'vendor_id' => $request->vendor_id,
        'product_type' => $request->product_type,
        'status' => $request->status,
        'primary_category_name' => $request->primary_category_name,
        'categories' => is_array($request->categories) ? $request->categories : [],
        'collections' => is_array($request->collections) ? $request->collections : [],
        'base_price' => $request->base_price ?? 0,
        'current_price' => $request->current_price ?? 0,
        'cost_price' => $request->cost_price ?? 0,
        'currency' => $request->currency,
        'tax_class' => $request->tax_class,
        'tax_rate' => $request->tax_rate ?? 0,
        'price_includes_tax' => $request->price_includes_tax ?? false,
        'discount_type' => $request->discount_type,
        'discount_value' => $request->discount_value ?? 0,
        'discount_start_date' => $request->discount_start_date,
        'discount_end_date' => $request->discount_end_date,
        'sku' => $request->sku,
        'barcode' => $request->barcode,
        'inventory_tracking' => $request->inventory_tracking ?? false,
        'stock_quantity' => $request->stock_quantity ?? 0,
        'stock_status' => $request->stock_status,
        'low_stock_threshold' => $request->low_stock_threshold ?? 0,
        'allow_backorders' => $request->allow_backorders ?? false,
        'min_order_quantity' => $request->min_order_quantity ?? 1,
        'max_order_quantity' => $request->max_order_quantity ?? 100,
        'short_description' => $request->short_description,
        'full_description' => $request->full_description,
        'alt_text' => $request->alt_text,
        'document_urls' => is_array($request->document_urls) ? $request->document_urls : [],
        'meta_title' => $request->meta_title,
        'meta_description' => $request->meta_description,
        'has_variants' => $request->has_variants ?? false,
        'variants' => is_array($request->variants) ? $request->variants : [],
        'weight' => $request->weight ?? 0,
        'weight_unit' => $request->weight_unit ?? 'kg',
        'dimensions' => is_array($request->dimensions) ? $request->dimensions : [],
        'shipping_class' => $request->shipping_class,
        'free_shipping' => $request->free_shipping ?? false,
        'shipping_dimensions' => is_array($request->shipping_dimensions) ? $request->shipping_dimensions : [],
        'shipping_weight' => $request->shipping_weight ?? 0,
        'additional_shipping_fee' => $request->additional_shipping_fee ?? 0,
        'related_products' => is_array($request->related_products) ? $request->related_products : [],
        'is_visible' => $request->is_visible ?? true,
        'available_from' => $request->available_from,
        'available_to' => $request->available_to,
        'is_digital' => $request->is_digital ?? false,
        'download_limit' => $request->download_limit ?? null,
        'download_expiry' => $request->download_expiry ?? null,
        'download_file' => $request->download_file,
        'download_file_size' => $request->download_file_size ?? null,
        'download_file_type' => $request->download_file_type ?? null,
        'is_subscription' => $request->is_subscription ?? false,
        'subscription_period' => $request->subscription_period ?? null,
        'subscription_length' => $request->subscription_length ?? null,
        'trial_period' => $request->trial_period ?? null,
        'sign_up_fee' => $request->sign_up_fee ?? 0,
        'features' => is_array($request->features) ? $request->features : [],
        'benefits' => is_array($request->benefits) ? $request->benefits : [],
        'tags' => is_array($request->tags) ? $request->tags : [],
        'meta_keywords' => is_array($request->meta_keywords) ? $request->meta_keywords : [],
        'video_urls' => is_array($request->video_urls) ? $request->video_urls : [],
        'variant_attributes' => is_array($request->variant_attributes) ? $request->variant_attributes : [],
        'specifications' => is_array($request->specifications) ? $request->specifications : [],
        'attributes' => is_array($request->attributes) ? $request->attributes : [],
        'custom_fields' => is_array($request->custom_fields) ? $request->custom_fields : [],
        'visibility_in' => is_array($request->visibility_in) ? $request->visibility_in : [],
        'available_in_locations' => is_array($request->available_in_locations) ? $request->available_in_locations : [],
        'badges' => is_array($request->badges) ? $request->badges : [],
        'labels' => is_array($request->labels) ? $request->labels : [],
        'warranty_information' => $request->warranty_information,
        'return_policy' => $request->return_policy,
        'safety_warnings' => $request->safety_warnings,
        'country_of_origin' => $request->country_of_origin,

      ];

      // Handle file uploads
      if ($request->hasFile('main_image')) {
        $productData['main_image'] = $request->file('main_image')->store('products/main_images', 'public');
      }
      if ($request->hasFile('thumbnail_image')) {
        $productData['thumbnail_image'] = $request->file('thumbnail_image')->store('products/thumbnails', 'public');
      }
      if ($request->hasFile('image_gallery')) {
        $imageGallery = [];
        foreach ($request->file('image_gallery') as $image) {
          $imageGallery[] = $image->store('products/gallery', 'public') ?? [];
        }
        $productData['image_gallery'] = json_encode($imageGallery);
      }
      if ($request->hasFile('download_file')) {
        $productData['download_file'] = $request->file('download_file')->store('products/downloads', 'public');
      }

      // Save the product
      $product = Product::create($productData);

      return redirect()->back()->with('success', 'Product created successfully');
    } catch (\Exception $e) {
      Log::error('Error storing product: ' . $e->getMessage());
      return redirect()->back()->with('error', 'Something went wrong! ' . $e->getMessage());
    }
  }






  public function duplicateProduct($id)
  {
    try {
      // Find the original product
      $originalProduct = Product::findOrFail($id);

      // Generate a new SKU and Slug
      $newSku = $originalProduct->sku . '-' . time(); // Append timestamp to make SKU unique
      $newSlug = Str::slug($originalProduct->product_name) . '-' . time();

      // Clone product data
      $newProductData = $originalProduct->toArray();
      unset($newProductData['id']); // Remove ID to allow Laravel to create a new one
      $newProductData['sku'] = $newSku;
      $newProductData['barcode'] = $originalProduct->barcode ? $originalProduct->barcode . '-' . time() : null;
      $newProductData['product_slug'] = $newSlug;
      $newProductData['status'] = 'draft'; // Set status to draft to allow modifications

      // Handle images (if needed, you can clone image paths)
      if ($originalProduct->main_image) {
        $newProductData['main_image'] = $originalProduct->main_image; // Or copy the file
      }
      if ($originalProduct->thumbnail_image) {
        $newProductData['thumbnail_image'] = $originalProduct->thumbnail_image;
      }

      // Convert JSON fields back to arrays before inserting
      $jsonFields = ['categories', 'collections', 'tags', 'meta_keywords', 'video_urls', 'document_urls'];
      foreach ($jsonFields as $field) {
        if (isset($newProductData[$field]) && is_string($newProductData[$field])) {
          $newProductData[$field] = json_decode($newProductData[$field], true);
        }
      }

      // Create a new product
      $newProduct = Product::create($newProductData);

      return redirect()->back()->with('success', 'Product duplicated successfully!');
    } catch (\Exception $e) {
      Log::error('Error duplicating product: ' . $e->getMessage());
      return redirect()->back()->with('error', 'Something went wrong! ' . $e->getMessage());
    }
  }

public function activate($id)
{
    try {
        $product = Product::findOrFail($id);
        $product->status = 'active';
        $product->save();

        return response()->json(['success' => true, 'message' => 'Product activated successfully!']);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'Failed to activate product: ' . $e->getMessage()]);
    }
}

public function deactivate($id)
{
    try {
        $product = Product::findOrFail($id);
        $product->status = 'inactive';
        $product->save();

        return response()->json(['success' => true, 'message' => 'Product deactivated successfully!']);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'Failed to deactivate product: ' . $e->getMessage()]);
    }
}

  public function bulkActivate(Request $request)
  {
    Product::whereIn('id', $request->product_ids)->update(['status' => 'active']);
    return response()->json(['message' => 'Selected products activated successfully.']);
  }

  public function bulkDeactivate(Request $request)
  {
    Product::whereIn('id', $request->product_ids)->update(['status' => 'inactive']);
    return response()->json(['message' => 'Selected products deactivated successfully.']);
  }

  public function bulkDelete(Request $request)
  {
    Product::whereIn('id', $request->product_ids)->delete();
    return response()->json(['message' => 'Selected products deleted successfully.']);
  }




  /**
   * Display the specified resource.
   */
  public function show($id)
  {
    $product = Product::with('category')->findOrFail($id);


    return view('content.apps.app-ecommerce-details', compact('product'));
    
  }
  public function shopProductShow($id)
  {
    $product = Product::findOrFail($id);
    return view('content.apps.shop-product-detail', compact('product'));
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit($id)
  {
    $product = Product::findOrFail($id);
    $vendors = Vendor::all();
    $categories = Category::all();
    $collections = Collection::all();
    $variants = Variant::all();
    $countries = Country::all();
    return view('content.apps.product-edit', compact('product' ,'vendors', 'categories', 'collections', 'variants', 'countries'));
  }


  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, $id)
  {
    try {
      // Find product
      $product = Product::findOrFail($id);

      // Validate request
      $validator = Validator::make($request->all(), [
        'product_name' => 'required|string|max:255',
        'brand_name' => 'required|string|max:255',
        'vendor_id' => 'required|exists:vendors,id',
        'product_type' => 'required|string',
        'status' => 'required|in:active,inactive,draft,archived',
        'primary_category_name' => 'required|string',
        'categories' => 'required|array',
        'collections' => 'nullable|array',
        'tags' => 'nullable|array',
        'tags.*' => 'string|max:255',
        'short_description' => 'required|string',
        'full_description' => 'required|string',
        'base_price' => 'required|numeric|min:0',
        'current_price' => 'required|numeric|min:0',
        'cost_price' => 'required|numeric|min:0',
        'currency' => 'required|string|max:10',
        'tax_class' => 'required|string|max:50',
        'tax_rate' => 'required|numeric|min:0|max:100',
        'sku' => [
          'required',
          'string',
          'max:255',
          Rule::unique('products', 'sku')->ignore($product->id),
        ],
        'barcode' => 'nullable|unique:products,barcode,' . $product->id . '|max:255',
        'inventory_tracking' => 'required|boolean',
        'stock_quantity' => 'required|integer|min:0',
        'stock_status' => 'required|string',
        'low_stock_threshold' => 'required|integer|min:0',
        'min_order_quantity' => 'nullable|integer|min:1',
        'max_order_quantity' => 'nullable|integer|min:1',
        'meta_title' => 'nullable|string|max:255',
        'meta_description' => 'nullable|string',
        'meta_keywords' => 'nullable|array',
        'meta_keywords.*' => 'string|max:255',
        'main_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        'image_gallery.*' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        'thumbnail_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:1024',
        'alt_text' => 'nullable|string|max:255',
        'weight' => 'nullable|numeric|min:0',
        'weight_unit' => 'nullable|string|max:10',
      ]);

      if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
      }

      // Generate a unique slug if product name is updated
      if ($product->product_name !== $request->product_name) {
        $slug = Str::slug($request->product_name);
        $count = Product::where('product_slug', $slug)->where('id', '!=', $product->id)->count();
        if ($count > 0) {
          $slug .= '-' . ($count + 1);
        }
        $product->product_slug = $slug;
      }

      // Update product data
      $product->update($request->except(['main_image', 'thumbnail_image', 'image_gallery']));

      // Handle file uploads
      if ($request->hasFile('main_image')) {
        $product->main_image = $request->file('main_image')->store('products/main_images', 'public');
      }
      if ($request->hasFile('thumbnail_image')) {
        $product->thumbnail_image = $request->file('thumbnail_image')->store('products/thumbnails', 'public');
      }
      if ($request->hasFile('image_gallery')) {
        $imageGallery = [];
        foreach ($request->file('image_gallery') as $image) {
          $imageGallery[] = $image->store('products/gallery', 'public');
        }
        $product->image_gallery = json_encode($imageGallery);
      }
      $product->tags = [json_encode(array_map(fn($tag) => ['value' => trim($tag)], $request->tags ?? []))];
      $product->save();

      return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    } catch (\Exception $e) {
      Log::error('Error updating product: ' . $e->getMessage());
      return redirect()->back()->with('error', 'Something went wrong! ' . $e->getMessage());
    }
  }


  /**
   * Remove the specified resource from storage.
   */
  public function destroy($id)
  {
    try {
      $product = Product::findOrFail($id);
      $product->delete();
      return redirect()->back()->with('success','Product Deleted Successfully!');
    } catch (\Exception $e) {
      return redirect()->back()->with('error' , 'Product not found.');
    }
  }
}
