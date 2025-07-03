@extends('layouts/layoutMaster')

@section('title', 'Product Detail')

@section('vendor-style')
    @vite([ 'resources/assets/vendor/libs/swiper/swiper.scss', 'resources/assets/vendor/libs/quill/typography.scss', 'resources/assets/vendor/libs/tagify/tagify.scss'])
@endsection

@section('page-style')
   @vite('resources/assets/vendor/scss/pages/product-detail.scss')
@endsection

@section('content')
    <div class="container-xxl product-detail-container">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Header Section -->
        <div class="product-header">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <h1 class="product-title">{{ $product->product_name }}</h1>
                    <div class="product-badges mt-2">
                        @if ($product->status == 'active')
                            <span class="badge bg-success rounded-pill">Active</span>
                        @elseif($product->status == 'draft')
                            <span class="badge bg-warning rounded-pill">Draft</span>
                        @elseif($product->status == 'inactive')
                            <span class="badge bg-secondary rounded-pill">Inactive</span>
                        @elseif($product->status == 'archived')
                            <span class="badge bg-danger rounded-pill">Archived</span>
                        @endif

                        <span class="badge bg-info rounded-pill">{{ ucfirst($product->product_type) }} Product</span>

                        @if (!empty($product->badges) && is_array($product->badges))
                            @foreach ($product->badges as $badge)
                                <span class="badge bg-primary rounded-pill">{{ e($badge) }}</span>
                            @endforeach
                        @endif
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="d-flex justify-content-lg-end gap-2 flex-wrap">
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                            <i class="ti ti-arrow-left me-1"></i> <span class="d-none d-sm-inline">Back</span>
                        </a>
                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary">
                            <i class="ti ti-pencil me-1"></i> <span class="d-none d-sm-inline">Edit</span>
                        </a>
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                                id="productActionsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ti ti-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="productActionsDropdown">
                                <li><a class="dropdown-item" href="{{route('product.duplicate',$product->id)}}"><i class="ti ti-copy me-2"></i> Duplicate</a>
                                </li>
                                <li><a class="dropdown-item" href="{{route('products.show',$product->id)}}"><i class="ti ti-eye me-2"></i> Preview</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item text-danger" href="#"><i class="ti ti-trash me-2"></i>
                                        Delete</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <ul class="nav nav-tabs product-tabs mt-4" id="productTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="basic-tab" data-bs-toggle="tab" data-bs-target="#basic" type="button"
                    role="tab" aria-controls="basic" aria-selected="true">
                    <i class="ti ti-info-circle me-1"></i> <span>Basic Info</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pricing-tab" data-bs-toggle="tab" data-bs-target="#pricing" type="button"
                    role="tab" aria-controls="pricing" aria-selected="false">
                    <i class="ti ti-currency-dollar me-1"></i> <span>Pricing</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="inventory-tab" data-bs-toggle="tab" data-bs-target="#inventory" type="button"
                    role="tab" aria-controls="inventory" aria-selected="false">
                    <i class="ti ti-box me-1"></i> <span>Inventory</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="media-tab" data-bs-toggle="tab" data-bs-target="#media" type="button"
                    role="tab" aria-controls="media" aria-selected="false">
                    <i class="ti ti-photo me-1"></i> <span>Media</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="variants-tab" data-bs-toggle="tab" data-bs-target="#variants" type="button"
                    role="tab" aria-controls="variants" aria-selected="false">
                    <i class="ti ti-layers me-1"></i> <span>Variants</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="shipping-tab" data-bs-toggle="tab" data-bs-target="#shipping"
                    type="button" role="tab" aria-controls="shipping" aria-selected="false">
                    <i class="ti ti-truck me-1"></i> <span>Shipping</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="meta-tab" data-bs-toggle="tab" data-bs-target="#meta" type="button"
                    role="tab" aria-controls="meta" aria-selected="false">
                    <i class="ti ti-search me-1"></i> <span>Meta</span>
                </button>
            </li>
            <li class="nav-item dropdown">
                <button class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="ti ti-dots-vertical me-1"></i> <span>More</span>
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <button class="dropdown-item" id="advanced-tab" data-bs-toggle="tab" data-bs-target="#advanced"
                            type="button" role="tab" aria-controls="advanced" aria-selected="false">
                            <i class="ti ti-settings me-1"></i> Advanced
                        </button>
                    </li>
                    @if ($product->product_type == 'digital' || $product->is_digital == 1)
                        <li>
                            <button class="dropdown-item" id="digital-tab" data-bs-toggle="tab"
                                data-bs-target="#digital" type="button" role="tab" aria-controls="digital"
                                aria-selected="false">
                                <i class="ti ti-download me-1"></i> Digital
                            </button>
                        </li>
                    @endif
                    @if ($product->product_type == 'subscription' || $product->is_subscription == 1)
                        <li>
                            <button class="dropdown-item" id="subscription-tab" data-bs-toggle="tab"
                                data-bs-target="#subscription" type="button" role="tab"
                                aria-controls="subscription" aria-selected="false">
                                <i class="ti ti-credit-card me-1"></i> Subscription
                            </button>
                        </li>
                    @endif
                </ul>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content product-tab-content" id="productTabsContent">
            <!-- Basic Info Tab -->
            <div class="tab-pane fade show active" id="basic" role="tabpanel" aria-labelledby="basic-tab">
                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">Product Information</h5>
                                <span class="badge bg-primary-subtle text-primary">ID: #{{ $product->id }}</span>
                            </div>
                            <div class="card-body">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="product-info-item">
                                            <h6 class="fw-semibold text-muted mb-1">Product Name</h6>
                                            <p class="mb-0 fs-5">{{ $product->product_name }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="product-info-item">
                                            <h6 class="fw-semibold text-muted mb-1">Product Slug</h6>
                                            <p class="mb-0 fs-5">{{ $product->product_slug }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="product-info-item">
                                            <h6 class="fw-semibold text-muted mb-1">Vendor</h6>
                                            <p class="mb-0">{{ $product->vendor->name ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="product-info-item">
                                            <h6 class="fw-semibold text-muted mb-1">Product Type</h6>
                                            <p class="mb-0">{{ ucfirst($product->product_type) }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="product-info-item">
                                            <h6 class="fw-semibold text-muted mb-1">Status</h6>
                                            <p class="mb-0">{{ ucfirst($product->status) }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="product-info-item">
                                            <h6 class="fw-semibold text-muted mb-1">Brand Name</h6>
                                            <p class="mb-0">{{ $product->brand_name }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card shadow-sm mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Product Description</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-4">
                                    <h6 class="fw-semibold text-muted mb-2">Short Description</h6>
                                    <p class="mb-0">{{ $product->short_description }}</p>
                                </div>
                                <div>
                                    <h6 class="fw-semibold text-muted mb-2">Full Description</h6>
                                    <div class="rich-text-content p-3 bg-body-tertiary rounded">
                                        {!! $product->full_description !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Features & Benefits -->
                        <div class="card shadow-sm mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">Features & Benefits</h5>
                                <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#featuresCollapse" aria-expanded="true"
                                    aria-controls="featuresCollapse">
                                    <i class="ti ti-chevron-down"></i>
                                </button>
                            </div>
                            <div class="collapse show" id="featuresCollapse">
                                <div class="card-body">
                                    <div class="row g-4">
                                        @if (!empty($product->features) && is_array($product->features))
                                            <div class="col-md-6">
                                                <h6 class="fw-semibold text-muted mb-3">Features</h6>
                                                <ul class="feature-list">
                                                    @foreach ($product->features as $feature)
                                                        <li class="feature-item">
                                                            <i class="ti ti-check-circle text-success me-2"></i>
                                                            <span>{{ e($feature) }}</span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        @if (!empty($product->benefits) && is_array($product->benefits))
                                            <div class="col-md-6">
                                                <h6 class="fw-semibold text-muted mb-3">Benefits</h6>
                                                <ul class="benefit-list">
                                                    @foreach ($product->benefits as $benefit)
                                                        <li class="benefit-item">
                                                            <i class="ti ti-star text-warning me-2"></i>
                                                            <span>{{ e($benefit) }}</span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="card shadow-sm mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Additional Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="accordion" id="additionalInfoAccordion">
                                    @if ($product->warranty_information)
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                    data-bs-target="#warrantyCollapse" aria-expanded="true"
                                                    aria-controls="warrantyCollapse">
                                                    Warranty Information
                                                </button>
                                            </h2>
                                            <div id="warrantyCollapse" class="accordion-collapse collapse show"
                                                data-bs-parent="#additionalInfoAccordion">
                                                <div class="accordion-body">
                                                    {{ $product->warranty_information }}
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($product->return_policy)
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#returnPolicyCollapse"
                                                    aria-expanded="false" aria-controls="returnPolicyCollapse">
                                                    Return Policy
                                                </button>
                                            </h2>
                                            <div id="returnPolicyCollapse" class="accordion-collapse collapse"
                                                data-bs-parent="#additionalInfoAccordion">
                                                <div class="accordion-body">
                                                    {{ $product->return_policy }}
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($product->safety_warnings)
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#safetyWarningsCollapse"
                                                    aria-expanded="false" aria-controls="safetyWarningsCollapse">
                                                    Safety Warnings
                                                </button>
                                            </h2>
                                            <div id="safetyWarningsCollapse" class="accordion-collapse collapse"
                                                data-bs-parent="#additionalInfoAccordion">
                                                <div class="accordion-body">
                                                    {{ $product->safety_warnings }}
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($product->country_of_origin)
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#countryOfOriginCollapse"
                                                    aria-expanded="false" aria-controls="countryOfOriginCollapse">
                                                    Country of Origin
                                                </button>
                                            </h2>
                                            <div id="countryOfOriginCollapse" class="accordion-collapse collapse"
                                                data-bs-parent="#additionalInfoAccordion">
                                                <div class="accordion-body">
                                                    {{ $product->country_of_origin }}
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <!-- Categories & Collections -->
                        <div class="card shadow-sm mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Categories & Collections</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <h6 class="fw-semibold text-muted mb-2">Primary Category</h6>
                                    <div class="d-flex align-items-center">
                                        <i class="ti ti-category me-2 text-primary"></i>
                                        <span>{{ optional($product->category)->name ?? 'N/A' }}</span>
                                    </div>
                                </div>

                                @if (!empty($product->categories))
                                    @php
                                        $categories = is_array($product->categories)
                                            ? $product->categories
                                            : json_decode($product->categories, true);
                                    @endphp
                                    @if (!empty($categories) && is_array($categories))
                                        <div class="mb-3">
                                            <h6 class="fw-semibold text-muted mb-2">Additional Categories</h6>
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach ($product->categoryNames() as $category)
                                                    <span
                                                        class="badge bg-secondary-subtle text-secondary rounded-pill">{{ $category }}</span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                @endif

                                @if (!empty($product->collections))
                                    @php
                                        $collections = is_array($product->collections)
                                            ? $product->collections
                                            : json_decode($product->collections, true);
                                    @endphp
                                    @if (!empty($collections) && is_array($collections))
                                        <div class="mb-3">
                                            <h6 class="fw-semibold text-muted mb-2">Collections</h6>
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach ($product->collectionNames() as $collection)
                                                    <span
                                                        class="badge bg-info-subtle text-info rounded-pill">{{ $collection }}</span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>

                        <!-- Tags -->
                        <div class="card shadow-sm mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Tags</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-flex flex-wrap gap-2">
                                    @if (!empty($product->tags))
                                        @php
                                            $tags = json_decode($product->tags[0], true);
                                        @endphp

                                        @if (!empty($tags))
                                            @foreach (array_column($tags, 'value') as $tag)
                                                <span
                                                    class="badge bg-primary-subtle text-primary rounded-pill">{{ $tag }}</span>
                                            @endforeach
                                        @else
                                            <span class="text-muted">No tags added</span>
                                        @endif
                                    @else
                                        <span class="text-muted">No tags added</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- QR Code -->
                        <div class="card shadow-sm mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Product QR Code</h5>
                            </div>
                            <div class="card-body text-center">
                                <div id="product-qrcode" class="qr-code-container mb-3 d-inline-block"></div>
                                <div>
                                    <button class="btn btn-sm btn-primary" id="download-qrcode">
                                        <i class="ti ti-download me-1"></i> Download QR Code
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pricing Tab -->
            <div class="tab-pane fade" id="pricing" role="tabpanel" aria-labelledby="pricing-tab">
                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Pricing Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="pricing-card">
                                            <div class="pricing-label">Base Price</div>
                                            <div class="pricing-value">
                                                {{ $product->currency }}
                                                {{ number_format($product->base_price, 2) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="pricing-card highlight">
                                            <div class="pricing-label">Current Price</div>
                                            <div class="pricing-value">
                                                {{ $product->currency }}
                                                {{ number_format($product->current_price, 2) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="pricing-card">
                                            <div class="pricing-label">Cost Price</div>
                                            <div class="pricing-value">
                                                {{ $product->currency }}
                                                {{ number_format($product->cost_price, 2) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="pricing-card">
                                            <div class="pricing-label">Profit Margin</div>
                                            <div class="pricing-value">
                                                @php
                                                    $profitMargin = 0;
                                                    if ($product->cost_price > 0) {
                                                        $profit = $product->current_price - $product->cost_price;
                                                        $profitMargin = ($profit / $product->cost_price) * 100;
                                                    }
                                                @endphp
                                                {{ number_format($profitMargin, 2) }}%
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr class="my-4">

                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="product-info-item">
                                            <h6 class="fw-semibold text-muted mb-1">Currency</h6>
                                            <p class="mb-0">{{ $product->currency }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="product-info-item">
                                            <h6 class="fw-semibold text-muted mb-1">Tax Class</h6>
                                            <p class="mb-0">{{ ucfirst($product->tax_class) }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="product-info-item">
                                            <h6 class="fw-semibold text-muted mb-1">Tax Rate</h6>
                                            <p class="mb-0">{{ $product->tax_rate }}%</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="product-info-item">
                                            <h6 class="fw-semibold text-muted mb-1">Price Includes Tax</h6>
                                            <p class="mb-0">{!! $product->price_includes_tax
                                                ? '<span class="badge bg-success rounded-pill">Yes</span>'
                                                : '<span class="badge bg-danger rounded-pill">No</span>' !!}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        @if ($product->discount_type)
                            <div class="card shadow-sm mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Discount Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="discount-badge mb-4">
                                        @if ($product->discount_type == 'percentage')
                                            <div class="discount-value">{{ $product->discount_value }}% OFF</div>
                                        @else
                                            <div class="discount-value">{{ $product->currency }}
                                                {{ number_format($product->discount_value, 2) }} OFF</div>
                                        @endif
                                    </div>

                                    <div class="mb-3">
                                        <h6 class="fw-semibold text-muted mb-1">Discount Type</h6>
                                        <p class="mb-0">{{ ucfirst($product->discount_type) }}</p>
                                    </div>

                                    @if ($product->discount_start_date && $product->discount_end_date)
                                        <div class="mb-3">
                                            <h6 class="fw-semibold text-muted mb-1">Discount Period</h6>
                                            <p class="mb-0">
                                                {{ \Carbon\Carbon::parse($product->discount_start_date)->format('M d, Y') }}
                                                to
                                                {{ \Carbon\Carbon::parse($product->discount_end_date)->format('M d, Y') }}
                                            </p>

                                            @php
                                                $now = \Carbon\Carbon::now();
                                                $startDate = \Carbon\Carbon::parse($product->discount_start_date);
                                                $endDate = \Carbon\Carbon::parse($product->discount_end_date);
                                                $isActive = $now->between($startDate, $endDate);
                                            @endphp

                                            @if ($isActive)
                                                <span class="badge bg-success rounded-pill mt-2">Active Discount</span>
                                            @elseif($now->lt($startDate))
                                                <span class="badge bg-info rounded-pill mt-2">Upcoming Discount</span>
                                            @else
                                                <span class="badge bg-secondary rounded-pill mt-2">Expired Discount</span>
                                            @endif
                                        </div>

                                        <div class="discount-progress mt-4">
                                            <div class="d-flex justify-content-between mb-1">
                                                <small>{{ \Carbon\Carbon::parse($product->discount_start_date)->format('M d') }}</small>
                                                <small>{{ \Carbon\Carbon::parse($product->discount_end_date)->format('M d') }}</small>
                                            </div>
                                            <div class="progress progress-animated" style="height: 8px;">
                                                @php
                                                    $totalDays = $startDate->diffInDays($endDate);
                                                    $daysElapsed = min($startDate->diffInDays($now), $totalDays);
                                                    $progressPercentage =
                                                        $totalDays > 0 ? ($daysElapsed / $totalDays) * 100 : 0;
                                                @endphp
                                                <div class="progress-bar bg-primary" role="progressbar"
                                                    style="width: {{ $progressPercentage }}%"
                                                    aria-valuenow="{{ $progressPercentage }}" aria-valuemin="0"
                                                    aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <div class="card shadow-sm mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Price History</h5>
                            </div>
                            <div class="card-body">
                                <div id="price-history-chart" class="price-chart" style="height: 250px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Inventory Tab -->
            <div class="tab-pane fade" id="inventory" role="tabpanel" aria-labelledby="inventory-tab">
                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Inventory Management</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="product-info-item">
                                            <h6 class="fw-semibold text-muted mb-1">SKU (Stock Keeping Unit)</h6>
                                            <p class="mb-0 fs-5 font-monospace">{{ $product->sku ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="product-info-item">
                                            <h6 class="fw-semibold text-muted mb-1">Barcode</h6>
                                            <p class="mb-0 fs-5 font-monospace">{{ $product->barcode ?? 'N/A' }}</p>
                                            @if ($product->barcode)
                                                <div class="text-center mt-2 p-2 bg-body-tertiary rounded">
                                                    <svg id="barcode-display"></svg>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="product-info-item">
                                            <h6 class="fw-semibold text-muted mb-1">Stock Quantity</h6>
                                            <p class="mb-0">{{ $product->stock_quantity ?? 0 }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="product-info-item">
                                            <h6 class="fw-semibold text-muted mb-1">Low Stock Threshold</h6>
                                            <p class="mb-0">{{ $product->low_stock_threshold ?? 0 }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="product-info-item">
                                            <h6 class="fw-semibold text-muted mb-1">Inventory Tracking</h6>
                                            <p class="mb-0">{!! $product->inventory_tracking
                                                ? '<span class="badge bg-success rounded-pill">Enabled</span>'
                                                : '<span class="badge bg-danger rounded-pill">Disabled</span>' !!}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="product-info-item">
                                            <h6 class="fw-semibold text-muted mb-1">Allow Backorders</h6>
                                            <p class="mb-0">{!! $product->allow_backorders
                                                ? '<span class="badge bg-success rounded-pill">Yes</span>'
                                                : '<span class="badge bg-danger rounded-pill">No</span>' !!}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Stock Level</h5>
                            </div>
                            <div class="card-body">
                                @if ($product->inventory_tracking)
                                    <div class="stock-gauge-container mb-4">
                                        <div id="stock-gauge" class="stock-gauge"></div>
                                    </div>

                                    @php
                                        $stockPercentage = 0;
                                        $stockStatus = 'danger';

                                        if ($product->stock_quantity > 0) {
                                            $stockPercentage = min(
                                                ($product->stock_quantity / max($product->low_stock_threshold * 3, 1)) *
                                                    100,
                                                100,
                                            );

                                            if ($stockPercentage > 66) {
                                                $stockStatus = 'success';
                                            } elseif ($stockPercentage > 33) {
                                                $stockStatus = 'warning';
                                            }
                                        }
                                    @endphp

                                    <div class="progress progress-animated mb-3" style="height: 12px;">
                                        <div class="progress-bar bg-{{ $stockStatus }}" role="progressbar"
                                            style="width: {{ $stockPercentage }}%"
                                            aria-valuenow="{{ $stockPercentage }}" aria-valuemin="0"
                                            aria-valuemax="100"></div>
                                    </div>

                                    <div class="d-flex justify-content-between">
                                        <span class="text-danger">Low Stock ({{ $product->low_stock_threshold }})</span>
                                        <span class="text-success">Optimal Stock</span>
                                    </div>
                                @else
                                    <div class="alert alert-info">
                                        <i class="ti ti-info-circle me-2"></i>
                                        Inventory tracking is disabled for this product.
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="card shadow-sm mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Inventory History</h5>
                            </div>
                            <div class="card-body">
                                <div id="inventory-history-chart" style="height: 250px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Media Tab -->
            <div class="tab-pane fade" id="media" role="tabpanel" aria-labelledby="media-tab">
                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Product Gallery</h5>
                            </div>
                            <div class="card-body">
                                <div class="product-gallery">
                                    <div class="zoom-container main-image-container mb-3">
                                        <img id="main-product-image"
                                            src="{{ asset('storage/' . ($product->main_image ?? 'placeholder.jpg')) }}"
                                            alt="{{ $product->alt_text ?? $product->product_name }}"
                                            class="img-fluid main-product-image zoom-image rounded">
                                    </div>
                                    <div class="thumbnail-gallery">
                                        <div class="swiper-container">
                                            <div class="swiper-wrapper">
                                                <div class="swiper-slide">
                                                    <img src="{{ asset('storage/' . ($product->main_image ?? 'placeholder.jpg')) }}"
                                                        alt="{{ $product->alt_text ?? $product->product_name }}"
                                                        class="img-fluid thumbnail-image rounded active"
                                                        data-src="{{ asset('storage/' . ($product->main_image ?? 'placeholder.jpg')) }}">
                                                </div>
                                                @if (!empty($product->gallery_images))
                                                    @foreach ($product->gallery_images as $image)
                                                        <div class="swiper-slide">
                                                            <img src="{{ asset('storage/' . $image->image_path) }}"
                                                                alt="{{ $image->alt_text ?? $product->product_name }}"
                                                                class="img-fluid thumbnail-image rounded"
                                                                data-src="{{ asset('storage/' . $image->image_path) }}">
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                            <div class="swiper-button-next"></div>
                                            <div class="swiper-button-prev"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card shadow-sm mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Product Videos</h5>
                            </div>
                            <div class="card-body">
                                @if (!empty($product->video_urls))
                                    @php
                                        $videoUrls = is_array($product->video_urls)
                                            ? $product->video_urls
                                            : json_decode($product->video_urls, true);
                                    @endphp

                                    @if (!empty($videoUrls) && is_array($videoUrls))
                                        <div class="row g-4">
                                            @foreach ($videoUrls as $video_url)
                                                <div class="col-md-6">
                                                    <div class="video-wrapper">
                                                        @php
                                                            $videoId = '';
                                                            if (strpos($video_url, 'youtube.com') !== false) {
                                                                parse_str(
                                                                    parse_url($video_url, PHP_URL_QUERY),
                                                                    $params,
                                                                );
                                                                $videoId = isset($params['v']) ? $params['v'] : '';
                                                            } elseif (strpos($video_url, 'youtu.be') !== false) {
                                                                $videoId = substr(
                                                                    parse_url($video_url, PHP_URL_PATH),
                                                                    1,
                                                                );
                                                            }
                                                        @endphp

                                                        @if ($videoId)
                                                            <div class="ratio ratio-16x9 rounded overflow-hidden">
                                                                <iframe
                                                                    src="https://www.youtube.com/embed/{{ $videoId }}"
                                                                    title="YouTube video" allowfullscreen
                                                                    loading="lazy"></iframe>
                                                            </div>
                                                        @else
                                                            <div class="alert alert-warning">
                                                                <i class="ti ti-alert-triangle me-2"></i>
                                                                Invalid video URL: {{ e($video_url) }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="alert alert-info">
                                            <i class="ti ti-info-circle me-2"></i>
                                            No videos have been added to this product.
                                        </div>
                                    @endif
                                @else
                                    <div class="alert alert-info">
                                        <i class="ti ti-info-circle me-2"></i>
                                        No videos have been added to this product.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <!-- 3D Model viewer if available -->
                        @if (isset($product->model_3d_url))
                            <div class="card shadow-sm mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">3D Model</h5>
                                </div>
                                <div class="card-body">
                                    <div class="model-viewer-container rounded overflow-hidden">
                                        <model-viewer src="{{ asset('storage/' . $product->model_3d_url) }}"
                                            alt="{{ $product->product_name }} 3D Model" auto-rotate camera-controls
                                            style="width: 100%; height: 300px;"></model-viewer>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Documents -->
                        <div class="card shadow-sm mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Documents</h5>
                            </div>
                            <div class="card-body">
                                <ul class="document-list">
                                    @if (isset($product->documents) && count($product->documents) > 0)
                                        @foreach ($product->documents as $document)
                                            <li class="document-item">
                                                @php
                                                    $extension = pathinfo($document->file_path, PATHINFO_EXTENSION);
                                                    $iconClass = 'ti ti-file';

                                                    if (in_array($extension, ['pdf'])) {
                                                        $iconClass = 'ti ti-file-text';
                                                    } elseif (in_array($extension, ['doc', 'docx'])) {
                                                        $iconClass = 'ti ti-file-text';
                                                    } elseif (in_array($extension, ['xls', 'xlsx'])) {
                                                        $iconClass = 'ti ti-file-spreadsheet';
                                                    }
                                                @endphp

                                                <i class="{{ $iconClass }} fs-4 me-2"></i>
                                                <div class="document-info">
                                                    <span class="document-name">{{ $document->original_name }}</span>
                                                    <small
                                                        class="text-muted d-block">{{ formatFileSize($document->file_size) }}</small>
                                                </div>
                                                <a href="{{ asset('storage/' . $document->file_path) }}"
                                                    class="btn btn-sm btn-primary rounded-circle" download>
                                                    <i class="ti ti-download"></i>
                                                </a>
                                            </li>
                                        @endforeach
                                    @else
                                        <li class="text-muted">No documents available</li>
                                    @endif
                                </ul>
                            </div>
                        </div>

                        <!-- Share product -->
                        <div class="card shadow-sm mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Share Product</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-3">
                                    <button class="btn btn-outline-primary share-btn" data-platform="facebook">
                                        <i class="ti ti-brand-facebook"></i>
                                    </button>
                                    <button class="btn btn-outline-info share-btn" data-platform="twitter">
                                        <i class="ti ti-brand-twitter"></i>
                                    </button>
                                    <button class="btn btn-outline-danger share-btn" data-platform="pinterest">
                                        <i class="ti ti-brand-pinterest"></i>
                                    </button>
                                    <button class="btn btn-outline-success share-btn" data-platform="whatsapp">
                                        <i class="ti ti-brand-whatsapp"></i>
                                    </button>
                                </div>

                                <div class="input-group">
                                    <input type="text" class="form-control" id="share-url"
                                        value="{{ route('products.show', $product->id) }}" readonly>
                                    <button class="btn btn-outline-primary" type="button" id="copy-share-url">
                                        <i class="ti ti-copy"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Variants Tab -->
            <div class="tab-pane fade" id="variants" role="tabpanel" aria-labelledby="variants-tab">
                <div class="row g-4">
                    <div class="col-lg-12">
                        @if ($product->has_variants)
                            <div class="card shadow-sm mb-4">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0">Product Variants</h5>
                                    <button class="btn btn-sm btn-primary">
                                        <i class="ti ti-plus me-1"></i> Add Variant
                                    </button>
                                </div>
                                <div class="card-body">
                                    @php
                                        $variantAttributes = is_array($product->variant_attributes)
                                            ? $product->variant_attributes
                                            : json_decode($product->variant_attributes, true);
                                        if (!is_array($variantAttributes)) {
                                            $variantAttributes = explode(',', $product->variant_attributes); // If it's CSV
                                        }
                                    @endphp

                                    @if (!empty($variantAttributes) && count($variantAttributes) > 0)
                                        <div class="mb-4">
                                            <h6 class="fw-semibold text-muted mb-2">Variant Attributes</h6>
                                            <div class="d-flex flex-wrap gap-2 mb-3">
                                                @foreach ($variantAttributes as $attribute)
                                                    <span
                                                        class="badge bg-primary-subtle text-primary rounded-pill">{{ ucfirst($attribute) }}</span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    @if (isset($product->variants) && count($product->variants) > 0)
                                        <div class="table-responsive">
                                            <table class="table table-hover align-middle">
                                                <thead class="table-light">
                                                    <tr>
                                                        @if (!empty($variantAttributes) && is_array($variantAttributes))
                                                            @foreach ($variantAttributes as $attribute)
                                                                <th>{{ ucfirst($attribute) }}</th>
                                                            @endforeach
                                                        @endif
                                                        <th>SKU</th>
                                                        <th>Price</th>
                                                        <th>Stock</th>
                                                        <th>Image</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($product->variants as $variant)
                                                        @php
                                                            $variant = (object) $variant; // Ensure $variant is an object
                                                        @endphp
                                                        <tr>
                                                            @if (!empty($variantAttributes) && is_array($variantAttributes))
                                                                @foreach ($variantAttributes as $attribute)
                                                                    <td>{{ $variant->attributes[$attribute] ?? 'N/A' }}
                                                                    </td>
                                                                @endforeach
                                                            @endif
                                                            <td><span
                                                                    class="font-monospace">{{ $variant->sku ?? 'N/A' }}</span>
                                                            </td>
                                                            <td>{{ $product->currency }}
                                                                {{ number_format($variant->price ?? 0, 2) }}</td>
                                                            <td>
                                                                @if ($product->inventory_tracking)
                                                                    <span
                                                                        class="badge bg-{{ ($variant->stock ?? 0) > 0 ? 'success' : 'danger' }} rounded-pill">
                                                                        {{ $variant->stock ?? 0 }}
                                                                    </span>
                                                                @else
                                                                    <span
                                                                        class="badge bg-secondary rounded-pill">N/A</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if (!empty($variant->image))
                                                                    <img src="{{ asset('storage/' . $variant->image) }}"
                                                                        alt="{{ $variant->sku ?? 'Variant' }}"
                                                                        class="variant-thumbnail rounded" width="40"
                                                                        height="40">
                                                                @else
                                                                    <span class="text-muted">No image</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <div class="dropdown">
                                                                    <button
                                                                        class="btn btn-sm btn-icon btn-outline-secondary dropdown-toggle hide-arrow"
                                                                        type="button" data-bs-toggle="dropdown">
                                                                        <i class="ti ti-dots-vertical"></i>
                                                                    </button>
                                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                                        <li><a class="dropdown-item" href="#"><i
                                                                                    class="ti ti-eye me-2"></i> View</a>
                                                                        </li>
                                                                        <li><a class="dropdown-item" href="#"><i
                                                                                    class="ti ti-pencil me-2"></i> Edit</a>
                                                                        </li>
                                                                        <li>
                                                                            <hr class="dropdown-divider">
                                                                        </li>
                                                                        <li><a class="dropdown-item text-danger"
                                                                                href="#"><i
                                                                                    class="ti ti-trash me-2"></i>
                                                                                Delete</a></li>
                                                                    </ul>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="alert alert-info">
                                            <i class="ti ti-info-circle me-2"></i>
                                            No variants have been created yet.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="ti ti-info-circle me-2"></i>
                                This product does not have variants. You can enable variants by editing the product.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Shipping Tab -->
            <div class="tab-pane fade" id="shipping" role="tabpanel" aria-labelledby="shipping-tab">
                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Shipping Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="shipping-info-card">
                                            <div class="d-flex align-items-center mb-3">
                                                <i class="ti ti-weight fs-3 me-3 text-primary"></i>
                                                <div>
                                                    <h6 class="fw-semibold text-muted mb-1">Weight</h6>
                                                    <p class="fs-5 mb-0">{{ $product->weight ?? 0 }}
                                                        {{ $product->weight_unit ?? 'kg' }}</p>
                                                </div>
                                            </div>

                                            @if (isset($product->dimensions))
                                                <div class="d-flex align-items-center mb-3">
                                                    <i class="ti ti-box fs-3 me-3 text-primary"></i>
                                                    <div>
                                                        <h6 class="fw-semibold text-muted mb-1">Dimensions (L × W × H)</h6>
                                                        <p class="fs-5 mb-0">
                                                            {{ $product->dimensions['length'] ?? ($product->length ?? '0') }}
                                                            ×
                                                            {{ $product->dimensions['width'] ?? ($product->width ?? '0') }} ×
                                                            {{ $product->dimensions['height'] ?? ($product->height ?? '0') }}
                                                            {{ $product->dimensions['unit'] ?? ($product->dimension_unit ?? 'cm') }}
                                                        </p>
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="d-flex align-items-center mb-3">
                                                <i class="ti ti-package fs-3 me-3 text-primary"></i>
                                                <div>
                                                    <h6 class="fw-semibold text-muted mb-1">Shipping Class</h6>
                                                    <p class="mb-0">
                                                        {{ ucfirst($product->shipping_class ?? 'Standard') }}</p>
                                                </div>
                                            </div>

                                            @if (isset($product->additional_shipping_fee) && $product->additional_shipping_fee > 0)
                                                <div class="d-flex align-items-center mb-3">
                                                    <i class="ti ti-cash fs-3 me-3 text-primary"></i>
                                                    <div>
                                                        <h6 class="fw-semibold text-muted mb-1">Additional Shipping Fee
                                                        </h6>
                                                        <p class="mb-0">{{ $product->currency ?? '$' }}
                                                            {{ number_format($product->additional_shipping_fee, 2) }}</p>
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="d-flex align-items-center">
                                                <i class="ti ti-truck fs-3 me-3 text-primary"></i>
                                                <div>
                                                    <h6 class="fw-semibold text-muted mb-1">Free Shipping</h6>
                                                    <p class="mb-0">{!! $product->free_shipping
                                                        ? '<span class="badge bg-success rounded-pill">Yes</span>'
                                                        : '<span class="badge bg-danger rounded-pill">No</span>' !!}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="shipping-visualization">
                                            <div class="package-dimensions-container">
                                                <div class="package-3d-wrapper">
                                                    <div class="package-3d-box"
                                                        data-length="{{ $product->dimensions['length'] ?? ($product->length ?? 10) }}"
                                                        data-width="{{ $product->dimensions['width'] ?? ($product->width ?? 10) }}"
                                                        data-height="{{ $product->dimensions['height'] ?? ($product->height ?? 10) }}">
                                                        <div class="package-face package-front"></div>
                                                        <div class="package-face package-back"></div>
                                                        <div class="package-face package-top"></div>
                                                        <div class="package-face package-bottom"></div>
                                                        <div class="package-face package-left"></div>
                                                        <div class="package-face package-right"></div>
                                                    </div>
                                                </div>

                                                <div class="d-flex justify-content-around mt-3">
                                                    <span class="dimension-label">
                                                        L: {{ $product->dimensions['length'] ?? ($product->length ?? '0') }}
                                                        {{ $product->dimensions['unit'] ?? ($product->dimension_unit ?? 'cm') }}
                                                    </span>
                                                    <span class="dimension-label">
                                                        W: {{ $product->dimensions['width'] ?? ($product->width ?? '0') }}
                                                        {{ $product->dimensions['unit'] ?? ($product->dimension_unit ?? 'cm') }}
                                                    </span>
                                                    <span class="dimension-label">
                                                        H: {{ $product->dimensions['height'] ?? ($product->height ?? '0') }}
                                                        {{ $product->dimensions['unit'] ?? ($product->dimension_unit ?? 'cm') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Shipping Policies</h5>
                            </div>
                            <div class="card-body">
                                <div class="accordion" id="shippingPoliciesAccordion">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#shippingPolicy" aria-expanded="true"
                                                aria-controls="shippingPolicy">
                                                Shipping Policy
                                            </button>
                                        </h2>
                                        <div id="shippingPolicy" class="accordion-collapse collapse show"
                                            data-bs-parent="#shippingPoliciesAccordion">
                                            <div class="accordion-body">
                                                <p>{{ $product->shipping_policy ?? 'We ship to most countries worldwide. Shipping times may vary depending on your location and the shipping method selected. All orders are processed within 1-2 business days after payment confirmation.' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#internationalShipping"
                                                aria-expanded="false" aria-controls="internationalShipping">
                                                International Shipping
                                            </button>
                                        </h2>
                                        <div id="internationalShipping" class="accordion-collapse collapse"
                                            data-bs-parent="#shippingPoliciesAccordion">
                                            <div class="accordion-body">
                                                <p>{{ $product->international_shipping_policy ?? 'International orders may be subject to customs duties and taxes, which are the responsibility of the customer. International shipping typically takes 7-14 business days depending on the destination country.' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Meta Tab -->
            <div class="tab-pane fade" id="meta" role="tabpanel" aria-labelledby="meta-tab">
                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">SEO Information</h5>
                                <button class="btn btn-sm btn-outline-primary">
                                    <i class="ti ti-pencil me-1"></i> Edit SEO
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="mb-4">
                                    <h6 class="fw-semibold text-muted mb-2">Meta Title</h6>
                                    <p class="p-2 bg-body-tertiary rounded">
                                        {{ $product->meta_title ?? $product->product_name }}</p>
                                </div>
                                <div class="mb-4">
                                    <h6 class="fw-semibold text-muted mb-2">Meta Description</h6>
                                    <p class="p-2 bg-body-tertiary rounded">
                                        {{ $product->meta_description ?? $product->short_description }}</p>
                                </div>
                                <div class="mb-4">
                                    <h6 class="fw-semibold text-muted mb-2">Meta Keywords</h6>
                                    @php
                                        $metaKeywords = is_array($product->meta_keywords ?? [])
                                            ? $product->meta_keywords
                                            : (is_string($product->meta_keywords ?? '')
                                                ? explode(',', $product->meta_keywords)
                                                : []);
                                        // Extract the first element and decode it
                                        $metaKeywords = json_decode($metaKeywords[0], true);
                                    @endphp

                                    @if (!empty($metaKeywords) && is_array($metaKeywords) && count($metaKeywords) > 0)




                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach ($metaKeywords as $keyword)
                                                <span
                                                    class="badge bg-secondary-subtle text-secondary rounded-pill">{{ implode(', ', array_column($metaKeywords, 'value')) }}</span>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-muted">No meta keywords specified</p>
                                    @endif
                                </div>

                                <div class="mt-4">
                                    <h6 class="fw-semibold text-muted mb-2">SEO Preview</h6>
                                    <div class="meta-preview p-3 border rounded">
                                        <div class="meta-title">{{ $product->meta_title ?? $product->product_name }} |
                                            Your Store Name</div>
                                        <div class="meta-url">{{ route('products.show', $product->id) }}</div>
                                        <div class="meta-description mt-1">
                                            {{ $product->meta_description ?? $product->short_description }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card shadow-sm mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Open Graph Data</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="product-info-item">
                                            <h6 class="fw-semibold text-muted mb-1">OG Title</h6>
                                            <p class="mb-0">
                                                {{ $product->og_title ?? ($product->meta_title ?? $product->product_name) }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="product-info-item">
                                            <h6 class="fw-semibold text-muted mb-1">OG Type</h6>
                                            <p class="mb-0">{{ $product->og_type ?? 'product' }}</p>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="product-info-item">
                                            <h6 class="fw-semibold text-muted mb-1">OG Description</h6>
                                            <p class="mb-0">
                                                {{ $product->og_description ?? ($product->meta_description ?? $product->short_description) }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="product-info-item">
                                            <h6 class="fw-semibold text-muted mb-1">OG Image</h6>
                                            @if (!empty($product->og_image))
                                                <img src="{{ asset('storage/' . $product->og_image) }}"
                                                    alt="Open Graph Image" class="img-fluid rounded"
                                                    style="max-height: 200px;">
                                            @else
                                                <img src="{{ asset('storage/' . ($product->main_image ?? 'placeholder.jpg')) }}"
                                                    alt="Open Graph Image" class="img-fluid rounded"
                                                    style="max-height: 200px;">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Structured Data</h5>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info mb-3">
                                    <i class="ti ti-info-circle me-2"></i>
                                    Structured data helps search engines understand your content better.
                                </div>

                                <div class="mb-3">
                                    <h6 class="fw-semibold text-muted mb-2">Schema Type</h6>
                                    <p class="mb-0">Product</p>
                                </div>

                                <div class="mb-3">
                                    <h6 class="fw-semibold text-muted mb-2">JSON-LD Preview</h6>
                                    <pre class="p-2 bg-body-tertiary rounded" style="max-height: 200px; overflow: auto;"><code>{
  "@context": "https://schema.org/",
  "@type": "Product",
  "name": "{{ $product->product_name }}",
  "description": "{{ $product->short_description }}",
  "sku": "{{ $product->sku ?? '' }}",
  "brand": {
    "@type": "Brand",
    "name": "{{ $product->brand_name ?? 'Brand' }}"
  },
  "offers": {
    "@type": "Offer",
    "url": "{{ route('products.show', $product->id) }}",
    "priceCurrency": "{{ $product->currency ?? 'USD' }}",
    "price": "{{ $product->current_price ?? 0 }}",
    "availability": "https://schema.org/{{ $product->stock_quantity > 0 ? 'InStock' : 'OutOfStock' }}"
  }
}</code></pre>
                                </div>

                                <button class="btn btn-sm btn-primary">
                                    <i class="ti ti-code me-1"></i> Test Structured Data
                                </button>
                            </div>
                        </div>

                        <div class="card shadow-sm mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Canonical URL</h5>
                            </div>
                            <div class="card-body">
                                <div class="product-info-item">
                                    <h6 class="fw-semibold text-muted mb-1">Current Canonical URL</h6>
                                    <p class="mb-0 text-break">
                                        {{ $product->canonical_url ?? route('products.show', $product->id) }}</p>
                                </div>

                                <div class="mt-3">
                                    <button class="btn btn-sm btn-outline-primary">
                                        <i class="ti ti-pencil me-1"></i> Edit Canonical URL
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Advanced Tab -->
            <div class="tab-pane fade" id="advanced" role="tabpanel" aria-labelledby="advanced-tab">
                <div class="row g-4">
                    <div class="col-lg-6">
                        <!-- Custom Fields -->
                        @php
                            $customFields = is_array($product->custom_fields)
                                ? $product->custom_fields
                                : (is_string($product->custom_fields)
                                    ? json_decode($product->custom_fields, true)
                                    : []);
                        @endphp

                        @if (!empty($customFields) && is_array($customFields) && count($customFields) > 0)
                            <div class="card shadow-sm mb-4">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0">Custom Fields</h5>
                                    <button class="btn btn-sm btn-outline-primary">
                                        <i class="ti ti-plus me-1"></i> Add Field
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Field Name</th>
                                                    <th>Value</th>
                                                    <th>Visibility</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($customFields as $field)
                                                    <tr>
                                                        <td class="fw-medium">{{ $field['name'] ?? 'N/A' }}</td>
                                                        <td>{{ $field['value'] ?? 'N/A' }}</td>
                                                        <td>
                                                            @if (isset($field['visibility']) && $field['visibility'] == 'public')
                                                                <span class="badge bg-success rounded-pill">Public</span>
                                                            @else
                                                                <span class="badge bg-secondary rounded-pill">Admin
                                                                    Only</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Advanced Settings -->
                        <div class="card shadow-sm mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Advanced Settings</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <h6 class="fw-semibold text-muted mb-2">Purchase Note</h6>
                                    <p class="p-2 bg-body-tertiary rounded">
                                        {{ $product->purchase_note ?? 'No purchase note specified' }}</p>
                                </div>

                                <div class="mb-3">
                                    <h6 class="fw-semibold text-muted mb-2">Menu Order</h6>
                                    <p class="mb-0">{{ $product->menu_order ?? '0' }}</p>
                                </div>

                                <div class="mb-3">
                                    <h6 class="fw-semibold text-muted mb-2">Reviews</h6>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch"
                                            id="reviewsEnabled" {{ $product->enable_reviews ? 'checked' : '' }} disabled>
                                        <label class="form-check-label" for="reviewsEnabled">
                                            {{ $product->enable_reviews ? 'Reviews Enabled' : 'Reviews Disabled' }}
                                        </label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <h6 class="fw-semibold text-muted mb-2">Featured Product</h6>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch"
                                            id="featuredProduct" {{ $product->is_featured ? 'checked' : '' }} disabled>
                                        <label class="form-check-label" for="featuredProduct">
                                            {{ $product->is_featured ? 'Featured' : 'Not Featured' }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <!-- Related Products -->
                        <div class="card shadow-sm mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">Related Products</h5>
                                <button class="btn btn-sm btn-outline-primary">
                                    <i class="ti ti-plus me-1"></i> Add Related Product
                                </button>
                            </div>
                            <div class="card-body">
                                @if (isset($product->related_products) && count($product->related_products) > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th style="width: 60px;">Image</th>
                                                    <th>Product</th>
                                                    <th>Price</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($product->related_products as $related)
                                                    <tr>
                                                        <td>
                                                            <img src="{{ asset('storage/' . $related->thumbnail_image) }}"
                                                                alt="{{ $related->product_name }}" class="img-thumbnail"
                                                                width="50" height="50">
                                                        </td>
                                                        <td>{{ $related->product_name }}</td>
                                                        <td>{{ $product->currency }}
                                                            {{ number_format($related->current_price, 2) }}</td>
                                                        <td>
                                                            <div class="dropdown">
                                                                <button
                                                                    class="btn btn-sm btn-icon btn-outline-secondary dropdown-toggle hide-arrow"
                                                                    type="button" data-bs-toggle="dropdown">
                                                                    <i class="ti ti-dots-vertical"></i>
                                                                </button>
                                                                <ul class="dropdown-menu dropdown-menu-end">
                                                                    <li><a class="dropdown-item"
                                                                            href="{{ route('products.show', $related->id) }}"><i
                                                                                class="ti ti-eye me-2"></i> View</a></li>
                                                                    <li><a class="dropdown-item" href="#"><i
                                                                                class="ti ti-x me-2"></i> Remove</a></li>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="alert alert-info">
                                        <i class="ti ti-info-circle me-2"></i>
                                        No related products have been added yet.
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Cross-Sells -->
                        <div class="card shadow-sm mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">Cross-Sell Products</h5>
                                <button class="btn btn-sm btn-outline-primary">
                                    <i class="ti ti-plus me-1"></i> Add Cross-Sell
                                </button>
                            </div>
                            <div class="card-body">
                                @if (isset($product->cross_sells) && count($product->cross_sells) > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th style="width: 60px;">Image</th>
                                                    <th>Product</th>
                                                    <th>Price</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($product->cross_sells as $cross_sell)
                                                    <tr>
                                                        <td>
                                                            <img src="{{ asset('storage/' . $cross_sell->thumbnail_image) }}"
                                                                alt="{{ $cross_sell->product_name }}"
                                                                class="img-thumbnail" width="50" height="50">
                                                        </td>
                                                        <td>{{ $cross_sell->product_name }}</td>
                                                        <td>{{ $product->currency }}
                                                            {{ number_format($cross_sell->current_price, 2) }}</td>
                                                        <td>
                                                            <div class="dropdown">
                                                                <button
                                                                    class="btn btn-sm btn-icon btn-outline-secondary dropdown-toggle hide-arrow"
                                                                    type="button" data-bs-toggle="dropdown">
                                                                    <i class="ti ti-dots-vertical"></i>
                                                                </button>
                                                                <ul class="dropdown-menu dropdown-menu-end">
                                                                    <li><a class="dropdown-item"
                                                                            href="{{ route('products.show', $cross_sell->id) }}"><i
                                                                                class="ti ti-eye me-2"></i> View</a></li>
                                                                    <li><a class="dropdown-item" href="#"><i
                                                                                class="ti ti-x me-2"></i> Remove</a></li>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="alert alert-info">
                                        <i class="ti ti-info-circle me-2"></i>
                                        No cross-sell products have been added yet.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Digital Tab (Conditional) -->
            @if ($product->product_type == 'digital' || $product->is_digital == 1)
                <div class="tab-pane fade" id="digital" role="tabpanel" aria-labelledby="digital-tab">
                    <div class="row g-4">
                        <div class="col-lg-8">
                            <div class="card shadow-sm mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Digital Product Files</h5>
                                </div>
                                <div class="card-body">
                                    @if (isset($product->digital_files) && count($product->digital_files) > 0)
                                        <div class="table-responsive">
                                            <table class="table table-hover align-middle">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>File Name</th>
                                                        <th>File Type</th>
                                                        <th>File Size</th>
                                                        <th>Download Limit</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($product->digital_files as $file)
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    @php
                                                                        $extension = pathinfo(
                                                                            $file->original_name,
                                                                            PATHINFO_EXTENSION,
                                                                        );
                                                                        $iconClass = 'ti ti-file';

                                                                        if (in_array($extension, ['pdf'])) {
                                                                            $iconClass = 'ti ti-file-text';
                                                                        } elseif (
                                                                            in_array($extension, ['doc', 'docx'])
                                                                        ) {
                                                                            $iconClass = 'ti ti-file-text';
                                                                        } elseif (
                                                                            in_array($extension, ['xls', 'xlsx'])
                                                                        ) {
                                                                            $iconClass = 'ti ti-file-spreadsheet';
                                                                        } elseif (
                                                                            in_array($extension, [
                                                                                'jpg',
                                                                                'jpeg',
                                                                                'png',
                                                                                'gif',
                                                                            ])
                                                                        ) {
                                                                            $iconClass = 'ti ti-photo';
                                                                        } elseif (
                                                                            in_array($extension, ['mp3', 'wav'])
                                                                        ) {
                                                                            $iconClass = 'ti ti-music';
                                                                        } elseif (
                                                                            in_array($extension, ['mp4', 'avi', 'mov'])
                                                                        ) {
                                                                            $iconClass = 'ti ti-video';
                                                                        } elseif (
                                                                            in_array($extension, ['zip', 'rar'])
                                                                        ) {
                                                                            $iconClass = 'ti ti-archive';
                                                                        }
                                                                    @endphp
                                                                    <i
                                                                        class="{{ $iconClass }} fs-4 me-2 text-primary"></i>
                                                                    <span>{{ $file->original_name }}</span>
                                                                </div>
                                                            </td>
                                                            <td>{{ strtoupper($extension) }}</td>
                                                            <td>{{ formatFileSize($file->file_size) }}</td>
                                                            <td>
                                                                @if ($file->download_limit > 0)
                                                                    {{ $file->download_limit }} downloads
                                                                @else
                                                                    <span
                                                                        class="badge bg-success rounded-pill">Unlimited</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <div class="dropdown">
                                                                    <button
                                                                        class="btn btn-sm btn-icon btn-outline-secondary dropdown-toggle hide-arrow"
                                                                        type="button" data-bs-toggle="dropdown">
                                                                        <i class="ti ti-dots-vertical"></i>
                                                                    </button>
                                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                                        <li><a class="dropdown-item" href="#"><i
                                                                                    class="ti ti-download me-2"></i>
                                                                                Download</a></li>
                                                                        <li><a class="dropdown-item" href="#"><i
                                                                                    class="ti ti-pencil me-2"></i> Edit</a>
                                                                        </li>
                                                                        <li>
                                                                            <hr class="dropdown-divider">
                                                                        </li>
                                                                        <li><a class="dropdown-item text-danger"
                                                                                href="#"><i
                                                                                    class="ti ti-trash me-2"></i>
                                                                                Delete</a></li>
                                                                    </ul>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="alert alert-info">
                                            <i class="ti ti-info-circle me-2"></i>
                                            No digital files have been uploaded yet.
                                        </div>
                                    @endif

                                    <div class="mt-4">
                                        <button class="btn btn-primary">
                                            <i class="ti ti-upload me-1"></i> Upload New File
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="card shadow-sm mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Download Settings</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <div class="product-info-item">
                                                <h6 class="fw-semibold text-muted mb-1">Download Expiry</h6>
                                                <p class="mb-0">
                                                    @if ($product->download_expiry > 0)
                                                        {{ $product->download_expiry }} days after purchase
                                                    @else
                                                        <span class="badge bg-success rounded-pill">Never Expires</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="product-info-item">
                                                <h6 class="fw-semibold text-muted mb-1">Download Limit</h6>
                                                <p class="mb-0">
                                                    @if ($product->download_limit > 0)
                                                        {{ $product->download_limit }} downloads per customer
                                                    @else
                                                        <span class="badge bg-success rounded-pill">Unlimited</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Subscription Tab (Conditional) -->
            @if ($product->product_type == 'subscription' || $product->is_subscription == 1)
                <div class="tab-pane fade" id="subscription" role="tabpanel" aria-labelledby="subscription-tab">
                    <div class="row g-4">
                        <div class="col-lg-8">
                            <div class="card shadow-sm mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Subscription Details</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <div class="product-info-item">
                                                <h6 class="fw-semibold text-muted mb-1">Billing Period</h6>
                                                <p class="mb-0 fs-5">{{ ucfirst($product->billing_period ?? 'monthly') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="product-info-item">
                                                <h6 class="fw-semibold text-muted mb-1">Billing Interval</h6>
                                                <p class="mb-0 fs-5">{{ $product->billing_interval ?? '1' }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="product-info-item">
                                                <h6 class="fw-semibold text-muted mb-1">Trial Period</h6>
                                                <p class="mb-0">
                                                    @if (isset($product->trial_period) && $product->trial_period > 0)
                                                        {{ $product->trial_period }} days
                                                    @else
                                                        <span class="badge bg-secondary rounded-pill">No Trial</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="product-info-item">
                                                <h6 class="fw-semibold text-muted mb-1">Sign-up Fee</h6>
                                                <p class="mb-0">
                                                    @if (isset($product->signup_fee) && $product->signup_fee > 0)
                                                        {{ $product->currency }}
                                                        {{ number_format($product->signup_fee, 2) }}
                                                    @else
                                                        <span class="badge bg-success rounded-pill">No Fee</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="card shadow-sm mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Subscription Statistics</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-4">
                                        <div class="col-6">
                                            <div class="stat-card text-center">
                                                <div class="stat-icon mb-2">
                                                    <i class="ti ti-users fs-3 text-primary"></i>
                                                </div>
                                                <div class="stat-value fs-4 fw-semibold">
                                                    {{ $product->active_subscribers ?? 0 }}</div>
                                                <div class="stat-label text-muted">Active Subscribers</div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="stat-card text-center">
                                                <div class="stat-icon mb-2">
                                                    <i class="ti ti-chart-line fs-3 text-success"></i>
                                                </div>
                                                <div class="stat-value fs-4 fw-semibold">
                                                    {{ $product->monthly_recurring_revenue ?? 0 }}</div>
                                                <div class="stat-label text-muted">Monthly Revenue</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Floating Action Button -->
    <div class="d-flex justify-content-between mt-5 mb-4">
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                    <i class="ti ti-arrow-left me-1"></i> Back to Products
                </a>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary" id="print-product">
                        <i class="ti ti-printer me-1"></i> Print
                    </button>
                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary">
                        <i class="ti ti-pencil me-1"></i> Edit Product
                    </a>
                </div>
@endsection

@section('page-script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Load required libraries
            function loadScript(url, callback) {
                const script = document.createElement('script');
                script.type = 'text/javascript';
                script.src = url;
                script.onload = callback;
                script.onerror = function() {
                    console.warn('Failed to load script: ' + url);
                    if (callback) callback();
                };
                document.head.appendChild(script);
            }

            // Load QR Code library
            loadScript('https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js', function() {
                console.log("QRCode library loaded successfully.");

                const qrCodeContainer = document.getElementById('product-qrcode');
                if (qrCodeContainer) {
                    try {
                        if (typeof QRCode === 'undefined') {
                            throw new Error('QRCode library not loaded properly');
                        }

                        new QRCode(qrCodeContainer, {
                            text: window.location.href,
                            width: 128,
                            height: 128
                        });

                    } catch (error) {
                        console.error('QR Code generation failed:', error);
                        qrCodeContainer.innerHTML =
                            '<div class="alert alert-warning">QR Code generation failed. Please refresh the page to try again.</div>';
                    }
                }
            });


            // Add a fallback method in case the CDN fails
            setTimeout(function() {
                const qrCodeContainer = document.getElementById('product-qrcode');
                if (qrCodeContainer && !qrCodeContainer.querySelector('canvas') && !qrCodeContainer
                    .querySelector('.alert')) {
                    // If after 3 seconds there's no canvas or alert, the library probably failed to load
                    qrCodeContainer.innerHTML =
                        '<div class="alert alert-warning">QR Code could not be generated. Please try again later.</div>';
                }
            }, 3000);

            // Load JsBarcode library
            loadScript('https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js', function() {
                const barcodeElement = document.getElementById('barcode-display');
                if (barcodeElement && typeof JsBarcode !== 'undefined' && '{{ $product->barcode }}') {
                    try {
                        JsBarcode(barcodeElement, '{{ $product->barcode }}', {
                            format: 'CODE128',
                            lineColor: '#000',
                            width: 2,
                            height: 50,
                            displayValue: true,
                            elementTag: 'svg'
                        });
                    } catch (error) {
                        console.error('Barcode generation failed:', error);
                        if (barcodeElement.parentNode) {
                            barcodeElement.parentNode.innerHTML =
                                '<div class="alert alert-warning">Barcode generation failed</div>';
                        }
                    }
                }
            });

            // Load Swiper library
            loadScript('https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js', function() {
                if (typeof Swiper !== 'undefined') {
                    const swiper = new Swiper('.swiper-container', {
                        slidesPerView: 4,
                        spaceBetween: 10,
                        navigation: {
                            nextEl: '.swiper-button-next',
                            prevEl: '.swiper-button-prev',
                        },
                        breakpoints: {
                            320: {
                                slidesPerView: 2,
                            },
                            480: {
                                slidesPerView: 3,
                            },
                            768: {
                                slidesPerView: 4,
                            }
                        }
                    });

                    // Thumbnail click event
                    document.querySelectorAll('.thumbnail-image').forEach(thumb => {
                        thumb.addEventListener('click', function() {
                            const mainImage = document.getElementById('main-product-image');
                            if (mainImage) {
                                mainImage.src = this.getAttribute('data-src') || this.src;

                                // Remove active class from all thumbnails
                                document.querySelectorAll('.thumbnail-image').forEach(t => {
                                    t.classList.remove('active');
                                });

                                // Add active class to clicked thumbnail
                                this.classList.add('active');
                            }
                        });
                    });
                }
            });

            // Load ApexCharts library
            loadScript('https://cdn.jsdelivr.net/npm/apexcharts@3.35.0/dist/apexcharts.min.js', function() {
                if (typeof ApexCharts !== 'undefined') {
                    // Price History Chart
                    const priceHistoryElement = document.getElementById('price-history-chart');
                    if (priceHistoryElement) {
                        const priceHistoryOptions = {
                            series: [{
                                name: 'Price',
                                data: [
                                    [1641024000000, {{ $product->base_price ?? 0 }}],
                                    [1643702400000,
                                        {{ ($product->base_price ?? 0) * 0.9 }}
                                    ],
                                    [1646121600000,
                                        {{ ($product->base_price ?? 0) * 0.95 }}
                                    ],
                                    [1648800000000,
                                        {{ ($product->base_price ?? 0) * 1.05 }}
                                    ],
                                    [1651392000000,
                                        {{ ($product->base_price ?? 0) * 1.1 }}
                                    ],
                                    [1654070400000, {{ $product->current_price ?? 0 }}]
                                ]
                            }],
                            chart: {
                                type: 'area',
                                height: 250,
                                toolbar: {
                                    show: false
                                },
                                fontFamily: 'inherit'
                            },
                            dataLabels: {
                                enabled: false
                            },
                            stroke: {
                                curve: 'smooth',
                                width: 2
                            },
                            xaxis: {
                                type: 'datetime'
                            },
                            yaxis: {
                                labels: {
                                    formatter: function(val) {
                                        return '{{ $product->currency ?? '$' }} ' + val.toFixed(
                                        2);
                                    }
                                }
                            },
                            tooltip: {
                                x: {
                                    format: 'dd MMM yyyy'
                                },
                                y: {
                                    formatter: function(val) {
                                        return '{{ $product->currency ?? '$' }} ' + val.toFixed(
                                        2);
                                    }
                                }
                            },
                            fill: {
                                type: 'gradient',
                                gradient: {
                                    shadeIntensity: 1,
                                    opacityFrom: 0.7,
                                    opacityTo: 0.2,
                                    stops: [0, 90, 100]
                                }
                            },
                            colors: ['#696cff']
                        };

                        try {
                            const priceHistoryChart = new ApexCharts(priceHistoryElement,
                                priceHistoryOptions);
                            priceHistoryChart.render();
                        } catch (error) {
                            console.error('Price history chart rendering failed:', error);
                            priceHistoryElement.innerHTML =
                                '<div class="alert alert-warning">Chart rendering failed</div>';
                        }
                    }

                    // Inventory History Chart
                    const inventoryHistoryElement = document.getElementById('inventory-history-chart');
                    if (inventoryHistoryElement) {
                        const inventoryHistoryOptions = {
                            series: [{
                                name: 'Stock Level',
                                data: [30, 40, 35, 50, 49, 60, 70, 91, 125,
                                    {{ $product->stock_quantity ?? 0 }}
                                ]
                            }],
                            chart: {
                                type: 'line',
                                height: 250,
                                toolbar: {
                                    show: false
                                },
                                fontFamily: 'inherit'
                            },
                            dataLabels: {
                                enabled: false
                            },
                            stroke: {
                                curve: 'straight',
                                width: 3
                            },
                            grid: {
                                row: {
                                    colors: ['#f3f3f3', 'transparent'],
                                    opacity: 0.5
                                },
                            },
                            xaxis: {
                                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug',
                                    'Sep', 'Oct'
                                ],
                            },
                            colors: ['#03c3ec']
                        };

                        try {
                            const inventoryHistoryChart = new ApexCharts(inventoryHistoryElement,
                                inventoryHistoryOptions);
                            inventoryHistoryChart.render();
                        } catch (error) {
                            console.error('Inventory history chart rendering failed:', error);
                            inventoryHistoryElement.innerHTML =
                                '<div class="alert alert-warning">Chart rendering failed</div>';
                        }
                    }
                } else {
                    document.querySelectorAll('[id$="-chart"]').forEach(chartElement => {
                        chartElement.innerHTML =
                            '<div class="alert alert-info">Chart visualization unavailable</div>';
                    });
                }
            });

            // 3D Package visualization
            const packageBox = document.querySelector('.package-3d-box');
            if (packageBox) {
                const length = parseInt(packageBox.getAttribute('data-length') || 10);
                const width = parseInt(packageBox.getAttribute('data-width') || 10);
                const height = parseInt(packageBox.getAttribute('data-height') || 10);

                // Scale dimensions for visualization
                const maxDimension = Math.max(length, width, height);
                const scale = 100 / maxDimension;

                packageBox.style.width = `${width * scale}px`;
                packageBox.style.height = `${height * scale}px`;
                packageBox.style.transform = `translateZ(-${length * scale / 2}px) rotateX(15deg) rotateY(30deg)`;

                document.querySelectorAll('.package-face').forEach(face => {
                    if (face.classList.contains('package-front') || face.classList.contains(
                        'package-back')) {
                        face.style.width = `${width * scale}px`;
                        face.style.height = `${height * scale}px`;
                    }

                    if (face.classList.contains('package-top') || face.classList.contains(
                        'package-bottom')) {
                        face.style.width = `${width * scale}px`;
                        face.style.height = `${length * scale}px`;
                    }

                    if (face.classList.contains('package-left') || face.classList.contains(
                        'package-right')) {
                        face.style.width = `${length * scale}px`;
                        face.style.height = `${height * scale}px`;
                    }

                    if (face.classList.contains('package-back')) {
                        face.style.transform = `rotateY(180deg) translateZ(${length * scale / 2}px)`;
                    }

                    if (face.classList.contains('package-front')) {
                        face.style.transform = `translateZ(${length * scale / 2}px)`;
                    }

                    if (face.classList.contains('package-top')) {
                        face.style.transform = `rotateX(90deg) translateZ(${height * scale / 2}px)`;
                    }

                    if (face.classList.contains('package-bottom')) {
                        face.style.transform = `rotateX(-90deg) translateZ(${height * scale / 2}px)`;
                    }

                    if (face.classList.contains('package-left')) {
                        face.style.transform = `rotateY(-90deg) translateZ(${width * scale / 2}px)`;
                    }

                    if (face.classList.contains('package-right')) {
                        face.style.transform = `rotateY(90deg) translateZ(${width * scale / 2}px)`;
                    }
                });

                // Add rotation on mouse move
                const packageWrapper = document.querySelector('.package-3d-wrapper');
                if (packageWrapper) {
                    packageWrapper.addEventListener('mousemove', function(e) {
                        const xRotation = (e.clientY - packageWrapper.getBoundingClientRect().top) /
                            packageWrapper.clientHeight * 30 - 15;
                        const yRotation = (e.clientX - packageWrapper.getBoundingClientRect().left) /
                            packageWrapper.clientWidth * 30 - 15;

                        packageBox.style.transform =
                            `translateZ(-${length * scale / 2}px) rotateX(${15 - xRotation}deg) rotateY(${30 + yRotation}deg)`;
                    }, {
                        passive: true
                    });

                    // Add touch support for mobile devices
                    packageWrapper.addEventListener('touchmove', function(e) {
                        if (e.touches.length > 0) {
                            const touch = e.touches[0];
                            const xRotation = (touch.clientY - packageWrapper.getBoundingClientRect().top) /
                                packageWrapper.clientHeight * 30 - 15;
                            const yRotation = (touch.clientX - packageWrapper.getBoundingClientRect()
                                .left) / packageWrapper.clientWidth * 30 - 15;

                            packageBox.style.transform =
                                `translateZ(-${length * scale / 2}px) rotateX(${15 - xRotation}deg) rotateY(${30 + yRotation}deg)`;
                            e.preventDefault();
                        }
                    }, {
                        passive: false
                    });
                }
            }

            // Share buttons
            document.querySelectorAll('.share-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const platform = this.getAttribute('data-platform');
                    const url = encodeURIComponent(window.location.href);
                    const title = encodeURIComponent('{{ $product->product_name }}');
                    let shareUrl = '';

                    switch (platform) {
                        case 'facebook':
                            shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${url}`;
                            break;
                        case 'twitter':
                            shareUrl = `https://twitter.com/intent/tweet?url=${url}&text=${title}`;
                            break;
                        case 'pinterest':
                            shareUrl =
                                `https://pinterest.com/pin/create/button/?url=${url}&description=${title}`;
                            break;
                        case 'whatsapp':
                            shareUrl = `https://wa.me/?text=${title}%20${url}`;
                            break;
                    }

                    if (shareUrl) {
                        window.open(shareUrl, '_blank', 'width=600,height=400');
                    }
                });
            });

            // Copy share URL
            const copyShareUrlBtn = document.getElementById('copy-share-url');
            if (copyShareUrlBtn) {
                copyShareUrlBtn.addEventListener('click', function() {
                    const shareUrl = document.getElementById('share-url');
                    if (shareUrl) {
                        shareUrl.select();
                        try {
                            document.execCommand('copy');
                            this.innerHTML = '<i class="ti ti-check"></i>';
                            setTimeout(() => {
                                this.innerHTML = '<i class="ti ti-copy"></i>';
                            }, 2000);
                        } catch (err) {
                            console.error('Failed to copy URL: ', err);
                            alert('Failed to copy URL. Please try again.');
                        }
                    }
                });
            }

            // Download QR Code
            const downloadQrCodeBtn = document.getElementById('download-qrcode');
            if (downloadQrCodeBtn) {
                downloadQrCodeBtn.addEventListener('click', function() {
                    const qrCanvas = document.querySelector('#product-qrcode canvas');
                    if (qrCanvas) {
                        try {
                            const link = document.createElement('a');
                            link.download =
                                '{{ \Illuminate\Support\Str::slug($product->product_name) }}-qrcode.png';
                            link.href = qrCanvas.toDataURL('image/png');
                            document.body.appendChild(link);
                            link.click();
                            document.body.removeChild(link);
                        } catch (error) {
                            console.error('Failed to download QR code:', error);
                            alert('Failed to download QR code. Please try again later.');
                        }
                    } else {
                        alert('QR code is not available for download.');
                    }
                });
            }

            // Image error handling
            document.querySelectorAll('img').forEach(img => {
                img.addEventListener('error', function() {
                    this.src = '/placeholder.svg?height=300&width=300';
                    this.alt = 'Image not available';
                });
            });

            // Initialize tooltips
            if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
                const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            }

            // Initialize popovers
            if (typeof bootstrap !== 'undefined' && bootstrap.Popover) {
                const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
                popoverTriggerList.map(function(popoverTriggerEl) {
                    return new bootstrap.Popover(popoverTriggerEl);
                });
            }
        });

        // Helper function to format file size
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }
    </script>
@endsection
