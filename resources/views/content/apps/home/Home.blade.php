@extends('layouts/blankLayout')

@section('title', 'Home')

@section('vendor-style')
    @vite(['resources/assets/vendor/libs/swiper/swiper.scss'])
@endsection

@section('page-style')
    @vite('resources/assets/vendor/scss/pages/home.scss')
@endsection

@section('content')
@include('layouts.sections.navbar.navbar-front')
<!-- Hero Section -->
<section class="hero-section">
    <div class="swiper hero-swiper">
        <div class="swiper-wrapper">
            @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
@endif

            <div class="swiper-slide hero-slide" style="background-image: url('/placeholder.svg?height=600&width=1200')">
                <div class="container h-100">
                    <div class="row h-100 align-items-center">
                        <div class="col-lg-6">
                            <div class="hero-content">
                                <h5 class="hero-subtitle">New Collection</h5>
                                <h1 class="hero-title">Discover Summer <br>Essentials</h1>
                                <p class="hero-text">Refresh your wardrobe with our latest summer collection. Lightweight fabrics, vibrant colors, and timeless designs.</p>
                                <div class="hero-buttons">
                                    <a href="{{route('shop.index')}}" class="btn btn-primary btn-lg">Shop Now</a>
                                    <a href="#" class="btn btn-outline-primary btn-lg ms-2">Explore</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="swiper-slide hero-slide" style="background-image: url('/placeholder.svg?height=600&width=1200')">
                <div class="container h-100">
                    <div class="row h-100 align-items-center">
                        <div class="col-lg-6">
                            <div class="hero-content">
                                <h5 class="hero-subtitle">Limited Time</h5>
                                <h1 class="hero-title">Summer Sale <br>Up to 50% Off</h1>
                                <p class="hero-text">Don't miss out on our biggest sale of the season. Get premium quality products at unbeatable prices.</p>
                                <div class="hero-buttons">
                                    <a href="#" class="btn btn-primary btn-lg">Shop Sale</a>
                                    <a href="#" class="btn btn-outline-primary btn-lg ms-2">Learn More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="swiper-pagination"></div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>
</section>

<!-- Features Section -->
<section class="features-section py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-3 col-sm-6">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="ti ti-truck-delivery"></i>
                    </div>
                    <div class="feature-content">
                        <h5>Free Shipping</h5>
                        <p>On all orders over $50</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="ti ti-rotate"></i>
                    </div>
                    <div class="feature-content">
                        <h5>Easy Returns</h5>
                        <p>30-day return policy</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="ti ti-shield-check"></i>
                    </div>
                    <div class="feature-content">
                        <h5>Secure Payment</h5>
                        <p>100% secure checkout</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="ti ti-headset"></i>
                    </div>
                    <div class="feature-content">
                        <h5>24/7 Support</h5>
                        <p>Dedicated support team</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="categories-section py-5">
    <div class="container">
        <div class="section-header text-center mb-5">
            <h2 class="section-title">Shop by Category</h2>
            <p class="section-subtitle">Explore our wide range of products</p>
        </div>
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="category-card">
                    <div class="category-image">
                        <img src="/placeholder.svg?height=300&width=400" alt="Fashion" class="img-fluid">
                    </div>
                    <div class="category-content">
                        <h3>Fashion</h3>
                        <a href="#" class="btn btn-outline-light">Shop Now</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="category-card">
                    <div class="category-image">
                        <img src="/placeholder.svg?height=300&width=400" alt="Electronics" class="img-fluid">
                    </div>
                    <div class="category-content">
                        <h3>Electronics</h3>
                        <a href="#" class="btn btn-outline-light">Shop Now</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="category-card">
                    <div class="category-image">
                        <img src="/placeholder.svg?height=300&width=400" alt="Home & Decor" class="img-fluid">
                    </div>
                    <div class="category-content">
                        <h3>Home & Decor</h3>
                        <a href="#" class="btn btn-outline-light">Shop Now</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="category-card category-card-wide">
                    <div class="category-image">
                        <img src="/placeholder.svg?height=300&width=600" alt="Beauty" class="img-fluid">
                    </div>
                    <div class="category-content">
                        <h3>Beauty & Personal Care</h3>
                        <a href="#" class="btn btn-outline-light">Shop Now</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12">
                <div class="category-card category-card-wide">
                    <div class="category-image">
                        <img src="/placeholder.svg?height=300&width=600" alt="Sports" class="img-fluid">
                    </div>
                    <div class="category-content">
                        <h3>Sports & Outdoors</h3>
                        <a href="#" class="btn btn-outline-light">Shop Now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products Section -->
<section class="featured-products-section py-5">
    <div class="container">
        <div class="section-header text-center mb-5">
            <h2 class="section-title">Featured Products</h2>
            <p class="section-subtitle">Handpicked for you</p>
        </div>
        <div class="row g-4">
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="product-card">
                    <div class="product-badge">New</div>
                    <div class="product-image">
                        <img src="/placeholder.svg?height=300&width=300" alt="Product 1" class="img-fluid">
                        <div class="product-actions">
                            <button class="btn-wishlist" data-bs-toggle="tooltip" title="Add to Wishlist">
                                <i class="ti ti-heart"></i>
                            </button>
                            <button class="btn-quickview" data-bs-toggle="tooltip" title="Quick View">
                                <i class="ti ti-eye"></i>
                            </button>
                            <button class="btn-compare" data-bs-toggle="tooltip" title="Compare">
                                <i class="ti ti-arrows-shuffle"></i>
                            </button>
                        </div>
                    </div>
                    <div class="product-content">
                        <div class="product-category">Fashion</div>
                        <h4 class="product-title"><a href="#">Premium Cotton T-Shirt</a></h4>
                        <div class="product-rating">
                            <i class="ti ti-star-filled"></i>
                            <i class="ti ti-star-filled"></i>
                            <i class="ti ti-star-filled"></i>
                            <i class="ti ti-star-filled"></i>
                            <i class="ti ti-star"></i>
                            <span>(24)</span>
                        </div>
                        <div class="product-price">
                            <span class="current-price">$29.99</span>
                        </div>
                        <button class="btn btn-primary btn-add-to-cart">
                            <i class="ti ti-shopping-cart"></i> Add to Cart
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="product-card">
                    <div class="product-badge product-badge-sale">-20%</div>
                    <div class="product-image">
                        <img src="/placeholder.svg?height=300&width=300" alt="Product 2" class="img-fluid">
                        <div class="product-actions">
                            <button class="btn-wishlist" data-bs-toggle="tooltip" title="Add to Wishlist">
                                <i class="ti ti-heart"></i>
                            </button>
                            <button class="btn-quickview" data-bs-toggle="tooltip" title="Quick View">
                                <i class="ti ti-eye"></i>
                            </button>
                            <button class="btn-compare" data-bs-toggle="tooltip" title="Compare">
                                <i class="ti ti-arrows-shuffle"></i>
                            </button>
                        </div>
                    </div>
                    <div class="product-content">
                        <div class="product-category">Electronics</div>
                        <h4 class="product-title"><a href="#">Wireless Bluetooth Earbuds</a></h4>
                        <div class="product-rating">
                            <i class="ti ti-star-filled"></i>
                            <i class="ti ti-star-filled"></i>
                            <i class="ti ti-star-filled"></i>
                            <i class="ti ti-star-filled"></i>
                            <i class="ti ti-star-filled"></i>
                            <span>(42)</span>
                        </div>
                        <div class="product-price">
                            <span class="old-price">$99.99</span>
                            <span class="current-price">$79.99</span>
                        </div>
                        <button class="btn btn-primary btn-add-to-cart">
                            <i class="ti ti-shopping-cart"></i> Add to Cart
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="product-card">
                    <div class="product-image">
                        <img src="/placeholder.svg?height=300&width=300" alt="Product 3" class="img-fluid">
                        <div class="product-actions">
                            <button class="btn-wishlist" data-bs-toggle="tooltip" title="Add to Wishlist">
                                <i class="ti ti-heart"></i>
                            </button>
                            <button class="btn-quickview" data-bs-toggle="tooltip" title="Quick View">
                                <i class="ti ti-eye"></i>
                            </button>
                            <button class="btn-compare" data-bs-toggle="tooltip" title="Compare">
                                <i class="ti ti-arrows-shuffle"></i>
                            </button>
                        </div>
                    </div>
                    <div class="product-content">
                        <div class="product-category">Home & Decor</div>
                        <h4 class="product-title"><a href="#">Scented Soy Candle Set</a></h4>
                        <div class="product-rating">
                            <i class="ti ti-star-filled"></i>
                            <i class="ti ti-star-filled"></i>
                            <i class="ti ti-star-filled"></i>
                            <i class="ti ti-star-filled"></i>
                            <i class="ti ti-star-half-filled"></i>
                            <span>(18)</span>
                        </div>
                        <div class="product-price">
                            <span class="current-price">$34.99</span>
                        </div>
                        <button class="btn btn-primary btn-add-to-cart">
                            <i class="ti ti-shopping-cart"></i> Add to Cart
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="product-card">
                    <div class="product-badge product-badge-out">Sold Out</div>
                    <div class="product-image">
                        <img src="/placeholder.svg?height=300&width=300" alt="Product 4" class="img-fluid">
                        <div class="product-actions">
                            <button class="btn-wishlist" data-bs-toggle="tooltip" title="Add to Wishlist">
                                <i class="ti ti-heart"></i>
                            </button>
                            <button class="btn-quickview" data-bs-toggle="tooltip" title="Quick View">
                                <i class="ti ti-eye"></i>
                            </button>
                            <button class="btn-compare" data-bs-toggle="tooltip" title="Compare">
                                <i class="ti ti-arrows-shuffle"></i>
                            </button>
                        </div>
                    </div>
                    <div class="product-content">
                        <div class="product-category">Beauty</div>
                        <h4 class="product-title"><a href="#">Organic Skincare Set</a></h4>
                        <div class="product-rating">
                            <i class="ti ti-star-filled"></i>
                            <i class="ti ti-star-filled"></i>
                            <i class="ti ti-star-filled"></i>
                            <i class="ti ti-star"></i>
                            <i class="ti ti-star"></i>
                            <span>(7)</span>
                        </div>
                        <div class="product-price">
                            <span class="current-price">$49.99</span>
                        </div>
                        <button class="btn btn-secondary btn-add-to-cart" disabled>
                            <i class="ti ti-alert-circle"></i> Out of Stock
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center mt-5">
            <a href="#" class="btn btn-outline-primary btn-lg">View All Products</a>
        </div>
    </div>
</section>

<!-- Deal of the Day Section -->
<section class="deal-section py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="deal-image">
                    <img src="/placeholder.svg?height=500&width=600" alt="Deal of the Day" class="img-fluid">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="deal-content">
                    <h6 class="deal-subtitle">Limited Time Offer</h6>
                    <h2 class="deal-title">Deal of the Day</h2>
                    <p class="deal-description">Get our premium wireless headphones at an unbeatable price. Superior sound quality, noise cancellation, and 30-hour battery life.</p>
                    <div class="deal-price">
                        <span class="old-price">$199.99</span>
                        <span class="current-price">$129.99</span>
                        <span class="discount-badge">35% OFF</span>
                    </div>
                    <div class="deal-countdown" id="dealCountdown">
                        <div class="countdown-item">
                            <span class="countdown-value" id="countdown-days">00</span>
                            <span class="countdown-label">Days</span>
                        </div>
                        <div class="countdown-item">
                            <span class="countdown-value" id="countdown-hours">00</span>
                            <span class="countdown-label">Hours</span>
                        </div>
                        <div class="countdown-item">
                            <span class="countdown-value" id="countdown-minutes">00</span>
                            <span class="countdown-label">Minutes</span>
                        </div>
                        <div class="countdown-item">
                            <span class="countdown-value" id="countdown-seconds">00</span>
                            <span class="countdown-label">Seconds</span>
                        </div>
                    </div>
                    <a href="#" class="btn btn-primary btn-lg">Shop Now</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- New Arrivals Section -->
<section class="new-arrivals-section py-5">
    <div class="container">
        <div class="section-header text-center mb-5">
            <h2 class="section-title">New Arrivals</h2>
            <p class="section-subtitle">Fresh off the shelves</p>
        </div>
        <div class="swiper new-arrivals-swiper">
            <div class="swiper-wrapper">
                @for ($i = 1; $i <= 8; $i++)
                <div class="swiper-slide">
                    <div class="product-card">
                        <div class="product-badge">New</div>
                        <div class="product-image">
                            <img src="/placeholder.svg?height=300&width=300" alt="New Arrival {{ $i }}" class="img-fluid">
                            <div class="product-actions">
                                <button class="btn-wishlist" data-bs-toggle="tooltip" title="Add to Wishlist">
                                    <i class="ti ti-heart"></i>
                                </button>
                                <button class="btn-quickview" data-bs-toggle="tooltip" title="Quick View">
                                    <i class="ti ti-eye"></i>
                                </button>
                                <button class="btn-compare" data-bs-toggle="tooltip" title="Compare">
                                    <i class="ti ti-arrows-shuffle"></i>
                                </button>
                            </div>
                        </div>
                        <div class="product-content">
                            <div class="product-category">Category</div>
                            <h4 class="product-title"><a href="#">New Product {{ $i }}</a></h4>
                            <div class="product-rating">
                                <i class="ti ti-star-filled"></i>
                                <i class="ti ti-star-filled"></i>
                                <i class="ti ti-star-filled"></i>
                                <i class="ti ti-star-filled"></i>
                                <i class="ti ti-star"></i>
                                <span>({{ rand(5, 50) }})</span>
                            </div>
                            <div class="product-price">
                                <span class="current-price">${{ rand(20, 100) }}.99</span>
                            </div>
                            <button class="btn btn-primary btn-add-to-cart">
                                <i class="ti ti-shopping-cart"></i> Add to Cart
                            </button>
                        </div>
                    </div>
                </div>
                @endfor
            </div>
            <div class="swiper-pagination"></div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="testimonials-section py-5">
    <div class="container">
        <div class="section-header text-center mb-5">
            <h2 class="section-title">What Our Customers Say</h2>
            <p class="section-subtitle">Trusted by thousands of customers worldwide</p>
        </div>
        <div class="swiper testimonials-swiper">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <div class="testimonial-card">
                        <div class="testimonial-rating">
                            <i class="ti ti-star-filled"></i>
                            <i class="ti ti-star-filled"></i>
                            <i class="ti ti-star-filled"></i>
                            <i class="ti ti-star-filled"></i>
                            <i class="ti ti-star-filled"></i>
                        </div>
                        <div class="testimonial-content">
                            <p>"I've been shopping here for years and the quality never disappoints. The customer service is exceptional and shipping is always fast. Highly recommend!"</p>
                        </div>
                        <div class="testimonial-author">
                            <div class="testimonial-avatar">
                                <img src="/placeholder.svg?height=60&width=60" alt="Sarah Johnson">
                            </div>
                            <div class="testimonial-info">
                                <h5>Sarah Johnson</h5>
                                <p>Loyal Customer</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="testimonial-card">
                        <div class="testimonial-rating">
                            <i class="ti ti-star-filled"></i>
                            <i class="ti ti-star-filled"></i>
                            <i class="ti ti-star-filled"></i>
                            <i class="ti ti-star-filled"></i>
                            <i class="ti ti-star-filled"></i>
                        </div>
                        <div class="testimonial-content">
                            <p>"The products are amazing and the prices are very competitive. I love the easy navigation of the website and how quickly my orders arrive. Will definitely shop here again!"</p>
                        </div>
                        <div class="testimonial-author">
                            <div class="testimonial-avatar">
                                <img src="/placeholder.svg?height=60&width=60" alt="Michael Chen">
                            </div>
                            <div class="testimonial-info">
                                <h5>Michael Chen</h5>
                                <p>Verified Buyer</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="testimonial-card">
                        <div class="testimonial-rating">
                            <i class="ti ti-star-filled"></i>
                            <i class="ti ti-star-filled"></i>
                            <i class="ti ti-star-filled"></i>
                            <i class="ti ti-star-filled"></i>
                            <i class="ti ti-star-half-filled"></i>
                        </div>
                        <div class="testimonial-content">
                            <p>"I was skeptical at first, but after my first purchase, I was completely sold. The quality of the products exceeded my expectations and the return process was hassle-free when I needed it."</p>
                        </div>
                        <div class="testimonial-author">
                            <div class="testimonial-avatar">
                                <img src="/placeholder.svg?height=60&width=60" alt="Emily Rodriguez">
                            </div>
                            <div class="testimonial-info">
                                <h5>Emily Rodriguez</h5>
                                <p>New Customer</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>

<!-- Brands Section -->
<section class="brands-section py-5">
    <div class="container">
        <div class="section-header text-center mb-5">
            <h2 class="section-title">Our Brands</h2>
            <p class="section-subtitle">Trusted partners we work with</p>
        </div>
        <div class="swiper brands-swiper">
            <div class="swiper-wrapper">
                @for ($i = 1; $i <= 8; $i++)
                <div class="swiper-slide">
                    <div class="brand-item">
                        <img src="/placeholder.svg?height=100&width=200" alt="Brand {{ $i }}" class="img-fluid">
                    </div>
                </div>
                @endfor
            </div>
        </div>
    </div>
</section>

<!-- Instagram Feed Section -->
<section class="instagram-section py-5">
    <div class="container">
        <div class="section-header text-center mb-5">
            <h2 class="section-title">Follow Us on Instagram</h2>
            <p class="section-subtitle">@yourstorename</p>
        </div>
        <div class="row g-2">
            @for ($i = 1; $i <= 6; $i++)
            <div class="col-lg-2 col-md-4 col-6">
                <div class="instagram-item">
                    <img src="/placeholder.svg?height=200&width=200" alt="Instagram {{ $i }}" class="img-fluid">
                    <div class="instagram-overlay">
                        <a href="#" class="instagram-link">
                            <i class="ti ti-brand-instagram"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endfor
        </div>
        <div class="text-center mt-4">
            <a href="#" class="btn btn-outline-primary">View Instagram</a>
        </div>
    </div>
</section>

<!-- Newsletter Section -->
{{-- <section class="newsletter-section py-5">
    <div class="container">
        <div class="newsletter-container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="newsletter-content">
                        <h2>Subscribe to Our Newsletter</h2>
                        <p>Stay updated with our latest products, exclusive offers, and promotions.</p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <form class="newsletter-form">
                        <div class="input-group">
                            <input type="email" class="form-control" placeholder="Your email address" required>
                            <button class="btn btn-primary" type="submit">Subscribe</button>
                        </div>
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" id="newsletter-consent" required>
                            <label class="form-check-label" for="newsletter-consent">
                                I agree to receive marketing emails and can unsubscribe at any time.
                            </label>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section> --}}
@include('layouts.sections.footer.footer-front')
@endsection


@section('vendor-script')
    @vite(['resources/assets/vendor/libs/swiper/swiper.js'])
@endsection

@section('page-script')
    @vite(['resources/assets/js/home.js'])
    
@endsection
