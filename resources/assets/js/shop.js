document.addEventListener('DOMContentLoaded', () => {
  // DOM Elements
  const gridContainer = document.getElementById('gridContainer');
  const listContainer = document.getElementById('listContainer');
  const productsGrid = document.getElementById('productsGrid');
  const productsList = document.getElementById('productsList');
  const gridViewBtn = document.getElementById('gridViewBtn');
  const listViewBtn = document.getElementById('listViewBtn');
  const showFiltersBtn = document.getElementById('showFilters');
  const closeSidebarBtn = document.getElementById('closeSidebar');
  const filterSidebar = document.getElementById('filterSidebar');
  const searchInput = document.getElementById('searchInput');
  const priceMinSlider = document.getElementById('priceMin');
  const priceMaxSlider = document.getElementById('priceMax');
  const minPriceInput = document.getElementById('minPrice');
  const maxPriceInput = document.getElementById('maxPrice');
  const loadMoreBtn = document.getElementById('loadMoreBtn');
  const paginationContainer = document.getElementById('pagination');
  const applyFiltersBtn = document.getElementById('applyFilters');
  const clearFiltersBtn = document.getElementById('clearFilters');
  const activeFiltersContainer = document.getElementById('activeFiltersContainer');
  const sortOptions = document.querySelectorAll('.sort-option');
  const sortDropdown = document.getElementById('sortDropdown');

  // Current view state
  let currentView = 'grid'; // 'grid' or 'list'
  let currentSort = 'featured'; // Default sort
  let activeFilters = {
    categories: [],
    priceRange: { min: 0, max: 1000 },
    vendors: [],
    ratings: []
  };


  // Create sidebar overlay
  const sidebarOverlay = document.createElement('div');
  sidebarOverlay.className = 'sidebar-overlay';
  document.body.appendChild(sidebarOverlay);

  // Toggle sidebar on mobile
  showFiltersBtn.addEventListener('click', () => {
    filterSidebar.classList.add('show');
    sidebarOverlay.classList.add('show');
    document.body.style.overflow = 'hidden';
  });

  closeSidebarBtn.addEventListener('click', () => {
    filterSidebar.classList.remove('show');
    sidebarOverlay.classList.remove('show');
    document.body.style.overflow = '';
  });

  sidebarOverlay.addEventListener('click', () => {
    filterSidebar.classList.remove('show');
    sidebarOverlay.classList.remove('show');
    document.body.style.overflow = '';
  });

  // View toggle handlers
  gridViewBtn.addEventListener('click', () => {
    if (currentView !== 'grid') {
      setView('grid');
    }
  });

  listViewBtn.addEventListener('click', () => {
    if (currentView !== 'list') {
      setView('list');
    }
  });

  function setView(view) {
    currentView = view;

    // Update active button state
    if (view === 'grid') {
      gridViewBtn.classList.add('active');
      listViewBtn.classList.remove('active');
      productsGrid.classList.add('view-active');
      productsList.classList.remove('view-active');
    } else {
      gridViewBtn.classList.remove('active');
      listViewBtn.classList.add('active');
      productsGrid.classList.remove('view-active');
      productsList.classList.add('view-active');
    }

    // Render products in the selected view
    applyFiltersAndSort();
  }

  // Price range slider functionality
  priceMinSlider.addEventListener('input', () => {
    const minVal = Number.parseInt(priceMinSlider.value);
    const maxVal = Number.parseInt(priceMaxSlider.value);

    if (minVal > maxVal) {
      priceMinSlider.value = maxVal;
      minPriceInput.value = maxVal;
    } else {
      minPriceInput.value = minVal;
    }
  });

  priceMaxSlider.addEventListener('input', () => {
    const minVal = Number.parseInt(priceMinSlider.value);
    const maxVal = Number.parseInt(priceMaxSlider.value);

    if (maxVal < minVal) {
      priceMaxSlider.value = minVal;
      maxPriceInput.value = minVal;
    } else {
      maxPriceInput.value = maxVal;
    }
  });

  minPriceInput.addEventListener('change', () => {
    const minVal = Number.parseInt(minPriceInput.value);
    const maxVal = Number.parseInt(maxPriceInput.value);

    if (minVal > maxVal) {
      minPriceInput.value = maxVal;
      priceMinSlider.value = maxVal;
    } else {
      priceMinSlider.value = minVal;
    }
  });

  maxPriceInput.addEventListener('change', () => {
    const minVal = Number.parseInt(minPriceInput.value);
    const maxVal = Number.parseInt(maxPriceInput.value);

    if (maxVal < minVal) {
      maxPriceInput.value = minVal;
      priceMaxSlider.value = minVal;
    } else {
      priceMaxSlider.value = maxVal;
    }
  });

  // Search functionality
  searchInput.addEventListener('input', () => {
    applyFiltersAndSort();
  });

  // Apply filters button
  applyFiltersBtn.addEventListener('click', () => {
    // Get selected categories
    activeFilters.categories = [];
    document.querySelectorAll('input[name="category"]:checked').forEach(checkbox => {
      activeFilters.categories.push(checkbox.value);
    });

    // Get price range
    activeFilters.priceRange.min = Number.parseInt(minPriceInput.value);
    activeFilters.priceRange.max = Number.parseInt(maxPriceInput.value);

    // Get selected vendors
    activeFilters.vendors = [];
    document.querySelectorAll('input[name="vendor"]:checked').forEach(checkbox => {
      activeFilters.vendors.push(checkbox.value);
    });

    // Get selected ratings
    activeFilters.ratings = [];
    document.querySelectorAll('input[name="rating"]:checked').forEach(checkbox => {
      activeFilters.ratings.push(Number.parseInt(checkbox.value));
    });

    // Update UI and apply filters
    updateActiveFiltersUI();
    applyFiltersAndSort();

    // Close sidebar on mobile
    if (window.innerWidth < 992) {
      filterSidebar.classList.remove('show');
      sidebarOverlay.classList.remove('show');
      document.body.style.overflow = '';
    }
  });

  // Clear filters button
  clearFiltersBtn.addEventListener('click', () => {
    // Reset checkboxes
    document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
      checkbox.checked = false;
    });

    // Reset price range
    priceMinSlider.value = 0;
    priceMaxSlider.value = 1000;
    minPriceInput.value = 0;
    maxPriceInput.value = 1000;

    // Reset active filters
    activeFilters = {
      categories: [],
      priceRange: { min: 0, max: 1000 },
      vendors: [],
      ratings: []
    };

    // Update UI and apply filters
    updateActiveFiltersUI();
    applyFiltersAndSort();
  });

  // Sort options
  sortOptions.forEach(option => {
    option.addEventListener('click', function (e) {
      e.preventDefault();
      currentSort = this.dataset.sort;
      sortDropdown.textContent = this.textContent;
      applyFiltersAndSort();
    });
  });

  // Update active filters UI
  function updateActiveFiltersUI() {
    activeFiltersContainer.innerHTML = '';

    // Add category filters
    activeFilters.categories.forEach(category => {
      addFilterTag('Category: ' + category, () => {
        // Remove this category from active filters
        activeFilters.categories = activeFilters.categories.filter(c => c !== category);
        // Uncheck the corresponding checkbox
        document.querySelector(`input[name="category"][value="${category}"]`).checked = false;
        updateActiveFiltersUI();
        applyFiltersAndSort();
      });
    });

    // Add price range filter if not default
    if (activeFilters.priceRange.min > 0 || activeFilters.priceRange.max < 1000) {
      addFilterTag(`Price: $${activeFilters.priceRange.min} - $${activeFilters.priceRange.max}`, () => {
        // Reset price range
        activeFilters.priceRange = { min: 0, max: 1000 };
        priceMinSlider.value = 0;
        priceMaxSlider.value = 1000;
        minPriceInput.value = 0;
        maxPriceInput.value = 1000;
        updateActiveFiltersUI();
        applyFiltersAndSort();
      });
    }

    // Add vendor filters
    activeFilters.vendors.forEach(vendor => {
      addFilterTag('Vendor: ' + vendor, () => {
        // Remove this vendor from active filters
        activeFilters.vendors = activeFilters.vendors.filter(v => v !== vendor);
        // Uncheck the corresponding checkbox
        document.querySelector(`input[name="vendor"][value="${vendor}"]`).checked = false;
        updateActiveFiltersUI();
        applyFiltersAndSort();
      });
    });

    // Add rating filters
    activeFilters.ratings.forEach(rating => {
      addFilterTag(`Rating: ${rating}★ & Up`, () => {
        // Remove this rating from active filters
        activeFilters.ratings = activeFilters.ratings.filter(r => r !== rating);
        // Uncheck the corresponding checkbox
        document.querySelector(`input[name="rating"][value="${rating}"]`).checked = false;
        updateActiveFiltersUI();
        applyFiltersAndSort();
      });
    });

    // Add sort filter if not default
    if (currentSort !== 'featured') {
      let sortText = '';
      switch (currentSort) {
        case 'price-asc':
          sortText = 'Price: Low to High';
          break;
        case 'price-desc':
          sortText = 'Price: High to Low';
          break;
        case 'rating':
          sortText = 'Customer Rating';
          break;
        case 'newest':
          sortText = 'Newest Arrivals';
          break;
      }

      addFilterTag(`Sort: ${sortText}`, () => {
        currentSort = 'featured';
        sortDropdown.textContent = 'Sort By';
        updateActiveFiltersUI();
        applyFiltersAndSort();
      });
    }
  }

  // Add filter tag to UI
  function addFilterTag(text, removeCallback) {
    const tag = document.createElement('span');
    tag.className = 'badge filter-tag';
    tag.innerHTML = `${text} <i class="ti ti-close"></i>`;
    tag.querySelector('.ti-close').addEventListener('click', removeCallback);
    activeFiltersContainer.appendChild(tag);
  }

  // Apply filters and sort
  function applyFiltersAndSort() {
    const searchTerm = searchInput.value.toLowerCase();

    // Filter products
    const filteredProducts = products.filter(product => {
      // Search filter
      const matchesSearch =
        searchTerm === '' ||
        product.product_name.toLowerCase().includes(searchTerm) ||
        product.category.toLowerCase().includes(searchTerm) ||
        (product.short_description && product.short_description.toLowerCase().includes(searchTerm));

      // Category filter
      const matchesCategory =
        activeFilters.categories.length === 0 || activeFilters.categories.includes(product.category);

      // Price filter
      const currentPrice = Number.parseFloat(product.current_price);
      const matchesPrice = currentPrice >= activeFilters.priceRange.min && currentPrice <= activeFilters.priceRange.max;

      // Vendor filter
      const matchesVendor =
        activeFilters.vendors.length === 0 || (product.vendor && activeFilters.vendors.includes(product.vendor));

      // Rating filter - assuming product has a rating property
      // If product doesn't have rating, we'll skip this filter
      const productRating = product.rating || 0;
      const matchesRating =
        activeFilters.ratings.length === 0 || activeFilters.ratings.some(rating => productRating >= rating);

      return matchesSearch && matchesCategory && matchesPrice && matchesVendor && matchesRating;
    });

    // Sort products
    switch (currentSort) {
      case 'price-asc':
        filteredProducts.sort((a, b) => Number.parseFloat(a.current_price) - Number.parseFloat(b.current_price));
        break;
      case 'price-desc':
        filteredProducts.sort((a, b) => Number.parseFloat(b.current_price) - Number.parseFloat(a.current_price));
        break;
      case 'rating':
        filteredProducts.sort((a, b) => (b.rating || 0) - (a.rating || 0));
        break;
      case 'newest':
        // Assuming products have a date property
        filteredProducts.sort((a, b) => new Date(b.date || 0) - new Date(a.date || 0));
        break;
      // Default is 'featured', no sorting needed
    }

    // Render the filtered and sorted products
    renderProducts(filteredProducts);
  }

  // Generate star rating HTML
  function generateStarRating(rating = 3) {
    // Default rating of 3 if not provided
    const fullStars = Math.floor(rating);
    const hasHalfStar = rating % 1 !== 0;
    let starsHtml = '';

    for (let i = 0; i < fullStars; i++) {
      starsHtml += '<i class="ti ti-star"></i> ';
    }

    if (hasHalfStar) {
      starsHtml += '<i class="ti ti-star-half"></i> ';
    }

    const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0);

    for (let i = 0; i < emptyStars; i++) {
      starsHtml += '<i class="ti ti-star star-empty"></i> ';
    }

    return starsHtml;
  }

  // Render products
  function renderProducts(productsToRender = products) {
    // Clear containers
    gridContainer.innerHTML = '';
    listContainer.innerHTML = '';

    if (productsToRender.length === 0) {
      // Show "No products found" message in both views
      const noProductsHtml = `
                <div class="col-12 text-center py-5">
                    <i class="ti ti-search" style="font-size: 3rem; color: var(--bs-gray-400);"></i>
                    <h3 class="mt-3">No products found</h3>
                    <p class="text-muted">Try adjusting your search or filter criteria</p>
                </div>
            `;
      gridContainer.innerHTML = noProductsHtml;
      listContainer.innerHTML = noProductsHtml;
      return;
    }

    // Render Grid View
    productsToRender.forEach(product => {
      const starsHtml = generateStarRating(product.rating || 3);

      const gridCard = document.createElement('div');
      gridCard.className = 'col';

      // Determine badge class based on badge text
      let badgeClass = 'bg-primary';
      if (product.badge === 'New') {
        badgeClass = 'badge-new';
      } else if (product.badge === 'Best Seller') {
        badgeClass = 'badge-bestseller';
      }

      gridCard.innerHTML = `
                <div class="product-card">
                    <div class="product-img">
                        <img src="${product.main_image}" alt="${product.product_name}">
                        ${product.badge ? `<div class="product-badge"><span class="badge ${badgeClass}">${product.badge}</span></div>` : ''}
                        <div class="product-actions">
                            <button class="btn rounded-circle" title="Add to Wishlist">
                                <i class="ti ti-heart"></i>
                            </button>
                            <button class="btn rounded-circle" title="Quick View" onclick="window.location.href='/shop/${product.id}'">
                                <i class="ti ti-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="product-body">
                        <div class="product-category">${product.category}</div>
                        <h3 class="product-title"><a href="/shop/${product.id}">${product.product_name}</a></h3>
                        <div class="product-rating">
                            ${starsHtml} <span class="rating-count">(3)</span>  
                        </div>

                        <div class="product-price">
                            <span class="current-price">$${product.current_price}</span>
                            ${product.price > product.current_price ? `<span class="old-price">$${product.price}</span>` : ''}
                            ${product.discount_value ? `<span class="discount">-${product.discount_value}${product.discount_type == 'fixed' ? '%' : '$'} OFF</span>` : ''}
                        </div>
                    </div>
                    <div class="product-footer">
    <form action="{{ route('cart.add') }}" method="POST">
        @csrf
        <input type="hidden" name="id" value="{{ $product->id }}">
        <button class="btn btn-primary">
            <i class="ti ti-shopping-cart"></i> Add to Cart
        </button>
    </form>
</div>

                </div>
            `;

      gridContainer.appendChild(gridCard);

      // Render List View
      const listItem = document.createElement('div');
      listItem.className = 'col-12 mb-4';

      listItem.innerHTML = `
                <div class="product-list-item">
                    <div class="row">
                        <div class="col-md-3 p-0">
                            <div class="product-img">
                                <img src="${product.main_image}" alt="${product.product_name}">
                                ${product.badge ? `<div class="product-badge"><span class="badge ${badgeClass}">${product.badge}</span></div>` : ''}
                            </div>
                        </div>
                        <div class="col-md-9 p-0">
                            <div class="product-content" >
                                <div class="product-category">${product.category}</div>
                                <h3 class="product-title"><a href="#">${product.product_name}</a></h3>
                                <div class="product-description">
                                    ${product.short_description || 'No description available.'}
                                </div>
                                <div class="product-rating">
                                    ${starsHtml} <span class="rating-count">(3)</span>
                                </div>
                                                       
                                <div class="product-price">
                                    <span class="current-price">$${product.current_price}</span>
                                    ${product.price > product.current_price ? `<span class="old-price">$${product.price}</span>` : ''}
                                    ${product.discount_value ? `<span class="discount">${product.discount_value}% OFF</span>` : ''}
                                </div>
                                <div class="product-actions">
                                    <button class="btn btn-primary">
                                        <i class="ti ti-shopping-cart"></i> Add to Cart
                                    </button>
                                    <button class="btn btn-icon" title="Add to Wishlist">
                                        <i class="ti ti-heart"></i>
                                    </button>
                                    <button class="btn btn-icon" title="Quick View" onclick="window.location.href='/shop/${product.id}'">
                                        <i class="ti ti-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

      listContainer.appendChild(listItem);
    });
  }

  // Toggle between pagination and load more
  function togglePaginationStyle(usePagination = true) {
    if (usePagination) {
      document.querySelector('.pagination-container').classList.remove('d-none');
      document.querySelector('.load-more-container').classList.add('d-none');
    } else {
      document.querySelector('.pagination-container').classList.add('d-none');
      document.querySelector('.load-more-container').classList.remove('d-none');
    }
  }

  // Pagination click handlers
  document.querySelectorAll('.page-link').forEach(link => {
    link.addEventListener('click', function (e) {
      e.preventDefault();

      // Remove active class from all page items
      document.querySelectorAll('.page-item').forEach(item => {
        item.classList.remove('active');
      });

      // Add active class to clicked page item
      if (!this.getAttribute('aria-label')) {
        this.closest('.page-item').classList.add('active');
      }

      // Simulate page change by scrolling to top
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  });

  // Load more button click handler
  loadMoreBtn.addEventListener('click', () => {
    // Simulate loading more products
    loadMoreBtn.innerHTML =
      '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...';
    loadMoreBtn.disabled = true;

    setTimeout(() => {
      // Reset button state
      loadMoreBtn.innerHTML = 'Load More';
      loadMoreBtn.disabled = false;

      // Show message when no more products
      const loadedAll = Math.random() > 0.5; // Randomly decide if all products loaded
      if (loadedAll) {
        loadMoreBtn.innerHTML = 'No More Products';
        loadMoreBtn.disabled = true;
      }
    }, 1500);
  });

  // Initialize the page
  updateActiveFiltersUI();
  applyFiltersAndSort();

  // Default to grid view
  setView('grid');

  // Default to pagination, but can be toggled
  // togglePaginationStyle(false); // Uncomment to use "Load More" instead
});
