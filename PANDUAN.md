# Panduan Membuat Toko Online Laravel — LKS Web Technology
> Stack: Laravel + Blade | Auth Manual | CSS Manual (tanpa Bootstrap/Tailwind)
> Untuk pemula yang ingin belajar membangun project lomba dari nol.

---

## Daftar Isi
1. [Persiapan](#1-persiapan)
2. [Buat Project Laravel](#2-buat-project-laravel)
3. [Konfigurasi .env](#3-konfigurasi-env)
4. [Buat Database](#4-buat-database)
5. [Migrations](#5-migrations)
6. [Models](#6-models)
7. [Middleware](#7-middleware)
8. [Form Request (Validasi)](#8-form-request-validasi)
9. [Controllers](#9-controllers)
10. [Routes](#10-routes)
11. [CSS Manual](#11-css-manual)
12. [JavaScript](#12-javascript)
13. [Layouts & Partials](#13-layouts--partials)
14. [Views](#14-views)
15. [Seeder (Data Dummy)](#15-seeder-data-dummy)
16. [Jalankan Project](#16-jalankan-project)
17. [Urutan Pengerjaan Lomba](#17-urutan-pengerjaan-lomba)

---

## 1. Persiapan

Pastikan sudah terinstall:
- **PHP 8.2+** — cek dengan `php -v`
- **Composer** — cek dengan `composer -v`
- **MySQL** — bisa lewat XAMPP, Laragon, atau Herd
- **Laravel Herd** (opsional, rekomendasi untuk Mac) — otomatis handle `.test` domain

---

## 2. Buat Project Laravel

```bash
composer create-project laravel/laravel lks-toko
cd lks-toko
```

Kalau pakai Herd, taruh folder project di dalam folder yang sudah di-link Herd (biasanya `~/Herd/`).

---

## 3. Konfigurasi .env

Buka file `.env` di root project, ubah bagian ini:

```env
APP_NAME=TokoLKS
APP_URL=http://lks-toko.test

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lks_toko
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=file
```

> **Kenapa SESSION_DRIVER=file?**
> Lebih simpel dan tidak perlu tabel tambahan. Data session disimpan di `storage/framework/sessions/`.

---

## 4. Buat Database

Buka MySQL (lewat phpMyAdmin, TablePlus, atau terminal), lalu jalankan:

```sql
CREATE DATABASE lks_toko CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Atau lewat terminal (jika MySQL sudah di PATH):

```bash
mysql -u root -e "CREATE DATABASE lks_toko CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

---

## 5. Migrations

Migrations adalah file yang mendefinisikan struktur tabel database.

### Lokasi
Semua file migration ada di `database/migrations/`.

### Cara Buat Migration Baru

```bash
php artisan make:migration create_kategoris_table
php artisan make:migration create_produks_table
php artisan make:migration create_orders_table
php artisan make:migration create_order_items_table
```

### Struktur Setiap Migration

**`create_kategoris_table`**
```php
public function up(): void {
    Schema::create('kategoris', function (Blueprint $table) {
        $table->id();
        $table->string('nama', 100);
        $table->string('slug', 110)->unique();
        $table->timestamps();
    });
}
```

**`create_produks_table`**
```php
public function up(): void {
    Schema::create('produks', function (Blueprint $table) {
        $table->id();
        $table->foreignId('kategori_id')->constrained('kategoris')->cascadeOnDelete();
        $table->string('nama', 255);
        $table->string('slug', 260)->unique();
        $table->text('deskripsi')->nullable();
        $table->integer('harga');        // pakai integer, BUKAN decimal/float
        $table->integer('stok')->default(0);
        $table->string('gambar', 255)->nullable();
        $table->timestamps();
    });
}
```

**`create_orders_table`**
```php
public function up(): void {
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users');
        $table->integer('total_harga');
        $table->enum('status', ['pending','proses','dikirim','selesai','batal'])->default('pending');
        $table->text('alamat');
        $table->text('catatan')->nullable();
        $table->timestamps();
    });
}
```

**`create_order_items_table`**
```php
public function up(): void {
    Schema::create('order_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
        $table->foreignId('produk_id')->constrained('produks');
        $table->string('nama_produk', 255);  // snapshot nama saat beli
        $table->integer('harga_satuan');      // snapshot harga saat beli
        $table->integer('jumlah');
        $table->timestamps();
    });
}
```

**Update `users` migration** — tambah kolom `role` setelah `password`:
```php
$table->enum('role', ['admin','user'])->default('user');
```

### Jalankan Migration
```bash
php artisan migrate
```

---

## 6. Models

Model adalah representasi tabel di PHP. Taruh di `app/Models/`.

### Cara Buat Model
```bash
php artisan make:model Kategori
php artisan make:model Produk
php artisan make:model Order
php artisan make:model OrderItem
```

### Isi Setiap Model

**`app/Models/Kategori.php`**
```php
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model {
    protected $fillable = ['nama', 'slug'];

    // Relasi: satu kategori punya banyak produk
    public function produk() {
        return $this->hasMany(Produk::class, 'kategori_id');
    }
}
```

**`app/Models/Produk.php`**
```php
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model {
    protected $fillable = ['kategori_id','nama','slug','deskripsi','harga','stok','gambar'];

    public function kategori() {
        return $this->belongsTo(Kategori::class);
    }
    public function orderItems() {
        return $this->hasMany(OrderItem::class);
    }
}
```

**`app/Models/Order.php`**
```php
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Order extends Model {
    protected $fillable = ['user_id','total_harga','status','alamat','catatan'];

    public function user() { return $this->belongsTo(User::class); }
    public function items() { return $this->hasMany(OrderItem::class); }
}
```

**`app/Models/OrderItem.php`**
```php
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model {
    protected $fillable = ['order_id','produk_id','nama_produk','harga_satuan','jumlah'];

    public function order() { return $this->belongsTo(Order::class); }
    public function produk() { return $this->belongsTo(Produk::class); }

    // Accessor: hitung subtotal otomatis
    public function getSubtotalAttribute() {
        return $this->harga_satuan * $this->jumlah;
    }
}
```

**Update `app/Models/User.php`** — tambah `role` ke fillable dan relasi orders:
```php
protected $fillable = ['name', 'email', 'password', 'role'];

public function orders() {
    return $this->hasMany(Order::class);
}
```

---

## 7. Middleware

Middleware adalah penjaga pintu — mengecek kondisi sebelum request masuk ke controller.

### Buat Middleware IsAdmin
```bash
php artisan make:middleware IsAdmin
```

**`app/Http/Middleware/IsAdmin.php`**
```php
<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;

class IsAdmin {
    public function handle(Request $request, Closure $next) {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }
        return $next($request);
    }
}
```

### Daftarkan Middleware

Buka `bootstrap/app.php`, tambahkan di dalam `withMiddleware`:

```php
$middleware->alias([
    'isAdmin' => \App\Http\Middleware\IsAdmin::class,
]);
```

---

## 8. Form Request (Validasi)

Form Request memisahkan logika validasi dari controller agar lebih rapi.

```bash
php artisan make:request StoreProdukRequest
php artisan make:request UpdateProdukRequest
php artisan make:request StoreKategoriRequest
```

**`app/Http/Requests/StoreProdukRequest.php`**
```php
<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class StoreProdukRequest extends FormRequest {
    public function authorize() { return true; }

    public function rules() {
        return [
            'nama'        => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
            'deskripsi'   => 'nullable|string',
            'harga'       => 'required|integer|min:0',
            'stok'        => 'required|integer|min:0',
            'gambar'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];
    }

    public function messages() {
        return [
            'nama.required'        => 'Nama produk wajib diisi.',
            'kategori_id.required' => 'Kategori wajib dipilih.',
            'harga.required'       => 'Harga wajib diisi.',
            'gambar.image'         => 'File harus berupa gambar.',
            'gambar.max'           => 'Ukuran gambar maksimal 2MB.',
        ];
    }
}
```

> `UpdateProdukRequest` isinya sama persis dengan `StoreProdukRequest`.

---

## 9. Controllers

### Struktur Folder Controller
```
app/Http/Controllers/
├── Auth/
│   ├── LoginController.php
│   └── RegisterController.php
├── Admin/
│   ├── DashboardController.php
│   ├── ProdukController.php
│   ├── KategoriController.php
│   └── OrderController.php
├── DashboardController.php
├── ProdukController.php
├── KeranjangController.php
└── OrderController.php
```

Buat semua dengan artisan:
```bash
php artisan make:controller Auth/LoginController
php artisan make:controller Auth/RegisterController
php artisan make:controller Admin/DashboardController
php artisan make:controller Admin/ProdukController
php artisan make:controller Admin/KategoriController
php artisan make:controller Admin/OrderController
php artisan make:controller DashboardController
php artisan make:controller ProdukController
php artisan make:controller KeranjangController
php artisan make:controller OrderController
```

### Poin Penting di Setiap Controller

**LoginController** — `Auth\LoginController`
```php
// Login manual: cek email+password, redirect sesuai role
public function login(Request $request) {
    $request->validate(['email' => 'required|email', 'password' => 'required']);

    if (Auth::attempt($request->only('email','password'))) {
        $request->session()->regenerate();
        return auth()->user()->role === 'admin'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('dashboard');
    }
    return back()->withErrors(['email' => 'Email atau password salah.']);
}

// Logout: wajib invalidate session
public function logout(Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('home');
}
```

**RegisterController** — password WAJIB di-hash
```php
public function register(Request $request) {
    $request->validate([
        'name'     => 'required|string|max:100',
        'email'    => 'required|email|unique:users,email',
        'password' => 'required|min:6|confirmed',
    ]);

    $user = User::create([
        'name'     => $request->name,
        'email'    => $request->email,
        'password' => Hash::make($request->password), // WAJIB Hash::make()
        'role'     => 'user',
    ]);

    Auth::login($user);
    return redirect()->route('dashboard');
}
```

**KeranjangController** — keranjang berbasis session
```php
// Keranjang disimpan di session, bukan database
public function tambah(Request $request, Produk $produk) {
    $cart = session()->get('cart', []);
    $id   = $produk->id;

    if (isset($cart[$id])) {
        $cart[$id]['jumlah'] += 1;
    } else {
        $cart[$id] = [
            'nama'         => $produk->nama,
            'harga_satuan' => $produk->harga,
            'jumlah'       => 1,
            'gambar'       => $produk->gambar,
        ];
    }
    session()->put('cart', $cart);
    return back()->with('success', "{$produk->nama} ditambahkan ke keranjang!");
}
```

**Admin/ProdukController** — upload gambar
```php
public function store(StoreProdukRequest $request) {
    $data = $request->validated();
    $data['slug'] = Str::slug($data['nama']) . '-' . time();

    if ($request->hasFile('gambar')) {
        // Simpan ke storage/app/public/produk/
        $data['gambar'] = $request->file('gambar')->store('produk', 'public');
    }
    Produk::create($data);
    return redirect()->route('admin.produk.index')->with('success', 'Produk ditambahkan.');
}

public function update(UpdateProdukRequest $request, Produk $produk) {
    $data = $request->validated();
    if ($request->hasFile('gambar')) {
        // Hapus gambar lama sebelum upload baru
        if ($produk->gambar) Storage::disk('public')->delete($produk->gambar);
        $data['gambar'] = $request->file('gambar')->store('produk', 'public');
    } else {
        unset($data['gambar']); // jangan timpa dengan null
    }
    $produk->update($data);
    return redirect()->route('admin.produk.index')->with('success', 'Produk diperbarui.');
}

public function destroy(Produk $produk) {
    if ($produk->gambar) Storage::disk('public')->delete($produk->gambar);
    $produk->delete();
    return redirect()->route('admin.produk.index')->with('success', 'Produk dihapus.');
}
```

---

## 10. Routes

Semua route ada di `routes/web.php`.

```php
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

// Publik (bisa diakses siapa saja)
Route::get('/',                [ProdukController::class, 'landing'])->name('home');
Route::get('/produk',          [ProdukController::class, 'index'])->name('produk.index');
Route::get('/produk/{produk}', [ProdukController::class, 'show'])->name('produk.show');

// Guest only (hanya untuk yang belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login',     [LoginController::class,    'showForm'])->name('login');
    Route::post('/login',    [LoginController::class,    'login']);
    Route::get('/register',  [RegisterController::class, 'showForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// Logout (bisa diakses siapa saja yang sudah login)
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Auth — hanya untuk yang sudah login
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Keranjang
    Route::get('/keranjang',             [KeranjangController::class, 'index'])->name('keranjang.index');
    Route::post('/keranjang/{produk}',   [KeranjangController::class, 'tambah'])->name('keranjang.tambah');
    Route::patch('/keranjang/{produk}',  [KeranjangController::class, 'ubahJumlah'])->name('keranjang.ubah');
    Route::delete('/keranjang/{produk}', [KeranjangController::class, 'hapus'])->name('keranjang.hapus');
    Route::delete('/keranjang',          [KeranjangController::class, 'kosongkan'])->name('keranjang.kosongkan');

    // Order
    Route::get('/checkout',       [OrderController::class, 'checkout'])->name('checkout');
    Route::post('/checkout',      [OrderController::class, 'proses'])->name('checkout.proses');
    Route::get('/orders',         [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
});

// Admin only — harus login DAN role admin
Route::middleware(['auth', 'isAdmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    Route::resource('produk',   AdminProduk::class);
    Route::resource('kategori', AdminKategori::class);
    Route::get('/orders',                  [AdminOrder::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}',          [AdminOrder::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [AdminOrder::class, 'ubahStatus'])->name('orders.status');
});
```

---

## 11. CSS Manual

> DILARANG pakai Bootstrap, Tailwind, atau framework CSS apapun.

### Buat 4 file di `public/css/`

| File | Isi |
|------|-----|
| `app.css` | CSS variables (warna, font, shadow) + reset |
| `layout.css` | Navbar, sidebar, footer, grid produk |
| `components.css` | Tombol, card, form, tabel, badge, modal |
| `pages.css` | Style spesifik per halaman (hero, auth, dll) |

### CSS Variables Wajib (di `app.css`)

```css
:root {
  --primary:       #2563EB;
  --primary-dark:  #1D4ED8;
  --primary-light: #DBEAFE;
  --danger:        #DC2626;
  --success:       #16A34A;
  --warning:       #D97706;

  --gray-100: #F3F4F6;
  --gray-200: #E5E7EB;
  --gray-500: #6B7280;
  --gray-800: #1F2937;
  --gray-900: #111827;

  --radius:    8px;
  --shadow:    0 2px 8px rgba(0,0,0,.10);
  --transition: all 0.25s ease;

  --sidebar-w: 240px;
  --navbar-h:  64px;
}
```

### Tip CSS Penting

**Footer selalu di bawah:**
```css
body {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}
main { flex: 1; }
```

**Grid produk responsif:**
```css
.produk-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr); /* desktop: 4 kolom */
  gap: 1.25rem;
}
@media (max-width: 1100px) { .produk-grid { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 768px)  { .produk-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 480px)  { .produk-grid { grid-template-columns: 1fr; } }
```

**Layout Admin (sidebar + konten):**
```css
.admin-body  { display: flex; min-height: 100vh; }
.sidebar     { width: 240px; position: fixed; height: 100vh; }
.admin-main  { margin-left: 240px; flex: 1; }
```

---

## 12. JavaScript

Semua JS ada di `public/js/app.js`. Fitur wajib:

```javascript
// 1. Toggle menu hamburger (mobile)
function toggleMenu() {
  document.getElementById('navbar-menu').classList.toggle('open');
}

// 2. Toggle sidebar admin (mobile)
function toggleSidebar() {
  document.getElementById('sidebar').classList.toggle('open');
}

// 3. Buka/tutup modal
function bukaModal(id) { document.getElementById(id).classList.add('open'); }
function tutupModal(id) { document.getElementById(id).classList.remove('open'); }

// 4. Keyboard event: Escape tutup modal, Ctrl+/ fokus search
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') {
    document.querySelectorAll('.modal-overlay.open')
      .forEach(m => m.classList.remove('open'));
  }
  if (e.ctrlKey && e.key === '/') {
    e.preventDefault();
    const s = document.getElementById('search-input');
    if (s) { s.focus(); s.select(); }
  }
});

// 5. Mouse tracking (koordinat di footer)
document.addEventListener('mousemove', function(e) {
  const el = document.getElementById('mouse-tracker');
  if (el) el.textContent = `X: ${e.clientX}  Y: ${e.clientY}`;
});

// 6. Preview gambar sebelum upload
function previewGambar(input, previewId) {
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = e => {
      const prev = document.getElementById(previewId);
      if (prev) { prev.src = e.target.result; prev.style.display = 'block'; }
    };
    reader.readAsDataURL(input.files[0]);
  }
}

// 7. Konfirmasi hapus
function konfirmasiHapus(formId, pesan) {
  if (confirm(pesan || 'Yakin ingin menghapus?')) {
    document.getElementById(formId).submit();
  }
}

// 8. Rotasi gambar
let sudutRotasi = 0;
function rotasiGambar(elId, derajat) {
  sudutRotasi += derajat;
  const el = document.getElementById(elId);
  if (el) el.style.transform = `rotate(${sudutRotasi}deg)`;
}

// 9. Animasi kartu produk saat scroll
document.addEventListener('DOMContentLoaded', function() {
  const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.style.animation = 'fadeInUp 0.4s ease forwards';
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.1 });

  document.querySelectorAll('.card').forEach(card => {
    card.style.opacity = '0';
    observer.observe(card);
  });
});

// 10. Audio efek
const sfxKlik   = new Audio('/sounds/klik.mp3');
const sfxSukses = new Audio('/sounds/sukses.mp3');
function mainkanEfek(audio) {
  audio.currentTime = 0;
  audio.play().catch(() => {});
}
```

---

## 13. Layouts & Partials

### Struktur Wajib

```
resources/views/
├── layouts/
│   ├── app.blade.php      ← layout publik
│   └── admin.blade.php    ← layout admin
└── partials/
    ├── navbar.blade.php
    ├── footer.blade.php
    ├── admin-sidebar.blade.php
    └── admin-topbar.blade.php
```

### Layout Publik (`layouts/app.blade.php`)

```blade
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Toko') — TokoLKS</title>
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
  <link rel="stylesheet" href="{{ asset('css/components.css') }}">
  <link rel="stylesheet" href="{{ asset('css/pages.css') }}">
  @stack('styles')
</head>
<body class="page-wrapper">      {{-- page-wrapper untuk footer selalu di bawah --}}

  @include('partials.navbar')

  {{-- Flash message global --}}
  @if(session('success'))
    <div class="container" style="padding-top:1rem">
      <div class="alert alert-success">{{ session('success') }}</div>
    </div>
  @endif
  @if(session('error'))
    <div class="container" style="padding-top:1rem">
      <div class="alert alert-danger">{{ session('error') }}</div>
    </div>
  @endif

  <main>@yield('content')</main>

  @include('partials.footer')

  <script src="{{ asset('js/app.js') }}"></script>
  @stack('scripts')
</body>
</html>
```

### Layout Admin (`layouts/admin.blade.php`)

```blade
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin — @yield('title') | TokoLKS</title>
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
  <link rel="stylesheet" href="{{ asset('css/components.css') }}">
  <link rel="stylesheet" href="{{ asset('css/pages.css') }}">
  @stack('styles')
</head>
<body class="admin-body">

  @include('partials.admin-sidebar')

  <div class="admin-main">
    @include('partials.admin-topbar')
    <main class="admin-content">
      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif
      @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
      @endif
      @yield('content')
    </main>
  </div>

  <script src="{{ asset('js/app.js') }}"></script>
  @stack('scripts')
</body>
</html>
```

### Cara Pakai Layout di View

```blade
@extends('layouts.app')          {{-- pakai layout publik --}}
@section('title', 'Judul Halaman')

@section('content')
  <div class="container section">
    <h1>Isi halaman di sini</h1>
  </div>
@endsection
```

---

## 14. Views

### Struktur Folder Views

```
resources/views/
├── home.blade.php
├── auth/
│   ├── login.blade.php
│   └── register.blade.php
├── produk/
│   ├── index.blade.php
│   └── show.blade.php
├── keranjang/
│   └── index.blade.php
├── orders/
│   ├── index.blade.php
│   ├── show.blade.php
│   └── checkout.blade.php
├── dashboard/
│   └── index.blade.php
└── admin/
    ├── dashboard/index.blade.php
    ├── produk/{index,create,edit}.blade.php
    ├── kategori/{index,create,edit}.blade.php
    └── orders/{index,show}.blade.php
```

### Tips Penting di View

**Tampilkan error validasi:**
```blade
<input type="text" name="nama"
       class="form-control {{ $errors->has('nama') ? 'is-invalid' : '' }}"
       value="{{ old('nama') }}">
@error('nama')
  <span class="invalid-feedback">{{ $message }}</span>
@enderror
```

**Form edit/hapus wajib pakai `@method`:**
```blade
{{-- Edit --}}
<form action="{{ route('admin.produk.update', $produk) }}" method="POST">
  @csrf
  @method('PUT')
  ...
</form>

{{-- Hapus --}}
<form action="{{ route('admin.produk.destroy', $produk) }}" method="POST">
  @csrf
  @method('DELETE')
  ...
</form>
```

**Tampilkan gambar dari storage:**
```blade
@if($produk->gambar)
  <img src="{{ Storage::url($produk->gambar) }}" alt="{{ $produk->nama }}">
@else
  <div class="card-img-placeholder">Tidak ada gambar</div>
@endif
```

**Format harga Rupiah:**
```blade
Rp {{ number_format($produk->harga, 0, ',', '.') }}
```

**Cek role di view:**
```blade
@auth
  @if(auth()->user()->role === 'admin')
    <a href="{{ route('admin.dashboard') }}">Admin Panel</a>
  @endif
@endauth
```

---

## 15. Seeder (Data Dummy)

Buka `database/seeders/DatabaseSeeder.php`:

```php
<?php
namespace Database\Seeders;
use App\Models\{Kategori, Produk, User};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder {
    public function run(): void {
        // Akun admin
        User::create([
            'name'     => 'Admin',
            'email'    => 'admin@lks.test',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        // Akun user biasa
        User::create([
            'name'     => 'User Demo',
            'email'    => 'user@lks.test',
            'password' => Hash::make('password'),
            'role'     => 'user',
        ]);

        // Kategori
        $kat1 = Kategori::create(['nama' => 'Elektronik', 'slug' => 'elektronik']);
        $kat2 = Kategori::create(['nama' => 'Pakaian',    'slug' => 'pakaian']);

        // Produk
        Produk::create([
            'nama'        => 'Laptop Gaming',
            'slug'        => 'laptop-gaming-001',
            'kategori_id' => $kat1->id,
            'harga'       => 15000000,
            'stok'        => 10,
            'deskripsi'   => 'Laptop gaming performa tinggi.',
        ]);
    }
}
```

Jalankan seeder:
```bash
php artisan db:seed
```

Atau migrate ulang + seeder sekaligus:
```bash
php artisan migrate:fresh --seed
```

---

## 16. Jalankan Project

### Pertama kali setup:
```bash
# 1. Install dependencies
composer install

# 2. Copy .env
cp .env.example .env

# 3. Generate app key
php artisan key:generate

# 4. Atur .env (DB, APP_URL, dll)

# 5. Migrate + seeder
php artisan migrate --seed

# 6. Link storage (untuk gambar upload)
php artisan storage:link
```

### Jalankan server:

**Pakai Herd** — otomatis jalan, buka `http://nama-folder.test`

**Pakai artisan:**
```bash
php artisan serve
# Buka http://127.0.0.1:8000
```

### Akun default:
| Role | Email | Password |
|------|-------|----------|
| Admin | admin@lks.test | password |
| User | user@lks.test | password |

---

## 17. Urutan Pengerjaan Lomba

Ikuti urutan ini supaya tidak bingung dan tidak bolak-balik:

```
00:00 – 00:20  Baca soal, identifikasi tabel, role, fitur
00:20 – 00:35  Setup .env, buat database, migrate, storage:link, 4 file CSS dasar
00:35 – 01:15  Semua migration + Model + fillable + relasi
01:15 – 01:45  Auth manual: Register, Login, Logout, Middleware IsAdmin
01:45 – 02:00  Layout app.blade.php + admin.blade.php + semua partial
02:00 – 03:30  CRUD Admin Produk (index+filter, create, edit, delete+gambar)
03:30 – 04:00  CRUD Admin Kategori
04:00 – 04:30  Form Request validasi semua fitur
04:30 – 05:00  Fitur publik: halaman produk, detail, keranjang session
05:00 – 05:30  Checkout → order + order_items, riwayat user
05:30 – 06:15  Fitur JS wajib (keyboard, mouse, audio, rotasi, animasi)
06:15 – 06:45  Admin order: list + ubah status
06:45 – 07:15  Polish CSS responsif, test semua halaman
07:15 – 08:00  Checklist + bugfix + backup
```

### Checklist Sebelum Submit

- [ ] `@csrf` ada di **semua** form
- [ ] `@method('PUT')` / `@method('DELETE')` ada di form edit/hapus
- [ ] Password register di-hash dengan `Hash::make()`
- [ ] Upload gambar produk berjalan, gambar lama dihapus saat update
- [ ] `php artisan storage:link` sudah dijalankan
- [ ] Middleware `isAdmin` aktif di semua route admin
- [ ] Filter + sort + pagination di halaman produk dan admin
- [ ] Keranjang session berfungsi (tambah, ubah jumlah, hapus)
- [ ] Checkout menyimpan order + order_items + kurangi stok
- [ ] Admin bisa ubah status pesanan
- [ ] CSS responsif: 4 kolom desktop → 3 tablet → 2 mobile → 1 kecil
- [ ] Fitur JS: keyboard event, mouse tracking, rotasi gambar, animasi kartu
- [ ] Footer selalu di bawah (body pakai flexbox `min-height: 100vh`)
- [ ] `APP_URL` di `.env` sesuai dengan domain yang dipakai

---

*Panduan ini dibuat untuk LKS Web Technology 2026 — Kabupaten Klaten*
