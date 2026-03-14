@extends('layouts.app')
@section('title', 'Beranda')
@section('content')
<div class="hero">
  <div class="container">
    <h1>Selamat Datang di TokoLKS</h1>
    <p>Temukan produk terbaik dengan harga terjangkau</p>
    <a href="{{ route('produk.index') }}" class="btn btn-primary btn-lg">Lihat Semua Produk</a>
  </div>
</div>

<section class="section">
  <div class="container">
    <h2 style="font-size:var(--text-2xl);font-weight:700;margin-bottom:1.5rem">Produk Terbaru</h2>
    <div class="produk-grid">
      @foreach($produkTerbaru as $p)
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
    <div style="text-align:center;margin-top:2rem">
      <a href="{{ route('produk.index') }}" class="btn btn-outline btn-lg">Lihat Semua Produk</a>
    </div>
  </div>
</section>

@if($kategoris->count())
<section class="section" style="background:#fff;padding:2rem 0">
  <div class="container">
    <h2 style="font-size:var(--text-2xl);font-weight:700;margin-bottom:1.5rem">Kategori</h2>
    <div style="display:flex;gap:1rem;flex-wrap:wrap">
      @foreach($kategoris as $kat)
      <a href="{{ route('produk.index', ['kategori' => $kat->id]) }}"
         style="display:flex;flex-direction:column;align-items:center;padding:1rem 1.5rem;background:var(--primary-light);border-radius:var(--radius-lg);color:var(--primary);font-weight:600;min-width:120px;text-align:center;transition:var(--transition)">
        <span style="margin-top:.5rem;font-size:var(--text-sm)">{{ $kat->nama }}</span>
        <span style="font-size:var(--text-xs);color:var(--gray-500)">{{ $kat->produk_count }} produk</span>
      </a>
      @endforeach
    </div>
  </div>
</section>
@endif
@endsection
