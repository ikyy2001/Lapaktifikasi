<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PremiumAdminController;
use App\Http\Controllers\PremiumCustomerController;
use App\Http\Controllers\LaporanController;
use App\Http\Middleware\OnlyCustomer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {

    Route::controller(DashboardController::class)->group(function () {
        Route::get('/dashboard', 'index')->name('dashboard')->middleware('prevent.customer');
    });

    Route::controller(CustomerController::class)->group(function () {
        Route::get('/profile_customer/{id}', 'index')->middleware('check.id.customer');
        Route::post('/update_profile', 'update_profile');
    });

    Route::controller(PengaturanController::class)->group(function () {
        Route::get('/ganti_password', 'index');
        Route::post('/proses_ganti_password', 'proses_ganti_password');
        Route::get('/extract_screenshots', 'extract_screenshots')->middleware('prevent.customer');
        Route::post('/proses_extract_screenshots', 'proses_extract_screenshots')->middleware('prevent.customer');
    });

    Route::controller(ProductController::class)->group(function () {
        Route::get('/produk_terjual', 'produk_terjual')->middleware('prevent.customer');
        Route::get('/beli/{id}', 'beli')->name('beli')->middleware('only.customer');
        Route::post('/proses_checkout', 'proses_checkout');
        Route::post('/proses_checkout_premium', 'proses_checkout_premium');
        Route::get('/varian/{id_varian}/stok', 'get_stok_varian');
        Route::get('/download_produk/{id_produk}', 'download_produk')->name('download_produk')->middleware('reset.headers');
    });

    Route::resource('menu_produk', ProductController::class);

    Route::controller(PembayaranController::class)->group(function () {
        Route::middleware([OnlyCustomer::class])->group(function () {
            Route::get('/download_bukti_pembayaran/{order_id}', 'download_bukti_pembayaran')->name('download_bukti_pembayaran');
            Route::get('/bukti_pembayaran', 'index');
            Route::get('/metode_pembayaran/{order_id}', 'metode_pembayaran')->name('metode_pembayaran');
        });
    });

    // Premium Admin Routes
    Route::middleware('prevent.customer')->group(function () {
        Route::get('/premium/produk', [PremiumAdminController::class, 'produk_index'])->name('premium.produk.index');
        Route::post('/premium/produk', [PremiumAdminController::class, 'produk_store'])->name('premium.produk.store');
        Route::put('/premium/produk/{id}', [PremiumAdminController::class, 'produk_update'])->name('premium.produk.update');

        Route::get('/premium/tipe', [PremiumAdminController::class, 'tipe_index'])->name('premium.tipe.index');
        Route::post('/premium/tipe', [PremiumAdminController::class, 'tipe_store'])->name('premium.tipe.store');
        Route::put('/premium/tipe/{id}', [PremiumAdminController::class, 'tipe_update'])->name('premium.tipe.update');

        Route::get('/premium/varian', [PremiumAdminController::class, 'varian_index'])->name('premium.varian.index');
        Route::post('/premium/varian', [PremiumAdminController::class, 'varian_store'])->name('premium.varian.store');
        Route::put('/premium/varian/{id}', [PremiumAdminController::class, 'varian_update'])->name('premium.varian.update');

        Route::get('/premium/stok', [PremiumAdminController::class, 'stok_index'])->name('premium.stok.index');
        Route::post('/premium/stok', [PremiumAdminController::class, 'stok_store'])->name('premium.stok.store');
        Route::post('/premium/stok/bulk', [PremiumAdminController::class, 'stok_bulk_store'])->name('premium.stok.bulk_store');
        Route::get('/premium/stok/detail/{id}', [PremiumAdminController::class, 'stok_decrypt'])->name('premium.stok.decrypt');

        Route::get('/premium/histori', [PremiumAdminController::class, 'histori_index'])->name('premium.histori.index');
        Route::get('/premium/laporan-admin', [LaporanController::class, 'admin_index'])->name('admin.laporan');
        Route::post('/premium/laporan-admin/{id}/status', [LaporanController::class, 'update_status'])->name('admin.laporan.status');
    });

    // Premium Customer Routes
    Route::middleware('only.customer')->group(function () {
        Route::get('/premium/katalog', [PremiumCustomerController::class, 'katalog'])->name('premium.katalog');
        Route::get('/premium/riwayat', [PremiumCustomerController::class, 'riwayat'])->name('premium.riwayat');
        Route::get('/premium/kredensial/{id_pembelian}', [PremiumCustomerController::class, 'kredensial'])->name('premium.kredensial');
        Route::get('/premium/laporan', [LaporanController::class, 'index'])->name('customer.laporan');
        Route::post('/premium/laporan', [LaporanController::class, 'store'])->name('customer.laporan.store');
    });

    Route::get('/logout', [AuthController::class, 'logout']);
});

Route::middleware('guest')->group(function () {

    Route::get('/lupa_password', function () {
        return view('auth.lupa_password');
    })->name('password.request');

    Route::post('/lupa_password', function (Request $request) {

        function statusFunction($status)
        {
            return $status;
        }

        $request->validate(['email' => 'required|email'], [
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Email tidak valid.'
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __(statusFunction('Link reset password telah berhasil dikirim melalui email. Silakan periksa email Anda.'))])
            : back()->withErrors(['email' => __(statusFunction('Email tidak ada.'))]);
    })->name('password.email');

    Route::get('/reset_password/{token}', function (string $token) {
        return view('auth.reset_password', ['token' => $token]);
    })->name('password.reset');

    Route::post('/reset_password', function (Request $request) {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:10|confirmed',
            'password_confirmation' => 'required',
        ], [
            'password.required' => 'Password harus di isi.',
            'password.min' => 'Password setidaknya minimal 10 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password_confirmation.required' => 'Konfirmasi password harus di isi.',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    })->name('password.update');

    Route::controller(AuthController::class)->group(function () {
        Route::get('/login', 'index')->name('login');
        Route::get('/pendaftaran', 'pendaftaran');
        Route::get('/redirect', 'redirect');
        Route::get('/auth/google/callback', 'callback');

        Route::post('/proses_login', 'proses_login')->middleware('throttle:limit_login');
        Route::post('/proses_pendaftaran', 'proses_pendaftaran');
        Route::post('/proses_lupa_password', 'proses_lupa_password');
    });
});

Route::resource('menu_produk', ProductController::class, ['except' => ['index', 'show']])->middleware('prevent.customer');
