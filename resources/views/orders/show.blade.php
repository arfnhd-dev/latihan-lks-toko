@extends('layouts.app')
@section('title', 'Detail Pesanan #' . $order->id)
@section('content')
<div class="container section">
  <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1.5rem">
    <a href="{{ route('orders.index') }}" class="btn btn-ghost btn-sm">← Kembali</a>
    <h1 style="font-size:var(--text-2xl);font-weight:700">Pesanan #{{ $order->id }}</h1>
    <span class="badge status-{{ $order->status }}" style="font-size:var(--text-sm);padding:.3rem .8rem">
      {{ ucfirst($order->status) }}
    </span>
  </div>

  <div style="display:grid;grid-template-columns:1fr 300px;gap:1.5rem">
    <div>
      <div class="table-wrapper">
        <div class="table-header"><h2>Item Pesanan</h2></div>
        <table class="table">
          <thead>
            <tr><th>Produk</th><th>Harga Satuan</th><th>Jumlah</th><th>Subtotal</th></tr>
          </thead>
          <tbody>
            @foreach($order->items as $item)
            <tr>
              <td>{{ $item->nama_produk }}</td>
              <td>Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
              <td>{{ $item->jumlah }}</td>
              <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    <div>
      <div style="background:#fff;border-radius:var(--radius-lg);border:1px solid var(--gray-200);padding:1.5rem">
        <h3 style="font-weight:600;margin-bottom:1rem">Info Pesanan</h3>
        <div style="font-size:var(--text-sm);color:var(--gray-600)">
          <p style="margin-bottom:.5rem"><strong>Tanggal:</strong> {{ $order->created_at->format('d M Y, H:i') }}</p>
          <p style="margin-bottom:.5rem"><strong>Alamat:</strong></p>
          <p style="margin-bottom:1rem;padding:.75rem;background:var(--gray-50);border-radius:var(--radius)">{{ $order->alamat }}</p>
          @if($order->catatan)
          <p style="margin-bottom:.5rem"><strong>Catatan:</strong> {{ $order->catatan }}</p>
          @endif
        </div>
        <div style="border-top:1px solid var(--gray-200);padding-top:1rem;margin-top:1rem">
          <div style="display:flex;justify-content:space-between;font-weight:700;font-size:var(--text-lg)">
            <span>Total</span>
            <span style="color:var(--primary)">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
