<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreKategoriRequest;
use App\Models\Kategori;
use Illuminate\Support\Str;

class KategoriController extends Controller {
    public function index() {
        $kategoris = Kategori::withCount('produk')->latest()->paginate(15);
        return view('admin.kategori.index', compact('kategoris'));
    }

    public function create() {
        return view('admin.kategori.create');
    }

    public function store(StoreKategoriRequest $request) {
        $data = $request->validated();
        if (empty($data['slug'])) $data['slug'] = Str::slug($data['nama']);
        Kategori::create($data);
        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Kategori $kategori) {
        return view('admin.kategori.edit', compact('kategori'));
    }

    public function update(StoreKategoriRequest $request, Kategori $kategori) {
        $data = $request->validated();
        if (empty($data['slug'])) $data['slug'] = Str::slug($data['nama']);
        $kategori->update($data);
        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Kategori $kategori) {
        $kategori->delete();
        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
