<?php

use App\Livewire\Auth\ForgotPasswordPage;
use App\Livewire\Auth\LoginPage;
use App\Livewire\Auth\RegisterPage;
use App\Livewire\Auth\ResetPasswordPage;
use App\Livewire\CancelPage;
use App\Livewire\CartPage;
use App\Livewire\CheckoutPage;
use App\Livewire\DetailProdukPage;
use App\Livewire\HomePage;
use App\Livewire\KategoriPage;
use App\Livewire\MyOrderDetailPage;
use App\Livewire\MyOrderPage;
use App\Livewire\ProdukPage;
use App\Livewire\SuksesPage;
use Filament\Pages\Auth\Register;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', HomePage::class);
Route::get('/kategori', KategoriPage::class);
Route::get('/produk', ProdukPage::class);
Route::get('/cart', CartPage::class);
Route::get('/produk/{slug}', DetailProdukPage::class)->name('produk.show');

/**
 * Authentication Routes
 */
 Route::middleware('guest')->group(function () {
    Route::get('/login', LoginPage::class);
    Route::get('/register', RegisterPage::class);
    Route::get('/forgot-password',ForgotPasswordPage::class)->name('password.request');
    Route::get('/reset-password/{token}', ResetPasswordPage::class)->name('password.reset');
 });

 Route::middleware('auth')->group(function () {
    Route::get('/logout', function () {
        auth()->logout();
        return redirect('/');
    });
    Route::get('/checkout', CheckoutPage::class);
    Route::get('/my-order', MyOrderPage::class);
    Route::get('/my-order/{order}', MyOrderDetailPage::class);
    Route::get('/sukses', SuksesPage::class)->name('success');
    Route::get('/cancel', CancelPage::class)->name('cancel');
});