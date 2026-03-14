<?php
namespace App\Http\Controllers;
use App\Models\Produk;
use Illuminate\Http\Request;

class KeranjangController extends Controller {
    public function index() {
        $cart = session()->get('cart', []);
        $total = collect($cart)->sum(fn($item) => $item['harga_satuan'] * $item['jumlah']);
        return view('keranjang.index', compact('cart','total'));
    }

    public function tambah(Request $request, Produk $produk) {
        if ($produk->stok <= 0) {
            return back()->with('error', 'Stok produk habis.');
        }
        $cart = session()->get('cart', []);
        $id = $produk->id;
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

    public function ubahJumlah(Request $request, Produk $produk) {
        $cart = session()->get('cart', []);
        $id = $produk->id;
        $jumlah = (int) $request->jumlah;
        if ($jumlah <= 0) {
            unset($cart[$id]);
        } else {
            $cart[$id]['jumlah'] = min($jumlah, $produk->stok);
        }
        session()->put('cart', $cart);
        return back()->with('success', 'Keranjang diperbarui.');
    }

    public function hapus(Produk $produk) {
        $cart = session()->get('cart', []);
        unset($cart[$produk->id]);
        session()->put('cart', $cart);
        return back()->with('success', 'Item dihapus dari keranjang.');
    }

    public function kosongkan() {
        session()->forget('cart');
        return back()->with('success', 'Keranjang dikosongkan.');
    }
}
