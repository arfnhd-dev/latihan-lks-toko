@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<div class="container section">
  <h1 style="font-size:var(--text-2xl);font-weight:700;margin-bottom:1.5rem">
    Halo, {{ auth()->user()->name }}!
  </h1>

  <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;margin-bottom:2rem">
    <div class="stat-card">
      <div class="stat-icon blue"></div>
      <div>
        <div class="stat-value">{{ $totalOrder }}</div>
        <div class="stat-label">Total Pesanan</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon green"></div>
      <div>
        <div class="stat-value">Rp {{ number_format($totalBelanja, 0, ',', '.') }}</div>
        <div class="stat-label">Total Belanja Selesai</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon amber"></div>
      <div>
        @php $jumlah = collect(session('cart', []))->sum('jumlah'); @endphp
        <div class="stat-value">{{ $jumlah }}</div>
        <div class="stat-label">Item di Keranjang</div>
      </div>
    </div>
  </div>

  <div class="table-wrapper">
    <div class="table-header">
      <h2>Pesanan Terbaru</h2>
      <a href="{{ route('orders.index') }}" class="btn btn-outline btn-sm">Lihat Semua</a>
    </div>
    <table class="table">
      <thead>
        <tr>
          <th>#ID</th>
          <th>Total</th>
          <th>Status</th>
          <th>Tanggal</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($orders as $order)
        <tr>
          <td>#{{ $order->id }}</td>
          <td>Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
          <td><span class="badge status-{{ $order->status }}">{{ ucfirst($order->status) }}</span></td>
          <td>{{ $order->created_at->format('d/m/Y') }}</td>
          <td><a href="{{ route('orders.show', $order) }}" class="btn btn-ghost btn-sm">Detail</a></td>
        </tr>
        @empty
        <tr><td colspan="5" style="text-align:center;color:var(--gray-400)">Belum ada pesanan</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
