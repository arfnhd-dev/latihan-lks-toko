@extends('layouts.admin')
@section('title', 'Kelola Kategori')
@section('content')
<div class="page-header">
  <h1>Kelola Kategori</h1>
  <a href="{{ route('admin.kategori.create') }}" class="btn btn-primary">+ Tambah Kategori</a>
</div>

<div class="table-wrapper">
  <table class="table">
    <thead>
      <tr><th>#</th><th>Nama</th><th>Slug</th><th>Jumlah Produk</th><th>Aksi</th></tr>
    </thead>
    <tbody>
      @forelse($kategoris as $kat)
      <tr>
        <td>{{ $loop->iteration }}</td>
        <td style="font-weight:600">{{ $kat->nama }}</td>
        <td><code style="font-size:var(--text-xs);background:var(--gray-100);padding:.2rem .4rem;border-radius:4px">{{ $kat->slug }}</code></td>
        <td>{{ $kat->produk_count }} produk</td>
        <td>
          <div style="display:flex;gap:.5rem">
            <a href="{{ route('admin.kategori.edit', $kat) }}" class="btn btn-outline btn-sm">Edit</a>
            <form id="form-hapus-kat-{{ $kat->id }}" action="{{ route('admin.kategori.destroy', $kat) }}" method="POST">
              @csrf @method('DELETE')
              <button type="button" class="btn btn-danger btn-sm"
                      onclick="konfirmasiHapus('form-hapus-kat-{{ $kat->id }}', 'Hapus kategori {{ addslashes($kat->nama) }}?')">Hapus</button>
            </form>
          </div>
        </td>
      </tr>
      @empty
      <tr><td colspan="5" style="text-align:center;color:var(--gray-400);padding:2rem">Belum ada kategori</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
<div class="pagination">{{ $kategoris->links() }}</div>
@endsection
