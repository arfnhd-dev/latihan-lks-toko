@extends('layouts.app')
@section('title', 'Riwayat Pesanan')
@section('content')
<div class="container section">
  <h1 style="font-size:var(--text-2xl);font-weight:700;margin-bottom:1.5rem">Riwayat Pesanan</h1>

  <div class="table-wrapper">
    <table class="table">
      <thead>
        <tr>
          <th>#ID</th>
          <th>Tanggal</th>
          <th>Total</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($orders as $order)
        <tr>
          <td>#{{ $order->id }}</td>
          <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
          <td>Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
          <td><span class="badge status-{{ $order->status }}">{{ ucfirst($order->status) }}</span></td>
          <td><a href="{{ route('orders.show', $order) }}" class="btn btn-ghost btn-sm">Detail</a></td>
        </tr>
        @empty
        <tr><td colspan="5" style="text-align:center;color:var(--gray-400);padding:2rem">Belum ada pesanan</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="pagination">{{ $orders->links() }}</div>
</div>
@endsection
