<div class="admin-topbar">
  <div>
    <h2 style="font-size:var(--text-lg);font-weight:700;color:var(--gray-800)">
      @yield('title', 'Dashboard')
    </h2>
  </div>
  <div style="display:flex;align-items:center;gap:1rem">
    <span style="font-size:var(--text-sm);color:var(--gray-500)">
      Halo, <strong>{{ auth()->user()->name }}</strong>
    </span>
  </div>
</div>
