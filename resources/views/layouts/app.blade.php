<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'TokoLKS') — TokoLKS</title>
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
  <link rel="stylesheet" href="{{ asset('css/components.css') }}">
  <link rel="stylesheet" href="{{ asset('css/pages.css') }}">
  @stack('styles')
</head>
<body class="page-wrapper" data-theme="light">
  @include('partials.navbar')

  @if(session('success'))
    <div class="flash-message">
      <div class="alert alert-success">{{ session('success') }}</div>
    </div>
  @endif
  @if(session('error'))
    <div class="flash-message">
      <div class="alert alert-danger">{{ session('error') }}</div>
    </div>
  @endif

  <main>
    @yield('content')
  </main>

  @include('partials.footer')

  <script src="{{ asset('js/app.js') }}"></script>
  @stack('scripts')
</body>
</html>
