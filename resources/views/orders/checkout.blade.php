@extends('layouts.app')
@section('title', 'Checkout')
@section('content')
<div class="container section">
  <h1 style="font-size:var(--text-2xl);font-weight:700;margin-bottom:1.5rem">Checkout</h1>

  <div style="display:grid;grid-template-columns:1fr 360px;gap:1.5rem;align-items:start">
    <div>
      <div style="background:#fff;border-radius:var(--radius-lg);border:1px solid var(--gray-200);padding:1.5rem;margin-bottom:1.5rem">
        <h3 style="font-weight:600;margin-bottom:1rem">Ringkasan Pesanan</h3>
        @foreach($cart as $item)
        <div style="display:flex;justify-content:space-between;padding:.5rem 0;border-bottom:1px solid var(--gray-100);font-size:var(--text-sm)">
          <span>{{ $item['nama'] }} × {{ $item['jumlah'] }}</span>
          <span>Rp {{ number_format($item['harga_satuan'] * $item['jumlah'], 0, ',', '.') }}</span>
        </div>
        @endforeach
        <div style="display:flex;justify-content:space-between;padding:.75rem 0;font-weight:700">
          <span>Total</span>
          <span style="color:var(--primary)">Rp {{ number_format($total, 0, ',', '.') }}</span>
        </div>
      </div>
    </div>

    <div style="background:#fff;border-radius:var(--radius-lg);border:1px solid var(--gray-200);padding:1.5rem">
      <h3 style="font-weight:600;margin-bottom:1rem">Informasi Pengiriman</h3>
      <form action="{{ route('checkout.proses') }}" method="POST">
        @csrf
        <div class="form-group">
          <label class="form-label">Alamat Pengiriman <span class="required">*</span></label>
          <textarea name="alamat" class="form-control {{ $errors->has('alamat') ? 'is-invalid' : '' }}"
                    rows="4" placeholder="Masukkan alamat lengkap pengiriman...">{{ old('alamat') }}</textarea>
          @error('alamat') <span class="invalid-feedback">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
          <label class="form-label">Catatan (opsional)</label>
          <textarea name="catatan" class="form-control" rows="2" placeholder="Catatan untuk penjual...">{{ old('catatan') }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary btn-block btn-lg" onclick="mainkanEfek(sfxSukses)">
          Buat Pesanan
        </button>
      </form>
    </div>
  </div>
</div>
@endsection
