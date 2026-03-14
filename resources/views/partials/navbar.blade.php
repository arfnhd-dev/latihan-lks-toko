<nav class="navbar">
  <div class="container navbar-inner">
    <a href="{{ route('home') }}" class="navbar-brand">TokoLKS</a>

    <div class="navbar-nav">
      <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>
      <a href="{{ route('produk.index') }}" class="nav-link {{ request()->routeIs('produk.*') ? 'active' : '' }}">Produk</a>
    </div>

    <div class="navbar-actions">
      @auth
        <a href="{{ route('keranjang.index') }}" class="btn btn-ghost btn-sm cart-badge">
          Keranjang
          @php $cartCount = collect(session('cart', []))->sum('jumlah'); @endphp
          @if($cartCount > 0)
            <span class="cart-count">{{ $cartCount }}</span>
          @endif
        </a>
        <a href="{{ route('dashboard') }}" class="btn btn-ghost btn-sm">{{ auth()->user()->name }}</a>
        @if(auth()->user()->role === 'admin')
          <a href="{{ route('admin.dashboard') }}" class="btn btn-outline btn-sm">Admin</a>
        @endif
        <form action="{{ route('logout') }}" method="POST" style="display:inline">
          @csrf
          <button type="submit" class="btn btn-ghost btn-sm">Keluar</button>
        </form>
      @else
        <a href="{{ route('login') }}" class="btn btn-ghost btn-sm">Masuk</a>
        <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Daftar</a>
      @endauth
    </div>
  </div>
</nav>
