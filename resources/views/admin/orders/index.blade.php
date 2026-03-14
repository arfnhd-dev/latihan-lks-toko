@extends('layouts.admin')
@section('title', 'Kelola Pesanan')
@section('content')
<div class="page-header">
  <h1>Semua Pesanan</h1>
</div>

<form method="GET" action="{{ route('admin.orders.index') }}">
  <div class="filter-bar">
    <div class="filter-group">
      <label>Filter Status</label>
      <select name="status" class="form-control">
        <option value="">Semua Status</option>
        @foreach(['pending','proses','dikirim','selesai','batal'] as $s)
          <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
        @endforeach
      </select>
    </div>
    <div class="filter-actions">
      <button type="submit" class="btn btn-primary">Filter</button>
      <a href="{{ route('admin.orders.index') }}" class="btn btn-ghost">Reset</a>
    </div>
  </div>
</form>

<div class="table-wrapper">
  <table class="table">
    <thead>
      <tr><th>#ID</th><th>Pelanggan</th><th>Total</th><th>Status</th><th>Tanggal</th><th>Aksi</th></tr>
    </thead>
    <tbody>
      @forelse($orders as $order)
      <tr>
        <td>#{{ $order->id }}</td>
        <td>{{ $order->user->name }}</td>
        <td>Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
        <td><span class="badge status-{{ $order->status }}">{{ ucfirst($order->status) }}</span></td>
        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
        <td><a href="{{ route('admin.orders.show', $order) }}" class="btn btn-ghost btn-sm">Detail</a></td>
      </tr>
      @empty
      <tr><td colspan="6" style="text-align:center;color:var(--gray-400);padding:2rem">Belum ada pesanan</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
<div class="pagination">{{ $orders->links() }}</div>
@endsection
