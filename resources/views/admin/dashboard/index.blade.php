@extends('layouts.admin')
@section('title', 'Dashboard Admin')
@section('content')
<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-icon blue"></div>
    <div>
      <div class="stat-value">{{ $totalProduk }}</div>
      <div class="stat-label">Total Produk</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon amber"></div>
    <div>
      <div class="stat-value">{{ $totalOrder }}</div>
      <div class="stat-label">Total Pesanan</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon green"></div>
    <div>
      <div class="stat-value">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
      <div class="stat-label">Pendapatan Selesai</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon red"></div>
    <div>
      <div class="stat-value">{{ $totalUser }}</div>
      <div class="stat-label">Total Pelanggan</div>
    </div>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem">
  <div class="table-wrapper">
    <div class="table-header">
      <h2>Pesanan Terbaru</h2>
      <a href="{{ route('admin.orders.index') }}" class="btn btn-outline btn-sm">Lihat Semua</a>
    </div>
    <table class="table">
      <thead><tr><th>#ID</th><th>Pelanggan</th><th>Total</th><th>Status</th></tr></thead>
      <tbody>
        @forelse($orderTerbaru as $order)
        <tr>
          <td><a href="{{ route('admin.orders.show', $order) }}">#{{ $order->id }}</a></td>
          <td>{{ $order->user->name }}</td>
          <td>Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
          <td><span class="badge status-{{ $order->status }}">{{ ucfirst($order->status) }}</span></td>
        </tr>
        @empty
        <tr><td colspan="4" style="text-align:center;color:var(--gray-400)">Belum ada pesanan</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="table-wrapper">
    <div class="table-header">
      <h2>Produk Stok Menipis</h2>
      <a href="{{ route('admin.produk.index') }}" class="btn btn-outline btn-sm">Lihat Semua</a>
    </div>
    <table class="table">
      <thead><tr><th>Produk</th><th>Stok</th><th>Aksi</th></tr></thead>
      <tbody>
        @forelse($produkMenipis as $p)
        <tr>
          <td>{{ $p->nama }}</td>
          <td><span class="badge badge-danger">{{ $p->stok }}</span></td>
          <td><a href="{{ route('admin.produk.edit', $p) }}" class="btn btn-ghost btn-sm">Edit</a></td>
        </tr>
        @empty
        <tr><td colspan="3" style="text-align:center;color:var(--gray-400)">Semua stok aman</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
