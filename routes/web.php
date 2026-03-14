<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\ProdukController    as AdminProduk;
use App\Http\Controllers\Admin\KategoriController  as AdminKategori;
use App\Http\Controllers\Admin\OrderController     as AdminOrder;

/* Publik */
Route::get('/',          [ProdukController::class, 'landing'])->name('home');
Route::get('/produk',    [ProdukController::class, 'index'])->name('produk.index');
Route::get('/produk/{produk}', [ProdukController::class, 'show'])->name('produk.show');

/* Guest only */
Route::middleware('guest')->group(function () {
    Route::get('/login',     [LoginController::class,    'showForm'])->name('login');
    Route::post('/login',    [LoginController::class,    'login']);
    Route::get('/register',  [RegisterController::class, 'showForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/* Auth — User */
Route::middleware('auth')->group(function () {
    Route::get('/dashboard',  [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/keranjang',              [KeranjangController::class, 'index'])->name('keranjang.index');
    Route::post('/keranjang/{produk}',    [KeranjangController::class, 'tambah'])->name('keranjang.tambah');
    Route::patch('/keranjang/{produk}',   [KeranjangController::class, 'ubahJumlah'])->name('keranjang.ubah');
    Route::delete('/keranjang/{produk}',  [KeranjangController::class, 'hapus'])->name('keranjang.hapus');
    Route::delete('/keranjang',           [KeranjangController::class, 'kosongkan'])->name('keranjang.kosongkan');
    Route::get('/checkout',       [OrderController::class, 'checkout'])->name('checkout');
    Route::post('/checkout',      [OrderController::class, 'proses'])->name('checkout.proses');
    Route::get('/orders',         [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
});

/* Admin only */
Route::middleware(['auth', 'isAdmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    Route::resource('produk',   AdminProduk::class);
    Route::resource('kategori', AdminKategori::class);
    Route::get('/orders',               [AdminOrder::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}',       [AdminOrder::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [AdminOrder::class, 'ubahStatus'])->name('orders.status');
});
