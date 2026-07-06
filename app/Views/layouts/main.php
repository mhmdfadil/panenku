<!DOCTYPE html>
<html lang="id" data-theme="" data-server-theme="<?= session()->get('theme_mode') ?? 'system' ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="<?= csrf_hash() ?>">
  <title><?= esc($title ?? 'PanenKu') ?> — PanenKu</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ag-grid-community@31.3.2/styles/ag-grid.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ag-grid-community@31.3.2/styles/ag-theme-alpine.min.css">
  <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
  <?= $this->renderSection('styles') ?>
  <script>
    (function() {
      var s = localStorage.getItem('pk_theme') || '<?= session()->get('theme_mode') ?? 'system' ?>';
      var d = s === 'dark' || (s === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches) ? 'dark' : 'light';
      document.documentElement.setAttribute('data-theme', d);
      if (localStorage.getItem('pk_read_mode') === '1') document.documentElement.classList.add('mode-baca-pre');
    })();
  </script>
</head>
<body class="<?= session()->get('read_mode') ? 'mode-baca' : '' ?>">

<!-- Flash messages -->
<?php if (session()->getFlashdata('success')): ?>
  <span data-flash="success" data-message="<?= esc(session()->getFlashdata('success')) ?>" style="display:none"></span>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
  <span data-flash="error" data-message="<?= esc(session()->getFlashdata('error')) ?>" style="display:none"></span>
<?php endif; ?>

<div class="app-wrapper">
  <div class="sidebar-overlay"></div>

  <!-- ======= SIDEBAR ======= -->
  <aside class="sidebar">
    <a href="<?= base_url('dashboard') ?>" class="sidebar-brand">
      <div class="sidebar-brand-icon">🌾</div>
      <div class="sidebar-brand-text">
        <div class="sidebar-brand-name">PanenKu</div>
        <div class="sidebar-brand-tagline">Catat Hasil Panen</div>
      </div>
    </a>

    <nav class="sidebar-nav">
      <div class="sidebar-section">Utama</div>
      <a href="<?= base_url('dashboard') ?>" class="nav-item <?= (uri_string() === 'dashboard') ? 'active' : '' ?>">
        <span class="nav-icon"><i class="bi bi-speedometer2"></i></span> Dashboard
      </a>

      <div class="sidebar-section">Data Master</div>
      <a href="<?= base_url('tanaman') ?>" class="nav-item <?= str_starts_with(uri_string(), 'tanaman') ? 'active' : '' ?>">
        <span class="nav-icon"><i class="bi bi-flower1"></i></span> Data Tanaman
      </a>
      <a href="<?= base_url('lahan') ?>" class="nav-item <?= str_starts_with(uri_string(), 'lahan') ? 'active' : '' ?>">
        <span class="nav-icon"><i class="bi bi-map"></i></span> Data Lahan
      </a>

      <div class="sidebar-section">Panen</div>
      <a href="<?= base_url('panen') ?>" class="nav-item <?= str_starts_with(uri_string(), 'panen') ? 'active' : '' ?>">
        <span class="nav-icon"><i class="bi bi-basket3"></i></span> Pencatatan Panen
      </a>
      <a href="<?= base_url('riwayat') ?>" class="nav-item <?= str_starts_with(uri_string(), 'riwayat') ? 'active' : '' ?>">
        <span class="nav-icon"><i class="bi bi-clock-history"></i></span> Riwayat Panen
      </a>

      <div class="sidebar-section">Laporan</div>
      <a href="<?= base_url('laporan') ?>" class="nav-item <?= str_starts_with(uri_string(), 'laporan') ? 'active' : '' ?>">
        <span class="nav-icon"><i class="bi bi-file-earmark-text"></i></span> Laporan Panen
      </a>
      <a href="<?= base_url('grafik') ?>" class="nav-item <?= str_starts_with(uri_string(), 'grafik') ? 'active' : '' ?>">
        <span class="nav-icon"><i class="bi bi-bar-chart-line"></i></span> Grafik & Analisis
      </a>

      <div class="sidebar-section">Akun</div>
      <a href="<?= base_url('profil') ?>" class="nav-item <?= str_starts_with(uri_string(), 'profil') ? 'active' : '' ?>">
        <span class="nav-icon"><i class="bi bi-person-circle"></i></span> Profil & Pengaturan
      </a>
      <a href="<?= base_url('logout') ?>" class="nav-item" onclick="return confirm('Yakin ingin keluar?')">
        <span class="nav-icon"><i class="bi bi-box-arrow-left"></i></span> Keluar
      </a>
    </nav>

    <div class="sidebar-footer">
      <a href="<?= base_url('profil') ?>" class="sidebar-user">
        <div class="sidebar-user-avatar">
          <?php $avatar = session()->get('user_avatar'); ?>
          <?php if ($avatar): ?>
            <img src="<?= base_url('uploads/avatars/' . $avatar) ?>" alt="avatar" style="width:100%;height:100%;object-fit:cover;">
          <?php else: ?>
            <?= strtoupper(substr(session()->get('user_nama') ?? 'U', 0, 1)) ?>
          <?php endif; ?>
        </div>
        <div class="sidebar-user-info">
          <div class="sidebar-user-name"><?= esc(session()->get('user_nama') ?? 'Pengguna') ?></div>
          <div class="sidebar-user-role">Petani</div>
        </div>
      </a>
    </div>
  </aside>

  <!-- ======= MAIN CONTENT ======= -->
  <div class="main-content">
    <header class="navbar">
      <div class="navbar-left">
        <button class="btn-menu-toggle" aria-label="Toggle sidebar">
          <i class="bi bi-list"></i>
        </button>
        <h1 class="page-title"><?= esc($title ?? 'Dashboard') ?></h1>
      </div>
      <div class="navbar-right">
        <button class="navbar-icon-btn <?= session()->get('read_mode') ? 'active' : '' ?>" data-read-mode-toggle title="Mode Baca">
          <i class="bi bi-book"></i>
        </button>
        <div class="theme-switcher">
          <button class="theme-btn" data-theme-btn="light" title="Light"><i class="bi bi-sun"></i></button>
          <button class="theme-btn" data-theme-btn="dark" title="Dark"><i class="bi bi-moon"></i></button>
          <button class="theme-btn" data-theme-btn="system" title="Sistem"><i class="bi bi-circle-half"></i></button>
        </div>
        <div class="navbar-divider"></div>
        <a href="<?= base_url('profil') ?>" class="navbar-user">
          <div class="navbar-user-avatar">
            <?php if ($avatar = session()->get('user_avatar')): ?>
              <img src="<?= base_url('uploads/avatars/' . $avatar) ?>" alt="avatar" style="width:100%;height:100%;border-radius:50%;object-fit:cover;">
            <?php else: ?>
              <?= strtoupper(substr(session()->get('user_nama') ?? 'U', 0, 1)) ?>
            <?php endif; ?>
          </div>
          <div class="navbar-user-info">
            <div class="navbar-user-name"><?= esc(session()->get('user_nama') ?? 'Pengguna') ?></div>
            <div class="navbar-user-role">Petani</div>
          </div>
        </a>
      </div>
    </header>

    <main class="page-body">
      <?= $this->renderSection('content') ?>
    </main>
  </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/ag-grid-community@31.3.2/dist/ag-grid-community.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script src="<?= base_url('assets/js/app.js') ?>"></script>

<!-- GridHelper — shared AG Grid utilities -->
<script>
const GridHelper = {
  gridOptions() {
    return {
      pagination: true,
      paginationPageSize: 10,
      paginationPageSizeSelector: [10, 25, 50, 100],
      suppressMovableColumns: true,
      animateRows: true,
      overlayNoRowsTemplate: '<div style="padding:40px;color:var(--text-muted);text-align:center"><div style="font-size:48px;margin-bottom:10px">🌾</div><div style="font-weight:700;font-size:15px;">Belum ada data</div></div>',
      defaultColDef: {
        resizable: true,
        sortable: true,
        filter: false,
      },
    };
  },
  numberFormatter(p) {
    if (p.value == null || p.value === '') return '-';
    return Number(p.value).toLocaleString('id-ID');
  },
  currencyFormatter(p) {
    if (p.value == null || p.value === '') return '-';
    return 'Rp ' + Number(p.value).toLocaleString('id-ID');
  },
  dateFormatter(p) {
    if (!p.value) return '-';
    const d = new Date(p.value);
    return d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
  },
  kualitasCellRenderer(p) {
    const map = { 'Sangat Baik': 'success', 'Baik': 'primary', 'Cukup': 'warning', 'Kurang': 'danger' };
    const cls = map[p.value] || 'secondary';
    return `<span class="badge badge-${cls}">${p.value || '-'}</span>`;
  },
  statusCellRenderer(p) {
    const aktif = p.value === 'aktif';
    return `<span class="badge badge-${aktif ? 'success' : 'secondary'}">${aktif ? 'Aktif' : 'Tidak Aktif'}</span>`;
  },
};

// Legacy helpers
function getCsrfToken() { return document.querySelector('meta[name="csrf-token"]')?.content || ''; }
async function apiFetch(url, options = {}) {
  const res = await fetch(url, {
    headers: {
      'Content-Type': 'application/json',
      'X-Requested-With': 'XMLHttpRequest',
      'X-CSRF-TOKEN': getCsrfToken(),
      ...(options.headers || {})
    },
    ...options,
  });

  let data = {};

  try {
    data = await res.json();
  } catch (e) {
    data = {};
  }

  if (!res.ok) {
    const error = new Error(
      data.message || `HTTP ${res.status}`
    );

    error.status = res.status;
    error.response = data;

    throw error;
  }

  return data;
}
async function apiDelete(url) {
  return apiFetch(url, { method: 'DELETE', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': getCsrfToken() } });
}
// Modal legacy shim
const _Modal = Modal;
Modal.show = (id) => _Modal.open(id);
Modal.hide = (id) => _Modal.close(id);
Modal.confirm = (msg, cb, opts = {}) => confirmDialog(msg, cb, opts);
</script>

<?= $this->renderSection('scripts') ?>
</body>
</html>