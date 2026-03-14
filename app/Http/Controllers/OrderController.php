<?php
namespace App\Http\Controllers;
use App\Models\Order;
use App\Models\Produk;
use Illuminate\Http\Request;

class OrderController extends Controller {
    public function index() {
        $orders = Order::where('user_id', auth()->id())->with('items')->latest()->paginate(10);
        return view('orders.index', compact('orders'));
    }

    public function show(Order $order) {
        abort_if($order->user_id !== auth()->id(), 403);
        $order->load('items.produk');
        return view('orders.show', compact('order'));
    }

    public function checkout() {
        $cart = session()->get('cart', []);
        if (empty($cart)) return redirect()->route('keranjang.index')->with('error', 'Keranjang kosong.');
        $total = collect($cart)->sum(fn($item) => $item['harga_satuan'] * $item['jumlah']);
        return view('orders.checkout', compact('cart','total'));
    }

    public function proses(Request $request) {
        $request->validate([
            'alamat'  => 'required|string|min:10',
            'catatan' => 'nullable|string',
        ], [
            'alamat.required' => 'Alamat pengiriman wajib diisi.',
            'alamat.min'      => 'Alamat minimal 10 karakter.',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) return redirect()->route('keranjang.index');

        $total = collect($cart)->sum(fn($item) => $item['harga_satuan'] * $item['jumlah']);

        $order = Order::create([
            'user_id'     => auth()->id(),
            'total_harga' => $total,
            'status'      => 'pending',
            'alamat'      => $request->alamat,
            'catatan'     => $request->catatan,
        ]);

        foreach ($cart as $produkId => $item) {
            $order->items()->create([
                'produk_id'    => $produkId,
                'nama_produk'  => $item['nama'],
                'harga_satuan' => $item['harga_satuan'],
                'jumlah'       => $item['jumlah'],
            ]);
            // Kurangi stok
            Produk::where('id', $produkId)->decrement('stok', $item['jumlah']);
        }

        session()->forget('cart');
        return redirect()->route('orders.show', $order)->with('success', 'Pesanan berhasil dibuat!');
    }
}
