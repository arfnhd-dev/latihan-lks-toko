/* ============================================================
   TokoLKS — app.js
   ============================================================ */

// ---- Auto-hide flash messages ----
document.addEventListener('DOMContentLoaded', function () {
  const flashes = document.querySelectorAll('.flash-message');
  flashes.forEach(function (el) {
    setTimeout(function () {
      el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
      el.style.opacity = '0';
      el.style.transform = 'translateX(100%)';
      setTimeout(function () { el.remove(); }, 500);
    }, 4000);
  });

  // ---- Slug auto-generate (kategori form) ----
  const namaInput = document.querySelector('[name="nama"]');
  const slugInput = document.querySelector('[name="slug"]');
  if (namaInput && slugInput && slugInput.dataset.auto !== 'false') {
    namaInput.addEventListener('input', function () {
      if (!slugInput.dataset.manual) {
        slugInput.value = namaInput.value
          .toLowerCase()
          .trim()
          .replace(/\s+/g, '-')
          .replace(/[^a-z0-9-]/g, '');
      }
    });
    slugInput.addEventListener('input', function () {
      slugInput.dataset.manual = 'true';
    });
  }
});

// ---- Image preview ----
function previewGambar(input, targetId) {
  const target = document.getElementById(targetId);
  if (!target) return;
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = function (e) {
      target.src = e.target.result;
      target.style.display = 'block';
    };
    reader.readAsDataURL(input.files[0]);
  }
}

// ---- Image rotation ----
const _rotasi = {};
function rotasiGambar(id, derajat) {
  const img = document.getElementById(id);
  if (!img) return;
  _rotasi[id] = ((_rotasi[id] || 0) + derajat + 360) % 360;
  img.style.transform = 'rotate(' + _rotasi[id] + 'deg)';
}
function resetRotasi(id) {
  const img = document.getElementById(id);
  if (!img) return;
  _rotasi[id] = 0;
  img.style.transform = 'rotate(0deg)';
}

// ---- Konfirmasi hapus ----
function konfirmasiHapus(formId, pesan) {
  if (confirm(pesan || 'Apakah Anda yakin ingin menghapus ini?')) {
    document.getElementById(formId).submit();
  }
}

// ---- Sound effects (Web Audio API) ----
const _ctx = window.AudioContext ? new AudioContext() : null;

function _beep(freq, dur, vol, type) {
  if (!_ctx) return;
  try {
    const osc  = _ctx.createOscillator();
    const gain = _ctx.createGain();
    osc.connect(gain);
    gain.connect(_ctx.destination);
    osc.frequency.value = freq;
    osc.type = type || 'sine';
    gain.gain.setValueAtTime(vol || 0.1, _ctx.currentTime);
    gain.gain.exponentialRampToValueAtTime(0.001, _ctx.currentTime + dur);
    osc.start(_ctx.currentTime);
    osc.stop(_ctx.currentTime + dur);
  } catch (e) {}
}

const sfxKlik   = function () { _beep(880, 0.08, 0.06, 'sine'); };
const sfxSukses = function () {
  _beep(523, 0.1, 0.08);
  setTimeout(function () { _beep(659, 0.1, 0.08); }, 100);
  setTimeout(function () { _beep(784, 0.15, 0.08); }, 200);
};
const sfxError  = function () {
  _beep(220, 0.2, 0.08, 'sawtooth');
};

function mainkanEfek(fn) {
  if (typeof fn === 'function') {
    try { fn(); } catch (e) {}
  }
}

// ---- Number format helper ----
function formatRupiah(angka) {
  return 'Rp ' + parseInt(angka).toLocaleString('id-ID');
}

// ---- Search debounce (auto-submit) ----
(function () {
  const searchInput = document.getElementById('search-input');
  if (!searchInput) return;
  let timer;
  searchInput.addEventListener('input', function () {
    clearTimeout(timer);
    timer = setTimeout(function () {
      searchInput.closest('form').submit();
    }, 600);
  });
})();
