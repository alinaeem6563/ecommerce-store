@extends('layouts/blankLayout')

@section('title', 'Product Detail')

@section('page-style')
    @vite('resources/assets/vendor/scss/pages/product-detail.scss')
@endsection

@section('content')
    <div class="container product-detail-container py-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="">category</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $product->product_name }}</li>
            </ol>
        </nav>

        <div class="row">
            <!-- Product Images Section -->
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="product-images">
                    <div class="main-image-container mb-3">
                        <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->name }}"
                            class="img-fluid main-product-image rounded">
                        @if ($product->current_price > 0)
                            <div class="discount-badge bg-label-success">-{{ $product->discount_value }}
                                {{ $product->discount_type == 'fixed' ? 'Fixed' : '%' }}</div>
                        @endif
                    </div>
                    <div class="thumbnail-container d-flex overflow-auto">
                        @php
                            // Decode image_gallery JSON if it's stored as a string
                            $galleryImages = json_decode($product->image_gallery, true) ?? [];
                        @endphp

                        @foreach ($galleryImages as $index => $image)
                            <div class="thumbnail-item me-2 {{ $index === 0 ? 'active' : '' }}"
                                data-image="{{ asset('storage/' . $image) }}">
                                <img src="{{ asset('storage/' . $image) }}"
                                    alt="{{ $product->product_name }} thumbnail {{ $index + 1 }}" class="img-thumbnail">
                            </div>
                        @endforeach

                    </div>
                </div>

                <!-- Add video gallery section if available -->
                @if (!empty($product->video_urls))
                    <div class="video-gallery mt-3">
                        <h6 class="mb-2">Product Videos:</h6>
                        <div class="row g-2">
                            @foreach ($product->video_urls as $videoUrl)
                                <div class="col-md-6">
                                    <div class="video-thumbnail position-relative rounded overflow-hidden">
                                        <div class="ratio ratio-16x9">
                                            <iframe src="{{ $videoUrl }}" allowfullscreen></iframe>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Product Info Section -->
            <div class="col-lg-6">
                <div class="product-info">
                    <h1 class="product-title mb-2">{{ $product->product_name }}</h1>

                    <div class="product-meta d-flex align-items-center mb-3">
                        <div class="product-rating me-3">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= floor($product->rating))
                                    <i class="ti ti-star filled"></i>
                                @elseif($i - 0.5 <= $product->rating)
                                    <i class="ti ti-star-half-alt filled"></i>
                                @else
                                    <i class="ti ti-star"></i>
                                @endif
                            @endfor
                            <span class="rating-value ms-1">{{ number_format($product->rating, 1) }}</span>
                        </div>
                        <div class="product-reviews">
                            <a href="#reviews" class="review-count">{{ $product->reviews_count }} Reviews</a>
                        </div>
                    </div>

                    <div class="product-price mb-4">
                        <span class="current-price">${{ number_format($product->current_price, 2) }}</span>
                        @if ($product->base_price > $product->current_price)
                            <span class="original-price ms-2">${{ number_format($product->base_price, 2) }}</span>
                        @endif
                    </div>

                    <div class="product-short-description mb-4">
                        <p>{{ $product->short_description }}</p>
                    </div>

                    <!-- Add availability information section -->
                    @if ($product->available_from || $product->available_to)
                        <div class="availability-info mb-4">
                            <div class="availability-dates p-2 bg-label-info rounded mb-2 small">
                                <div class="d-flex align-items-center">
                                    <i class="ti ti-calendar me-2"></i>
                                    <div>
                                        @if ($product->available_from)
                                            <div>Available from:
                                                <strong>{{ \Carbon\Carbon::parse($product->available_from)->format('M d, Y') }}</strong>
                                            </div>
                                        @endif
                                        @if ($product->available_to)
                                            <div>Available until:
                                                <strong>{{ \Carbon\Carbon::parse($product->available_to)->format('M d, Y') }}</strong>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if (!empty($product->available_in_locations))
                                <div class="availability-locations p-2 bg-label-info rounded small">
                                    <div class="d-flex align-items-center">
                                        <i class="ti ti-map-pin me-2"></i>
                                        <div>
                                            Available in:
                                            <strong>{{ implode(', ', $product->available_in_locations) }}</strong>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    <form action="" method="POST" id="add-to-cart-form">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                        @if ($product->has_variants)
                            <div class="product-variations mb-4">
                                @php
                                    $productColors = json_decode($product->colors, true) ?? [];
                                @endphp

                                @if (count($productColors) > 0)
                                    <div class="color-selection mb-3">
                                        <h6 class="variation-title">Color:</h6>
                                        <div class="color-options d-flex flex-wrap">
                                            @foreach ($productColors as $index => $color)
                                                <div class="color-option {{ $index === 0 ? 'active' : '' }}"
                                                    title="{{ $color['name'] }}" data-color-id="{{ $color['id'] }}">
                                                    <span class="color-circle"
                                                        style="background-color: {{ $color['color'] }}"></span>
                                                </div>
                                            @endforeach
                                        </div>
                                        <input type="hidden" name="color_id" id="selected-color"
                                            value="{{ $productColors[0]['id'] ?? '' }}">
                                    </div>
                                @endif

                                @if (optional($product->sizes)->count() > 0)
                                    <div class="size-selection mb-4">
                                        <h6 class="variation-title">Size:</h6>
                                        <div class="size-options d-flex flex-wrap">
                                            @foreach ($product->sizes as $index => $size)
                                                <div class="size-option {{ $index === 2 || ($index === 0 && $product->sizes->count() < 3) ? 'active' : '' }}"
                                                    data-size-id="{{ $size->id }}">
                                                    {{ $size->name }}
                                                </div>
                                            @endforeach
                                        </div>
                                        <input type="hidden" name="size_id" id="selected-size"
                                            value="{{ optional($product->sizes->get(2))->id ?? ($product->sizes->first()->id ?? '') }}">
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- Add variant selection matrix for products with multiple variant attributes -->
                        @if ($product->has_variants && !empty($product->variant_attributes) && count($product->variant_attributes) > 1)
                            <div class="variant-matrix mb-4">
                                <h6 class="mb-2">Available Combinations:</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered">
                                        <thead>
                                            <tr>
                                                @foreach ($product->variant_attributes as $attribute)
                                                    <th>{{ $attribute['name'] }}</th>
                                                @endforeach
                                                <th>Price</th>
                                                <th>Stock</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($product->variants as $variant)
                                                <tr class="{{ $variant['stock_quantity'] <= 0 ? 'table-danger' : '' }}">
                                                    @foreach ($product->variant_attributes as $attribute)
                                                        <td>{{ $variant['attributes'][$attribute['name']] ?? '-' }}</td>
                                                    @endforeach
                                                    <td>${{ number_format($variant['price'] ?? $product->current_price, 2) }}
                                                    </td>
                                                    <td>
                                                        @if ($variant['stock_quantity'] > 0)
                                                            <span class="text-success">In Stock
                                                                ({{ $variant['stock_quantity'] }})</span>
                                                        @else
                                                            <span class="text-danger">Out of Stock</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif

                        <div class="product-actions mb-4">
                            <div class="quantity-selector d-flex align-items-center mb-3">
                                <h6 class="me-3 mb-0">Quantity:</h6>
                                <div class="quantity-input-group">
                                    <button type="button" class="quantity-btn" id="decrease-quantity">-</button>
                                    <input type="number" class="quantity-input" name="quantity" value="1"
                                        min="{{ $product->min_order_quantity }}"
                                        max="{{ $product->max_order_quantity }}">
                                    <button type="button" class="quantity-btn" id="increase-quantity">+</button>

                                </div>
                                <span class="stock-status ms-3">
                                    @if ($product->stock_status === 'in_stock')
                                        <span class="text-success">In Stock</span>
                                    @elseif($product->stock_status === 'out_of_stock')
                                        <span class="text-danger">Out of Stock</span>
                                    @elseif($product->stock_status === 'on_backorder')
                                        <span class="text-warning">On Backorder</span>
                                    @endif
                                </span>
                            </div>
                            <small class="mb-1">Max Order Quantity
                                :<strong>{{ $product->max_order_quantity }}</strong></small>

                            <div class="action-buttons d-flex flex-wrap">
                                <button type="submit" class="btn btn-primary add-to-cart-btn me-2 mb-2 mb-md-0"
                                    {{ $product->stock_status === 'out_of_stock' ? 'disabled' : '' }}>
                                    <i class="ti ti-shopping-cart me-2"></i>
                                    Add to Cart
                                </button>
                                <button type="button" class="btn btn-outline-secondary wishlist-btn"
                                    data-product-id="{{ $product->id }}">
                                    <i class="far fa-heart me-2"></i>
                                    Add to Wishlist
                                </button>
                            </div>

                            <!-- Add social sharing buttons -->
                            <div class="social-sharing mt-3">
                                <h6 class="mb-2">Share This Product:</h6>
                                <div class="d-flex gap-2">
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}"
                                        target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="ti ti-brand-facebook me-1"></i> Facebook
                                    </a>
                                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($product->product_name) }}"
                                        target="_blank" class="btn btn-sm btn-outline-info">
                                        <i class="ti ti-brand-twitter me-1"></i> Twitter
                                    </a>
                                    <a href="https://pinterest.com/pin/create/button/?url={{ urlencode(request()->url()) }}&media={{ urlencode(asset('storage/' . $product->main_image)) }}&description={{ urlencode($product->short_description) }}"
                                        target="_blank" class="btn btn-sm btn-outline-danger">
                                        <i class="ti ti-brand-pinterest me-1"></i> Pinterest
                                    </a>
                                    <a href="mailto:?subject={{ urlencode('Check out this product: ' . $product->product_name) }}&body={{ urlencode('I thought you might be interested in this: ' . request()->url()) }}"
                                        class="btn btn-sm btn-outline-secondary">
                                        <i class="ti ti-mail me-1"></i> Email
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="product-meta-info mb-4">
                        <div class="meta-item d-flex align-items-center mb-2">
                            <span class="meta-label">SKU:</span>
                            <span class="meta-value">{{ $product->sku }}</span>
                        </div>
                        <div class="meta-item d-flex align-items-center mb-2">
                            <span class="meta-label">Category:</span>
                            <span class="meta-value">

                                {{ optional($product->category)->name ?? 'Unknown' }}

                                @if (!empty($product->categoryNames()))
                                    ,{{ implode(', ', $product->categoryNames()) }}
                                @else
                                    <p>Categories: Unknown</p>
                                @endif
                            </span>
                        </div>
                        <div class="meta-item d-flex align-items-center">
                            <span class="meta-label">Tags:</span>
                            <span class="meta-value">
                                @if (!empty($product->tags))
                                    @php
                                        // Extract the first element and decode it
                                        $tags = json_decode($product->tags[0], true);
                                    @endphp

                                    {{ !empty($tags) ? implode(', ', array_column($tags, 'value')) : 'No Tags' }}
                                @endif

                            </span>
                        </div>
                    </div>

                    <!-- Add shipping information section -->
                    <div class="shipping-info mb-4">
                        <h6 class="mb-2">Shipping Information:</h6>
                        <div class="shipping-details">
                            @if ($product->free_shipping)
                                <div class="free-shipping-badge mb-2">
                                    <span class="badge bg-success"><i class="ti ti-truck me-1"></i>Free Shipping</span>
                                </div>
                            @endif

                            <div class="shipping-meta small">
                                @if ($product->shipping_class)
                                    <div class="mb-1">Shipping Class: <strong>{{ $product->shipping_class }}</strong>
                                    </div>
                                @endif

                                @if ($product->shipping_weight)
                                    <div class="mb-1">Shipping Weight: <strong>{{ $product->shipping_weight }}
                                            {{ $product->weight_unit }}</strong></div>
                                @endif

                                @if (!empty($product->shipping_dimensions))
                                    <div class="mb-1">
                                        Dimensions:
                                        <strong>
                                            {{ $product->shipping_dimensions['length'] ?? 0 }} ×
                                            {{ $product->shipping_dimensions['width'] ?? 0 }} ×
                                            {{ $product->shipping_dimensions['height'] ?? 0 }}
                                            {{ $product->shipping_dimensions['unit'] ?? 'cm' }}
                                        </strong>
                                    </div>
                                @endif

                                @if ($product->additional_shipping_fee > 0)
                                    <div class="text-warning">Additional Shipping Fee:
                                        <strong>${{ number_format($product->additional_shipping_fee, 2) }}</strong></div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Add tax information -->
                    <div class="tax-info mb-4 small">
                        <div class="d-flex align-items-center">
                            <i class="ti ti-receipt-tax me-2"></i>
                            <div>
                                Tax Class: <strong>{{ $product->tax_class }}</strong>
                                ({{ $product->tax_rate }}%)
                                @if ($product->price_includes_tax)
                                    <span class="badge bg-info ms-2">Tax Included</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Add Country of Origin if available -->
                    @if ($product->country_of_origin)
                        <div class="origin-info mb-4">
                            <div class="d-flex align-items-center">
                                <i class="ti ti-map-pin me-2"></i>
                                <div>
                                    Country of Origin: <strong>{{ $product->country_of_origin }}</strong>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Add Product Policies (Warranty, Return, Safety) -->
                    @if ($product->warranty_information || $product->return_policy || $product->safety_warnings)
                        <div class="policies-accordion accordion mb-4" id="productPoliciesAccordion">
                            @if ($product->warranty_information)
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="warrantyHeading">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#warrantyCollapse"
                                            aria-expanded="false" aria-controls="warrantyCollapse">
                                            <i class="ti ti-shield me-2"></i> Warranty Information
                                        </button>
                                    </h2>
                                    <div id="warrantyCollapse" class="accordion-collapse collapse"
                                        aria-labelledby="warrantyHeading" data-bs-parent="#productPoliciesAccordion">
                                        <div class="accordion-body">
                                            <p class="small mb-0">{!! $product->warranty_information !!}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($product->return_policy)
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="returnPolicyHeading">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#returnPolicyCollapse"
                                            aria-expanded="false" aria-controls="returnPolicyCollapse">
                                            <i class="ti ti-refresh me-2"></i> Return Policy
                                        </button>
                                    </h2>
                                    <div id="returnPolicyCollapse" class="accordion-collapse collapse"
                                        aria-labelledby="returnPolicyHeading" data-bs-parent="#productPoliciesAccordion">
                                        <div class="accordion-body">
                                            <p class="small mb-0">{!! $product->return_policy !!}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($product->safety_warnings)
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="safetyWarningsHeading">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#safetyWarningsCollapse"
                                            aria-expanded="false" aria-controls="safetyWarningsCollapse">
                                            <i class="ti ti-alert-triangle me-2"></i> Safety Warnings
                                        </button>
                                    </h2>
                                    <div id="safetyWarningsCollapse" class="accordion-collapse collapse"
                                        aria-labelledby="safetyWarningsHeading"
                                        data-bs-parent="#productPoliciesAccordion">
                                        <div class="accordion-body">
                                            <p class="small mb-0">{!! $product->safety_warnings !!}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    <div class="product-benefits">
                        <div class="benefit-item d-flex align-items-center mb-2">
                            <i class="ti ti-truck benefit-icon me-2"></i>
                            <span class="benefit-text">Free shipping on orders over $50</span>
                        </div>
                        <div class="benefit-item d-flex align-items-center mb-2">
                            <i class="ti ti-refresh  benefit-icon me-2"></i>
                            <span class="benefit-text">30-day easy returns</span>
                        </div>
                        <div class="benefit-item d-flex align-items-center">
                            <i class="ti ti-shield benefit-icon me-2"></i>
                            <span class="benefit-text">2-year warranty</span>
                        </div>
                    </div>

                    <!-- Add badges and labels section -->
                    @if (!empty($product->badges) || !empty($product->labels))
                        <div class="product-badges-labels mt-4 mb-4">
                            @if (!empty($product->badges))
                                <div class="badges mb-3">
                                    <h6 class="mb-2">Product Badges:</h6>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach ($product->badges as $badge)
                                            @php
                                                // Map badge types to theme colors
                                                $badgeColors = [
                                                    'new' => 'primary',
                                                    'sale' => 'danger',
                                                    'hot' => 'warning',
                                                    'featured' => 'success',
                                                    'limited' => 'info',
                                                    'exclusive' => 'purple',
                                                    'bestseller' => 'secondary',
                                                ];
                                                $badgeType = strtolower(preg_replace('/\s+/', '', $badge));
                                                $badgeColor = $badgeColors[$badgeType] ?? 'primary';
                                            @endphp
                                            <span class="badge bg-{{ $badgeColor }} rounded-pill">
                                                <i class="ti ti-award me-1"></i>{{ $badge }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if (!empty($product->labels))
                                <div class="labels">
                                    <h6 class="mb-2">Product Labels:</h6>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach ($product->labels as $label)
                                            @php
                                                // Map label types to theme colors with softer background
                                                $labelColors = [
                                                    'organic' => 'success',
                                                    'vegan' => 'success',
                                                    'gluten-free' => 'info',
                                                    'eco-friendly' => 'primary',
                                                    'handmade' => 'warning',
                                                    'natural' => 'success',
                                                    'fair-trade' => 'secondary',
                                                ];
                                                $labelType = strtolower(preg_replace('/\s+/', '', $label));
                                                $labelColor = $labelColors[$labelType] ?? 'secondary';
                                            @endphp
                                            <span
                                                class="badge bg-{{ $labelColor }}-subtle text-{{ $labelColor }} border border-{{ $labelColor }}">
                                                {{ $label }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Add digital product information if applicable -->
                    @if ($product->is_digital)
                        <div class="digital-product-info mb-4 p-3 border rounded bg-light">
                            <h6 class="mb-2"><i class="ti ti-download me-2"></i>Digital Product</h6>
                            <ul class="list-unstyled mb-0">
                                @if ($product->download_limit)
                                    <li><small>Download Limit: <strong>{{ $product->download_limit }}</strong></small></li>
                                @endif
                                @if ($product->download_expiry)
                                    <li><small>Access Expires: <strong>{{ $product->download_expiry }}</strong></small>
                                    </li>
                                @endif
                                @if ($product->download_file_size)
                                    <li><small>File Size: <strong>{{ $product->download_file_size }}</strong></small></li>
                                @endif
                                @if ($product->download_file_type)
                                    <li><small>File Type: <strong>{{ $product->download_file_type }}</strong></small></li>
                                @endif
                            </ul>
                        </div>
                    @endif

                    <!-- Add subscription information if applicable -->
                    @if ($product->is_subscription)
                        <div class="subscription-info mb-4 p-3 border rounded bg-light">
                            <h6 class="mb-2"><i class="ti ti-calendar me-2"></i>Subscription Product</h6>
                            <ul class="list-unstyled mb-0">
                                @if ($product->subscription_period)
                                    <li><small>Billing Period: <strong>{{ $product->subscription_period }}</strong></small>
                                    </li>
                                @endif
                                @if ($product->subscription_length)
                                    <li><small>Subscription Length:
                                            <strong>{{ $product->subscription_length }}</strong></small></li>
                                @endif
                                @if ($product->trial_period)
                                    <li><small>Trial Period: <strong>{{ $product->trial_period }}</strong></small></li>
                                @endif
                                @if ($product->sign_up_fee > 0)
                                    <li><small>Sign-up Fee:
                                            <strong>${{ number_format($product->sign_up_fee, 2) }}</strong></small></li>
                                @endif
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Add document downloads section -->
        @if (!empty($product->document_urls))
            <div class="document-downloads mt-4">
                <h6 class="mb-2">Product Documents:</h6>
                <div class="list-group">
                    @foreach ($product->document_urls as $index => $docUrl)
                        <a href="{{ $docUrl }}"
                            class="list-group-item list-group-item-action d-flex align-items-center" target="_blank">
                            <i class="ti ti-file-text me-2"></i>
                            <span>Document {{ $index + 1 }}</span>
                            <i class="ti ti-download ms-auto"></i>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Product Details Tabs -->
        <div class="product-details-tabs mt-5">
            <ul class="nav nav-tabs" id="productTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="description-tab" data-bs-toggle="tab"
                        data-bs-target="#description" type="button" role="tab" aria-controls="description"
                        aria-selected="true">
                        Description
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="specifications-tab" data-bs-toggle="tab"
                        data-bs-target="#specifications" type="button" role="tab" aria-controls="specifications"
                        aria-selected="false">
                        Specifications
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews"
                        type="button" role="tab" aria-controls="reviews" aria-selected="false">
                        Reviews ({{ $product->reviews_count }})
                    </button>
                </li>
            </ul>
            <div class="tab-content p-4 border border-top-0 rounded-bottom" id="productTabsContent">
                <div class="tab-pane fade show active" id="description" role="tabpanel"
                    aria-labelledby="description-tab">
                    <div class="product-description">
                        {!! $product->full_description !!}

                        @if ($product->features && count($product->features) > 0)
                            <h5 class="features-title mb-3">Key Features:</h5>
                            <ul class="features-list">
                                @foreach ($product->features as $feature)
                                    <li class="feature-item mb-2">{{ $feature }}</li>
                                @endforeach
                            </ul>
                        @endif

                        @if ($product->benefits && count($product->benefits) > 0)
                            <h5 class="benefits-title mb-3">Benefits:</h5>
                            <ul class="benefits-list">
                                @foreach ($product->benefits as $benefit)
                                    <li class="benefit-item mb-2">{{ $benefit }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
                <div class="tab-pane fade" id="specifications" role="tabpanel" aria-labelledby="specifications-tab">
                    <div class="product-specifications">
                        @if (!empty($product->specifications) && count($product->specifications) > 0)
                            <table class="table specifications-table">
                                <tbody>
                                    @foreach ($product->specifications as $spec)
                                        <tr>{{ $spec['group'] }}
                                            <td><strong>{{ $spec['name'] }}</strong></td>
                                            <td>
                                                {{ $spec['value'] }}
                                                @if (!empty($spec['unit']))
                                                    ({{ $spec['unit'] }})
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            @if (count($product->specifications) > 3)
                                <button class="btn btn-link p-0 specs-toggle" data-expanded="false">
                                    Show More <i class="ti ti-chevron-down ms-1"></i>
                                </button>
                            @endif
                        @else
                            <p>No specifications available for this product.</p>
                        @endif

                        <!-- Add product attributes if available -->
                        @if (!empty($product->attributes) && count($product->attributes) > 0)
                            <h5 class="mt-4 mb-3">Product Attributes</h5>
                            <div class="row">
                                @foreach ($product->attributes as $attribute)
                                    <div class="col-md-6 mb-3">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <h6 class="card-title">{{ $attribute['name'] }}</h6>
                                                <p class="card-text">{{ $attribute['value'] }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <!-- Add custom fields if available -->
                        @if (!empty($product->custom_fields) && count($product->custom_fields) > 0)
                            <h5 class="mt-4 mb-3">Additional Information</h5>
                            <table class="table">
                                <tbody>
                                    @foreach ($product->custom_fields as $field)
                                        <tr>
                                            <td><strong>{{ $field['name'] }}</strong></td>
                                            <td>{{ $field['value'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
                <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                    <div class="product-reviews-content">
                        <div class="reviews-summary mb-4">
                            <div class="row align-items-center">
                                <div class="col-md-4 text-center mb-4 mb-md-0">
                                    <div class="overall-rating">
                                        <div class="rating-number">{{ number_format($product->rating, 1) }}</div>
                                        <div class="rating-stars mb-2">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= floor($product->rating))
                                                    <i class="ti ti-star filled"></i>
                                                @elseif($i - 0.5 <= $product->rating)
                                                    <i class="ti ti-star-half-alt filled"></i>
                                                @else
                                                    <i class="ti ti-star"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <div class="rating-count">Based on {{ $product->reviews_count }} reviews</div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="rating-bars">
                                        @foreach ([5, 4, 3, 2, 1] as $star)
                                            @php
                                                $percentage =
                                                    $product->reviews_count > 0
                                                        ? round(
                                                            ($product->reviews->where('rating', $star)->count() /
                                                                $product->reviews_count) *
                                                                100,
                                                        )
                                                        : 0;
                                            @endphp
                                            <div class="rating-bar-item d-flex align-items-center mb-2">
                                                <div class="rating-label me-2">{{ $star }} stars</div>
                                                <div class="progress flex-grow-1 me-2">
                                                    <div class="progress-bar" role="progressbar"
                                                        style="width: {{ $percentage }}%"
                                                        aria-valuenow="{{ $percentage }}" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                                <div class="rating-percent">{{ $percentage }}%</div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="reviews-list">
                            {{-- @foreach (optional($product->reviews)->take(5) as $review)
    <div class="review-item p-3 mb-3 border rounded">
        <div class="review-header d-flex justify-content-between mb-3">
            <div class="reviewer-info">
                <h6 class="reviewer-name mb-0">{{ optional($review->user)->name ?? 'Anonymous' }}</h6>
                <div class="review-date text-muted">
                    {{ optional($review->created_at)->format('F d, Y') }}
                </div>
            </div>
            <div class="review-rating">
                @for ($i = 1; $i <= 5; $i++)
                    <i class="ti ti-star {{ $i <= $review->rating ? 'filled' : '' }}"></i>
                @endfor
            </div>
        </div>
        <div class="review-content">
            <p class="review-text mb-0">{{ $review->comment }}</p>
        </div>
    </div>
@endforeach --}}



                            @if ($product->reviews_count > 5)
                                <div class="text-center mt-4">
                                    <a href="" class="btn btn-outline-primary">Load More Reviews</a>
                                </div>
                            @endif
                        </div>

                        @auth
                            <div class="write-review mt-5">
                                <h4 class="mb-3">Write a Review</h4>
                                <form action="" method="POST" id="review-form">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                                    <div class="mb-3">
                                        <label for="rating" class="form-label">Your Rating</label>
                                        <div class="rating-select">
                                            @for ($i = 5; $i >= 1; $i--)
                                                <input type="radio" id="star{{ $i }}" name="rating"
                                                    value="{{ $i }}" required>
                                                <label for="star{{ $i }}"><i class="ti ti-star"></i></label>
                                            @endfor
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="comment" class="form-label">Your Review</label>
                                        <textarea class="form-control" id="comment" name="comment" rows="4" required></textarea>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Submit Review</button>
                                </form>
                            </div>
                        @else
                            <div class="text-center mt-5 p-4 border rounded">
                                <p>Please <a href="">login</a> to write a review.</p>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products Section -->
        @if (!empty($product->related_products) && count($product->related_products) > 0)
            <div class="related-products mt-5">
                <h3 class="section-title mb-4">You May Also Like</h3>
                <div class="row">
                    @foreach ($product->related_products as $relatedProduct)
                        <div class="col-6 col-md-3 mb-4">
                            <div class="product-card">
                                <div class="product-card-img position-relative">
                                    <a href="{{ route('products.show', $relatedProduct->slug) }}">
                                        <img src="{{ asset('storage/' . $relatedProduct->main_image) }}"
                                            alt="{{ $relatedProduct->name }}" class="img-fluid">
                                    </a>
                                    <div class="product-actions">
                                        <button class="btn btn-sm btn-light rounded-circle action-btn wishlist-btn-small"
                                            data-product-id="{{ $relatedProduct->id }}">
                                            <i class="far fa-heart"></i>
                                        </button>
                                        <form action="{{ route('cart.add') }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $relatedProduct->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="btn btn-sm btn-primary action-btn">
                                                <i class="ti ti-shopping-cart"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                <div class="product-card-body p-3">
                                    <h5 class="product-card-title">
                                        <a
                                            href="{{ route('products.show', $relatedProduct->slug) }}">{{ $relatedProduct->name }}</a>
                                    </h5>
                                    <div class="product-card-price">
                                        <span
                                            class="current-price">${{ number_format($relatedProduct->current_price, 2) }}</span>
                                        @if ($relatedProduct->base_price > $relatedProduct->current_price)
                                            <span
                                                class="original-price ms-2">${{ number_format($relatedProduct->base_price, 2) }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection

@section('page-script')
    @vite('resources/assets/js/product-detail.js')

    <script>
        // Additional JavaScript for new features
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize video gallery
            const videoThumbnails = document.querySelectorAll('.video-thumbnail');
            if (videoThumbnails.length) {
                videoThumbnails.forEach(thumbnail => {
                    thumbnail.addEventListener('click', function() {
                        const iframe = this.querySelector('iframe');
                        if (iframe) {
                            const videoUrl = iframe.getAttribute('src');
                            // Handle video click if needed
                        }
                    });
                });
            }

            // Initialize document downloads tracking
            const documentLinks = document.querySelectorAll('.document-downloads .list-group-item');
            if (documentLinks.length) {
                documentLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        // Track document downloads if needed
                        const documentUrl = this.getAttribute('href');
                        console.log('Document downloaded:', documentUrl);
                    });
                });
            }

            // Initialize social sharing
            const socialButtons = document.querySelectorAll('.social-sharing a');
            if (socialButtons.length) {
                socialButtons.forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();

                        const url = this.getAttribute('href');
                        const width = 600;
                        const height = 400;
                        const left = (window.innerWidth - width) / 2;
                        const top = (window.innerHeight - height) / 2;

                        // Don't open popup for email links
                        if (url.startsWith('mailto:')) {
                            window.location.href = url;
                            return;
                        }

                        window.open(
                            url,
                            'share',
                            `width=${width},height=${height},left=${left},top=${top},toolbar=0,menubar=0,location=0,status=0`
                        );
                    });
                });
            }

            // Initialize specifications toggle
            const specsToggle = document.querySelector('.specs-toggle');
            if (specsToggle) {
                specsToggle.addEventListener('click', function() {
                    const hiddenSpecs = document.querySelectorAll('.spec-hidden');
                    const isExpanded = this.getAttribute('data-expanded') === 'true';

                    if (isExpanded) {
                        // Collapse specs
                        hiddenSpecs.forEach(spec => {
                            spec.style.display = 'none';
                        });
                        this.innerHTML = 'Show More <i class="ti ti-chevron-down ms-1"></i>';
                        this.setAttribute('data-expanded', 'false');
                    } else {
                        // Expand specs
                        hiddenSpecs.forEach(spec => {
                            spec.style.display = '';
                        });
                        this.innerHTML = 'Show Less <i class="ti ti-chevron-up ms-1"></i>';
                        this.setAttribute('data-expanded', 'true');
                    }
                });
            }
        });
    </script>
@endsection
