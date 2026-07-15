/* ============================================================
   PanenKu — app.js  (Neumorphic Edition)
   ============================================================ */

/* ── Theme ───────────────────────────────────────────────── */
const Theme = (() => {
  const KEY = 'pk_theme';

  function apply(mode) {
    const effective = mode === 'system'
      ? (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light')
      : mode;
    document.documentElement.setAttribute('data-theme', effective);
    document.querySelectorAll('.theme-btn').forEach(btn => {
      btn.classList.toggle('active', btn.dataset.themeBtn === mode);
    });
  }

  function set(mode) {
    localStorage.setItem(KEY, mode);
    apply(mode);
    fetch('/profil/set-theme', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf() },
      body: JSON.stringify({ theme: mode })
    }).catch(() => {});
  }

  function init() {
    const stored = localStorage.getItem(KEY) || 'system';
    apply(stored);
    document.querySelectorAll('[data-theme-btn]').forEach(btn => {
      btn.addEventListener('click', () => set(btn.dataset.themeBtn));
    });
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
      if ((localStorage.getItem(KEY) || 'system') === 'system') apply('system');
    });
  }

  return { init, set, apply };
})();

/* ── CSRF helper ─────────────────────────────────────────── */
function csrf() {
  const m = document.querySelector('meta[name="csrf-token"]');
  return m ? m.content : '';
}

/* ── Toast Notifications ─────────────────────────────────── */
const Toast = (() => {
  let container;

  function getContainer() {
    if (!container) {
      container = document.createElement('div');
      container.style.cssText = `
        position:fixed; top:16px; right:16px; z-index:9999;
        display:flex; flex-direction:column; gap:10px;
        max-width: min(360px, calc(100vw - 32px));
      `;
      document.body.appendChild(container);
    }
    return container;
  }

  function show(message, type = 'info', duration = 4000) {
    const colors = {
      success: { bg: 'rgba(39,174,96,.12)',  border: '#27ae60', icon: '✓', color: '#1e8449' },
      error:   { bg: 'rgba(231,76,60,.12)',  border: '#e74c3c', icon: '✕', color: '#c0392b' },
      warning: { bg: 'rgba(243,156,18,.12)', border: '#f39c12', icon: '⚠', color: '#b7770d' },
      info:    { bg: 'rgba(52,152,219,.12)', border: '#3498db', icon: 'ℹ', color: '#1a6fa8' },
    };
    const c = colors[type] || colors.info;

    const toast = document.createElement('div');
    toast.style.cssText = `
      background: var(--bg-card);
      border-left: 4px solid ${c.border};
      border-radius: 12px;
      padding: 12px 16px;
      display: flex; align-items: flex-start; gap: 10px;
      box-shadow: var(--nm-shadow);
      animation: slideUp .3s cubic-bezier(.34,1.56,.64,1);
      cursor: pointer;
      word-break: break-word;
    `;
    toast.innerHTML = `
      <span style="font-size:15px;color:${c.color};font-weight:700;flex-shrink:0;margin-top:1px">${c.icon}</span>
      <span style="font-size:13px;font-weight:600;color:var(--text-primary);flex:1;line-height:1.5">${message}</span>
      <span style="font-size:16px;color:var(--text-muted);line-height:1;margin-top:-1px;cursor:pointer" onclick="this.parentElement.remove()">×</span>
    `;
    toast.addEventListener('click', () => remove(toast));
    getContainer().appendChild(toast);

    const timer = setTimeout(() => remove(toast), duration);
    toast._timer = timer;

    return toast;
  }

  function remove(toast) {
    clearTimeout(toast._timer);
    toast.style.opacity = '0';
    toast.style.transform = 'translateX(20px)';
    toast.style.transition = 'all .25s ease';
    setTimeout(() => toast.remove(), 260);
  }

  function init() {
    document.querySelectorAll('[data-flash]').forEach(el => {
      show(el.dataset.message, el.dataset.flash === 'success' ? 'success' : 'error');
      el.remove();
    });
  }

  return { show, init };
})();

/* ── Sidebar ─────────────────────────────────────────────── */
const Sidebar = (() => {
  function init() {
    const sidebar  = document.querySelector('.sidebar');
    const overlay  = document.querySelector('.sidebar-overlay');
    const toggles  = document.querySelectorAll('.btn-menu-toggle');

    if (!sidebar) return;

    function open()  { sidebar.classList.add('open'); overlay?.classList.add('show'); document.body.style.overflow = 'hidden'; }
    function close() { sidebar.classList.remove('open'); overlay?.classList.remove('show'); document.body.style.overflow = ''; }
    function toggle(){ sidebar.classList.contains('open') ? close() : open(); }

    toggles.forEach(btn => btn.addEventListener('click', toggle));
    overlay?.addEventListener('click', close);

    // Close on escape
    document.addEventListener('keydown', e => { if (e.key === 'Escape') close(); });

    // Auto-close on desktop resize
    window.addEventListener('resize', () => {
      if (window.innerWidth > 768) close();
    });
  }
  return { init };
})();

/* ── Read Mode ───────────────────────────────────────────── */
const ReadMode = (() => {
  const KEY = 'pk_read_mode';

  function init() {
    const stored = localStorage.getItem(KEY) === '1';
    if (stored) document.body.classList.add('mode-baca');

    document.querySelectorAll('[data-read-mode-toggle]').forEach(btn => {
      btn.classList.toggle('active', stored);
      btn.addEventListener('click', () => toggle(btn));
    });
  }

  function toggle(btn) {
    const active = document.body.classList.toggle('mode-baca');
    localStorage.setItem(KEY, active ? '1' : '0');
    document.querySelectorAll('[data-read-mode-toggle]').forEach(b => b.classList.toggle('active', active));
    fetch('/profil/set-read-mode', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf() },
      body: JSON.stringify({ read_mode: active ? 1 : 0 })
    }).catch(() => {});
  }

  return { init };
})();

/* ── Password Visibility Toggle ───────────────────────────── */
const PasswordToggle = (() => {
  function init() {
    document.querySelectorAll('[data-pwd-toggle]').forEach(btn => {
      const input = document.getElementById(btn.dataset.pwdToggle);
      const icon  = btn.querySelector('.bi');
      if (!input || !icon) return;

      btn.setAttribute('aria-pressed', 'false');
      btn.setAttribute('aria-label', 'Tampilkan password');

      btn.addEventListener('click', () => {
        const willShow = input.type === 'password';
        input.type = willShow ? 'text' : 'password';
        icon.className = willShow ? 'bi bi-eye-slash' : 'bi bi-eye';
        btn.setAttribute('aria-pressed', String(willShow));
        btn.setAttribute('aria-label', willShow ? 'Sembunyikan password' : 'Tampilkan password');
      });
    });
  }
  return { init };
})();

/* ── Modal Helper ─────────────────────────────────────────── */
const Modal = (() => {
  function open(id) {
    const el = document.getElementById(id);
    if (!el) return;
    el.style.display = 'flex';
    requestAnimationFrame(() => el.style.opacity = '1');
    document.body.style.overflow = 'hidden';
  }

  function close(id) {
    const el = document.getElementById(id);
    if (!el) return;
    el.style.opacity = '0';
    el.style.transition = 'opacity .2s';
    setTimeout(() => {
      el.style.display = 'none';
      el.style.opacity = '';
      el.style.transition = '';
    }, 200);
    document.body.style.overflow = '';
  }

  function init() {
    // Open buttons
    document.querySelectorAll('[data-modal-open]').forEach(btn => {
      btn.addEventListener('click', () => open(btn.dataset.modalOpen));
    });

    // Close buttons
    document.querySelectorAll('[data-modal-close]').forEach(btn => {
      btn.addEventListener('click', () => {
        const modal = btn.closest('.modal-backdrop');
        if (modal) close(modal.id);
      });
    });

    // Close on backdrop click
    document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
      backdrop.addEventListener('click', e => {
        if (e.target === backdrop) close(backdrop.id);
      });
    });

    // Close on Escape
    document.addEventListener('keydown', e => {
      if (e.key === 'Escape') {
        const open = document.querySelector('.modal-backdrop[style*="flex"]');
        if (open) close(open.id);
      }
    });
  }

  return { open, close, init };
})();

/* ── Confirm Dialog ──────────────────────────────────────── */
function confirmDialog(message, onConfirm, options = {}) {
  const { title = 'Konfirmasi', type = 'danger', confirmText = 'Ya, Lanjutkan', cancelText = 'Batal' } = options;

  const icons = { danger: '⚠️', warning: '⚠️', info: 'ℹ️', success: '✅' };
  const id = 'confirm-dialog-' + Date.now();
  const el = document.createElement('div');
  el.id = id;
  el.className = 'modal-backdrop';
  el.style.cssText = 'display:flex;';
  el.innerHTML = `
    <div class="modal modal-sm">
      <div class="modal-header">
        <div class="modal-title">${icons[type] || ''} ${title}</div>
      </div>
      <div class="modal-body">
        <p style="color:var(--text-secondary);font-size:14px;line-height:1.6;margin:0">${message}</p>
      </div>
      <div class="modal-footer">
        <button class="btn btn-outline" id="${id}-cancel">${cancelText}</button>
        <button class="btn btn-${type}" id="${id}-confirm">${confirmText}</button>
      </div>
    </div>
  `;
  document.body.appendChild(el);

  document.getElementById(id + '-cancel').addEventListener('click', () => el.remove());
  document.getElementById(id + '-confirm').addEventListener('click', () => { el.remove(); onConfirm(); });
  el.addEventListener('click', e => { if (e.target === el) el.remove(); });
}

/* ── AG Grid Helper ──────────────────────────────────────── */
function createGrid(container, options = {}) {
  if (!window.agGrid || !container) return null;

  const defaults = {
    theme: 'legacy',
    pagination: true,
    paginationPageSize: 15,
    paginationPageSizeSelector: [10, 15, 25, 50],
    suppressMovableColumns: true,
    animateRows: true,
    rowSelection: 'single',
    overlayNoRowsTemplate: '<div style="padding:40px;color:var(--text-muted);text-align:center"><div style="font-size:40px;margin-bottom:8px">🌾</div><div style="font-weight:600">Belum ada data</div></div>',
    defaultColDef: {
      resizable: true,
      sortable: true,
      filter: false,
      cellStyle: { display: 'flex', alignItems: 'center' },
    },
  };

  const merged = Object.assign({}, defaults, options, {
    defaultColDef: Object.assign({}, defaults.defaultColDef, options.defaultColDef || {}),
  });

  container.classList.add('ag-theme-panenku');

  return agGrid.createGrid(container, merged);
}

/* ── Mobile Card Renderer ─────────────────────────────────── */
// Renders data as neumorphic cards on mobile when AG Grid is hidden
function renderMobileCards(containerId, data, config) {
  const container = document.getElementById(containerId);
  if (!container) return;
  container.innerHTML = '';

  if (!data || !data.length) {
    container.innerHTML = `
      <div style="text-align:center;padding:40px;color:var(--text-muted)">
        <div style="font-size:40px;margin-bottom:8px">🌾</div>
        <div style="font-weight:600">Belum ada data</div>
      </div>`;
    return;
  }

  data.forEach(row => {
    const card = document.createElement('div');
    card.className = 'mobile-data-card';
    card.innerHTML = config.render(row);
    container.appendChild(card);
  });
}

/* ── Fetch wrapper ───────────────────────────────────────── */
async function apiFetch(url, options = {}) {
  const defaults = {
    headers: {
      'Content-Type': 'application/json',
      'X-Requested-With': 'XMLHttpRequest',
      'X-CSRF-TOKEN': csrf(),
    },
  };
  const opts = Object.assign({}, defaults, options, {
    headers: Object.assign({}, defaults.headers, options.headers || {}),
  });
  const res = await fetch(url, opts);
  if (!res.ok) throw new Error(`HTTP ${res.status}`);
  return res.json();
}

/* ── Form helpers ────────────────────────────────────────── */
function serializeForm(form) {
  const data = {};
  new FormData(form).forEach((v, k) => { data[k] = v; });
  return data;
}

function clearErrors(form) {
  form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
  form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
}

function showErrors(form, errors) {
  Object.entries(errors).forEach(([field, msg]) => {
    const input = form.querySelector(`[name="${field}"]`);
    if (!input) return;
    input.classList.add('is-invalid');
    const fb = document.createElement('div');
    fb.className = 'invalid-feedback';
    fb.textContent = msg;
    input.parentNode.appendChild(fb);
  });
}

/* ── Number Formatter ─────────────────────────────────────── */
function formatNumber(n, decimals = 0) {
  if (n == null || n === '') return '-';
  return Number(n).toLocaleString('id-ID', {
    minimumFractionDigits: decimals,
    maximumFractionDigits: decimals,
  });
}

function formatCurrency(n) {
  if (n == null || n === '') return '-';
  return 'Rp ' + formatNumber(n, 0);
}

function formatDate(str) {
  if (!str) return '-';
  const d = new Date(str);
  return d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
}

/* ── Chart.js default styles ─────────────────────────────── */
function getChartColors(alpha = 1) {
  return {
    primary:   `rgba(45,138,78,${alpha})`,
    secondary: `rgba(52,152,219,${alpha})`,
    warning:   `rgba(243,156,18,${alpha})`,
    danger:    `rgba(231,76,60,${alpha})`,
    purple:    `rgba(142,68,173,${alpha})`,
    teal:      `rgba(26,188,156,${alpha})`,
  };
}

/* ── Avatar Upload Preview ────────────────────────────────── */
function initAvatarPreview() {
  const input   = document.getElementById('avatar-input');
  const preview = document.getElementById('avatar-preview');
  if (!input || !preview) return;

  input.addEventListener('change', () => {
    const file = input.files[0];
    if (!file) return;
    if (file.size > 2 * 1024 * 1024) {
      Toast.show('Ukuran foto maksimal 2MB', 'error');
      return;
    }
    const reader = new FileReader();
    reader.onload = e => {
      preview.src = e.target.result;
      preview.style.display = 'block';
    };
    reader.readAsDataURL(file);
  });
}

/* ── Filter / Search debounce ─────────────────────────────── */
function debounce(fn, delay = 300) {
  let t;
  return (...args) => { clearTimeout(t); t = setTimeout(() => fn(...args), delay); };
}

/* ── Smooth counter animation ─────────────────────────────── */
function animateCounter(el, target, duration = 800) {
  const start = performance.now();
  const from  = parseFloat(el.textContent.replace(/[^0-9.]/g, '')) || 0;
  const isInt = Number.isInteger(target);

  function step(now) {
    const progress = Math.min((now - start) / duration, 1);
    const ease = 1 - Math.pow(1 - progress, 3); // ease-out cubic
    const current = from + (target - from) * ease;
    el.textContent = isInt
      ? Math.round(current).toLocaleString('id-ID')
      : current.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    if (progress < 1) requestAnimationFrame(step);
  }
  requestAnimationFrame(step);
}

/* ── Intersection Observer for counter animation ─────────── */
function initCounterAnimation() {
  const els = document.querySelectorAll('[data-counter]');
  if (!els.length) return;

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        const el = entry.target;
        const target = parseFloat(el.dataset.counter);
        animateCounter(el, target);
        observer.unobserve(el);
      }
    });
  }, { threshold: 0.2 });

  els.forEach(el => observer.observe(el));
}

/* ── Loading Button State ─────────────────────────────────── */
function btnLoading(btn, loading) {
  if (loading) {
    btn._orig = btn.innerHTML;
    btn.innerHTML = '<i class="bi bi-arrow-clockwise spin"></i> Menyimpan...';
    btn.disabled = true;
  } else {
    btn.innerHTML = btn._orig || btn.innerHTML;
    btn.disabled = false;
  }
}

/* ── INIT ─────────────────────────────────────────────────── */
document.addEventListener('DOMContentLoaded', () => {
  Theme.init();
  Toast.init();
  Sidebar.init();
  ReadMode.init();
  Modal.init();
  PasswordToggle.init();
  initAvatarPreview();
  initCounterAnimation();

  document.querySelectorAll('[data-theme-btn]').forEach(btn => {
    btn.addEventListener('click', () => {
      setTimeout(applyChartDefaults, 100);
    });
  });
});

/* ── AG Grid Quick Search + Toolbar Builder ───────────────── */
/**
 * Builds a toolbar above an AG Grid with:
 *  - neumorphic quick-filter search input
 *  - optional right-side slot (buttons etc.)
 *
 * Usage: buildGridToolbar(gridApi, wrapperEl, { placeholder, rightHtml })
 */
function buildGridToolbar(gridApiRef, wrapperEl, opts = {}) {
  const { placeholder = 'Cari data...', rightHtml = '' } = opts;

  const toolbar = document.createElement('div');
  toolbar.className = 'ag-grid-toolbar';
  toolbar.innerHTML = `
    <div class="ag-quick-search">
      <i class="bi bi-search"></i>
      <input type="text" placeholder="${placeholder}" aria-label="Cari">
    </div>
    <div class="ag-toolbar-right">${rightHtml}</div>`;

  // Insert before the grid element (first child of wrapper)
  wrapperEl.insertBefore(toolbar, wrapperEl.firstChild);

  const input = toolbar.querySelector('input');
  input.addEventListener('input', debounce(() => {
    if (gridApiRef.current) gridApiRef.current.setGridOption('quickFilterText', input.value);
  }, 250));

  return { input, toolbar };
}

/* ── Mobile Card Pager ────────────────────────────────────── */
/**
 * Wraps a mobile card list with search + pagination.
 *
 * Usage:
 *   const pager = new MobilePager('containerEl', data, renderFn, { pageSize:8 });
 *   pager.render();
 *
 * renderFn(row) → HTML string for one card
 */
class MobilePager {
  constructor(containerEl, data, renderFn, opts = {}) {
    this.el       = typeof containerEl === 'string' ? document.getElementById(containerEl) : containerEl;
    this.allData  = data || [];
    this.renderFn = renderFn;
    this.pageSize = opts.pageSize || 8;
    this.page     = 0;
    this.query    = '';
    this.placeholder = opts.placeholder || 'Cari...';
    this._filtered = [];
  }

  setData(data) {
    this.allData = data || [];
    this.page = 0;
    this.query = '';
    const inp = this.el.querySelector('.mobile-search-bar input');
    if (inp) inp.value = '';
    this._applyFilter();
    this._renderPage();
  }

  _applyFilter() {
    const q = this.query.toLowerCase().trim();
    this._filtered = q
      ? this.allData.filter(r => JSON.stringify(r).toLowerCase().includes(q))
      : [...this.allData];
  }

  _renderPage() {
    const el = this.el;
    if (!el) return;

    const total = this._filtered.length;
    const totalPages = Math.max(1, Math.ceil(total / this.pageSize));
    this.page = Math.min(this.page, totalPages - 1);

    const slice = this._filtered.slice(this.page * this.pageSize, (this.page + 1) * this.pageSize);
    const from  = total === 0 ? 0 : this.page * this.pageSize + 1;
    const to    = Math.min((this.page + 1) * this.pageSize, total);

    // Build page number buttons (show max 5)
    let pageNums = '';
    const SHOW = 5;
    let start = Math.max(0, this.page - 2);
    let end   = Math.min(totalPages - 1, start + SHOW - 1);
    start = Math.max(0, end - SHOW + 1);
    for (let i = start; i <= end; i++) {
      pageNums += `<button class="mobile-pg-btn${i === this.page ? ' active' : ''}" data-pg="${i}">${i + 1}</button>`;
    }

    // Cards HTML
    const cardsHtml = slice.length
      ? slice.map((r, i) => this.renderFn(r, this.page * this.pageSize + i)).join('')
      : `<div style="text-align:center;padding:32px 0;color:var(--text-muted);">
           <div style="font-size:36px;margin-bottom:8px;">🌾</div>
           <div style="font-weight:700;">Belum ada data</div>
         </div>`;

    el.innerHTML = `
      <div class="mobile-search-bar">
        <i class="bi bi-search"></i>
        <input type="text" placeholder="${this.placeholder}" value="${this.query.replace(/"/g,'&quot;')}" autocomplete="off">
      </div>
      ${cardsHtml}
      ${total > this.pageSize ? `
      <div class="mobile-pagination">
        <div class="mobile-pagination-info">${from}–${to} dari ${total} data</div>
        <div class="mobile-pagination-btns">
          <button class="mobile-pg-btn" data-pg="${this.page - 1}" ${this.page === 0 ? 'disabled' : ''}>‹</button>
          ${pageNums}
          <button class="mobile-pg-btn" data-pg="${this.page + 1}" ${this.page >= totalPages - 1 ? 'disabled' : ''}>›</button>
        </div>
      </div>` : (total > 0 ? `<div class="mobile-pagination-info" style="padding:8px 0;color:var(--text-muted);font-size:12px;font-weight:600;">${total} data</div>` : '')}`;

    // Search listener
    const inp = el.querySelector('.mobile-search-bar input');
    if (inp) {
      inp.addEventListener('input', debounce(e => {
        this.query = e.target.value;
        this.page  = 0;
        this._applyFilter();
        this._renderPage();
      }, 250));
    }

    // Pagination listeners
    el.querySelectorAll('.mobile-pg-btn[data-pg]').forEach(btn => {
      btn.addEventListener('click', () => {
        const pg = parseInt(btn.dataset.pg);
        if (isNaN(pg) || pg < 0 || pg >= totalPages) return;
        this.page = pg;
        this._renderPage();
        el.scrollIntoView({ behavior: 'smooth', block: 'start' });
      });
    });
  }

  render() {
    this._applyFilter();
    this._renderPage();
  }
}