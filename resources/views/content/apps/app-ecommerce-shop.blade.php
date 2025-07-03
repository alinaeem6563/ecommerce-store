@extends('layouts/blankLayout')

@section('title', 'Shop')

@section('page-style')
    @vite('resources/assets/vendor/scss/pages/shop.scss')
@endsection

@section('content')
@include('layouts.sections.navbar.navbar-front')
<div class="container-fluid shop-page ">
  <div class="row">
    <!-- Sidebar Filters -->
    <div class="col-lg-3">
      <div class="filter-sidebar" id="filterSidebar">
        <div class="filter-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Filters</h5>
          <button type="button" class="btn-close d-lg-none" id="closeSidebar" aria-label="Close"></button>
        </div>
        
        <div class="filter-body">
          <!-- Categories Filter -->
          <div class="filter-section">
            <h6 class="filter-title">Categories</h6>
            <div class="filter-options">
              @foreach($categories as $category)
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="category" value="{{ $category->name }}" id="category-{{ $category->id }}">
                <label class="form-check-label" for="category-{{ $category->id }}">
                  {{ $category->name }}
                </label>
              </div>
              @endforeach
            </div>
          </div>
          
          <!-- Price Range Filter -->
          <div class="filter-section">
            <h6 class="filter-title">Price Range</h6>
            <div class="price-range">
              <div class="range-slider">
                <input type="range" class="form-range" min="0" max="1000" step="10" value="0" id="priceMin">
                <input type="range" class="form-range" min="0" max="1000" step="10" value="1000" id="priceMax">
              </div>
              <div class="price-inputs d-flex align-items-center mt-2">
                <div class="input-group input-group-sm">
                  <span class="input-group-text">$</span>
                  <input type="number" class="form-control" id="minPrice" value="0" min="0">
                </div>
                <span class="mx-2">to</span>
                <div class="input-group input-group-sm">
                  <span class="input-group-text">$</span>
                  <input type="number" class="form-control" id="maxPrice" value="1000" min="0">
                </div>
              </div>
            </div>
          </div>
          
          <!-- Vendors Filter -->
          <div class="filter-section">
            <h6 class="filter-title">Vendors</h6>
            <div class="filter-options">
              @foreach($vendors as $vendor)
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="vendor" value="{{ $vendor->name }}" id="vendor-{{ $vendor->id }}">
                <label class="form-check-label" for="vendor-{{ $vendor->id }}">
                  {{ $vendor->name }}
                </label>
              </div>
              @endforeach
            </div>
          </div>
          
          <!-- Ratings Filter -->
          <div class="filter-section">
            <h6 class="filter-title">Customer Ratings</h6>
            <div class="filter-options">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="rating" value="4" id="rating-4">
                <label class="form-check-label" for="rating-4">
                  <div class="stars">
                    <i class="ti ti-star"></i>
                    <i class="ti ti-star"></i>
                    <i class="ti ti-star"></i>
                    <i class="ti ti-star"></i>
                    <i class="ti ti-star star-empty"></i>
                  </div>
                  <span>& Up</span>
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="rating" value="3" id="rating-3">
                <label class="form-check-label" for="rating-3">
                  <div class="stars">
                    <i class="ti ti-star"></i>
                    <i class="ti ti-star"></i>
                    <i class="ti ti-star"></i>
                    <i class="ti ti-star star-empty"></i>
                    <i class="ti ti-star star-empty"></i>
                  </div>
                  <span>& Up</span>
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="rating" value="2" id="rating-2">
                <label class="form-check-label" for="rating-2">
                  <div class="stars">
                    <i class="ti ti-star"></i>
                    <i class="ti ti-star"></i>
                    <i class="ti ti-star star-empty"></i>
                    <i class="ti ti-star star-empty"></i>
                    <i class="ti ti-star star-empty"></i>
                  </div>
                  <span>& Up</span>
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="rating" value="1" id="rating-1">
                <label class="form-check-label" for="rating-1">
                  <div class="stars">
                    <i class="ti ti-star"></i>
                    <i class="ti ti-star star-empty"></i>
                    <i class="ti ti-star star-empty"></i>
                    <i class="ti ti-star star-empty"></i>
                    <i class="ti ti-star star-empty"></i>
                  </div>
                  <span>& Up</span>
                </label>
              </div>
            </div>
          </div>
          
          <!-- Filter Actions -->
          <div class="filter-actions mt-4">
            <button type="button" class="btn btn-primary w-100 mb-2" id="applyFilters">
              Apply Filters
            </button>
            <button type="button" class="btn btn-outline-secondary w-100" id="clearFilters">
              Clear All
            </button>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Products Content -->
    <div class="col-lg-9">
      <!-- Shop Header -->
      <div class="shop-header">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
          <div class="d-flex align-items-center mb-2 mb-md-0">
            <button type="button" class="btn btn-outline-secondary d-lg-none me-2" id="showFilters">
              <i class="ti ti-filter me-1"></i> Filters
            </button>
            <div class="view-options">
              <button type="button" class="btn btn-icon active" id="gridViewBtn" title="Grid View">
                <i class="ti ti-layout-grid"></i>
              </button>
              <button type="button" class="btn btn-icon" id="listViewBtn" title="List View">
                <i class="ti ti-layout-list"></i>
              </button>
            </div>
            <div class="results-count ms-3">
              <span>Showing <strong>{{ count($products) }}</strong> products</span>
            </div>
          </div>
          
          <div class="d-flex align-items-center">
            <div class="input-group shop-search me-2">
              <span class="input-group-text"><i class="ti ti-search"></i></span>
              <input type="text" class="form-control" id="searchInput" placeholder="Search products...">
            </div>
            
            <div class="dropdown">
              <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                Sort By
              </button>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="sortDropdown">
                <li><a class="dropdown-item sort-option" href="#" data-sort="featured">Featured</a></li>
                <li><a class="dropdown-item sort-option" href="#" data-sort="price-asc">Price: Low to High</a></li>
                <li><a class="dropdown-item sort-option" href="#" data-sort="price-desc">Price: High to Low</a></li>
                <li><a class="dropdown-item sort-option" href="#" data-sort="rating">Customer Rating</a></li>
                <li><a class="dropdown-item sort-option" href="#" data-sort="newest">Newest Arrivals</a></li>
              </ul>
            </div>
          </div>
        </div>
        
        <!-- Active Filters -->
        <div class="active-filters mb-3" id="activeFiltersContainer"></div>
      </div>
      
      <!-- Products Grid View -->
      <div class="products-grid view-active" id="productsGrid">
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4" id="gridContainer">
          <!-- Products will be rendered here by JS -->
        </div>
      </div>
      
      <!-- Products List View -->
      <div class="products-list" id="productsList">
        <div class="row" id="listContainer">
          <!-- Products will be rendered here by JS -->
        </div>
      </div>
      
      <!-- Pagination -->
      <div class="shop-footer mt-4">
        <div class="pagination-container">
          <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
              <li class="page-item disabled">
                <a class="page-link" href="#" aria-label="Previous">
                  <i class="ti ti-chevron-left"></i>
                </a>
              </li>
              <li class="page-item active"><a class="page-link" href="#">1</a></li>
              <li class="page-item"><a class="page-link" href="#">2</a></li>
              <li class="page-item"><a class="page-link" href="#">3</a></li>
              <li class="page-item">
                <a class="page-link" href="#" aria-label="Next">
                  <i class="ti ti-chevron-right"></i>
                </a>
              </li>
            </ul>
          </nav>
        </div>
        
        <div class="load-more-container text-center d-none">
          <button class="btn btn-primary" id="loadMoreBtn">Load More</button>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('page-script')
<script>
  // Pass PHP data to JavaScript
  const products = @json($products);
</script>
@vite('resources/assets/js/shop.js')
@endsection
