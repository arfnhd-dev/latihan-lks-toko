<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProdukRequest;
use App\Http\Requests\UpdateProdukRequest;
use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller {
    public function index(\Illuminate\Http\Request $request) {
        $query = Produk::with('kategori');
        if ($request->filled('kategori')) $query->where('kategori_id', $request->kategori);
        if ($request->filled('cari')) $query->where('nama', 'like', '%'.$request->cari.'%');
        $sort = $request->get('sort', 'terbaru');
        match($sort) {
            'harga_asc'  => $query->orderBy('harga'),
            'harga_desc' => $query->orderByDesc('harga'),
            'nama_asc'   => $query->orderBy('nama'),
            default      => $query->latest(),
        };
        $produks = $query->paginate(15)->withQueryString();
        $kategoris = Kategori::all();
        return view('admin.produk.index', compact('produks','kategoris','sort'));
    }

    public function create() {
        $kategoris = Kategori::all();
        return view('admin.produk.create', compact('kategoris'));
    }

    public function store(StoreProdukRequest $request) {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['nama']) . '-' . time();
        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('produk', 'public');
        }
        Produk::create($data);
        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Produk $produk) {
        $kategoris = Kategori::all();
        return view('admin.produk.edit', compact('produk','kategoris'));
    }

    public function update(UpdateProdukRequest $request, Produk $produk) {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['nama']) . '-' . $produk->id;
        if ($request->hasFile('gambar')) {
            if ($produk->gambar) Storage::disk('public')->delete($produk->gambar);
            $data['gambar'] = $request->file('gambar')->store('produk', 'public');
        } else {
            unset($data['gambar']);
        }
        $produk->update($data);
        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Produk $produk) {
        if ($produk->gambar) Storage::disk('public')->delete($produk->gambar);
        $produk->delete();
        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil dihapus.');
    }
}
