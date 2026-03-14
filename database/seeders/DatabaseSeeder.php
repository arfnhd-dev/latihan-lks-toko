<?php
namespace Database\Seeders;
use App\Models\Kategori;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder {
    public function run(): void {
        User::create([
            'name'     => 'Admin LKS',
            'email'    => 'admin@lks.test',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);
        User::create([
            'name'     => 'User Demo',
            'email'    => 'user@lks.test',
            'password' => Hash::make('password'),
            'role'     => 'user',
        ]);

        $kategoris = [
            ['nama' => 'Elektronik',  'slug' => 'elektronik'],
            ['nama' => 'Pakaian',     'slug' => 'pakaian'],
            ['nama' => 'Makanan',     'slug' => 'makanan'],
            ['nama' => 'Olahraga',    'slug' => 'olahraga'],
        ];
        foreach ($kategoris as $k) Kategori::create($k);

        $produks = [
            ['nama' => 'Laptop Gaming ASUS', 'kategori_id' => 1, 'harga' => 15000000, 'stok' => 10, 'deskripsi' => 'Laptop gaming terbaik untuk performa tinggi.'],
            ['nama' => 'Mouse Wireless Logitech', 'kategori_id' => 1, 'harga' => 350000, 'stok' => 25, 'deskripsi' => 'Mouse wireless ergonomis.'],
            ['nama' => 'Keyboard Mechanical', 'kategori_id' => 1, 'harga' => 750000, 'stok' => 15, 'deskripsi' => 'Keyboard mechanical dengan RGB.'],
            ['nama' => 'Kaos Polos Premium', 'kategori_id' => 2, 'harga' => 85000, 'stok' => 50, 'deskripsi' => 'Kaos polos bahan cotton combed 30s.'],
            ['nama' => 'Celana Jogger', 'kategori_id' => 2, 'harga' => 145000, 'stok' => 30, 'deskripsi' => 'Celana jogger sporty dan nyaman.'],
            ['nama' => 'Jaket Hoodie', 'kategori_id' => 2, 'harga' => 275000, 'stok' => 20, 'deskripsi' => 'Hoodie tebal anti dingin.'],
            ['nama' => 'Kopi Arabika 250g', 'kategori_id' => 3, 'harga' => 65000, 'stok' => 100, 'deskripsi' => 'Kopi arabika pilihan dari pegunungan.'],
            ['nama' => 'Snack Mix Sehat', 'kategori_id' => 3, 'harga' => 35000, 'stok' => 80, 'deskripsi' => 'Camilan sehat tanpa pengawet.'],
            ['nama' => 'Dumbbell Set 10kg', 'kategori_id' => 4, 'harga' => 450000, 'stok' => 12, 'deskripsi' => 'Set dumbbell untuk latihan di rumah.'],
            ['nama' => 'Sepatu Lari Nike', 'kategori_id' => 4, 'harga' => 1200000, 'stok' => 8, 'deskripsi' => 'Sepatu lari ringan dan nyaman.'],
        ];
        foreach ($produks as $p) {
            Produk::create([
                'nama'        => $p['nama'],
                'slug'        => Str::slug($p['nama']) . '-' . rand(1000,9999),
                'kategori_id' => $p['kategori_id'],
                'harga'       => $p['harga'],
                'stok'        => $p['stok'],
                'deskripsi'   => $p['deskripsi'],
                'gambar'      => null,
            ]);
        }
    }
}
