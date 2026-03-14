@extends('layouts.admin')
@section('title', 'Tambah Produk')
@section('content')
<div class="page-header">
  <h1>Tambah Produk</h1>
  <a href="{{ route('admin.produk.index') }}" class="btn btn-ghost">← Kembali</a>
</div>

<div style="background:#fff;border-radius:var(--radius-lg);border:1px solid var(--gray-200);padding:1.5rem;max-width:700px">
  <form action="{{ route('admin.produk.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
      <div class="form-group" style="grid-column:1/-1">
        <label class="form-label">Nama Produk <span class="required">*</span></label>
        <input type="text" name="nama" class="form-control {{ $errors->has('nama') ? 'is-invalid' : '' }}"
               value="{{ old('nama') }}" placeholder="Nama produk" required>
        @error('nama') <span class="invalid-feedback">{{ $message }}</span> @enderror
      </div>
      <div class="form-group">
        <label class="form-label">Kategori <span class="required">*</span></label>
        <select name="kategori_id" class="form-control {{ $errors->has('kategori_id') ? 'is-invalid' : '' }}" required>
          <option value="">-- Pilih Kategori --</option>
          @foreach($kategoris as $kat)
            <option value="{{ $kat->id }}" {{ old('kategori_id') == $kat->id ? 'selected' : '' }}>{{ $kat->nama }}</option>
          @endforeach
        </select>
        @error('kategori_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
      </div>
      <div class="form-group">
        <label class="form-label">Harga <span class="required">*</span></label>
        <div class="input-group">
          <span class="input-prefix">Rp</span>
          <input type="number" name="harga" class="form-control {{ $errors->has('harga') ? 'is-invalid' : '' }}"
                 value="{{ old('harga') }}" placeholder="0" min="0" required>
        </div>
        @error('harga') <span class="invalid-feedback">{{ $message }}</span> @enderror
      </div>
      <div class="form-group">
        <label class="form-label">Stok <span class="required">*</span></label>
        <input type="number" name="stok" class="form-control {{ $errors->has('stok') ? 'is-invalid' : '' }}"
               value="{{ old('stok', 0) }}" min="0" required>
        @error('stok') <span class="invalid-feedback">{{ $message }}</span> @enderror
      </div>
      <div class="form-group" style="grid-column:1/-1">
        <label class="form-label">Deskripsi</label>
        <textarea name="deskripsi" class="form-control" rows="4"
                  placeholder="Deskripsi produk...">{{ old('deskripsi') }}</textarea>
      </div>
      <div class="form-group" style="grid-column:1/-1">
        <label class="form-label">Gambar Produk</label>
        <label class="file-upload-box" for="gambar-input">
          <input type="file" id="gambar-input" name="gambar" accept="image/*"
                 onchange="previewGambar(this, 'preview-gambar')">
          <p style="font-size:var(--text-sm);color:var(--gray-500)">Klik untuk upload gambar</p>
          <p style="font-size:var(--text-xs);color:var(--gray-400)">JPG, PNG, WEBP — Maks. 2MB</p>
        </label>
        <img id="preview-gambar" style="display:none;margin-top:1rem;max-height:200px;border-radius:var(--radius);border:1px solid var(--gray-200)" alt="Preview">
        @error('gambar') <span class="invalid-feedback">{{ $message }}</span> @enderror
      </div>
    </div>
    <div style="display:flex;gap:.75rem;margin-top:.5rem">
      <button type="submit" class="btn btn-primary">Simpan Produk</button>
      <a href="{{ route('admin.produk.index') }}" class="btn btn-ghost">Batal</a>
    </div>
  </form>
</div>
@endsection
