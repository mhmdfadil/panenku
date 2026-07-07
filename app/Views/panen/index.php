<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<!-- Filter Bar -->
<div class="filter-bar">
  <div class="filter-group search">
    <label class="form-label">Cari</label>
    <div class="search-wrapper">
      <i class="bi bi-search"></i>
      <input type="text" id="searchInput" class="form-control search-input" placeholder="Cari tanaman, lahan...">
    </div>
  </div>
  <div class="filter-group">
    <label class="form-label">Tanaman</label>
    <select id="filterTanaman" class="form-control">
      <option value="">Semua Tanaman</option>
      <!-- diisi otomatis oleh JS dari data contoh -->
    </select>
  </div>
  <div class="filter-group">
    <label class="form-label">Lahan</label>
    <select id="filterLahan" class="form-control">
      <option value="">Semua Lahan</option>
      <!-- diisi otomatis oleh JS dari data contoh -->
    </select>
  </div>
  <div class="filter-group">
    <label class="form-label">Kualitas</label>
    <select id="filterKualitas" class="form-control">
      <option value="">Semua</option>
      <option>Sangat Baik</option><option>Baik</option><option>Cukup</option><option>Kurang</option>
    </select>
  </div>
  <div class="filter-group">
    <label class="form-label">Dari</label>
    <input type="date" id="filterDari" class="form-control">
  </div>
  <div class="filter-group">
    <label class="form-label">Sampai</label>
    <input type="date" id="filterSampai" class="form-control">
  </div>
  <div style="display:flex;gap:8px;align-items:flex-end;">
    <button onclick="loadData()" class="btn btn-primary"><i class="bi bi-funnel"></i> Filter</button>
    <button onclick="resetFilter()" class="btn btn-outline" title="Reset"><i class="bi bi-x-lg"></i></button>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <div style="display:flex;align-items:center;gap:8px;">
      <h3 class="card-title"><i class="bi bi-basket3" style="color:var(--pk-primary);"></i> Data Pencatatan Panen</h3>
      <span id="rowCount" class="badge badge-primary"></span>
    </div>
    <div style="display:flex;gap:8px;flex-wrap:wrap;">
      <button onclick="exportCsv()" class="btn btn-outline btn-sm"><i class="bi bi-download"></i> Export CSV</button>
      <a href="/panen/create" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Tambah</a>
    </div>
  </div>
  <div class="card-body" style="padding:0;">
    <div class="ag-grid-wrapper">
      <div class="ag-grid-toolbar">
        <div class="ag-quick-search">
          <i class="bi bi-search"></i>
          <input type="text" id="gridQuickSearch" placeholder="Cari komoditas, lahan..." autocomplete="off">
        </div>
        <div class="ag-toolbar-right">
          <button onclick="exportCsv()" class="btn btn-outline btn-sm"><i class="bi bi-download"></i> CSV</button>
        </div>
      </div>
      <div id="panenGrid" class="ag-theme-panenku" style="width:100%;height:480px;"></div>
    </div>
    <div class="mobile-card-list" id="panenMobile" style="padding:12px;"></div>
  </div>
</div>

<!-- Modal Detail -->
<div id="modalDetail" class="modal-backdrop" style="display:none;">
  <div class="modal">
    <div class="modal-header">
      <h5 class="modal-title"><i class="bi bi-eye"></i> Detail Panen</h5>
      <button class="btn-close" data-modal-close>&times;</button>
    </div>
    <div class="modal-body" id="detailContent">
      <div style="text-align:center;padding:30px;"><i class="bi bi-arrow-clockwise spin" style="font-size:24px;color:var(--text-muted);"></i></div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-outline" data-modal-close>Tutup</button>
    </div>
  </div>
</div>

<script>
/* =========================================================
   DATA CONTOH (statis) — pengganti data dari controller PHP
   ($tanaman & $lahan sebelumnya dikirim via view())
   ========================================================= */
const tanamanOptions = {
  1: 'Padi',
  2: 'Jagung',
  3: 'Cabai',
  4: 'Tomat',
  5: 'Kedelai',
  6: 'Singkong',
};

const lahanOptions = {
  1: 'Sawah Utara',
  2: 'Ladang Timur',
  3: 'Kebun Belakang',
};

(function populateFilterOptions() {
  const tanamanSel = document.getElementById('filterTanaman');
  Object.entries(tanamanOptions).forEach(([id, nama]) => {
    const opt = document.createElement('option');
    opt.value = id; opt.textContent = nama;
    tanamanSel.appendChild(opt);
  });

  const lahanSel = document.getElementById('filterLahan');
  Object.entries(lahanOptions).forEach(([id, nama]) => {
    const opt = document.createElement('option');
    opt.value = id; opt.textContent = nama;
    lahanSel.appendChild(opt);
  });
})();

/* ========================================================= */

let gridApi;
let currentData = [];
let mobilePager;

function kualitasRenderer(p) {
  const map = { 'Sangat Baik':'success', 'Baik':'primary', 'Cukup':'warning', 'Kurang':'danger' };
  const span = document.createElement('span');
  span.className = 'badge badge-' + (map[p.value] || 'secondary');
  span.textContent = p.value || '-';
  return span;
}

function dateFormatter(p) {
  if (!p.value) return '-';
  return new Date(p.value).toLocaleDateString('id-ID', { day:'2-digit', month:'short', year:'numeric' });
}

const colDefs = [
  { headerName:'No', valueGetter: p => p.node.rowIndex + 1, width:65, pinned:'left', sortable:false, suppressSizeToFit:true },
  { headerName:'Tanggal', field:'tanggal_panen', valueFormatter: dateFormatter, sort:'desc', minWidth:130 },
  { headerName:'Komoditas', field:'komoditas', minWidth:140, cellStyle:{ fontWeight:'600' } },
  { headerName:'Lahan', field:'nama_lahan', minWidth:130, cellStyle:{ color:'#4a6070' } },
  { headerName:'Jumlah Panen', field:'jumlah_panen_fmt', minWidth:130 },
  { headerName:'Harga/Satuan', field:'harga_per_kg',  minWidth:130,
    valueFormatter: p => p.value ? 'Rp ' + Number(p.value).toLocaleString('id-ID') : '-' },
  { headerName:'Total Nilai', field:'total_nilai', minWidth:150,
    valueFormatter: p => p.value ? 'Rp ' + Number(p.value).toLocaleString('id-ID') : '-',
    cellStyle: { fontWeight:'700', color:'#2d8a4e' } },
  { headerName:'Kualitas', field:'kualitas', cellRenderer: kualitasRenderer, minWidth:110 },
  { headerName:'Cuaca', field:'cuaca', minWidth:100, cellStyle:{ color:'#4a6070' } },
  {
    headerName:'Aksi', sortable:false, width:130, pinned:'right', suppressSizeToFit:true,
    cellRenderer: p => {
      const div = document.createElement('div');
      div.style.cssText = 'display:flex;align-items:center;gap:4px;height:100%;';
      div.innerHTML = `
        <button class="btn btn-icon btn-sm btn-info" title="Detail"><i class="bi bi-eye"></i></button>
        <a href="/panen/edit/${p.data.id}" class="btn btn-icon btn-sm btn-warning" title="Edit"><i class="bi bi-pencil"></i></a>
        <button class="btn btn-icon btn-sm btn-danger" title="Hapus"><i class="bi bi-trash"></i></button>`;
      div.querySelector('.btn-info').addEventListener('click', () => lihatDetail(p.data.id));
      div.querySelector('.btn-danger').addEventListener('click', () => hapus(p.data.id));
      return div;
    }
  }
];

const _panenEl = document.getElementById('panenGrid');
_panenEl.classList.add('ag-theme-panenku');
agGrid.createGrid(_panenEl, {
  theme: 'legacy',
  columnDefs: colDefs,
  rowData: [],
  pagination: true,
  paginationPageSize: 14,
  paginationPageSizeSelector: [10, 14, 25, 50, 100],
  suppressMovableColumns: true,
  animateRows: true,
  domLayout: 'normal',
  overlayNoRowsTemplate: '<div style="padding:40px;color:#7a95a5;text-align:center"><div style="font-size:44px;margin-bottom:10px">🌾</div><div style="font-weight:700;font-size:15px;">Belum ada data panen</div><div style="margin-top:6px;font-size:13px;">Gunakan filter di atas atau tambah data baru</div></div>',
  defaultColDef: { resizable: true, sortable: true, filter: false },
  onGridReady: p => {
    gridApi = p.api;
    document.getElementById('gridQuickSearch')?.addEventListener('input', debounce(e => {
      gridApi.setGridOption('quickFilterText', e.target.value);
    }, 200));
    loadData();
  },
  onModelUpdated: () => {
    if (gridApi) document.getElementById('rowCount').textContent = gridApi.getDisplayedRowCount() + ' data';
  }
});

async function loadData() {
  const params = new URLSearchParams({
    search:     document.getElementById('searchInput').value,
    tanaman_id: document.getElementById('filterTanaman').value,
    lahan_id:   document.getElementById('filterLahan').value,
    kualitas:   document.getElementById('filterKualitas').value,
    dari:       document.getElementById('filterDari').value,
    sampai:     document.getElementById('filterSampai').value,
  });
  try {
    const res = await apiFetch('/panen/data?' + params);
    currentData = res.data || [];
    if (gridApi) gridApi.setGridOption('rowData', currentData);
    renderMobile(currentData);
  } catch(e) { Toast.show(e.message, 'error'); }
}

const KUALITAS_CLASS = {'Sangat Baik':'success','Baik':'primary','Cukup':'warning','Kurang':'danger'};
const FMT_DATE = v => v ? new Date(v).toLocaleDateString('id-ID',{day:'2-digit',month:'short',year:'numeric'}) : '-';

mobilePager = new MobilePager('panenMobile', [], d => `
  <div class="mobile-data-card">
    <div class="mdc-header">
      <div class="mdc-title">${d.komoditas||'-'}</div>
      <span class="badge badge-${KUALITAS_CLASS[d.kualitas]||'secondary'}">${d.kualitas||'-'}</span>
    </div>
    <div class="mdc-grid">
      <div class="mdc-item"><label>Tanggal</label><span>${FMT_DATE(d.tanggal_panen)}</span></div>
      <div class="mdc-item"><label>Lahan</label><span>${d.nama_lahan||'-'}</span></div>
      <div class="mdc-item"><label>Jumlah</label><span>${Number(d.jumlah_panen||0).toLocaleString('id-ID')} ${d.satuan||'kg'}</span></div>
      <div class="mdc-item"><label>Total Nilai</label><span style="color:var(--pk-primary);font-weight:700;">Rp ${Number(d.total_nilai||0).toLocaleString('id-ID')}</span></div>
    </div>
    <div class="mdc-actions">
      <button class="btn btn-info btn-sm" onclick="lihatDetail(${d.id})"><i class="bi bi-eye"></i> Detail</button>
      <a href="/panen/edit/${d.id}" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i> Edit</a>
      <button class="btn btn-danger btn-sm btn-icon" onclick="hapus(${d.id})"><i class="bi bi-trash"></i></button>
    </div>
  </div>`, { pageSize: 8, placeholder: 'Cari komoditas, lahan...' });

function renderMobile(data) { mobilePager.setData(data); }

function resetFilter() {
  ['searchInput','filterTanaman','filterLahan','filterKualitas','filterDari','filterSampai'].forEach(id => {
    const el = document.getElementById(id); if (el) el.value = '';
  });
  loadData();
}

function exportCsv() {
  if (gridApi) gridApi.exportDataAsCsv({ fileName: 'panen-' + new Date().toISOString().slice(0,10) + '.csv' });
}

async function lihatDetail(id) {
  Modal.open('modalDetail');
  document.getElementById('detailContent').innerHTML = '<div style="text-align:center;padding:30px;"><i class="bi bi-arrow-clockwise spin" style="font-size:26px;color:var(--text-muted);"></i></div>';
  try {
    const res = await apiFetch('/panen/show/' + id);
    const d = res.data;
    const fmt = v => v ? new Date(v).toLocaleDateString('id-ID',{day:'2-digit',month:'long',year:'numeric'}) : '-';
    const rp = v => v ? 'Rp ' + Number(v).toLocaleString('id-ID') : '-';
    const kMap = {'Sangat Baik':'success','Baik':'primary','Cukup':'warning','Kurang':'danger'};
    document.getElementById('detailContent').innerHTML = `
      <div class="grid-2" style="gap:12px;">
        <div class="nm-box"><div class="form-label">Tanggal Panen</div><strong>${fmt(d.tanggal_panen)}</strong></div>
        <div class="nm-box"><div class="form-label">Komoditas</div><strong>${d.komoditas||d.nama_tanaman||'-'}</strong></div>
        <div class="nm-box"><div class="form-label">Lahan</div><strong>${d.nama_lahan||'-'}</strong></div>
        <div class="nm-box"><div class="form-label">Jumlah</div><strong>${Number(d.jumlah_panen||0).toLocaleString('id-ID')} ${d.satuan||'kg'}</strong></div>
        <div class="nm-box"><div class="form-label">Harga per ${d.satuan||'kg'}</div><strong>${rp(d.harga_per_kg)}</strong></div>
        <div class="nm-box"><div class="form-label">Total Nilai</div><strong style="color:var(--pk-primary);">${rp(d.total_nilai)}</strong></div>
        <div class="nm-box"><div class="form-label">Kualitas</div><span class="badge badge-${kMap[d.kualitas]||'secondary'}">${d.kualitas||'-'}</span></div>
        <div class="nm-box"><div class="form-label">Kondisi Cuaca</div><strong>${d.cuaca||'-'}</strong></div>
      </div>
      ${d.catatan ? `<div class="nm-box" style="margin-top:12px;"><div class="form-label">Catatan</div><p style="margin:0;color:var(--text-secondary);">${d.catatan}</p></div>` : ''}`;
  } catch(e) {
    document.getElementById('detailContent').innerHTML = '<p style="color:var(--pk-danger);text-align:center;">Gagal memuat detail.</p>';
  }
}

function hapus(id) {
  confirmDialog('Yakin ingin menghapus data panen ini?', async () => {
    try {
      const res = await apiDelete('/panen/delete/' + id);
      if (res.status === 'success') { Toast.show('Data berhasil dihapus.', 'success'); loadData(); }
      else Toast.show(res.message || 'Gagal menghapus.', 'error');
    } catch(e) { Toast.show(e.message, 'error'); }
  }, { title:'Hapus Data Panen', type:'danger', confirmText:'Ya, Hapus' });
}

document.getElementById('searchInput').addEventListener('keyup', e => { if (e.key === 'Enter') loadData(); });
</script>

<?= $this->endSection() ?>