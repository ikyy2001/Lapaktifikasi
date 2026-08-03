<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Webhook / Callbacks
Route::post('/callback', [\App\Http\Controllers\MidtransController::class, 'callback']);
Route::post('/callback/pakasir', [\App\Http\Controllers\PakasirController::class, 'callback']);

// API V1
Route::prefix('v1')->namespace('App\Http\Controllers\Api')->group(function () {
    
    // Auth
    Route::post('/auth/login', 'AuthController@login');
    Route::post('/auth/register', 'AuthController@register');
    Route::post('/auth/forgot-password', 'AuthController@forgotPassword');
    Route::post('/auth/reset-password', 'AuthController@resetPassword');

    // Public Catalog
    Route::get('/toko', 'CatalogController@getShops');
    Route::get('/toko/{id}/produk', 'CatalogController@getCatalog'); // with id_toko parameter implicitly passed or query string
    Route::get('/katalog', 'CatalogController@getCatalog');
    Route::get('/produk/{id}', 'CatalogController@getProductDetail');
    Route::get('/produk/varian/{id}/stok', 'CatalogController@checkStock');

    // Protected Routes
    Route::middleware('auth:sanctum')->group(function () {
        
        // Auth Logout & Profile
        Route::post('/auth/logout', 'AuthController@logout');
        Route::get('/profile', 'ProfileController@getProfile');
        Route::post('/profile/update', 'ProfileController@updateProfile');

        // Customer Only Features
        Route::middleware('only.customer')->group(function () {
            // Checkout & Pembayaran
            Route::post('/checkout', 'CheckoutController@checkout');
            Route::post('/pembayaran/generate/{order_id}', 'CheckoutController@generateTransaction');
            Route::get('/pembayaran/status/{order_id}', 'CheckoutController@status');
            
            // Premium Customer
            Route::get('/customer/member', 'CustomerPremiumController@getMemberData');
            Route::get('/customer/referral', 'CustomerPremiumController@getReferralData');
            Route::get('/customer/riwayat', 'CustomerPremiumController@getRiwayat');
            Route::get('/customer/kredensial/{order_id}', 'CustomerPremiumController@getKredensial');
            Route::post('/customer/voucher/{id}/klaim', 'CustomerPremiumController@klaimVoucher');
            Route::post('/customer/review/{order_id}', 'CustomerPremiumController@storeReview');
            Route::get('/customer/laporan', 'CustomerPremiumController@getLaporan');
            Route::post('/customer/laporan', 'CustomerPremiumController@storeLaporan');
        });

        // Seller Only Features
        Route::middleware('only.seller')->group(function () {
            // Dashboard & Toko
            Route::get('/seller/dashboard', 'SellerController@getDashboard');
            Route::get('/seller/mutasi', 'SellerController@getMutasi');
            Route::get('/seller/profil', 'SellerController@getProfil');
            Route::post('/seller/profil', 'SellerController@updateProfil');
            Route::get('/seller/badges', 'SellerController@getBadges');

            // CRUD Produk
            Route::get('/seller/produk', 'SellerProductController@index');
            Route::post('/seller/produk', 'SellerProductController@store');
            Route::post('/seller/produk/{id}', 'SellerProductController@update'); // Use POST with _method=PUT or manual for multipart/form-data
            Route::delete('/seller/produk/{id}', 'SellerProductController@destroy');

            // CRUD Voucher
            Route::get('/seller/voucher', 'SellerVoucherController@index');
            Route::post('/seller/voucher', 'SellerVoucherController@store');
            Route::put('/seller/voucher/{id}', 'SellerVoucherController@update');
            Route::delete('/seller/voucher/{id}', 'SellerVoucherController@destroy');
        });

        // Admin Only Features
        Route::middleware('admin.only')->group(function () {
            Route::get('/admin/dashboard', 'AdminController@getDashboard');
            
            Route::get('/admin/kelola-seller', 'AdminController@getSellers');
            Route::post('/admin/kelola-seller/{id}/toggle-status', 'AdminController@toggleSellerStatus');

            Route::get('/admin/laporan', 'AdminController@getLaporan');
            Route::put('/admin/laporan/{id}/status', 'AdminController@updateLaporanStatus');

            Route::get('/admin/setting-komisi', 'AdminController@getSettingKomisi');
            Route::post('/admin/setting-komisi', 'AdminController@updateSettingKomisi');

            Route::get('/admin/voucher', 'AdminController@getVoucherAdmin');
            Route::post('/admin/voucher', 'AdminController@storeVoucherAdmin');
            Route::put('/admin/voucher/{id}', 'AdminController@updateVoucherAdmin');
            Route::delete('/admin/voucher/{id}', 'AdminController@destroyVoucherAdmin');
        });

    });

});
