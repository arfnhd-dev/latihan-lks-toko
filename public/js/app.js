/* ══════════════════════════════════════════════════════════
   TOKO ONLINE — app.js
   LKS Web Technology 2026
   ══════════════════════════════════════════════════════════ */

/* ── Audio ──────────────────────────────────────────────────
   OPSI A: Pakai file MP3 (taruh file di public/sounds/)
   Uncomment blok ini dan comment blok OPSI B di bawah

const sfxKlik   = new Audio('/sounds/klik.mp3');
const sfxSukses = new Audio('/sounds/sukses.mp3');
const bgm       = new Audio('/sounds/bgm.mp3');
bgm.loop   = true;
bgm.volume = 0.35;

let bgmBerjalan = false;

function mainkanEfek(audio) {
  audio.currentTime = 0;
  audio.play().catch(() => {});
}

function toggleMusik() {
  bgmBerjalan ? bgm.pause() : bgm.play().catch(() => {});
  bgmBerjalan = !bgmBerjalan;
  const btn = document.getElementById('btn-musik');
  if (btn) btn.textContent = bgmBerjalan ? 'Pause' : 'Play';
}
   ── END OPSI A ─────────────────────────────────────────── */

/* ── OPSI B: Web Audio API (tanpa file MP3) ─────────────── */
const _ctx = window.AudioContext ? new (window.AudioContext || window.webkitAudioContext)() : null;

function _beep(freq, dur, vol, type) {
  if (!_ctx) return;
  try {
    const osc  = _ctx.createOscillator();
    const gain = _ctx.createGain();
    osc.connect(gain);
    gain.connect(_ctx.destination);
    osc.frequency.value = freq;
    osc.type = type || 'sine';
    gain.gain.setValueAtTime(vol || 0.08, _ctx.currentTime);
    gain.gain.exponentialRampToValueAtTime(0.001, _ctx.currentTime + dur);
    osc.start(_ctx.currentTime);
    osc.stop(_ctx.currentTime + dur);
  } catch(e) {}
}

const sfxKlik   = { play: function() { _beep(880, 0.08, 0.06, 'sine'); } };
const sfxSukses = { play: function() {
  _beep(523, 0.1, 0.08);
  setTimeout(function() { _beep(659, 0.1, 0.08); }, 100);
  setTimeout(function() { _beep(784, 0.15, 0.08); }, 200);
}};

let bgmBerjalan = false;

function mainkanEfek(audio) {
  try { audio.play(); } catch(e) {}
}

function toggleMusik() {
  bgmBerjalan = !bgmBerjalan;
  const btn = document.getElementById('btn-musik');
  if (btn) btn.textContent = bgmBerjalan ? 'Pause' : 'Play';
  // BGM dari file — aktif jika pakai OPSI A
}
/* ── END OPSI B ─────────────────────────────────────────── */

/* ── Hamburger Navbar Publik ─────────────────────────────── */
function toggleMenu() {
  const menu = document.getElementById('navbar-menu');
  if (menu) menu.classList.toggle('open');
}

/* ── Sidebar Admin Toggle (mobile) ──────────────────────── */
function toggleSidebar() {
  const sidebar = document.getElementById('sidebar');
  if (sidebar) sidebar.classList.toggle('open');
}

/* ── Modal ───────────────────────────────────────────────── */
function bukaModal(id) {
  const modal = document.getElementById(id);
  if (modal) {
    modal.classList.add('open');
    mainkanEfek(sfxKlik);
  }
}
function tutupModal(id) {
  const modal = document.getElementById(id);
  if (modal) modal.classList.remove('open');
}

// Tutup modal klik luar
document.addEventListener('click', function(e) {
  if (e.target.classList.contains('modal-overlay')) {
    e.target.classList.remove('open');
  }
});

/* ── Keyboard Events ─────────────────────────────────────── */
document.addEventListener('keydown', function(e) {
  // Escape = tutup semua modal yang terbuka
  if (e.key === 'Escape') {
    document.querySelectorAll('.modal-overlay.open').forEach(function(m) {
      m.classList.remove('open');
    });
  }
  // Ctrl+/ = fokus ke search input
  if (e.ctrlKey && e.key === '/') {
    e.preventDefault();
    const search = document.getElementById('search-input');
    if (search) { search.focus(); search.select(); }
  }
});

/* ── Mouse / Pointer Tracking ────────────────────────────── */
document.addEventListener('mousemove', function(e) {
  const tracker = document.getElementById('mouse-tracker');
  if (tracker) {
    tracker.textContent = 'X: ' + e.clientX + '  Y: ' + e.clientY;
  }
});

/* ── Rotasi Gambar Produk ────────────────────────────────── */
let sudutRotasi = 0;
function rotasiGambar(elId, derajat) {
  sudutRotasi += derajat;
  const el = document.getElementById(elId);
  if (el) {
    el.style.transform  = 'rotate(' + sudutRotasi + 'deg)';
    el.style.transition = 'transform 0.35s ease';
  }
}
function resetRotasi(elId) {
  sudutRotasi = 0;
  const el = document.getElementById(elId);
  if (el) el.style.transform = 'rotate(0deg)';
}

/* ── Z-Index Layer Dinamis ───────────────────────────────── */
let zCounter = 10;
function angkatLayer(el) {
  zCounter++;
  el.style.zIndex = zCounter;
}

/* ── Preview Gambar Sebelum Upload ───────────────────────── */
function previewGambar(input, previewId) {
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = function(e) {
      const prev = document.getElementById(previewId);
      if (prev) {
        prev.src = e.target.result;
        prev.style.display = 'block';
      }
    };
    reader.readAsDataURL(input.files[0]);
  }
}

/* ── Konfirmasi Hapus ─────────────────────────────────────── */
function konfirmasiHapus(formId, pesan) {
  pesan = pesan || 'Yakin ingin menghapus data ini?';
  if (confirm(pesan)) {
    mainkanEfek(sfxKlik);
    document.getElementById(formId).submit();
  }
}

/* ── Flash Message Auto-hide ─────────────────────────────── */
document.addEventListener('DOMContentLoaded', function() {
  const alerts = document.querySelectorAll('.alert');
  alerts.forEach(function(alert) {
    setTimeout(function() {
      alert.style.transition = 'opacity 0.5s ease';
      alert.style.opacity    = '0';
      setTimeout(function() { alert.remove(); }, 500);
    }, 4000);
  });
});

/* ── Animasi Kartu Produk (Intersection Observer) ────────── */
document.addEventListener('DOMContentLoaded', function() {
  const cards = document.querySelectorAll('.card');
  const observer = new IntersectionObserver(function(entries) {
    entries.forEach(function(entry) {
      if (entry.isIntersecting) {
        entry.target.classList.remove("card-hidden"); entry.target.classList.add("card-visible");
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.1 });

  cards.forEach(function(card) {
    card.classList.add("card-hidden");
    observer.observe(card);
  });
});

/* ── Active Link Sidebar ─────────────────────────────────── */
document.addEventListener('DOMContentLoaded', function() {
  const links = document.querySelectorAll('.sidebar-link');
  const current = window.location.pathname;
  links.forEach(function(link) {
    if (link.getAttribute('href') === current) {
      link.classList.add('active');
    }
  });
});

/* ── Slug Auto-generate (form kategori) ─────────────────── */
document.addEventListener('DOMContentLoaded', function() {
  const namaInput = document.querySelector('[name="nama"]');
  const slugInput = document.querySelector('[name="slug"]');
  if (namaInput && slugInput) {
    namaInput.addEventListener('input', function() {
      slugInput.value = namaInput.value
        .toLowerCase()
        .trim()
        .replace(/\s+/g, '-')
        .replace(/[^a-z0-9-]/g, '');
    });
  }
});
