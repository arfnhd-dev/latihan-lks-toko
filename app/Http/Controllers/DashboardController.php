<?php
namespace App\Http\Controllers;
use App\Models\Order;

class DashboardController extends Controller {
    public function index() {
        $orders = Order::where('user_id', auth()->id())
            ->latest()->take(5)->get();
        $totalOrder = Order::where('user_id', auth()->id())->count();
        $totalBelanja = Order::where('user_id', auth()->id())
            ->where('status', 'selesai')->sum('total_harga');
        return view('dashboard.index', compact('orders','totalOrder','totalBelanja'));
    }
}
