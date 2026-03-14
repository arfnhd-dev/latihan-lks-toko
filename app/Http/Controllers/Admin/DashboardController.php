<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Produk;
use App\Models\User;
use App\Models\Kategori;

class DashboardController extends Controller {
    public function index() {
        $totalProduk   = Produk::count();
        $totalOrder    = Order::count();
        $totalUser     = User::where('role', 'user')->count();
        $totalPendapatan = Order::where('status', 'selesai')->sum('total_harga');
        $orderTerbaru  = Order::with('user')->latest()->take(5)->get();
        $produkMenipis = Produk::where('stok', '<', 5)->latest()->take(5)->get();
        return view('admin.dashboard.index', compact(
            'totalProduk','totalOrder','totalUser','totalPendapatan',
            'orderTerbaru','produkMenipis'
        ));
    }
}
