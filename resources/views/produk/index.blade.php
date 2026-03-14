@extends('layouts.app')
@section('title', 'Produk')
@section('content')
<div class="container section">
  <h1 style="font-size:var(--text-2xl);font-weight:700;margin-bottom:1.5rem">Semua Produk</h1>

  <form method="GET" action="{{ route('produk.index') }}">
    <div class="filter-bar">
      <div class="filter-group">
        <label>Cari Produk</label>
        <input type="text" id="search-input" name="cari" class="form-control"
               placeholder="Nama produk..." value="{{ request('cari') }}">
      </div>
      <div class="filter-group">
        <label>Kategori</label>
        <select name="kategori" class="form-control">
          <option value="">Semua Kategori</option>
          @foreach($kategoris as $kat)
            <option value="{{ $kat->id }}" {{ request('kategori') == $kat->id ? 'selected' : '' }}>
              {{ $kat->nama }}
            </option>
          @endforeach
        </select>
      </div>
      <div class="filter-group">
        <label>Urutkan</label>
        <select name="sort" class="form-control">
          <option value="terbaru"    {{ $sort === 'terbaru'    ? 'selected' : '' }}>Terbaru</option>
          <option value="harga_asc"  {{ $sort === 'harga_asc'  ? 'selected' : '' }}>Harga Terendah</option>
          <option value="harga_desc" {{ $sort === 'harga_desc' ? 'selected' : '' }}>Harga Tertinggi</option>
          <option value="nama_asc"   {{ $sort === 'nama_asc'   ? 'selected' : '' }}>Nama A-Z</option>
        </select>
      </div>
      <div class="filter-actions">
        <button type="submit" class="btn btn-primary">Terapkan</button>
        <a href="{{ route('produk.index') }}" class="btn btn-ghost">Reset</a>
      </div>
    </div>
  </form>

  @if($produks->isEmpty())
    <div style="text-align:center;padding:3rem;color:var(--gray-500)">
      <p style="font-size:var(--text-xl)">Produk tidak ditemukan</p>
    </div>
  @else
    <div class="produk-grid">
      @foreach($produks as $p)
      <div class="card">
        @if($p->gambar)
          <img src="{{ Storage::url($p->gambar) }}" alt="{{ $p->nama }}" class="card-img">
        @else
          <div class="card-img-placeholder">Tidak ada gambar</div>
        @endif
        <div class="card-body">
          <p class="card-subtitle">{{ $p->kategori->nama }}</p>
          <h3 class="card-title">{{ $p->nama }}</h3>
          <p class="card-price">Rp {{ number_format($p->harga, 0, ',', '.') }}</p>
          <p style="font-size:var(--text-xs);color:var(--gray-500);margin-bottom:.75rem">Stok: {{ $p->stok }}</p>
          <div class="card-actions">
            <a href="{{ route('produk.show', $p) }}" class="btn btn-outline btn-sm">Detail</a>
            @auth
            <form action="{{ route('keranjang.tambah', $p) }}" method="POST">
              @csrf
              <button type="submit" class="btn btn-primary btn-sm">+ Keranjang</button>
            </form>
            @endauth
          </div>
        </div>
      </div>
      @endforeach
    </div>
    <div class="pagination">
      {{ $produks->links() }}
    </div>
  @endif
</div>
@endsection
