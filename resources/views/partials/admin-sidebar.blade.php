<aside class="admin-sidebar">
  <div class="sidebar-brand">TokoLKS Admin</div>

  <nav class="sidebar-nav">
    <div class="sidebar-section">Menu Utama</div>

    <a href="{{ route('admin.dashboard') }}"
       class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
      <span class="sidebar-icon"></span> Dashboard
    </a>

    <div class="sidebar-section">Katalog</div>

    <a href="{{ route('admin.produk.index') }}"
       class="sidebar-link {{ request()->routeIs('admin.produk.*') ? 'active' : '' }}">
      <span class="sidebar-icon"></span> Produk
    </a>

    <a href="{{ route('admin.kategori.index') }}"
       class="sidebar-link {{ request()->routeIs('admin.kategori.*') ? 'active' : '' }}">
      <span class="sidebar-icon"></span> Kategori
    </a>

    <div class="sidebar-section">Transaksi</div>

    <a href="{{ route('admin.orders.index') }}"
       class="sidebar-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
      <span class="sidebar-icon"></span> Pesanan
    </a>

    <div class="sidebar-section">Akun</div>

    <a href="{{ route('home') }}" class="sidebar-link">
      <span class="sidebar-icon"></span> Lihat Toko
    </a>

    <form action="{{ route('logout') }}" method="POST">
      @csrf
      <button type="submit" class="sidebar-link" style="width:100%;background:none;border:none;cursor:pointer;text-align:left">
        <span class="sidebar-icon"></span> Keluar
      </button>
    </form>
  </nav>
</aside>
