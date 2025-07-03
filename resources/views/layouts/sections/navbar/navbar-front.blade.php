@php
    use Illuminate\Support\Facades\Route;
    $currentRouteName = Route::currentRouteName();
    $activeRoutes = ['front-pages-pricing', 'front-pages-payment', 'front-pages-checkout', 'front-pages-help-center'];
    $activeClass = in_array($currentRouteName, $activeRoutes) ? 'active' : '';
@endphp
<!-- Navbar: Start -->
<nav class="layout-navbar shadow-none py-0">
    <div class="container">
        <div class="navbar navbar-expand-lg landing-navbar px-3 px-md-8">
            <!-- Menu logo wrapper: Start -->
            <div class="navbar-brand app-brand demo d-flex py-0 py-lg-2 me-4 me-xl-8">
                <!-- Mobile menu toggle: Start-->
                <button class="navbar-toggler border-0 px-0 me-4" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <i class="ti ti-menu-2 ti-lg align-middle text-heading fw-medium"></i>
                </button>
                <!-- Mobile menu toggle: End-->
                <a href="{{ url('front-pages/landing') }}" class="app-brand-link">
                    <span class="app-brand-logo demo">@include('_partials.macros', ['height' => 20, 'withbg' => 'fill: #fff;'])</span>
                    <span
                        class="app-brand-text demo menu-text fw-bold ms-2 ps-1">{{ config('variables.templateName') }}</span>
                </a>
            </div>
            <!-- Menu logo wrapper: End -->
            <!-- Menu wrapper: Start -->
            <div class="collapse navbar-collapse landing-nav-menu" id="navbarSupportedContent">
                <button class="navbar-toggler border-0 text-heading position-absolute end-0 top-0 scaleX-n1-rtl"
                    type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="ti ti-x ti-lg"></i>
                </button>
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link fw-medium" aria-current="page" href="{{ url('/') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-medium"
                            href="{{ url('front-pages/landing') }}#landingFeatures">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-medium" href="{{ url('front-pages/landing') }}#landingTeam">Team</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-medium" href="{{ url('front-pages/landing') }}#landingFAQ">FAQ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-medium" href="{{ url('front-pages/landing') }}#landingContact">Contact
                            us</a>
                    </li>
                    <li class="nav-item mega-dropdown {{ $activeClass }}">
                        <a href="javascript:void(0);"
                            class="nav-link dropdown-toggle navbar-ex-14-mega-dropdown mega-dropdown fw-medium"
                            aria-expanded="false" data-bs-toggle="mega-dropdown" data-trigger="hover">
                            <span>Pages</span>
                        </a>
                        <div class="dropdown-menu p-4 p-xl-8">
                            <div class="row gy-4">
                                <div class="col-12 col-lg">
                                    <div class="h6 d-flex align-items-center mb-3 mb-lg-5">
                                        <div class="avatar flex-shrink-0 me-3">
                                            <span class="avatar-initial rounded bg-label-primary"><i
                                                    class='ti ti-layout-grid ti-lg'></i></span>
                                        </div>
                                        <span class="ps-1">Other</span>
                                    </div>
                                    <ul class="nav flex-column">
                                        <li
                                            class="nav-item {{ $currentRouteName === 'front-pages-pricing' ? 'active' : '' }}">
                                            <a class="nav-link mega-dropdown-link"
                                                href="{{ url('front-pages/pricing') }}">
                                                <i class='ti ti-circle me-1'></i>
                                                <span>Pricing</span>
                                            </a>
                                        </li>
                                        <li
                                            class="nav-item {{ $currentRouteName === 'front-pages-payment' ? 'active' : '' }}">
                                            <a class="nav-link mega-dropdown-link"
                                                href="{{ url('front-pages/payment') }}">
                                                <i class='ti ti-circle me-1'></i>
                                                <span>Payment</span>
                                            </a>
                                        </li>
                                        <li
                                            class="nav-item {{ $currentRouteName === 'front-pages-checkout' ? 'active' : '' }}">
                                            <a class="nav-link mega-dropdown-link"
                                                href="{{ url('front-pages/checkout') }}">
                                                <i class='ti ti-circle me-1'></i>
                                                <span>Checkout</span>
                                            </a>
                                        </li>
                                        <li
                                            class="nav-item {{ $currentRouteName === 'front-pages-help-center' ? 'active' : '' }}">
                                            <a class="nav-link mega-dropdown-link"
                                                href="{{ url('front-pages/help-center') }}">
                                                <i class='ti ti-circle me-1'></i>
                                                <span>Help Center</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-12 col-lg">
                                    <div class="h6 d-flex align-items-center mb-3 mb-lg-5">
                                        <div class="avatar flex-shrink-0 me-3">
                                            <span class="avatar-initial rounded bg-label-primary"><i
                                                    class='ti ti-lock-open ti-lg'></i></span>
                                        </div>
                                        <span class="ps-1">Auth Demo</span>
                                    </div>
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link mega-dropdown-link" href="{{ url('/login') }}">
                                                <i class='ti ti-circle me-1'></i>
                                                Login
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link mega-dropdown-link" href="{{ url('/register') }}"
                                                target="_blank">
                                                <i class='ti ti-circle me-1'></i>
                                                Register
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link mega-dropdown-link" href="{{ url('/forgot-password') }}"
                                                target="_blank">
                                                <i class='ti ti-circle me-1'></i>
                                                Forgot Password
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link mega-dropdown-link" href="{{ url('/reset-password') }}"
                                                target="_blank">
                                                <i class='ti ti-circle me-1'></i>
                                                Reset Password
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-12 col-lg">
                                    <div class="h6 d-flex align-items-center mb-3 mb-lg-5">
                                        <div class="avatar flex-shrink-0 me-3">
                                            <span class="avatar-initial rounded bg-label-primary"><i
                                                    class='ti ti-file-analytics ti-lg'></i></span>
                                        </div>
                                        <span class="ps-1">Other</span>
                                    </div>
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link mega-dropdown-link"
                                                href="{{ url('/pages/misc-error') }}" target="_blank">
                                                <i class='ti ti-circle me-1'></i>
                                                Error
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link mega-dropdown-link"
                                                href="{{ url('/pages/misc-under-maintenance') }}" target="_blank">
                                                <i class='ti ti-circle me-1'></i>
                                                Under Maintenance
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link mega-dropdown-link"
                                                href="{{ url('/pages/misc-comingsoon') }}" target="_blank">
                                                <i class='ti ti-circle me-1'></i>
                                                Coming Soon
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link mega-dropdown-link"
                                                href="{{ url('/pages/misc-not-authorized') }}" target="_blank">
                                                <i class='ti ti-circle me-1'></i>
                                                Not Authorized
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link mega-dropdown-link"
                                                href="{{ url('/auth/verify-email-basic') }}" target="_blank">
                                                <i class='ti ti-circle me-1'></i>
                                                Verify Email (Basic)
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link mega-dropdown-link"
                                                href="{{ url('/auth/verify-email-cover') }}" target="_blank">
                                                <i class='ti ti-circle me-1'></i>
                                                Verify Email (Cover)
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link mega-dropdown-link"
                                                href="{{ url('/auth/two-steps-basic') }}" target="_blank">
                                                <i class='ti ti-circle me-1'></i>
                                                Two Steps (Basic)
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link mega-dropdown-link"
                                                href="{{ url('/auth/two-steps-cover') }}" target="_blank">
                                                <i class='ti ti-circle me-1'></i>
                                                Two Steps (Cover)
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-lg-4 d-none d-lg-block">
                                    <div class="bg-body nav-img-col p-2">
                                        <img src="{{ asset('assets/img/front-pages/misc/nav-item-col-img.png') }}"
                                            alt="nav item col image" class="w-100">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-medium" href="{{ url('/') }}" target="_blank">Admin</a>
                    </li>
                </ul>
            </div>
            <div class="landing-menu-overlay d-lg-none"></div>
            <!-- Menu wrapper: End -->
            <!-- Toolbar: Start -->
            <ul class="navbar-nav flex-row align-items-center ms-auto">
                {{-- @if ($configData['hasCustomizer'] == true) --}}
                <!-- Style Switcher -->
                <li class="nav-item dropdown-style-switcher dropdown me-2 me-xl-1">
                    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);"
                        data-bs-toggle="dropdown">
                        <i class='ti ti-lg'></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-styles">
                        <li>
                            <a class="dropdown-item" href="javascript:void(0);" data-theme="light">
                                <span class="align-middle"><i class='ti ti-sun me-3'></i>Light</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="javascript:void(0);" data-theme="dark">
                                <span class="align-middle"><i class="ti ti-moon-stars me-3"></i>Dark</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="javascript:void(0);" data-theme="system">
                                <span class="align-middle"><i
                                        class="ti ti-device-desktop-analytics me-3"></i>System</span>
                            </a>
                        </li>
                    </ul>
                </li>
                @php
    $cart = session('cart', []);
    $subtotal = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
@endphp

<li class="mx-3">
    <div class="cart-dropdown-container">
        <button class="cart-icon-btn" type="button" id="cartDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="ti ti-shopping-cart"></i>
            <span class="cart-badge">{{ count($cart) }}</span>
        </button>
        <div class="dropdown-menu dropdown-menu-end cart-dropdown" aria-labelledby="cartDropdown">
            <div class="cart-header">
                <h6 class="mb-0">Your Cart</h6>
                <span class="cart-count">{{ count($cart) }} items</span>
            </div>

            <div class="dropdown-cart-items">
                @forelse ($cart as $id => $item)
                    <div class="cart-item">
                        <div class="cart-item-img">
                            <img src="{{ $item['image'] ?? '/placeholder.svg?height=60&width=60' }}" alt="{{ $item['name'] }}">
                        </div>
                        <div class="cart-item-details">
                            <h6 class="cart-item-title"><a href="#">{{ $item['name'] }}</a></h6>
                            <div class="cart-item-info">
                                <span class="cart-item-quantity">{{ $item['quantity'] }} ×</span>
                                <span class="cart-item-price">${{ number_format($item['price'], 2) }}</span>
                            </div>
                        </div>
                        <form action="{{ route('cart.remove', $id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="cart-item-remove" aria-label="Remove item">
                                <i class="ti ti-x"></i>
                            </button>
                        </form>
                    </div>
                @empty
                    <p class="text-center p-3">Your cart is empty.</p>
                @endforelse
            </div>

            @if(count($cart))
                <div class="cart-footer">
                    <div class="cart-subtotal">
                        <span>Subtotal</span>
                        <span class="subtotal-amount">${{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="cart-actions">
                        <a href="{{ route('cart.view') }}" class="btn-view-cart">View Cart</a>
                        <a href="{{ route('checkout.index') }}" class="btn-checkout">Checkout</a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</li>

                <!-- / Style Switcher-->
                {{-- @endif --}}
                <!-- navbar button: Start -->
                <li>
                    <a href="{{ url('/login') }}" class="btn btn-primary"><span
                            class="tf-icons ti ti-login scaleX-n1-rtl me-md-1"></span><span
                            class="d-none d-md-block">Login/Register</span></a>
                </li>
                <!-- navbar button: End -->
            </ul>
            <!-- Toolbar: End -->
        </div>
    </div>
</nav>
<!-- Navbar: End -->
