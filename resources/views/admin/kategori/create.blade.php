@extends('layouts.admin')
@section('title', 'Tambah Kategori')
@section('content')
<div class="page-header">
  <h1>Tambah Kategori</h1>
  <a href="{{ route('admin.kategori.index') }}" class="btn btn-ghost">← Kembali</a>
</div>

<div style="background:#fff;border-radius:var(--radius-lg);border:1px solid var(--gray-200);padding:1.5rem;max-width:500px">
  <form action="{{ route('admin.kategori.store') }}" method="POST">
    @csrf
    <div class="form-group">
      <label class="form-label">Nama Kategori <span class="required">*</span></label>
      <input type="text" name="nama" class="form-control {{ $errors->has('nama') ? 'is-invalid' : '' }}"
             value="{{ old('nama') }}" placeholder="Nama kategori" required>
      @error('nama') <span class="invalid-feedback">{{ $message }}</span> @enderror
    </div>
    <div class="form-group">
      <label class="form-label">Slug <span class="required">*</span></label>
      <input type="text" name="slug" class="form-control {{ $errors->has('slug') ? 'is-invalid' : '' }}"
             value="{{ old('slug') }}" placeholder="nama-kategori" required>
      <span class="form-hint">Diisi otomatis dari nama, atau isi manual.</span>
      @error('slug') <span class="invalid-feedback">{{ $message }}</span> @enderror
    </div>
    <div style="display:flex;gap:.75rem">
      <button type="submit" class="btn btn-primary">Simpan</button>
      <a href="{{ route('admin.kategori.index') }}" class="btn btn-ghost">Batal</a>
    </div>
  </form>
</div>

@push('scripts')
<script>
document.querySelector('[name="nama"]').addEventListener('input', function() {
  const slug = this.value.toLowerCase().replace(/\s+/g, '-').replace(/[^a-z0-9-]/g, '');
  document.querySelector('[name="slug"]').value = slug;
});
</script>
@endpush
@endsection
