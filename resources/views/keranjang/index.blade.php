@extends('layouts.app')
@section('title', 'Keranjang')
@section('content')
<div class="container section">
  <h1 style="font-size:var(--text-2xl);font-weight:700;margin-bottom:1.5rem">Keranjang Belanja</h1>

  @if(empty($cart))
    <div style="text-align:center;padding:4rem;color:var(--gray-400)">
      <p style="font-size:3rem;margin-bottom:1rem"></p>
      <p style="font-size:var(--text-lg)">Keranjang Anda kosong</p>
      <a href="{{ route('produk.index') }}" class="btn btn-primary" style="margin-top:1rem">Belanja Sekarang</a>
    </div>
  @else
  <div class="keranjang-layout">
    <div>
      <div class="table-wrapper">
        <table class="table">
          <thead>
            <tr>
              <th>Produk</th>
              <th>Harga</th>
              <th>Jumlah</th>
              <th>Subtotal</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach($cart as $produkId => $item)
            <tr>
              <td>
                <div style="display:flex;align-items:center;gap:.75rem">
                  @if($item['gambar'])
                    <img src="{{ Storage::url($item['gambar']) }}" class="table-img" alt="{{ $item['nama'] }}">
                  @else
                    <div class="table-img" style="background:var(--gray-100);display:flex;align-items:center;justify-content:center"></div>
                  @endif
                  <span style="font-weight:600">{{ $item['nama'] }}</span>
                </div>
              </td>
              <td>Rp {{ number_format($item['harga_satuan'], 0, ',', '.') }}</td>
              <td>
                <form action="{{ route('keranjang.ubah', $produkId) }}" method="POST" style="display:flex;align-items:center;gap:.5rem">
                  @csrf @method('PATCH')
                  <input type="number" name="jumlah" value="{{ $item['jumlah'] }}" min="1" max="99"
                         class="form-control" style="width:70px">
                  <button type="submit" class="btn btn-ghost btn-sm">Ubah</button>
                </form>
              </td>
              <td style="font-weight:600">Rp {{ number_format($item['harga_satuan'] * $item['jumlah'], 0, ',', '.') }}</td>
              <td>
                <form action="{{ route('keranjang.hapus', $produkId) }}" method="POST">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                </form>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div style="margin-top:1rem">
        <form action="{{ route('keranjang.kosongkan') }}" method="POST">
          @csrf @method('DELETE')
          <button type="submit" class="btn btn-ghost btn-sm"
                  onclick="return confirm('Kosongkan semua keranjang?')">Kosongkan Keranjang</button>
        </form>
      </div>
    </div>

    <div class="keranjang-summary">
      <h3 style="font-size:var(--text-lg);font-weight:700;margin-bottom:1rem">Ringkasan Belanja</h3>
      <div style="display:flex;justify-content:space-between;margin-bottom:.5rem;font-size:var(--text-sm)">
        <span>Total Item</span>
        <span>{{ collect($cart)->sum('jumlah') }} item</span>
      </div>
      <div style="display:flex;justify-content:space-between;padding:1rem 0;border-top:1px solid var(--gray-200);border-bottom:1px solid var(--gray-200);font-weight:700;font-size:var(--text-lg);margin:1rem 0">
        <span>Total Harga</span>
        <span style="color:var(--primary)">Rp {{ number_format($total, 0, ',', '.') }}</span>
      </div>
      <a href="{{ route('checkout') }}" class="btn btn-primary btn-block btn-lg">Checkout Sekarang</a>
      <a href="{{ route('produk.index') }}" class="btn btn-ghost btn-block" style="margin-top:.5rem">Lanjut Belanja</a>
    </div>
  </div>
  @endif
</div>
@endsection
