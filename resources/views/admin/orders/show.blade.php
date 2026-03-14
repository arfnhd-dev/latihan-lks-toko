@extends('layouts.admin')
@section('title', 'Detail Pesanan #' . $order->id)
@section('content')
<div class="page-header">
  <h1>Pesanan #{{ $order->id }}</h1>
  <a href="{{ route('admin.orders.index') }}" class="btn btn-ghost">← Kembali</a>
</div>

<div style="display:grid;grid-template-columns:1fr 320px;gap:1.5rem">
  <div>
    <div class="table-wrapper">
      <div class="table-header"><h2>Item Pesanan</h2></div>
      <table class="table">
        <thead><tr><th>Produk</th><th>Harga Satuan</th><th>Jumlah</th><th>Subtotal</th></tr></thead>
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
    <div style="background:#fff;border-radius:var(--radius-lg);border:1px solid var(--gray-200);padding:1.5rem;margin-bottom:1rem">
      <h3 style="font-weight:600;margin-bottom:1rem">Info Pesanan</h3>
      <p style="font-size:var(--text-sm);margin-bottom:.5rem"><strong>Pelanggan:</strong> {{ $order->user->name }}</p>
      <p style="font-size:var(--text-sm);margin-bottom:.5rem"><strong>Email:</strong> {{ $order->user->email }}</p>
      <p style="font-size:var(--text-sm);margin-bottom:.5rem"><strong>Tanggal:</strong> {{ $order->created_at->format('d M Y, H:i') }}</p>
      <p style="font-size:var(--text-sm);margin-bottom:.75rem"><strong>Alamat:</strong></p>
      <p style="font-size:var(--text-sm);padding:.75rem;background:var(--gray-50);border-radius:var(--radius);margin-bottom:.75rem">{{ $order->alamat }}</p>
      @if($order->catatan)
        <p style="font-size:var(--text-sm)"><strong>Catatan:</strong> {{ $order->catatan }}</p>
      @endif
      <div style="border-top:1px solid var(--gray-200);padding-top:1rem;margin-top:1rem;display:flex;justify-content:space-between;font-weight:700">
        <span>Total</span>
        <span style="color:var(--primary)">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
      </div>
    </div>

    <div style="background:#fff;border-radius:var(--radius-lg);border:1px solid var(--gray-200);padding:1.5rem">
      <h3 style="font-weight:600;margin-bottom:1rem">Ubah Status</h3>
      <div style="margin-bottom:1rem">
        <span>Status saat ini: </span>
        <span class="badge status-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
      </div>
      <form action="{{ route('admin.orders.status', $order) }}" method="POST">
        @csrf @method('PATCH')
        <div class="form-group">
          <select name="status" class="form-control">
            @foreach(['pending','proses','dikirim','selesai','batal'] as $s)
              <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
            @endforeach
          </select>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Perbarui Status</button>
      </form>
    </div>
  </div>
</div>
@endsection
