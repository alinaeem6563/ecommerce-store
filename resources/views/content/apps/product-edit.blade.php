@extends('layouts/layoutMaster')

@section('title', 'Edit Product')

@section('vendor-style')
    @vite(['resources/assets/vendor/scss/pages/product-edit.scss', 'resources/assets/vendor/libs/quill/typography.scss', 'resources/assets/vendor/libs/quill/katex.scss', 'resources/assets/vendor/libs/quill/editor.scss', 'resources/assets/vendor/libs/select2/select2.scss', 'resources/assets/vendor/libs/dropzone/dropzone.scss', 'resources/assets/vendor/libs/flatpickr/flatpickr.scss', 'resources/assets/vendor/libs/tagify/tagify.scss'])
@endsection

@section('content')
    <div class="container-fluid edit-product-wrapper">
        <div class="row">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Main Content -->
            <main class="col-md-12 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Edit Product: {{ $product->product_name }}</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-secondary">
                                <i class="ti ti-arrow-left"></i> Back to Products
                            </a>
                            <a href="{{ route('products.show', $product->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="ti ti-eye"></i> View Product
                            </a>
                        </div>
                    </div>
                </div>

                <div class="product-status-bar mb-4">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body d-flex align-items-center">
                                    <div class="avatar avatar-md bg-primary-subtle me-3">
                                        <i class="ti ti-calendar text-primary"></i>
                                    </div>
                                    <div>
                                        <span class="d-block text-muted small">Created</span>
                                        <span class="fw-semibold">{{ $product->created_at->format('M d, Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body d-flex align-items-center">
                                    <div class="avatar avatar-md bg-info-subtle me-3">
                                        <i class="ti ti-refresh text-info"></i>
                                    </div>
                                    <div>
                                        <span class="d-block text-muted small">Last Updated</span>
                                        <span class="fw-semibold">{{ $product->updated_at->format('M d, Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body d-flex align-items-center">
                                    <div class="avatar avatar-md 
                                        @if($product->status == 'active')
                                            bg-success-subtle
                                        @elseif($product->status == 'draft')
                                            bg-secondary-subtle
                                        @elseif($product->status == 'inactive')
                                            bg-warning-subtle
                                        @else
                                            bg-danger-subtle
                                        @endif
                                        me-3">
                                        <i class="ti ti-circle-check 
                                            @if($product->status == 'active')
                                                text-success
                                            @elseif($product->status == 'draft')
                                                text-secondary
                                            @elseif($product->status == 'inactive')
                                                text-warning
                                            @else
                                                text-danger
                                            @endif"></i>
                                    </div>
                                    <div>
                                        <span class="d-block text-muted small">Status</span>
                                        <span class="fw-semibold text-capitalize">{{ $product->status }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body d-flex align-items-center">
                                    <div class="avatar avatar-md 
                                        @if($product->stock_status == 'in_stock')
                                            bg-success-subtle
                                        @elseif($product->stock_status == 'on_backorder')
                                            bg-warning-subtle
                                        @else
                                            bg-danger-subtle
                                        @endif
                                        me-3">
                                        <i class="ti ti-box 
                                            @if($product->stock_status == 'in_stock')
                                                text-success
                                            @elseif($product->stock_status == 'on_backorder')
                                                text-warning
                                            @else
                                                text-danger
                                            @endif"></i>
                                    </div>
                                    <div>
                                        <span class="d-block text-muted small">Stock</span>
                                        <span class="fw-semibold">
                                            @if($product->stock_status == 'in_stock')
                                                {{ $product->stock_quantity }} in stock
                                            @elseif($product->stock_status == 'on_backorder')
                                                On Backorder
                                            @else
                                                Out of Stock
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <form id="product-form" action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')

                    <!-- Form Navigation Tabs -->
                    <ul class="nav nav-tabs mb-4" id="productTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="basic-tab" data-bs-toggle="tab" data-bs-target="#basic" type="button" role="tab" aria-controls="basic" aria-selected="true">
                                <i class="ti ti-info-alt"></i> Basic Info
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pricing-tab" data-bs-toggle="tab" data-bs-target="#pricing" type="button" role="tab" aria-controls="pricing" aria-selected="false">
                                <i class="ti ti-money"></i> Pricing
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="inventory-tab" data-bs-toggle="tab" data-bs-target="#inventory" type="button" role="tab" aria-controls="inventory" aria-selected="false">
                                <i class="ti ti-package"></i> Inventory
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="media-tab" data-bs-toggle="tab" data-bs-target="#media" type="button" role="tab" aria-controls="media" aria-selected="false">
                                <i class="ti ti-image"></i> Media
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="variants-tab" data-bs-toggle="tab" data-bs-target="#variants" type="button" role="tab" aria-controls="variants" aria-selected="false">
                                <i class="ti ti-layers"></i> Variants
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="shipping-tab" data-bs-toggle="tab" data-bs-target="#shipping" type="button" role="tab" aria-controls="shipping" aria-selected="false">
                                <i class="ti ti-truck"></i> Shipping
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="advanced-tab" data-bs-toggle="tab" data-bs-target="#advanced" type="button" role="tab" aria-controls="advanced" aria-selected="false">
                                <i class="ti ti-settings"></i> Advanced
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="digital-tab" data-bs-toggle="tab" data-bs-target="#digital" type="button" role="tab" aria-controls="digital" aria-selected="false">
                                <i class="ti ti-download"></i> Digital
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="subscription-tab" data-bs-toggle="tab" data-bs-target="#subscription" type="button" role="tab" aria-controls="subscription" aria-selected="false">
                                <i class="ti ti-credit-card"></i> Subscription
                            </button>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content" id="productTabsContent">
                        <!-- Basic Info Tab -->
                        <div class="tab-pane fade show active" id="basic" role="tabpanel" aria-labelledby="basic-tab">
                            <div class="card shadow-sm mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Product Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="product_name" class="form-label">Product Name <span class="text-danger">*</span></label>
                                            <input value="{{ old('product_name', $product->product_name) }}" type="text" class="form-control @error('product_name') is-invalid @enderror" id="product_name" name="product_name" required>
                                            @error('product_name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="product_slug" class="form-label">Product Slug <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input value="{{ old('product_slug', $product->product_slug) }}" type="text" class="form-control @error('product_slug') is-invalid @enderror" id="product_slug" name="product_slug" required>
                                                <button class="btn btn-outline-secondary" type="button" id="generate-slug">
                                                    <i class="ti ti-reload"></i> Generate
                                                </button>
                                            </div>
                                            @error('product_slug')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="vendor_id" class="form-label">Vendor <span class="text-danger">*</span></label>
                                            <select class="form-select select2 @error('vendor_id') is-invalid @enderror" id="vendor_id" name="vendor_id" required>
                                                <option value="">Select vendor</option>
                                                @foreach ($vendors as $vendor)
                                                    <option value="{{ $vendor->id }}" {{ old('vendor_id', $product->vendor_id) == $vendor->id ? 'selected' : '' }}>
                                                        {{ $vendor->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('vendor_id')
                                                <span class="text-danger">Please select a vendor.</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="product_type" class="form-label">Product Type <span class="text-danger">*</span></label>
                                            <select class="form-select select2 @error('product_type') is-invalid @enderror" id="product_type" name="product_type" required>
                                                <option value="">Select Type</option>
                                                <option value="physical" {{ old('product_type', $product->product_type) == 'physical' ? 'selected' : '' }}>Physical Product</option>
                                                <option value="digital" {{ old('product_type', $product->product_type) == 'digital' ? 'selected' : '' }}>Digital Product</option>
                                                <option value="service" {{ old('product_type', $product->product_type) == 'service' ? 'selected' : '' }}>Service</option>
                                                <option value="subscription" {{ old('product_type', $product->product_type) == 'subscription' ? 'selected' : '' }}>Subscription</option>
                                                <option value="bundle" {{ old('product_type', $product->product_type) == 'bundle' ? 'selected' : '' }}>Bundle</option>
                                            </select>
                                            @error('product_type')
                                                <span class="text-danger">Please select a product type.</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                            <select class="form-select select2 @error('status') is-invalid @enderror" id="status" name="status" required>
                                                <option value="draft" {{ old('status', $product->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                                <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>Active</option>
                                                <option value="inactive" {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                                <option value="archived" {{ old('status', $product->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                                            </select>
                                            @error('status')
                                                <span class="text-danger">Please select a status.</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="primary_category_name" class="form-label">Primary Category <span class="text-danger">*</span></label>
                                            <select class="form-select select2 @error('primary_category_name') is-invalid @enderror" id="primary_category_name" name="primary_category_name" required>
                                                <option value="">Select Category</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}" {{ old('primary_category_name', $product->primary_category_id) == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('primary_category_name')
                                                <span class="text-danger">Please select a primary category.</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="categories" class="form-label">Additional Categories</label>
                                            <select class="form-select select2 @error('categories') is-invalid @enderror" id="categories" name="categories[]" multiple>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}" {{ in_array($category->id, old('categories', $productCategories ?? [])) ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('categories')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="collections" class="form-label">Collections</label>
                                            <select class="form-select select2" id="collections" name="collections[]" multiple>
                                                {{-- @foreach ($collections as $collection)
                                                    <option value="{{ $collection->id }}" {{ in_array($collection->id, old('collections', $collection ?? [])) ? 'selected' : '' }}>
                                                        {{ $product->collection }}
                                                    </option>
                                                @endforeach --}}
                                                
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="brand" class="form-label">Brand Name <span class="text-danger">*</span></label>
                                            <input value="{{ old('brand_name', $product->brand_name) }}" type="text" class="form-control @error('brand_name') is-invalid @enderror" id="brand" name="brand_name" required>
                                            @error('brand_name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="tags" class="form-label">Tags</label>
                                     <input type="text" class="form-control" id="tags" name="tags[]" 
       value="{{ old('tags', isset($product->tags) && is_string($product->tags[0]) 
       ? implode(', ', array_column(json_decode($product->tags[0], true) ?? [], 'value')) 
       : '') }}">





                                            <small class="text-muted">Separate tags with commas</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card shadow-sm mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Product Description</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="short_description" class="form-label">Short Description <span class="text-danger">*</span></label>
                                        <textarea class="form-control @error('short_description') is-invalid @enderror" id="short_description" name="short_description" rows="3" required>{{ old('short_description', $product->short_description) }}</textarea>
                                        @error('short_description')
                                            <span class="text-danger">Please provide a short description.</span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="full_description" class="form-label">Full Description <span class="text-danger">*</span></label>
                                        <textarea class="form-control rich-editor @error('full_description') is-invalid @enderror" id="full_description" name="full_description" rows="6" required>{{ old('full_description', $product->full_description) }}</textarea>
                                        @error('full_description')
                                            <span class="text-danger">Please provide a full description.</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="card shadow-sm mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Features & Benefits</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="features" class="form-label">Features</label>
                                        <div id="features-container">
                                            @if (old('features', $product->features))
                                                @foreach (old('features', $product->features) as $feature)
                                                    <div class="input-group mb-2">
                                                        <input value="{{ $feature }}" type="text" class="form-control" name="features[]" placeholder="Product feature">
                                                        <button class="btn btn-outline-secondary remove-feature" type="button">
                                                            <i class="ti ti-trash"></i>
                                                        </button>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="input-group mb-2">
                                                    <input type="text" class="form-control" name="features[]" placeholder="Product feature">
                                                    <button class="btn btn-outline-secondary remove-feature" type="button">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-primary" id="add-feature">
                                            <i class="ti ti-plus"></i> Add Feature
                                        </button>
                                    </div>
                                    <div class="mb-3">
                                        <label for="benefits" class="form-label">Benefits</label>
                                        <div id="benefits-container">
                                            @if (old('benefits', $product->benefits))
                                                @foreach (old('benefits', $product->benefits) as $benefit)
                                                    <div class="input-group mb-2">
                                                        <input value="{{ $benefit }}" type="text" class="form-control" name="benefits[]" placeholder="Product benefit">
                                                        <button class="btn btn-outline-secondary remove-benefit" type="button">
                                                            <i class="ti ti-trash"></i>
                                                        </button>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="input-group mb-2">
                                                    <input type="text" class="form-control" name="benefits[]" placeholder="Product benefit">
                                                    <button class="btn btn-outline-secondary remove-benefit" type="button">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-primary" id="add-benefit">
                                            <i class="ti ti-plus"></i> Add Benefit
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pricing Tab -->
                        <div class="tab-pane fade" id="pricing" role="tabpanel" aria-labelledby="pricing-tab">
                            <div class="card shadow-sm mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Pricing Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label for="base_price" class="form-label">Base Price <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text">$</span>
                                                <input value="{{ old('base_price', $product->base_price) }}" type="number" class="form-control @error('base_price') is-invalid @enderror" id="base_price" name="base_price" step="0.01" min="0" required>
                                            </div>
                                            @error('base_price')
                                                <span class="text-danger">Please provide a base price.</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-4">
                                            <label for="current_price" class="form-label">Current Price <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text">$</span>
                                                <input value="{{ old('current_price', $product->current_price) }}" type="number" class="form-control @error('current_price') is-invalid @enderror" id="current_price" name="current_price" step="0.01" min="0" required>
                                            </div>
                                            @error('current_price')
                                                <span class="text-danger">Please provide a current price.</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-4">
                                            <label for="cost_price" class="form-label">Cost Price <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text">$</span>
                                                <input value="{{ old('cost_price', $product->cost_price) }}" type="number" class="form-control @error('cost_price') is-invalid @enderror" id="cost_price" name="cost_price" step="0.01" min="0" required>
                                            </div>
                                            @error('cost_price')
                                                <span class="text-danger">Please provide a cost price.</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-4">
                                            <label for="currency" class="form-label">Currency <span class="text-danger">*</span></label>
                                            <select class="form-select @error('currency') is-invalid @enderror" id="currency" name="currency" required>
                                                <option value="USD" {{ old('currency', $product->currency) == 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                                                <option value="EUR" {{ old('currency', $product->currency) == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                                                <option value="GBP" {{ old('currency', $product->currency) == 'GBP' ? 'selected' : '' }}>GBP - British Pound</option>
                                                <option value="CAD" {{ old('currency', $product->currency) == 'CAD' ? 'selected' : '' }}>CAD - Canadian Dollar</option>
                                                <option value="AUD" {{ old('currency', $product->currency) == 'AUD' ? 'selected' : '' }}>AUD - Australian Dollar</option>
                                                <option value="JPY" {{ old('currency', $product->currency) == 'JPY' ? 'selected' : '' }}>JPY - Japanese Yen</option>
                                            </select>
                                            @error('currency')
                                                <span class="text-danger">Please select a currency</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-4">
                                            <label for="tax_class" class="form-label">Tax Class <span class="text-danger">*</span></label>
                                            <select class="form-select @error('tax_class') is-invalid @enderror" id="tax_class" name="tax_class" required>
                                                <option value="standard" {{ old('tax_class', $product->tax_class) == 'standard' ? 'selected' : '' }}>Standard</option>
                                                <option value="reduced" {{ old('tax_class', $product->tax_class) == 'reduced' ? 'selected' : '' }}>Reduced Rate</option>
                                                <option value="zero" {{ old('tax_class', $product->tax_class) == 'zero' ? 'selected' : '' }}>Zero Rate</option>
                                            </select>
                                            @error('tax_class')
                                                <span class="text-danger">Please select a Tax class.</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-4">
                                            <label for="tax_rate" class="form-label">Tax Rate (%) <span class="text-danger">*</span></label>
                                            <input value="{{ old('tax_rate', $product->tax_rate) }}" type="number" class="form-control @error('tax_rate') is-invalid @enderror" id="tax_rate" name="tax_rate" step="0.01" min="0" max="100" required>
                                            @error('tax_rate')
                                                <span class="text-danger">Please provide a valid tax rate.</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="price_includes_tax" name="price_includes_tax" value="1" {{ old('price_includes_tax', $product->price_includes_tax) == '1' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="price_includes_tax">Price includes tax</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card shadow-sm mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Discount Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label for="discount_type" class="form-label">Discount Type</label>
                                            <select class="form-select" id="discount_type" name="discount_type">
                                                <option value="">No Discount</option>
                                                <option value="percentage" {{ old('discount_type', $product->discount_type) == 'percentage' ? 'selected' : '' }}>Percentage</option>
                                                <option value="fixed" {{ old('discount_type', $product->discount_type) == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="discount_value" class="form-label">Discount Value</label>
                                            <input value="{{ old('discount_value', $product->discount_value) }}" type="number" class="form-control" id="discount_value" name="discount_value" step="0.01" min="0">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="discount_dates" class="form-label">Discount Period</label>
                                            <div class="input-group">
                                                <input value="{{ old('discount_start_date', $product->discount_start_date) && old('discount_end_date', $product->discount_end_date) ? old('discount_start_date', $product->discount_start_date) . ' to ' . old('discount_end_date', $product->discount_end_date) : '' }}" type="text" class="form-control date-range-picker" id="discount_dates" placeholder="Select date range">
                                                <input type="hidden" name="discount_start_date" id="discount_start_date" value="{{ old('discount_start_date', $product->discount_start_date ? $product->discount_start_date : '') }}">
                                                <input type="hidden" name="discount_end_date" id="discount_end_date" value="{{ old('discount_end_date', $product->discount_end_date ? $product->discount_end_date : '') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Inventory Tab -->
                        <div class="tab-pane fade" id="inventory" role="tabpanel" aria-labelledby="inventory-tab">
                            <div class="card shadow-sm mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Inventory Management</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="sku" class="form-label">SKU (Stock Keeping Unit) <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input value="{{ old('sku', $product->sku) }}" type="text" class="form-control @error('sku') is-invalid @enderror" id="sku" name="sku" required>
                                                <button class="btn btn-outline-secondary" type="button" id="generate-sku">
                                                    <i class="ti ti-reload"></i> Generate
                                                </button>
                                            </div>
                                            @error('sku')
                                                <span class="text-danger">Please provide a SKU.</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="barcode" class="form-label">Barcode (UPC, EAN, etc.)</label>
                                            <div class="input-group">
                                                <input value="{{ old('barcode', $product->barcode) }}" type="text" class="form-control" id="barcode" name="barcode">
                                                <button class="btn btn-outline-secondary" type="button" id="generate-barcode">
                                                    <i class="ti ti-reload"></i> Generate Barcode
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="inventory_tracking" name="inventory_tracking" value="1" {{ old('inventory_tracking', $product->inventory_tracking) == '1' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="inventory_tracking">Track inventory for this product</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4 inventory-field">
                                            <label for="stock_quantity" class="form-label">Stock Quantity <span class="text-danger">*</span></label>
                                            <input value="{{ old('stock_quantity', $product->stock_quantity) }}" type="number" class="form-control @error('stock_quantity') is-invalid @enderror" id="stock_quantity" name="stock_quantity" min="0" required>
                                            @error('stock_quantity')
                                                <span class="text-danger">Please provide a stock quantity.</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-4 inventory-field">
                                            <label for="stock_status" class="form-label">Stock Status <span class="text-danger">*</span></label>
                                            <select class="form-select @error('stock_status') is-invalid @enderror" id="stock_status" name="stock_status" required>
                                                <option value="in_stock" {{ old('stock_status', $product->stock_status) == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                                                <option value="out_of_stock" {{ old('stock_status', $product->stock_status) == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                                                <option value="on_backorder" {{ old('stock_status', $product->stock_status) == 'on_backorder' ? 'selected' : '' }}>On Backorder</option>
                                            </select>
                                            @error('stock_status')
                                                <span class="text-danger">Please select an option</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-4 inventory-field">
                                            <label for="low_stock_threshold" class="form-label">Low Stock Threshold <span class="text-danger">*</span></label>
                                            <input value="{{ old('low_stock_threshold', $product->low_stock_threshold) }}" type="number" class="form-control @error('low_stock_threshold') is-invalid @enderror" id="low_stock_threshold" name="low_stock_threshold" min="0" required>
                                            @error('low_stock_threshold')
                                                <span class="text-danger">Please provide a low stock threshold.</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-4 inventory-field">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="allow_backorders" name="allow_backorders" value="1" {{ old('allow_backorders', $product->allow_backorders) == '1' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="allow_backorders">Allow backorders</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4 inventory-field">
                                            <label for="min_order_quantity" class="form-label">Min Order Quantity <span class="text-danger">*</span></label>
                                            <input value="{{ old('min_order_quantity', $product->min_order_quantity) }}" type="number" class="form-control" id="min_order_quantity" name="min_order_quantity" min="1" required>
                                        </div>
                                        <div class="col-md-4 inventory-field">
                                            <label for="max_order_quantity" class="form-label">Max Order Quantity <span class="text-danger">*</span></label>
                                            <input value="{{ old('max_order_quantity', $product->max_order_quantity) }}" type="number" class="form-control" id="max_order_quantity" name="max_order_quantity" min="1" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Media Tab -->
                        <div class="tab-pane fade" id="media" role="tabpanel" aria-labelledby="media-tab">
                            <div class="card shadow-sm mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Product Images</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="main_image" class="form-label">Main Image</label>
                                            <div class="image-upload-container">
                                                <div class="image-preview" id="main-image-preview">
                                                    @if($product->main_image)
                                                        <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->product_name }}" class="img-fluid">
                                                    @else
                                                        <i class="ti ti-cloud-upload"></i>
                                                        <span>Click or drag to upload</span>
                                                    @endif
                                                </div>
                                                <input type="file" class="form-control visually-hidden" id="main_image" name="main_image" accept="image/*">
                                                <input type="hidden" name="existing_main_image" value="{{ $product->main_image }}">
                                            </div>
                                            @error('main_image')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="thumbnail_image" class="form-label">Thumbnail Image</label>
                                            <div class="image-upload-container">
                                                <div class="image-preview" id="thumbnail-image-preview">
                                                    @if($product->thumbnail_image)
                                                        <img src="{{ asset('storage/' . $product->thumbnail_image) }}" alt="{{ $product->product_name }} thumbnail" class="img-fluid">
                                                    @else
                                                        <i class="ti ti-cloud-upload"></i>
                                                        <span>Click or drag to upload</span>
                                                    @endif
                                                </div>
                                                <input type="file" class="form-control visually-hidden" id="thumbnail_image" name="thumbnail_image" accept="image/*">
                                                <input type="hidden" name="existing_thumbnail_image" value="{{ $product->thumbnail_image }}">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <label for="alt_text" class="form-label">Image Alt Text</label>
                                            <input value="{{ old('alt_text', $product->alt_text) }}" type="text" class="form-control" id="alt_text" name="alt_text" placeholder="Descriptive text for the image">
                                        </div>
                                        <div class="col-md-12">
                                            <label for="image_gallery" class="form-label">Image Gallery</label>
                                            <div class="image-gallery-container">
                                                <div class="image-gallery-preview" id="gallery-preview">
                                                    @if($product->image_gallery && count(json_decode($product->image_gallery, true)) > 0)
                                                        @foreach(json_decode($product->image_gallery, true) as $index => $galleryImage)
                                                            <div class="gallery-item">
                                                                <img src="{{ asset('storage/' . $galleryImage) }}" alt="Gallery image {{ $index + 1 }}" class="img-fluid">
                                                                <div class="gallery-item-actions">
                                                                    <button type="button" class="btn btn-sm btn-danger remove-gallery-item" data-index="{{ $index }}">
                                                                        <i class="ti ti-trash"></i>
                                                                    </button>
                                                                </div>
                                                                <input type="hidden" name="existing_gallery_images[]" value="{{ $galleryImage }}">
                                                            </div>
                                                        @endforeach
                                                        <div class="gallery-upload-placeholder">
                                                            <i class="ti ti-cloud-upload"></i>
                                                            <span>Add more images</span>
                                                        </div>
                                                    @else
                                                        <div class="gallery-upload-placeholder">
                                                            <i class="ti ti-cloud-upload"></i>
                                                            <span>Click or drag to upload multiple images</span>
                                                        </div>
                                                    @endif
                                                </div>
                                                <input type="file" class="form-control visually-hidden" id="image_gallery" name="image_gallery[]" accept="image/*" multiple>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card shadow-sm mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Videos & Documents</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="video_urls" class="form-label">Video URLs</label>
                                        <div id="video-urls-container">
                                            @if (old('video_urls', $product->video_urls))
                                                @foreach (old('video_urls', $product->video_urls) as $video_url)
                                                    <div class="input-group mb-2">
                                                        <input value="{{ $video_url }}" type="url" class="form-control" name="video_urls[]" placeholder="https://www.youtube.com/watch?v=...">
                                                        <button class="btn btn-outline-secondary remove-video" type="button">
                                                            <i class="ti ti-trash"></i>
                                                        </button>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="input-group mb-2">
                                                    <input type="url" class="form-control" name="video_urls[]" placeholder="https://www.youtube.com/watch?v=...">
                                                    <button class="btn btn-outline-secondary remove-video" type="button">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-primary" id="add-video">
                                            <i class="ti ti-plus"></i> Add Video URL
                                        </button>
                                    </div>
                                    <div class="mb-3">
                                        <label for="document_urls" class="form-label">Documents</label>
                                        <div class="document-upload-container">
                                            <div class="document-preview" id="document-preview">
                                                @if($product->document_urls && count($product->document_urls) > 0)
                                                    <div class="existing-documents mb-3">
                                                        <h6>Current Documents</h6>
                                                        <ul class="list-group">
                                                            @foreach($product->document_urls as $index => $document)
                                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                    <span>{{ basename($document) }}</span>
                                                                    <div>
                                                                        <a href="{{ asset('storage/' . $document) }}" class="btn btn-sm btn-outline-primary me-1" target="_blank">
                                                                            <i class="ti ti-eye"></i>
                                                                        </a>
                                                                        <button type="button" class="btn btn-sm btn-outline-danger remove-document" data-index="{{ $index }}">
                                                                            <i class="ti ti-trash"></i>
                                                                        </button>
                                                                    </div>
                                                                    <input type="hidden" name="existing_documents[]" value="{{ $document }}">
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                    <div class="document-upload-new">
                                                        <i class="ti ti-file"></i>
                                                        <span>Add more documents (PDF, DOC, etc.)</span>
                                                    </div>
                                                @else
                                                    <i class="ti ti-file"></i>
                                                    <span>Click or drag to upload documents (PDF, DOC, etc.)</span>
                                                @endif
                                            </div>
                                            <input type="file" class="form-control visually-hidden" id="document_urls" name="document_urls[]" accept=".pdf,.doc,.docx,.xls,.xlsx" multiple>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Variants Tab -->
                        <div class="tab-pane fade" id="variants" role="tabpanel" aria-labelledby="variants-tab">
                            <div class="card shadow-sm mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Product Variants</h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="has_variants" name="has_variants" value="1" {{ old('has_variants', $product->has_variants) == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="has_variants">This product has multiple variants</label>
                                    </div>

                                    <div id="variants-container" style="{{ old('has_variants', $product->has_variants) == '1' ? '' : 'display: none;' }}">
                                        <div class="mb-3">
                                            <label for="variant_attributes" class="form-label">Variant Attributes</label>
                                            <select class="form-select select2" id="variant_attributes" name="variant_attributes[]" multiple>
                                                <option value="color" {{ in_array('color', old('variant_attributes', $product->variant_attributes ?? [])) ? 'selected' : '' }}>Color</option>
                                                <option value="size" {{ in_array('size', old('variant_attributes', $product->variant_attributes ?? [])) ? 'selected' : '' }}>Size</option>
                                                <option value="material" {{ in_array('material', old('variant_attributes', $product->variant_attributes ?? [])) ? 'selected' : '' }}>Material</option>
                                                <option value="style" {{ in_array('style', old('variant_attributes', $product->variant_attributes ?? [])) ? 'selected' : '' }}>Style</option>
                                                <option value="weight" {{ in_array('weight', old('variant_attributes', $product->variant_attributes ?? [])) ? 'selected' : '' }}>Weight</option>
                                            </select>
                                            <small class="text-muted">Select attributes that define your product variants</small>
                                        </div>

                                        <div id="variant-options-container" class="mb-3">
                                            <!-- Dynamically populated based on selected attributes -->
                                            @if($product->variant_options)
                                                @foreach($product->variant_options as $attribute => $options)
                                                    <div class="variant-option-group mb-3" data-attribute="{{ $attribute }}">
                                                        <label class="form-label text-capitalize">{{ $attribute }} Options</label>
                                                        <div class="variant-options">
                                                            @foreach($options as $index => $option)
                                                                <div class="input-group mb-2">
                                                                    <input type="text" class="form-control" name="variant_options[{{ $attribute }}][]" value="{{ $option }}" placeholder="{{ $attribute }} option">
                                                                    <button class="btn btn-outline-secondary remove-variant-option" type="button">
                                                                        <i class="ti ti-trash"></i>
                                                                    </button>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                        <button type="button" class="btn btn-sm btn-outline-primary add-variant-option" data-attribute="{{ $attribute }}">
                                                            <i class="ti ti-plus"></i> Add {{ ucfirst($attribute) }} Option
                                                        </button>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>

                                        <div class="mb-3">
                                            <button type="button" class="btn btn-primary" id="generate-variants">
                                                <i class="ti ti-layers"></i> Generate Variants
                                            </button>
                                        </div>

                                        <div id="variants-table-container" class="table-responsive">
                                            <!-- Variants table will be generated here -->
                                            @if($product->variants && count($product->variants) > 0)
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            @foreach($product->variant_attributes as $attribute)
                                                                <th class="text-capitalize">{{ $attribute }}</th>
                                                            @endforeach
                                                            <th>Image</th>
                                                            <th>SKU</th>
                                                            <th>Price</th>
                                                            <th>Stock</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($product->variants as $index => $variant)
                                                            <tr>
                                                                @foreach($product->variant_attributes as $attribute)
                                                                    <td>
                                                                        <input type="text" class="form-control" name="variants[{{ $index }}][attributes][{{ $attribute }}]" value="{{ $variant['attributes'][$attribute] ?? '' }}">
                                                                    </td>
                                                                @endforeach
                                                                <td>
                                                                    <div class="variant-image-preview">
                                                                        @if(isset($variant['image']) && $variant['image'])
                                                                            <img src="{{ asset('storage/' . $variant['image']) }}" alt="Variant image" class="img-thumbnail">
                                                                        @else
                                                                            <div class="no-image">No Image</div>
                                                                        @endif
                                                                        <input type="file" class="form-control variant-image-input" name="variant_images[{{ $index }}]" accept="image/*">
                                                                        <input type="hidden" name="variants[{{ $index }}][existing_image]" value="{{ $variant['image'] ?? '' }}">
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="variants[{{ $index }}][sku]" value="{{ $variant['sku'] ?? '' }}">
                                                                </td>
                                                                <td>
                                                                    <input type="number" class="form-control" name="variants[{{ $index }}][price]" value="{{ $variant['price'] ?? '' }}" step="0.01" min="0">
                                                                </td>
                                                                <td>
                                                                    <input type="number" class="form-control" name="variants[{{ $index }}][stock_quantity]" value="{{ $variant['stock_quantity'] ?? '' }}" min="0">
                                                                </td>
                                                                <td>
                                                                    <button type="button" class="btn btn-sm btn-outline-danger remove-variant">
                                                                        <i class="ti ti-trash"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card shadow-sm mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Product Attributes</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <p class="text-muted">Add attributes that don't create variants (e.g., material, care instructions)</p>
                                        <div id="attributes-container">
                                            @if (old('attributes', $product->attributes))
                                                @foreach (old('attributes', $product->attributes) as $index => $attribute)
                                                    <div class="row mb-2 attribute-row">
                                                        <div class="col-md-5 mt-1">
                                                            <input value="{{ is_array($attribute) ? $attribute['name'] : $attribute->name }}" type="text" class="form-control" name="attributes[{{ $index }}][name]" placeholder="Attribute name (e.g., Material)">
                                                        </div>
                                                        <div class="col-md-5 mt-1">
                                                            <input value="{{ is_array($attribute) ? $attribute['value'] : $attribute->value }}" type="text" class="form-control" name="attributes[{{ $index }}][value]" placeholder="Attribute value (e.g., Cotton)">
                                                        </div>
                                                        <div class="col-md-1 mt-1">
                                                            <button type="button" class="btn btn-outline-danger remove-attribute">
                                                                <i class="ti ti-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="row mb-2 attribute-row">
                                                    <div class="col-md-5 mt-1">
                                                        <input type="text" class="form-control" name="attributes[0][name]" placeholder="Attribute name (e.g., Material)">
                                                    </div>
                                                    <div class="col-md-5 mt-1">
                                                        <input type="text" class="form-control" name="attributes[0][value]" placeholder="Attribute value (e.g., Cotton)">
                                                    </div>
                                                    <div class="col-md-1 mt-1">
                                                        <button type="button" class="btn btn-outline-danger remove-attribute">
                                                            <i class="ti ti-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-primary" id="add-attribute">
                                            <i class="ti ti-plus"></i> Add Attribute
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="card shadow-sm mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Specifications</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <div id="specifications-container">
                                            @if (old('specifications', $product->specifications))
                                                @foreach (old('specifications', $product->specifications) as $index => $spec)
                                                    <div class="row mb-2 specification-row">
                                                        <div class="col-md-3 mt-1">
                                                            <input value="{{ is_array($spec) ? $spec['group'] : $spec->group }}" type="text" class="form-control" name="specifications[{{ $index }}][group]" placeholder="Group (e.g., Dimensions)">
                                                        </div>
                                                        <div class="col-md-3 mt-1">
                                                            <input value="{{ is_array($spec) ? $spec['name'] : $spec->name }}" type="text" class="form-control" name="specifications[{{ $index }}][name]" placeholder="Name (e.g., Height)">
                                                        </div>
                                                        <div class="col-md-3 mt-1">
                                                            <input value="{{ is_array($spec) ? $spec['value'] : $spec->value }}" type="text" class="form-control" name="specifications[{{ $index }}][value]" placeholder="Value (e.g., 10)">
                                                        </div>
                                                        <div class="col-md-2 mt-1">
                                                            <input value="{{ is_array($spec) ? $spec['unit'] : $spec->unit }}" type="text" class="form-control" name="specifications[{{ $index }}][unit]" placeholder="Unit">
                                                        </div>
                                                        <div class="col-md-1 mt-1">
                                                            <button type="button" class="btn btn-outline-danger remove-specification">
                                                                <i class="ti ti-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="row mb-2 specification-row">
                                                    <div class="col-md-3 mt-1">
                                                        <input type="text" class="form-control" name="specifications[0][group]" placeholder="Group (e.g., Dimensions)">
                                                    </div>
                                                    <div class="col-md-3 mt-1">
                                                        <input type="text" class="form-control" name="specifications[0][name]" placeholder="Name (e.g., Height)">
                                                    </div>
                                                    <div class="col-md-3 mt-1">
                                                        <input type="text" class="form-control" name="specifications[0][value]" placeholder="Value (e.g., 10)">
                                                    </div>
                                                    <div class="col-md-2 mt-1">
                                                        <input type="text" class="form-control" name="specifications[0][unit]" placeholder="Unit">
                                                    </div>
                                                    <div class="col-md-1 mt-1">
                                                        <button type="button" class="btn btn-outline-danger remove-specification">
                                                            <i class="ti ti-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-primary" id="add-specification">
                                            <i class="ti ti-plus"></i> Add Specification
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Shipping Tab -->
                        <div class="tab-pane fade" id="shipping" role="tabpanel" aria-labelledby="shipping-tab">
                            <div class="card shadow-sm mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Shipping Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="weight" class="form-label">Weight <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input value="{{ old('weight', $product->weight) }}" type="number" class="form-control @error('weight') is-invalid @enderror" id="weight" name="weight" step="0.01" min="0" required>
                                                <select class="form-select" id="weight_unit" name="weight_unit" style="max-width: 100px;">
                                                    <option value="kg" {{ old('weight_unit', $product->weight_unit) == 'kg' ? 'selected' : '' }}>kg</option>
                                                    <option value="g" {{ old('weight_unit', $product->weight_unit) == 'g' ? 'selected' : '' }}>g</option>
                                                    <option value="lb" {{ old('weight_unit', $product->weight_unit) == 'lb' ? 'selected' : '' }}>lb</option>
                                                    <option value="oz" {{ old('weight_unit', $product->weight_unit) == 'oz' ? 'selected' : '' }}>oz</option>
                                                </select>
                                            </div>
                                            @error('weight')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="dimensions" class="form-label">Dimensions (L × W × H)</label>
                                            <div class="input-group">
                                                <input value="{{ old('dimensions.length', $product->dimensions['length'] ?? '') }}" type="number" class="form-control" id="length" name="dimensions[length]" step="0.01" min="0" placeholder="L">
                                                <input value="{{ old('dimensions.width', $product->dimensions['width'] ?? '') }}" type="number" class="form-control" id="width" name="dimensions[width]" step="0.01" min="0" placeholder="W">
                                                <input value="{{ old('dimensions.height', $product->dimensions['height'] ?? '') }}" type="number" class="form-control" id="height" name="dimensions[height]" step="0.01" min="0" placeholder="H">
                                                <select class="form-select" id="dimension_unit" name="dimensions[unit]" style="max-width: 100px;">
                                                    <option value="cm" {{ old('dimensions.unit', $product->dimensions['unit'] ?? 'cm') == 'cm' ? 'selected' : '' }}>cm</option>
                                                    <option value="mm" {{ old('dimensions.unit', $product->dimensions['unit'] ?? '') == 'mm' ? 'selected' : '' }}>mm</option>
                                                    <option value="in" {{ old('dimensions.unit', $product->dimensions['unit'] ?? '') == 'in' ? 'selected' : '' }}>in</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="shipping_class" class="form-label">Shipping Class</label>
                                            <select class="form-select" id="shipping_class" name="shipping_class">
                                                <option value="standard" {{ old('shipping_class', $product->shipping_class) == 'standard' ? 'selected' : '' }}>Standard</option>
                                                <option value="bulky" {{ old('shipping_class', $product->shipping_class) == 'bulky' ? 'selected' : '' }}>Bulky</option>
                                                <option value="heavy" {{ old('shipping_class', $product->shipping_class) == 'heavy' ? 'selected' : '' }}>Heavy</option>
                                                <option value="fragile" {{ old('shipping_class', $product->shipping_class) == 'fragile' ? 'selected' : '' }}>Fragile</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="additional_shipping_fee" class="form-label">Additional Shipping Fee</label>
                                            <div class="input-group">
                                                <span class="input-group-text">$</span>
                                                <input value="{{ old('additional_shipping_fee', $product->additional_shipping_fee) }}" type="number" class="form-control" id="additional_shipping_fee" name="additional_shipping_fee" step="0.01" min="0">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="free_shipping" name="free_shipping" value="1" {{ old('free_shipping', $product->free_shipping) == '1' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="free_shipping">This product qualifies for free shipping</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Advanced Tab -->
                        <div class="tab-pane fade" id="advanced" role="tabpanel" aria-labelledby="advanced-tab">
                            <div class="card shadow-sm mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">SEO Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="meta_title" class="form-label">Meta Title</label>
                                        <input value="{{ old('meta_title', $product->meta_title) }}" type="text" class="form-control" id="meta_title" name="meta_title">
                                        <div class="form-text">Leave empty to use product name</div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="meta_description" class="form-label">Meta Description</label>
                                        <textarea class="form-control" id="meta_description" name="meta_description" rows="3">{{ old('meta_description', $product->meta_description) }}</textarea>
                                        <div class="form-text">Leave empty to use short description</div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="meta_keywords" class="form-label">Meta Keywords</label>
                                        <input value="{{ old('meta_keywords', is_array($product->meta_keywords) ? implode(',', $product->meta_keywords) : $product->meta_keywords) }}" type="text" class="form-control" id="meta_keywords" name="meta_keywords[]" data-role="tagsinput">
                                        <small class="text-muted">Select or type to add multiple keywords</small>
                                    </div>
                                </div>
                            </div>

                            <div class="card shadow-sm mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Custom Fields</h5>
                                </div>
                                <div class="card-body">
                                    <div id="custom-fields-container">
                                        @if (old('custom_fields', $product->custom_fields))
                                            @foreach (old('custom_fields', $product->custom_fields) as $index => $field)
                                                <div class="row mb-2 custom-field-row">
                                                    <div class="col-md-5 mt-1">
                                                        <input value="{{ is_array($field) ? $field['name'] : $field->name }}" type="text" class="form-control" name="custom_fields[{{ $index }}][name]" placeholder="Field name">
                                                    </div>
                                                    <div class="col-md-5 mt-1">
                                                        <input value="{{ is_array($field) ? $field['value'] : $field->value }}" type="text" class="form-control" name="custom_fields[{{ $index }}][value]" placeholder="Field value">
                                                    </div>
                                                    <div class="col-md-1 mt-1">
                                                        <button type="button" class="btn btn-outline-danger remove-custom-field">
                                                            <i class="ti ti-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="row mb-2 custom-field-row">
                                                <div class="col-md-5 mt-1">
                                                    <input type="text" class="form-control" name="custom_fields[0][name]" placeholder="Field name">
                                                </div>
                                                <div class="col-md-5 mt-1">
                                                    <input type="text" class="form-control" name="custom_fields[0][value]" placeholder="Field value">
                                                </div>
                                                <div class="col-md-1 mt-1">
                                                    <button type="button" class="btn btn-outline-danger remove-custom-field">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-primary" id="add-custom-field">
                                        <i class="ti ti-plus"></i> Add Custom Field
                                    </button>
                                </div>
                            </div>

                            <div class="card shadow-sm mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Visibility Settings</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-12">
                                            <label for="visibility_in" class="form-label">Visible In</label>
                                            <select class="form-select select2" id="visibility_in" name="visibility_in[]" multiple>
                                                <option value="search" {{ in_array('search', old('visibility_in', $product->visibility_in ?? [])) ? 'selected' : '' }}>Search Results</option>
                                                <option value="catalog" {{ in_array('catalog', old('visibility_in', $product->visibility_in ?? [])) ? 'selected' : '' }}>Catalog</option>
                                                <option value="related" {{ in_array('related', old('visibility_in', $product->visibility_in ?? [])) ? 'selected' : '' }}>Related Products</option>
                                                <option value="upsell" {{ in_array('upsell', old('visibility_in', $product->visibility_in ?? [])) ? 'selected' : '' }}>Upsell</option>
                                                <option value="cross_sell" {{ in_array('cross_sell', old('visibility_in', $product->visibility_in ?? [])) ? 'selected' : '' }}>Cross-sell</option>
                                                <option value="homepage" {{ in_array('homepage', old('visibility_in', $product->visibility_in ?? [])) ? 'selected' : '' }}>Homepage</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card shadow-sm mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Badges and Labels</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="badges" class="form-label">Badges</label>
                                            <input type="text" class="form-control" id="badges" name="badges" value="{{ old('badges', is_array($product->badges) ? implode(',', $product->badges) : $product->badges) }}">
                                            <small class="text-muted">Add badges to highlight product (e.g., New, Hot, Sale)</small>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="labels" class="form-label">Labels</label>
                                            <input type="text" class="form-control" id="labels" name="labels" value="{{ old('labels', is_array($product->labels) ? implode(',', $product->labels) : $product->labels) }}">
                                            <small class="text-muted">Add labels to describe product features (e.g., Organic, Vegan)</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card shadow-sm mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Related Products</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="related_products" class="form-label">Related Products</label>
                                        <select class="form-select select2" id="related_products" name="related_products[]" multiple>
                                            @foreach ($relatedProducts ?? [] as $relatedProduct)
                                                <option value="{{ $relatedProduct->id }}" {{ in_array($relatedProduct->id, old('related_products', $productRelatedIds ?? [])) ? 'selected' : '' }}>
                                                    {{ $relatedProduct->product_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="upsell_products" class="form-label">Upsell Products</label>
                                        <select class="form-select select2" id="upsell_products" name="upsell_products[]" multiple>
                                            @foreach ($relatedProducts ?? [] as $relatedProduct)
                                                <option value="{{ $relatedProduct->id }}" {{ in_array($relatedProduct->id, old('upsell_products', $productUpsellIds ?? [])) ? 'selected' : '' }}>
                                                    {{ $relatedProduct->product_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="cross_sell_products" class="form-label">Cross-sell Products</label>
                                        <select class="form-select select2" id="cross_sell_products" name="cross_sell_products[]" multiple>
                                            @foreach ($relatedProducts ?? [] as $relatedProduct)
                                                <option value="{{ $relatedProduct->id }}" {{ in_array($relatedProduct->id, old('cross_sell_products', $productCrossSellIds ?? [])) ? 'selected' : '' }}>
                                                    {{ $relatedProduct->product_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="card shadow-sm mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Availability</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-12">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="is_visible" name="is_visible" value="1" {{ old('is_visible', $product->is_visible) == '1' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_visible">Visible in catalog</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="available_from" class="form-label">Available From</label>
                                            <input value="{{ old('available_from', $product->available_from ? $product->available_from : '') }}" type="date" class="form-control" id="available_from" name="available_from">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="available_to" class="form-label">Available To</label>
                                            <input value="{{ old('available_to', $product->available_to ? $product->available_to : '') }}" type="date" class="form-control" id="available_to" name="available_to">
                                        </div>
                                        <div class="col-md-12">
                                            <label for="available_in_locations" class="form-label">Available Locations</label>
                                            <select class="form-select select2" id="available_in_locations" name="available_in_locations[]" multiple>
                                                @foreach ($countries ?? [] as $country)
                                                    <option value="{{ $country->name }}" {{ in_array($country->name, old('available_in_locations', $product->available_in_locations ?? [])) ? 'selected' : '' }}>
                                                        {{ $country->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card shadow-sm mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Additional Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="warranty_information" class="form-label">Warranty Information</label>
                                        <textarea class="form-control" id="warranty_information" name="warranty_information" rows="3">{{ old('warranty_information', $product->warranty_information) }}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="return_policy" class="form-label">Return Policy</label>
                                        <textarea class="form-control" id="return_policy" name="return_policy" rows="3">{{ old('return_policy', $product->return_policy) }}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="safety_warnings" class="form-label">Safety Warnings</label>
                                        <textarea class="form-control" id="safety_warnings" name="safety_warnings" rows="3">{{ old('safety_warnings', $product->safety_warnings) }}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="country_of_origin" class="form-label">Country of Origin</label>
                                        <select class="form-select" id="country_of_origin" name="country_of_origin">
                                            <option value="">Select Country</option>
                                            @foreach ($countries ?? [] as $country)
                                                <option value="{{ $country->name }}" {{ old('country_of_origin', $product->country_of_origin) == $country->name ? 'selected' : '' }}>
                                                    {{ $country->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Digital Tab -->
                        <div class="tab-pane fade" id="digital" role="tabpanel" aria-labelledby="digital-tab">
                            <div class="card shadow-sm mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Digital Product Details</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-12">
                                            <div class="form-check form-switch mb-3">
                                                <input class="form-check-input" type="checkbox" id="is_digital" name="is_digital" value="1" {{ old('is_digital', $product->is_digital) == '1' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_digital">This is a digital product</label>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <label for="download_file" class="form-label">Downloadable File</label>
                                            <div class="input-group">
                                                <input type="file" class="form-control" id="download_file" name="download_file">
                                                <span class="input-group-text" id="download_file_size">
                                                    Size: {{ $product->download_file_size ?? '0 KB' }}
                                                </span>
                                                <span class="input-group-text" id="download_file_type">
                                                    Type: {{ $product->download_file_type ?? '-' }}
                                                </span>
                                            </div>
                                            @if($product->download_file)
                                                <div class="mt-2">
                                                    <span class="badge bg-info">Current file: {{ basename($product->download_file) }}</span>
                                                </div>
                                            @endif
                                            <small class="text-muted">Upload the file customers will download after purchase</small>
                                            <input type="hidden" name="download_file_size" id="download_file_size_input" value="{{ old('download_file_size', $product->download_file_size) }}">
                                            <input type="hidden" name="download_file_type" id="download_file_type_input" value="{{ old('download_file_type', $product->download_file_type) }}">
                                            <input type="hidden" name="existing_download_file" value="{{ $product->download_file }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="download_limit" class="form-label">Download Limit</label>
                                            <input value="{{ old('download_limit', $product->download_limit) }}" type="number" class="form-control" id="download_limit" name="download_limit" min="0" placeholder="Number of downloads allowed (0 for unlimited)">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="download_expiry" class="form-label">Download Expiry (days)</label>
                                            <input value="{{ old('download_expiry', $product->download_expiry) }}" type="number" class="form-control" id="download_expiry" name="download_expiry" min="0" placeholder="Days until download expires (0 for never)">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Subscription Tab -->
                        <div class="tab-pane fade" id="subscription" role="tabpanel" aria-labelledby="subscription-tab">
                            <div class="card shadow-sm mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Subscription Details</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-12">
                                            <div class="form-check form-switch mb-3">
                                                <input class="form-check-input" type="checkbox" id="is_subscription" name="is_subscription" value="1" {{ old('is_subscription', $product->is_subscription) == '1' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_subscription">This is a subscription product</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="subscription_period" class="form-label">Billing Period</label>
                                            <select class="form-select" id="subscription_period" name="subscription_period">
                                                <option value="">Select Period</option>
                                                <option value="day" {{ old('subscription_period', $product->subscription_period) == 'day' ? 'selected' : '' }}>Daily</option>
                                                <option value="week" {{ old('subscription_period', $product->subscription_period) == 'week' ? 'selected' : '' }}>Weekly</option>
                                                <option value="month" {{ old('subscription_period', $product->subscription_period) == 'month' ? 'selected' : '' }}>Monthly</option>
                                                <option value="year" {{ old('subscription_period', $product->subscription_period) == 'year' ? 'selected' : '' }}>Yearly</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="subscription_period_interval" class="form-label">Billing Interval</label>
                                            <input value="{{ old('subscription_period_interval', $product->subscription_period_interval) }}" type="number" class="form-control" id="subscription_period_interval" name="subscription_period_interval" min="1" placeholder="Interval between billings">
                                            <small class="text-muted">E.g., 1 for monthly, 3 for quarterly (if period is month)</small>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="subscription_length" class="form-label">Subscription Length</label>
                                            <input value="{{ old('subscription_length', $product->subscription_length) }}" type="number" class="form-control" id="subscription_length" name="subscription_length" min="0" placeholder="Number of renewals (0 for unlimited)">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="sign_up_fee" class="form-label">Sign-up Fee</label>
                                            <div class="input-group">
                                                <span class="input-group-text">$</span>
                                                <input value="{{ old('sign_up_fee', $product->sign_up_fee) }}" type="number" class="form-control" id="sign_up_fee" name="sign_up_fee" step="0.01" min="0" placeholder="One-time fee at sign-up">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="trial_period" class="form-label">Trial Period (days)</label>
                                            <input value="{{ old('trial_period', $product->trial_period) }}" type="number" class="form-control" id="trial_period" name="trial_period" min="0" placeholder="Free trial days (0 for no trial)">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mb-5">
                        <button type="button" class="btn btn-secondary" id="prev-tab">
                            <i class="ti ti-arrow-left"></i> Previous
                        </button>
                        <div>
                            <button type="button" class="btn btn-outline-danger me-2" id="delete-product" data-id="{{ $product->id }}">
                                <i class="ti ti-trash"></i> Delete Product
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-check"></i> Update Product
                            </button>
                        </div>
                        <button type="button" class="btn btn-primary" id="next-tab">
                            Next <i class="ti ti-arrow-right"></i>
                        </button>
                    </div>
                </form>

                <!-- Delete Confirmation Modal -->
                <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to delete this product? This action cannot be undone.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                <form id="deleteForm" action="{{ route('products.destroy', $product->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
@endsection

@section('vendor-script')
    @vite(['resources/assets/vendor/libs/quill/katex.js', 'resources/assets/vendor/libs/quill/quill.js', 'resources/assets/vendor/libs/select2/select2.js', 'resources/assets/vendor/libs/dropzone/dropzone.js', 'resources/assets/vendor/libs/jquery-repeater/jquery-repeater.js', 'resources/assets/vendor/libs/flatpickr/flatpickr.js', 'resources/assets/vendor/libs/tagify/tagify.js'])
    <script src="https://cdn.jsdelivr.net/npm/tinymce@6.0.0/tinymce.min.js"></script>
@endsection

@section('page-script')
    @vite('resources/assets/js/product-edit.js')
@endsection
