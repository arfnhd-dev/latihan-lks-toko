@extends('layouts.admin')
@section('title', 'Kelola Produk')
@section('content')
<div class="page-header">
  <h1>Kelola Produk</h1>
  <a href="{{ route('admin.produk.create') }}" class="btn btn-primary">+ Tambah Produk</a>
</div>

<form method="GET" action="{{ route('admin.produk.index') }}">
  <div class="filter-bar">
    <div class="filter-group">
      <label>Cari</label>
      <input type="text" name="cari" class="form-control" placeholder="Nama produk..." value="{{ request('cari') }}">
    </div>
    <div class="filter-group">
      <label>Kategori</label>
      <select name="kategori" class="form-control">
        <option value="">Semua</option>
        @foreach($kategoris as $kat)
          <option value="{{ $kat->id }}" {{ request('kategori') == $kat->id ? 'selected' : '' }}>{{ $kat->nama }}</option>
        @endforeach
      </select>
    </div>
    <div class="filter-group">
      <label>Urutkan</label>
      <select name="sort" class="form-control">
        <option value="terbaru"    {{ $sort === 'terbaru'    ? 'selected' : '' }}>Terbaru</option>
        <option value="harga_asc"  {{ $sort === 'harga_asc'  ? 'selected' : '' }}>Harga ↑</option>
        <option value="harga_desc" {{ $sort === 'harga_desc' ? 'selected' : '' }}>Harga ↓</option>
        <option value="nama_asc"   {{ $sort === 'nama_asc'   ? 'selected' : '' }}>Nama A-Z</option>
      </select>
    </div>
    <div class="filter-actions">
      <button type="submit" class="btn btn-primary">Filter</button>
      <a href="{{ route('admin.produk.index') }}" class="btn btn-ghost">Reset</a>
    </div>
  </div>
</form>

<div class="table-wrapper">
  <table class="table">
    <thead>
      <tr><th>Gambar</th><th>Nama</th><th>Kategori</th><th>Harga</th><th>Stok</th><th>Aksi</th></tr>
    </thead>
    <tbody>
      @forelse($produks as $p)
      <tr>
        <td>
          @if($p->gambar)
            <img src="{{ Storage::url($p->gambar) }}" class="table-img" alt="{{ $p->nama }}">
          @else
            <div class="table-img" style="background:var(--gray-100);display:flex;align-items:center;justify-content:center"></div>
          @endif
        </td>
        <td style="font-weight:600">{{ $p->nama }}</td>
        <td>{{ $p->kategori->nama }}</td>
        <td>Rp {{ number_format($p->harga, 0, ',', '.') }}</td>
        <td>
          @if($p->stok > 10)
            <span class="badge badge-success">{{ $p->stok }}</span>
          @elseif($p->stok > 0)
            <span class="badge badge-warning">{{ $p->stok }}</span>
          @else
            <span class="badge badge-danger">Habis</span>
          @endif
        </td>
        <td>
          <div style="display:flex;gap:.5rem">
            <a href="{{ route('admin.produk.edit', $p) }}" class="btn btn-outline btn-sm">Edit</a>
            <form id="form-hapus-{{ $p->id }}" action="{{ route('admin.produk.destroy', $p) }}" method="POST">
              @csrf @method('DELETE')
              <button type="button" class="btn btn-danger btn-sm"
                      onclick="konfirmasiHapus('form-hapus-{{ $p->id }}', 'Hapus produk {{ addslashes($p->nama) }}?')">Hapus</button>
            </form>
          </div>
        </td>
      </tr>
      @empty
      <tr><td colspan="6" style="text-align:center;color:var(--gray-400);padding:2rem">Belum ada produk</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
<div class="pagination">{{ $produks->links() }}</div>
@endsection
