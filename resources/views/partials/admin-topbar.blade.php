<div class="admin-topbar">
  <div>
    <h2 style="font-size:var(--text-lg);font-weight:700;color:var(--gray-800)">
      @yield('title', 'Dashboard')
    </h2>
  </div>
  <div style="display:flex;align-items:center;gap:1rem">
    <button id="btn-musik" onclick="toggleMusik()"
            style="background:none;border:1px solid var(--gray-300);border-radius:var(--radius);padding:.3rem .75rem;cursor:pointer;font-size:var(--text-sm);color:var(--gray-600)"
            title="Toggle musik latar">Play</button>
    <span style="font-size:var(--text-sm);color:var(--gray-500)">
      Halo, <strong>{{ auth()->user()->name }}</strong>
    </span>
  </div>
</div>
