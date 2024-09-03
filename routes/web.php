<?php

use App\Livewire\CartPage;
use App\Livewire\CheckoutPage;
use App\Livewire\DetailProdukPage;
use App\Livewire\HomePage;
use App\Livewire\KategoriPage;
use App\Livewire\MyOrderDetailPage;
use App\Livewire\MyOrderPage;
use App\Livewire\ProdukPage;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', HomePage::class);
Route::get('/kategori', KategoriPage::class);
Route::get('/produk', ProdukPage::class);
Route::get('/cart', CartPage::class);
Route::get('/produk/{produk}', DetailProdukPage::class)->name('produk.show');
Route::get('/checkout', CheckoutPage::class);
Route::get('/my-order', MyOrderPage::class);
Route::get('/my-order/{order}', MyOrderDetailPage::class);