@extends('layouts.admin')
@section('title', 'Edit Produk')
@section('content')
<div class="page-header">
  <h1>Edit Produk</h1>
  <a href="{{ route('admin.produk.index') }}" class="btn btn-ghost">← Kembali</a>
</div>

<div style="background:#fff;border-radius:var(--radius-lg);border:1px solid var(--gray-200);padding:1.5rem;max-width:700px">
  <form action="{{ route('admin.produk.update', $produk) }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
      <div class="form-group" style="grid-column:1/-1">
        <label class="form-label">Nama Produk <span class="required">*</span></label>
        <input type="text" name="nama" class="form-control {{ $errors->has('nama') ? 'is-invalid' : '' }}"
               value="{{ old('nama', $produk->nama) }}" required>
        @error('nama') <span class="invalid-feedback">{{ $message }}</span> @enderror
      </div>
      <div class="form-group">
        <label class="form-label">Kategori <span class="required">*</span></label>
        <select name="kategori_id" class="form-control" required>
          <option value="">-- Pilih Kategori --</option>
          @foreach($kategoris as $kat)
            <option value="{{ $kat->id }}" {{ old('kategori_id', $produk->kategori_id) == $kat->id ? 'selected' : '' }}>{{ $kat->nama }}</option>
          @endforeach
        </select>
        @error('kategori_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
      </div>
      <div class="form-group">
        <label class="form-label">Harga <span class="required">*</span></label>
        <div class="input-group">
          <span class="input-prefix">Rp</span>
          <input type="number" name="harga" class="form-control"
                 value="{{ old('harga', $produk->harga) }}" min="0" required>
        </div>
        @error('harga') <span class="invalid-feedback">{{ $message }}</span> @enderror
      </div>
      <div class="form-group">
        <label class="form-label">Stok <span class="required">*</span></label>
        <input type="number" name="stok" class="form-control"
               value="{{ old('stok', $produk->stok) }}" min="0" required>
        @error('stok') <span class="invalid-feedback">{{ $message }}</span> @enderror
      </div>
      <div class="form-group" style="grid-column:1/-1">
        <label class="form-label">Deskripsi</label>
        <textarea name="deskripsi" class="form-control" rows="4">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
      </div>
      <div class="form-group" style="grid-column:1/-1">
        <label class="form-label">Gambar Produk</label>
        @if($produk->gambar)
          <div style="margin-bottom:.75rem">
            <img src="{{ Storage::url($produk->gambar) }}" style="max-height:150px;border-radius:var(--radius);border:1px solid var(--gray-200)" alt="{{ $produk->nama }}">
            <p style="font-size:var(--text-xs);color:var(--gray-500);margin-top:.25rem">Gambar saat ini</p>
          </div>
        @endif
        <label class="file-upload-box" for="gambar-input">
          <input type="file" id="gambar-input" name="gambar" accept="image/*"
                 onchange="previewGambar(this, 'preview-gambar')">
          <p style="font-size:var(--text-sm);color:var(--gray-500)">Upload gambar baru (opsional)</p>
        </label>
        <img id="preview-gambar" style="display:none;margin-top:1rem;max-height:200px;border-radius:var(--radius)" alt="Preview">
        @error('gambar') <span class="invalid-feedback">{{ $message }}</span> @enderror
      </div>
    </div>
    <div style="display:flex;gap:.75rem;margin-top:.5rem">
      <button type="submit" class="btn btn-primary">Perbarui Produk</button>
      <a href="{{ route('admin.produk.index') }}" class="btn btn-ghost">Batal</a>
    </div>
  </form>
</div>
@endsection
