<?php
namespace App\Http\Controllers;
use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Http\Request;

class ProdukController extends Controller {
    public function landing() {
        $produkTerbaru = Produk::with('kategori')->latest()->take(8)->get();
        $kategoris = Kategori::withCount('produk')->get();
        return view('home', compact('produkTerbaru','kategoris'));
    }

    public function index(Request $request) {
        $query = Produk::with('kategori')->where('stok', '>', 0);

        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }
        if ($request->filled('cari')) {
            $query->where('nama', 'like', '%'.$request->cari.'%');
        }
        $sort = $request->get('sort', 'terbaru');
        match($sort) {
            'harga_asc'  => $query->orderBy('harga'),
            'harga_desc' => $query->orderByDesc('harga'),
            'nama_asc'   => $query->orderBy('nama'),
            default      => $query->latest(),
        };

        $produks = $query->paginate(12)->withQueryString();
        $kategoris = Kategori::all();
        return view('produk.index', compact('produks','kategoris','sort'));
    }

    public function show(Produk $produk) {
        $produkLain = Produk::where('kategori_id', $produk->kategori_id)
            ->where('id', '!=', $produk->id)->take(4)->get();
        return view('produk.show', compact('produk','produkLain'));
    }
}
