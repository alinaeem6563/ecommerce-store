@extends('layouts/layoutMaster')

@section('title', 'Products')

@section('vendor-style')
    @vite(['resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss'])
@endsection
@section('page-style')
    @vite('resources/assets/vendor/scss/pages/product-list.scss')
@endsection

@section('content')
    <div class="container-fluid product-list-wrapper">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-primary text-white shadow-sm">
                    <div class="card-body py-4">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <div>
                                <h2 class="fw-semibold mb-1 text-white">Products</h2>
                                <p class="mb-0">Manage your product catalog</p>
                            </div>
                            <div class="d-flex gap-2 mt-3 mt-md-0">
                                <a href="{{ route('products.create') }}" class="btn btn-white">
                                    <i class="ti ti-plus me-1"></i> Add New Product
                                </a>
                                <div class="dropdown">
                                    <button class="btn btn-white dropdown-toggle" type="button" id="bulkActionsDropdown"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="ti ti-settings me-1"></i> Bulk Actions
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="bulkActionsDropdown">
                                        <li><a class="dropdown-item" href="#" id="bulkActivate"><i
                                                    class="ti ti-check me-1"></i> Activate Selected</a></li>
                                        <li><a class="dropdown-item" href="#" id="bulkDeactivate"><i
                                                    class="ti ti-x me-1"></i> Deactivate Selected</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item text-danger" href="#" id="bulkDelete"><i
                                                    class="ti ti-trash me-1"></i> Delete Selected</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters and Stats -->
        <div class="row mb-4">
            <!-- Stats Cards -->
            <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                <div class="row g-3">
                    <div class="col-6 col-md-3">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="avatar avatar-sm me-2 bg-label-primary">
                                        <i class="ti ti-box text-primary"></i>
                                    </div>
                                    <span class="text-muted">Total</span>
                                </div>
                                <h4 class="mb-1 fw-semibold">{{ count($products) ?? 0 }}</h4>
                                <div class="progress progress-sm mb-1">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: 100%"
                                        aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <small class="text-muted">All products</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="avatar avatar-sm me-2 bg-label-success">
                                        <i class="ti ti-check text-success"></i>
                                    </div>
                                    <span class="text-muted">Active</span>
                                </div>
                                <h4 class="mb-1 fw-semibold">{{ $products->where('status', 'active')->count() }}</h4>
                                <div class="progress progress-sm mb-1">
                                    <div class="progress-bar bg-success" role="progressbar"
                                        style="width: {{ (($products->where('status', 'active')->count() ?? 0) / max(count($products) ?? 1, 1)) * 100 }}%"
                                        aria-valuenow="{{ (($products->where('status', 'active')->count() ?? 0) / max(count($products) ?? 1, 1)) * 100 }}"
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <small class="text-muted">Published products</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="avatar avatar-sm me-2  bg-label-warning">
                                        <i class="ti ti-alert-triangle text-warning"></i>
                                    </div>
                                    <span class="text-muted">Low Stock</span>
                                </div>
                                <h4 class="mb-1 fw-semibold">
                                    {{ $products->where('stock_quantity', 'low_stock_threshold')->count() ?? 0 }}</h4>
                                <div class="progress progress-sm mb-1">
                                    <div class="progress-bar bg-warning" role="progressbar"
                                        style="width: {{ (($lowStockProducts ?? 0) / max($totalProducts ?? 1, 1)) * 100 }}%"
                                        aria-valuenow="{{ (($lowStockProducts ?? 0) / max($totalProducts ?? 1, 1)) * 100 }}"
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <small class="text-muted">Need attention</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="avatar avatar-sm me-2 bg-label-danger">
                                        <i class="ti ti-x text-danger"></i>
                                    </div>
                                    <span class="text-muted">Out of Stock</span>
                                </div>
                                <h4 class="mb-1 fw-semibold">
                                    {{ $products->where('stock_status', 'out_of_stock')->count() ?? 0 }}</h4>
                                <div class="progress progress-sm mb-1">
                                    <div class="progress-bar bg-danger" role="progressbar"
                                        style="width: {{ (($products->where('stock_status', 'out_of_stock')->count() ?? 0) / max(count($products) ?? 1, 1)) * 100 }}%"
                                        aria-valuenow="{{ (($products->where('stock_status', 'out_of_stock')->count() ?? 0) / max(count($products) ?? 1, 1)) * 100 }}"
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <small class="text-muted">Need restocking</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="col-12 col-xl-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <form id="product-filters" class="row g-3">
                            <div class="col-md-6">
                                <label for="filter-category" class="form-label">Category</label>
                                <select class="form-select form-select-sm" id="filter-category">
                                    <option value="">All Categories</option>
                                    @foreach ($categories ?? [] as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="filter-status" class="form-label">Status</label>
                                <select class="form-select form-select-sm" id="filter-status">
                                    <option value="">All Statuses</option>
                                    <option value="active">Active</option>
                                    <option value="draft">Draft</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="archived">Archived</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="filter-stock" class="form-label">Stock</label>
                                <select class="form-select form-select-sm" id="filter-stock">
                                    <option value="">All Stock Statuses</option>
                                    <option value="in_stock">In Stock</option>
                                    <option value="low_stock">Low Stock</option>
                                    <option value="out_of_stock">Out of Stock</option>
                                    <option value="on_backorder">On Backorder</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="filter-type" class="form-label">Type</label>
                                <select class="form-select form-select-sm" id="filter-type">
                                    <option value="">All Types</option>
                                    <option value="physical">Physical</option>
                                    <option value="digital">Digital</option>
                                    <option value="service">Service</option>
                                    <option value="subscription">Subscription</option>
                                    <option value="bundle">Bundle</option>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products Table -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                        <h5 class="card-title mb-0">Product List</h5>
                        <div class="d-flex align-items-center gap-2">
                            <div class="input-group input-group-merge">
                                <span class="input-group-text" id="search-icon"><i class="ti ti-search"></i></span>
                                <input type="text" class="form-control" id="product-search"
                                    placeholder="Search products..." aria-label="Search products"
                                    aria-describedby="search-icon">
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary btn-icon dropdown-toggle hide-arrow"
                                    type="button" id="columnVisibilityDropdown" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="ti ti-columns"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end column-visibility"
                                    aria-labelledby="columnVisibilityDropdown">
                                    <li>
                                        <h6 class="dropdown-header">Toggle Columns</h6>
                                    </li>
                                    <li><label class="dropdown-item"><input type="checkbox" class="form-check-input me-2"
                                                checked data-column="1"> Image</label></li>
                                    <li><label class="dropdown-item"><input type="checkbox" class="form-check-input me-2"
                                                checked data-column="2"> Name</label></li>
                                    <li><label class="dropdown-item"><input type="checkbox" class="form-check-input me-2"
                                                checked data-column="3"> SKU</label></li>
                                    <li><label class="dropdown-item"><input type="checkbox" class="form-check-input me-2"
                                                checked data-column="4"> Category</label></li>
                                    <li><label class="dropdown-item"><input type="checkbox" class="form-check-input me-2"
                                                checked data-column="5"> Price</label></li>
                                    <li><label class="dropdown-item"><input type="checkbox" class="form-check-input me-2"
                                                checked data-column="6"> Stock</label></li>
                                    <li><label class="dropdown-item"><input type="checkbox" class="form-check-input me-2"
                                                checked data-column="7"> Status</label></li>
                                    <li><label class="dropdown-item"><input type="checkbox" class="form-check-input me-2"
                                                checked data-column="8"> Actions</label></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="products-table" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>
                                            <div class="form-check">
                                                @foreach ($products as $product)
                                                    
                                                @endforeach
                                                <input class="form-check-input" type="checkbox" id="select-all" value="{{ $product->id }}">
                                                <label class="form-check-label" for="select-all"></label>
                                            </div>
                                        </th>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>SKU</th>
                                        <th>Category</th>
                                        <th>Price</th>
                                        <th>Stock</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products ?? [] as $product)
                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input product-checkbox" type="checkbox"
                                                        value="{{ $product->id }}" id="product-{{ $product->id }}">
                                                    <label class="form-check-label"
                                                        for="product-{{ $product->id }}"></label>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="product-img-wrapper">
                                                    <img src="{{ asset('storage/' . ($product->main_image ?? 'placeholder.jpg')) }}"
                                                        alt="{{ $product->product_name }}" class="product-img">
                                                    @if ($product->has_variants)
                                                        <span
                                                            class="badge bg-info position-absolute top-0 end-0 rounded-circle"
                                                            title="Has variants">
                                                            <i class="ti ti-layers"></i>
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <a href="{{ route('shop.products.show', $product->id) }}"
                                                        class="product-name fw-semibold text-truncate">{{ $product->product_name }}</a>
                                                    <small class="text-muted">{{ $product->product_type }}</small>
                                                    @if (!empty($product->badges))
                                                        <div class="product-badges mt-1">
                                                            @foreach (json_decode($product->badges) as $badge)
                                                                @php
                                                                    $badgeClass = match (strtolower($badge)) {
                                                                        'new' => 'bg-primary',
                                                                        'sale' => 'bg-danger',
                                                                        'hot' => 'bg-warning text-dark',
                                                                        'featured' => 'bg-success',
                                                                        default => 'bg-secondary',
                                                                    };
                                                                @endphp
                                                                <span
                                                                    class="badge {{ $badgeClass }} badge-sm">{{ $badge }}</span>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>{{ $product->sku }}</td>
                                            <td>{{ optional($product->category)->name ?? 'N/A' }}</td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <span
                                                        class="fw-semibold">${{ number_format($product->current_price, 2) }}</span>
                                                    @if ($product->base_price > $product->current_price)
                                                        <small
                                                            class="text-decoration-line-through text-muted">${{ number_format($product->base_price, 2) }}</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                @php
                                                    $stockStatusClass = match ($product->stock_status) {
                                                        'in_stock' => 'bg-label-success',
                                                        'out_of_stock' => 'bg-label-danger',
                                                        'on_backorder' => 'bg-label-warning text-dark',
                                                        default => 'bg-secondary',
                                                    };

                                                    $stockStatusText = match ($product->stock_status) {
                                                        'in_stock' => 'In Stock',
                                                        'out_of_stock' => 'Out of Stock',
                                                        'on_backorder' => 'On Backorder',
                                                        default => 'Unknown',
                                                    };
                                                @endphp
                                                <div class="d-flex flex-column">
                                                    <span
                                                        class="badge {{ $stockStatusClass }}">{{ $stockStatusText }}</span>
                                                    @if ($product->inventory_tracking && $product->stock_status !== 'out_of_stock')
                                                        <small
                                                            class="text-muted text-center mt-1">{{ $product->stock_quantity }}
                                                            units</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                @php
                                                    $statusClass = match ($product->status) {
                                                        'active' => 'bg-label-success',
                                                        'draft' => 'bg-label-secondary',
                                                        'inactive' => 'bg-label-warning text-dark',
                                                        'archived' => 'bg-label-danger',
                                                        default => 'bg-label-secondary',
                                                    };

                                                    $statusText = match ($product->status) {
                                                        'active' => 'Active',
                                                        'draft' => 'Draft',
                                                        'inactive' => 'Inactive',
                                                        'archived' => 'Archived',
                                                        default => 'Unknown',
                                                    };
                                                @endphp
                                                <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button"
                                                        class="btn btn-sm dropdown-toggle hide-arrow py-0"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="ti ti-dots-vertical"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item"
                                                            href="{{ route('products.show', $product->id) }}">
                                                            <i class="ti ti-eye me-1"></i> View
                                                        </a>
                                                        <a class="dropdown-item"
                                                            href="{{ route('products.edit', $product->id) }}">
                                                            <i class="ti ti-edit me-1"></i> Edit
                                                        </a>
                                                        <a class="dropdown-item"
                                                            href="{{ route('product.duplicate', $product->id) }}">
                                                            <i class="ti ti-copy me-1"></i> Duplicate
                                                        </a>
                                                        <div class="dropdown-divider"></div>
                                                        @if ($product->status !== 'active')
                                                            <a class="dropdown-item text-success product-activate"
                                                                href="#" data-id="{{ $product->id }}">
                                                                <i class="ti ti-check me-1"></i> Activate
                                                            </a>
                                                        @else
                                                            <a class="dropdown-item text-warning product-deactivate"
                                                                href="#" data-id="{{ $product->id }}">
                                                                <i class="ti ti-x me-1"></i> Deactivate
                                                            </a>
                                                        @endif

                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item text-danger product-delete" href=""
                                                            data-id="{{ $product->id }}">
                                                            <i class="ti ti-trash me-1"></i> Delete
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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
                        <form id="deleteForm" action="" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('vendor-script')
    @vite(['resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.js'])
@endsection

@section('page-script')
    @vite('resources/assets/js/product-list.js')
@endsection
{{-- <script>
$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(".product-activate").click(function (e) {
        e.preventDefault();
        let productId = $(this).data("id");

        $.ajax({
            url: "/product/activate/" + productId,
            type: "POST",
            success: function (response) {
                if (response.success) {
                    alert(response.message);
                    location.reload();
                } else {
                    alert("Error: " + response.message);
                }
            },
            error: function (xhr) {
                alert("Request failed: " + xhr.responseText);
            }
        });
    });

    $(".product-deactivate").click(function (e) {
        e.preventDefault();
        let productId = $(this).data("id");

        $.ajax({
            url: "/product/deactivate/" + productId,
            type: "POST",
            success: function (response) {
                if (response.success) {
                    alert(response.message);
                    location.reload();
                } else {
                    alert("Error: " + response.message);
                }
            },
            error: function (xhr) {
                alert("Request failed: " + xhr.responseText);
            }
        });
    });
});
</script>
<script>
    $(document).ready(function () {
        function getSelectedProductIds() {
            let selected = [];
            $('.product-checkbox:checked').each(function () {
                selected.push($(this).val());
            });
            return selected;
        }

        function performBulkAction(route) {
            let selectedIds = getSelectedProductIds();
            if (selectedIds.length === 0) {
                alert('Please select at least one product.');
                return;
            }

            $.ajax({
                url: route,
                type: "POST",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    ids: selectedIds
                },
                success: function (response) {
                    alert(response.message);
                    location.reload();
                },
                error: function (xhr) {
                    alert('Something went wrong! Check console.');
                    console.log(xhr.responseText);
                }
            });
        }

        $('#bulkActivate').click(function () {
            performBulkAction("{{ route('product.bulkActivate') }}");
        });

        $('#bulkDeactivate').click(function () {
            performBulkAction("{{ route('product.bulkDeactivate') }}");
        });

        $('#bulkDelete').click(function () {
            if (confirm("Are you sure you want to delete selected products?")) {
                performBulkAction("{{ route('product.bulkDelete') }}");
            }
        });
    });
</script> --}}
