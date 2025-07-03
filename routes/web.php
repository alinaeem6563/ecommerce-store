<?php

use App\Http\Controllers\apps\CartController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\language\LanguageController;
use App\Http\Controllers\front_pages\Checkout;
use App\Http\Controllers\apps\EcommerceDashboard;
use App\Http\Controllers\apps\EcommerceProductList;
use App\Http\Controllers\apps\EcommerceProductAdd;
use App\Http\Controllers\apps\EcommerceProductCategory;
use App\Http\Controllers\apps\EcommerceOrderList;
use App\Http\Controllers\apps\EcommerceOrderDetails;
use App\Http\Controllers\apps\EcommerceCustomerAll;
use App\Http\Controllers\apps\EcommerceCustomerDetailsOverview;
use App\Http\Controllers\apps\EcommerceCustomerDetailsSecurity;
use App\Http\Controllers\apps\EcommerceCustomerDetailsBilling;
use App\Http\Controllers\apps\EcommerceCustomerDetailsNotifications;
use App\Http\Controllers\apps\EcommerceManageReviews;
use App\Http\Controllers\apps\EcommerceReferrals;
use App\Http\Controllers\apps\EcommerceSettingsDetails;
use App\Http\Controllers\apps\EcommerceSettingsPayments;
use App\Http\Controllers\apps\EcommerceSettingsCheckout;
use App\Http\Controllers\apps\EcommerceSettingsShipping;
use App\Http\Controllers\apps\EcommerceSettingsLocations;
use App\Http\Controllers\apps\EcommerceSettingsNotifications;
use App\Http\Controllers\apps\ShopController;
use App\Http\Controllers\apps\VariantController;
use App\Http\Controllers\apps\VendorController as AppsVendorController;
use App\Http\Controllers\authentications\ForgotPasswordBasic;
use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\authentications\RegisterBasic;
use App\Http\Controllers\authentications\ResetPasswordBasic;
use App\Http\Controllers\authentications\TwoStepsBasic;
use App\Http\Controllers\authentications\VerifyEmailBasic;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\home\HomeController;
use App\Http\Controllers\ProfileController;

//Home
Route::resource('/', HomeController::class);
//Error
Route::fallback(function () {
    return response()->view('content.pages.pages-misc-error', [], 404);
});



// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // Main Page Route
    Route::get('home', [EcommerceDashboard::class, 'index'])->name('dashboard');

    // Route::get('/dashboard/crm', [Crm::class, 'index'])->name('dashboard-crm');
    // locale
    Route::get('/lang/{locale}', [LanguageController::class, 'swap']);

    // layout


    // Front Pages

    Route::get('/front-pages/checkout', [Checkout::class, 'index'])->name('front-pages-checkout');

    // apps

    Route::get('/app/ecommerce/product/list', [EcommerceProductList::class, 'index'])->name('app-ecommerce-product-list');
    Route::resource('products', EcommerceProductAdd::class);
    Route::get('/product/duplicate/{id}', [EcommerceProductAdd::class, 'duplicateProduct'])->name('product.duplicate');
    Route::post('/product/activate/{id}', [EcommerceProductAdd::class, 'activate'])->name('product.activate');
    Route::post('/product/deactivate/{id}', [EcommerceProductAdd::class, 'deactivate'])->name('product.deactivate');
    Route::post('/products/bulk-delete', 'EcommerceProductAdd@bulkDelete')->name('product.bulkDelete');
    Route::post('/products/bulk-activate', 'EcommerceProductAdd@bulkActivate')->name('product.bulkActivate');
    Route::post('/products/bulk-deactivate', 'EcommerceProductAdd@bulkDeactivate')->name('product.bulkDeactivate');

    Route::resource('collections', CollectionController::class);

    
    
    
    Route::get('/product-detail/{id}', [EcommerceProductAdd::class, 'shopProductShow'])->name('shop.products.show');
    
    
    Route::resource('category', EcommerceProductCategory::class);
    
    Route::resource('variant', VariantController::class);
    
    Route::resource('vendors', AppsVendorController::class);
    
    Route::get('/vendors/data', [AppsVendorController::class, 'getData'])->name('vendors.data');
    
    
    Route::get('/app/ecommerce/order/list', [EcommerceOrderList::class, 'index'])->name('app-ecommerce-order-list');
    Route::get('/app/ecommerce/order/details', [EcommerceOrderDetails::class, 'index'])->name('app-ecommerce-order-details');
    Route::get('/app/ecommerce/customer/all', [EcommerceCustomerAll::class, 'index'])->name('app-ecommerce-customer-all');
    Route::get('/app/ecommerce/customer/details/overview', [EcommerceCustomerDetailsOverview::class, 'index'])->name('app-ecommerce-customer-details-overview');
    Route::get('/app/ecommerce/customer/details/security', [EcommerceCustomerDetailsSecurity::class, 'index'])->name('app-ecommerce-customer-details-security');
    Route::get('/app/ecommerce/customer/details/billing', [EcommerceCustomerDetailsBilling::class, 'index'])->name('app-ecommerce-customer-details-billing');
    Route::get('/app/ecommerce/customer/details/notifications', [EcommerceCustomerDetailsNotifications::class, 'index'])->name('app-ecommerce-customer-details-notifications');
    Route::get('/app/ecommerce/manage/reviews', [EcommerceManageReviews::class, 'index'])->name('app-ecommerce-manage-reviews');
    Route::get('/app/ecommerce/referrals', [EcommerceReferrals::class, 'index'])->name('app-ecommerce-referrals');
    Route::get('/app/ecommerce/settings/details', [EcommerceSettingsDetails::class, 'index'])->name('app-ecommerce-settings-details');
    Route::get('/app/ecommerce/settings/payments', [EcommerceSettingsPayments::class, 'index'])->name('app-ecommerce-settings-payments');
    Route::get('/app/ecommerce/settings/checkout', [EcommerceSettingsCheckout::class, 'index'])->name('app-ecommerce-settings-checkout');
    Route::get('/app/ecommerce/settings/shipping', [EcommerceSettingsShipping::class, 'index'])->name('app-ecommerce-settings-shipping');
    Route::get('/app/ecommerce/settings/locations', [EcommerceSettingsLocations::class, 'index'])->name('app-ecommerce-settings-locations');
    Route::get('/app/ecommerce/settings/notifications', [EcommerceSettingsNotifications::class, 'index'])->name('app-ecommerce-settings-notifications');
    
    // authentication
});
Route::resource('shop', ShopController::class);
Route::get('/login', [LoginBasic::class, 'index'])->name('login');
Route::get('/register', [RegisterBasic::class, 'index'])->name('register');
Route::get('/verify-email', [VerifyEmailBasic::class, 'index'])->name('verify-email');
    Route::get('/reset-password', [ResetPasswordBasic::class, 'index'])->name('reset-password');
    Route::get('/forgot-password', [ForgotPasswordBasic::class, 'index'])->name('forgot-password');
    Route::get('/two-steps', [TwoStepsBasic::class, 'index'])->name('two-steps  ');

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/apply-coupon', [CartController::class, 'applyCoupon'])->name('cart.applyCoupon');
    Route::post('/cart/remove-coupon', [CartController::class, 'removeCoupon'])->name('cart.removeCoupon');

    require __DIR__ . '/auth.php';
    