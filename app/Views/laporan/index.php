<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Panen - PanenKu</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script src="https://cdn.jsdelivr.net/npm/ag-grid-community/dist/ag-grid-community.min.js"></script>
</head>
<body>

<div class="filter-bar">
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
    <label class="form-label">Dari Tanggal</label>
    <input type="date" id="filterDari" class="form-control">
  </div>
  <div class="filter-group">
    <label class="form-label">Sampai Tanggal</label>
    <input type="date" id="filterSampai" class="form-control">
  </div>
  <div style="display:flex;gap:8px;align-items:flex-end;">
    <button onclick="loadData()" class="btn btn-primary"><i class="bi bi-search"></i> Tampilkan</button>
    <button onclick="cetakLaporan()" class="btn btn-outline"><i class="bi bi-printer"></i> Cetak</button>
  </div>
</div>

<div class="grid-4" style="margin-bottom:20px;">
  <div class="stat-card" style="--stat-color:#2d8a4e;--stat-bg:#e8f5ee;">
    <div class="stat-icon"><i class="bi bi-basket3-fill"></i></div>
    <div class="stat-content"><div class="stat-label">Total Panen</div><div class="stat-value" id="sumCount">—</div></div>
  </div>
  <div class="stat-card" style="--stat-color:#3498db;--stat-bg:#d6eaf8;">
    <div class="stat-icon"><i class="bi bi-speedometer2"></i></div>
    <div class="stat-content"><div class="stat-label">Total Produksi</div><div class="stat-value stat-value-stack" id="sumProduksi">—</div></div>
  </div>
  <div class="stat-card" style="--stat-color:#8e44ad;--stat-bg:#f3e5f5;">
    <div class="stat-icon"><i class="bi bi-wallet2"></i></div>
    <div class="stat-content"><div class="stat-label">Total Nilai</div><div class="stat-value" id="sumNilai" style="font-size:16px;">—</div></div>
  </div>
  <div class="stat-card" style="--stat-color:#e67e22;--stat-bg:#fdebd0;">
    <div class="stat-icon"><i class="bi bi-graph-up"></i></div>
    <div class="stat-content"><div class="stat-label">Rata-rata/Panen</div><div class="stat-value" id="sumAvg">—</div></div>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <div style="display:flex;align-items:center;gap:8px;">
      <h3 class="card-title"><i class="bi bi-file-earmark-text" style="color:var(--pk-primary);"></i> Data Laporan Panen</h3>
      <span id="rowCount" class="badge badge-primary"></span>
    </div>
    <div style="display:flex;gap:8px;flex-wrap:wrap;">
      <button onclick="gridApi?.exportDataAsCsv({fileName:'laporan-panen.csv'})" class="btn btn-outline btn-sm"><i class="bi bi-file-earmark-csv"></i> CSV</button>
      <button onclick="cetakLaporan()" class="btn btn-primary btn-sm"><i class="bi bi-printer"></i> Cetak Laporan</button>
    </div>
  </div>
  <div class="card-body" style="padding:0;">
    <div class="ag-grid-wrapper">
      <div class="ag-grid-toolbar">
        <div class="ag-quick-search">
          <i class="bi bi-search"></i>
          <input type="text" id="gridQS" placeholder="Cari komoditas, lahan..." autocomplete="off">
        </div>
        <div class="ag-toolbar-right">
          <button onclick="cetakLaporan()" class="btn btn-outline btn-sm"><i class="bi bi-printer"></i> Cetak</button>
          <button onclick="gridApi?.exportDataAsCsv({fileName:'laporan-panen.csv'})" class="btn btn-outline btn-sm"><i class="bi bi-download"></i> CSV</button>
        </div>
      </div>
      <div id="laporanGrid" class="ag-theme-panenku" style="width:100%;height:480px;"></div>
    </div>
    <div class="mobile-card-list" id="laporanMobile" style="padding:12px;"></div>
  </div>
</div>

<script>
/* =========================================================
   DATA CONTOH (statis) — pengganti data dari controller PHP
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
let mobilePager;

function kualitasRenderer(p) {
  const map = { 'Sangat Baik':'success', 'Baik':'primary', 'Cukup':'warning', 'Kurang':'danger' };
  const span = document.createElement('span');
  span.className = 'badge badge-' + (map[p.value] || 'secondary');
  span.textContent = p.value || '-';
  return span;
}

const colDefs = [
  { headerName:'No', valueGetter: p => p.node.rowIndex + 1, width:65, sortable:false, suppressSizeToFit:true },
  { headerName:'Tanggal', field:'tanggal_panen', sort:'desc', minWidth:130,
    valueFormatter: p => p.value ? new Date(p.value).toLocaleDateString('id-ID',{day:'2-digit',month:'short',year:'numeric'}) : '-' },
  { headerName:'Komoditas', field:'nama_tanaman', minWidth:130, cellStyle:{ fontWeight:'600' } },
  { headerName:'Varietas', field:'varietas', minWidth:120, cellStyle:{ color:'#4a6070' } },
  { headerName:'Lahan', field:'nama_lahan', minWidth:130 },
  { headerName:'Jumlah Panen', field:'jumlah_panen_fmt', minWidth:140 },
  { headerName:'Harga/Satuan', field:'harga_per_kg', minWidth:130,
    valueFormatter: p => p.value ? 'Rp ' + Number(p.value).toLocaleString('id-ID') : '-' },
  { headerName:'Total Nilai', field:'total_nilai', minWidth:155, flex:1,
    valueFormatter: p => p.value ? 'Rp ' + Number(p.value).toLocaleString('id-ID') : '-',
    cellStyle: { fontWeight:'700', color:'#2d8a4e' } },
  { headerName:'Kualitas', field:'kualitas', cellRenderer: kualitasRenderer, minWidth:110 },
  { headerName:'Catatan', field:'catatan', minWidth:160, cellStyle:{ color:'#4a6070' } },
];

const _laporanEl = document.getElementById('laporanGrid');
_laporanEl.classList.add('ag-theme-panenku');
agGrid.createGrid(_laporanEl, {
  theme: 'legacy',
  columnDefs: colDefs,
  rowData: [],
  pagination: true,
  paginationPageSize: 14,
  paginationPageSizeSelector: [10, 14, 25, 50, 100],
  suppressMovableColumns: true,
  animateRows: true,
  domLayout: 'normal',
  overlayNoRowsTemplate: '<div style="padding:40px;color:#7a95a5;text-align:center"><div style="font-size:44px;margin-bottom:10px">📋</div><div style="font-weight:700;font-size:15px;">Belum ada data</div><div style="margin-top:6px;font-size:13px;">Gunakan filter di atas untuk menampilkan laporan</div></div>',
  defaultColDef: { resizable: true, sortable: true, filter: false },
  onGridReady: p => {
    gridApi = p.api;
    document.getElementById('gridQS')?.addEventListener('input', debounce(e => {
      gridApi.setGridOption('quickFilterText', e.target.value);
    }, 200));
  },
  onModelUpdated: () => {
    if (gridApi) document.getElementById('rowCount').textContent = gridApi.getDisplayedRowCount() + ' data';
  }
});
setTimeout(() => loadData(), 0);

async function loadData() {
  const params = new URLSearchParams({ tanaman_id:document.getElementById('filterTanaman').value, lahan_id:document.getElementById('filterLahan').value, dari:document.getElementById('filterDari').value, sampai:document.getElementById('filterSampai').value });
  try {
    gridApi?.showLoadingOverlay();
    const res = await apiFetch('/laporan/data?' + params);
    gridApi?.setGridOption('rowData', res.data||[]);
    document.getElementById('sumCount').textContent = res.total||0;
    const sumProduksiEl = document.getElementById('sumProduksi');
    sumProduksiEl.innerHTML = (res.total_produksi_fmt || '0')
  .split('•').map(s => `<span>${s.trim()}</span>`).join('');
    document.getElementById('sumNilai').textContent = 'Rp '+Number(res.total_nilai||0).toLocaleString('id-ID');
    document.getElementById('sumAvg').textContent = res.total>0?(res.total_produksi/res.total).toLocaleString('id-ID',{maximumFractionDigits:1}):'0';
    renderMobile(res.data||[]);
  } catch(e) { Toast.show(e.message, 'error'); }
}

const KQ2 = {'Sangat Baik':'success','Baik':'primary','Cukup':'warning','Kurang':'danger'};
mobilePager = new MobilePager('laporanMobile', [], d => `
  <div class="mobile-data-card">
    <div class="mdc-header">
      <div class="mdc-title">${d.nama_tanaman||'-'}</div>
      <span class="badge badge-${KQ2[d.kualitas]||'secondary'}">${d.kualitas||'-'}</span>
    </div>
    <div class="mdc-grid">
      <div class="mdc-item"><label>Tanggal</label><span>${d.tanggal_panen ? new Date(d.tanggal_panen).toLocaleDateString('id-ID',{day:'2-digit',month:'short',year:'numeric'}) : '-'}</span></div>
      <div class="mdc-item"><label>Lahan</label><span>${d.nama_lahan||'-'}</span></div>
      <div class="mdc-item"><label>Jumlah</label><span>${Number(d.jumlah_panen||0).toLocaleString('id-ID')} ${d.satuan||'kg'}</span></div>
      <div class="mdc-item"><label>Total Nilai</label><span style="color:var(--pk-primary);font-weight:700;">Rp ${Number(d.total_nilai||0).toLocaleString('id-ID')}</span></div>
    </div>
  </div>`, { pageSize: 10, placeholder: 'Cari laporan...' });

function renderMobile(data) { mobilePager.setData(data); }

function cetakLaporan() {
  const params = new URLSearchParams({ tanaman_id:document.getElementById('filterTanaman').value, lahan_id:document.getElementById('filterLahan').value, dari:document.getElementById('filterDari').value, sampai:document.getElementById('filterSampai').value });
  window.open('/laporan/cetak?'+params,'_blank');
}
</script>

</body>
</html>