@extends('layouts.app')
@section('title', $produk->nama)
@section('content')
<div class="container section">
  <div class="produk-detail">
    <div class="produk-detail-img">
      @if($produk->gambar)
        <img src="{{ Storage::url($produk->gambar) }}" alt="{{ $produk->nama }}"
             id="gambar-produk" style="cursor:pointer" onclick="rotasiGambar('gambar-produk', 90)">
      @else
        <div style="aspect-ratio:1;background:var(--gray-100);display:flex;align-items:center;justify-content:center;font-size:4rem"></div>
      @endif
      @if($produk->gambar)
      <div style="display:flex;gap:.5rem;padding:.75rem;background:var(--gray-50)">
        <button onclick="rotasiGambar('gambar-produk', 90)"  class="btn btn-ghost btn-sm">Putar</button>
        <button onclick="rotasiGambar('gambar-produk', -90)" class="btn btn-ghost btn-sm">Balik</button>
        <button onclick="resetRotasi('gambar-produk')"       class="btn btn-ghost btn-sm">Reset</button>
      </div>
      @endif
    </div>

    <div>
      <p style="color:var(--gray-500);font-size:var(--text-sm);margin-bottom:.5rem">
        <a href="{{ route('produk.index', ['kategori' => $produk->kategori_id]) }}">
          {{ $produk->kategori->nama }}
        </a>
      </p>
      <h1 style="font-size:var(--text-3xl);font-weight:800;margin-bottom:.75rem">{{ $produk->nama }}</h1>
      <p style="font-size:var(--text-3xl);font-weight:700;color:var(--primary);margin-bottom:1rem">
        Rp {{ number_format($produk->harga, 0, ',', '.') }}
      </p>

      @if($produk->stok > 0)
        <span class="badge badge-success" style="margin-bottom:1rem">Stok: {{ $produk->stok }}</span>
      @else
        <span class="badge badge-danger" style="margin-bottom:1rem">Stok habis</span>
      @endif

      @if($produk->deskripsi)
        <p style="color:var(--gray-600);line-height:1.7;margin-bottom:1.5rem">{{ $produk->deskripsi }}</p>
      @endif

      @auth
        @if($produk->stok > 0)
        <form action="{{ route('keranjang.tambah', $produk) }}" method="POST">
          @csrf
          <button type="submit" class="btn btn-primary btn-lg" onclick="mainkanEfek(sfxKlik)">
            Tambah ke Keranjang
          </button>
        </form>
        @endif
      @else
        <a href="{{ route('login') }}" class="btn btn-primary btn-lg">Login untuk Beli</a>
      @endauth
    </div>
  </div>

  @if($produkLain->count())
  <div style="margin-top:3rem">
    <h2 style="font-size:var(--text-xl);font-weight:700;margin-bottom:1rem">Produk Serupa</h2>
    <div class="produk-grid">
      @foreach($produkLain as $p)
      <div class="card">
        @if($p->gambar)
          <img src="{{ Storage::url($p->gambar) }}" alt="{{ $p->nama }}" class="card-img">
        @else
          <div class="card-img-placeholder"></div>
        @endif
        <div class="card-body">
          <h3 class="card-title">{{ $p->nama }}</h3>
          <p class="card-price">Rp {{ number_format($p->harga, 0, ',', '.') }}</p>
          <a href="{{ route('produk.show', $p) }}" class="btn btn-outline btn-sm">Lihat</a>
        </div>
      </div>
      @endforeach
    </div>
  </div>
  @endif
</div>
@endsection
