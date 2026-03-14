<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login — TokoLKS</title>
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
  <link rel="stylesheet" href="{{ asset('css/components.css') }}">
  <link rel="stylesheet" href="{{ asset('css/pages.css') }}">
</head>
<body>
<div class="auth-wrapper">
  <div class="auth-card">
    <div class="auth-logo">TokoLKS</div>
    <p class="auth-subtitle">Masuk ke akun Anda</p>

    @if($errors->any())
      <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <form action="{{ route('login') }}" method="POST">
      @csrf
      <div class="form-group">
        <label class="form-label">Email <span class="required">*</span></label>
        <input type="email" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
               value="{{ old('email') }}" placeholder="email@contoh.com" required>
        @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
      </div>
      <div class="form-group">
        <label class="form-label">Password <span class="required">*</span></label>
        <input type="password" name="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
               placeholder="••••••••" required>
        @error('password') <span class="invalid-feedback">{{ $message }}</span> @enderror
      </div>
      <button type="submit" class="btn btn-primary btn-block btn-lg" style="margin-top:.5rem">Masuk</button>
    </form>

    <p style="text-align:center;margin-top:1.5rem;font-size:var(--text-sm);color:var(--gray-500)">
      Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a>
    </p>
  </div>
</div>
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
