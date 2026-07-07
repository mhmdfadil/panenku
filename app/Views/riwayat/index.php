<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Riwayat Semua Panen - PanenKu</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script src="https://cdn.jsdelivr.net/npm/ag-grid-community/dist/ag-grid-community.min.js"></script>
</head>
<body>

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

<!-- Summary (visible after load) -->
<div id="summaryCards" class="grid-4" style="margin-bottom:20px;display:none;">
  <div class="stat-card" style="--stat-color:#2d8a4e;--stat-bg:#e8f5ee;">
    <div class="stat-icon"><i class="bi bi-basket3-fill"></i></div>
    <div class="stat-content"><div class="stat-label">Jumlah Panen</div><div class="stat-value" id="sumCount">0</div></div>
  </div>
  <div class="stat-card" style="--stat-color:#3498db;--stat-bg:#d6eaf8;">
    <div class="stat-icon"><i class="bi bi-speedometer2"></i></div>
    <div class="stat-content"><div class="stat-label">Total Produksi</div><div class="stat-value stat-value-stack" id="sumProduksi">0 kg</div></div>
  </div>
  <div class="stat-card" style="--stat-color:#8e44ad;--stat-bg:#f3e5f5;">
    <div class="stat-icon"><i class="bi bi-wallet2"></i></div>
    <div class="stat-content"><div class="stat-label">Total Nilai</div><div class="stat-value" id="sumNilai" style="font-size:16px;">Rp 0</div></div>
  </div>
  <div class="stat-card" style="--stat-color:#e67e22;--stat-bg:#fdebd0;">
    <div class="stat-icon"><i class="bi bi-graph-up"></i></div>
    <div class="stat-content"><div class="stat-label">Rata-rata/Panen</div><div class="stat-value" id="sumAvg">0 kg</div></div>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <div style="display:flex;align-items:center;gap:8px;">
      <h3 class="card-title"><i class="bi bi-clock-history" style="color:var(--pk-primary);"></i> Riwayat Semua Panen</h3>
      <span id="rowCount" class="badge badge-primary"></span>
    </div>
    <button onclick="gridApi?.exportDataAsCsv({fileName:'riwayat-panen.csv'})" class="btn btn-outline btn-sm">
      <i class="bi bi-download"></i> Export CSV
    </button>
  </div>
  <div class="card-body" style="padding:0;">
    <div class="ag-grid-wrapper">
      <div class="ag-grid-toolbar">
        <div class="ag-quick-search">
          <i class="bi bi-search"></i>
          <input type="text" id="gridQS" placeholder="Cari komoditas, lahan, kualitas..." autocomplete="off">
        </div>
        <div class="ag-toolbar-right">
          <button onclick="gridApi?.exportDataAsCsv({fileName:'riwayat-panen.csv'})" class="btn btn-outline btn-sm"><i class="bi bi-download"></i> CSV</button>
        </div>
      </div>
      <div id="riwayatGrid" class="ag-theme-panenku" style="width:100%;height:480px;"></div>
    </div>
    <div class="mobile-card-list" id="riwayatMobile" style="padding:12px;"></div>
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

function fmtDate(v) {
  if (!v) return '-';
  return new Date(v).toLocaleDateString('id-ID', { day:'2-digit', month:'short', year:'numeric' });
}

const colDefs = [
  { headerName:'No', valueGetter: p => p.node.rowIndex + 1, width:65, pinned:'left', sortable:false, suppressSizeToFit:true },
  { headerName:'Tanggal', field:'tanggal_panen', valueFormatter: p => fmtDate(p.value), sort:'desc', minWidth:130 },
  { headerName:'Komoditas', field:'komoditas', minWidth:140, cellStyle:{ fontWeight:'600' } },
  { headerName:'Lahan', field:'nama_lahan', minWidth:130, cellStyle:{ color:'#4a6070' } },
  { headerName:'Jumlah Panen', field:'jumlah_panen',  minWidth:130,
    valueFormatter: p => p.value ? Number(p.value).toLocaleString('id-ID') + ' ' + (p.data?.satuan||'kg') : '-' },
  { headerName:'Harga/Satuan', field:'harga_per_kg', minWidth:130,
    valueFormatter: p => p.value ? 'Rp ' + Number(p.value).toLocaleString('id-ID') : '-' },
  { headerName:'Total Nilai', field:'total_nilai', minWidth:155,
    valueFormatter: p => p.value ? 'Rp ' + Number(p.value).toLocaleString('id-ID') : '-',
    cellStyle: { fontWeight:'700', color:'#2d8a4e' } },
  { headerName:'Kualitas', field:'kualitas', cellRenderer: kualitasRenderer, minWidth:110 },
  { headerName:'Cuaca', field:'cuaca', minWidth:100, cellStyle:{ color:'#4a6070' } },
  { headerName:'Catatan', field:'catatan', minWidth:160, flex:1, cellStyle:{ color:'#4a6070' } },
  {
    headerName:'Aksi', sortable:false, width:110, pinned:'right', suppressSizeToFit:true,
    cellRenderer: p => {
      const div = document.createElement('div');
      div.style.cssText = 'display:flex;align-items:center;gap:4px;height:100%;';
      div.innerHTML = `<a href="/panen/edit/${p.data.id}" class="btn btn-icon btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                       <button class="btn btn-icon btn-sm btn-danger"><i class="bi bi-trash"></i></button>`;
      div.querySelector('.btn-danger').addEventListener('click', () => hapus(p.data.id));
      return div;
    }
  }
];

const _riwayatEl = document.getElementById('riwayatGrid');
_riwayatEl.classList.add('ag-theme-panenku');
agGrid.createGrid(_riwayatEl, {
  theme: 'legacy',
  columnDefs: colDefs,
  rowData: [],
  pagination: true,
  paginationPageSize: 14,
  paginationPageSizeSelector: [10, 14, 25, 50, 100],
  suppressMovableColumns: true,
  animateRows: true,
  domLayout: 'normal',
  overlayNoRowsTemplate: '<div style="padding:40px;color:#7a95a5;text-align:center"><div style="font-size:44px;margin-bottom:10px">🌾</div><div style="font-weight:700;font-size:15px;">Belum ada data riwayat</div></div>',
  defaultColDef: { resizable: true, sortable: true, filter: false },
  onGridReady: p => {
    gridApi = p.api;
    document.getElementById('gridQS')?.addEventListener('input', debounce(e => {
      gridApi.setGridOption('quickFilterText', e.target.value);
    }, 200));
    loadData();
  },
  onModelUpdated: updateSummary
});

async function loadData() {
  const params = new URLSearchParams({ search:document.getElementById('searchInput').value, tanaman_id:document.getElementById('filterTanaman').value, lahan_id:document.getElementById('filterLahan').value, kualitas:document.getElementById('filterKualitas').value, dari:document.getElementById('filterDari').value, sampai:document.getElementById('filterSampai').value });
  try {
    gridApi?.showLoadingOverlay();
    const res = await apiFetch('/riwayat/data?' + params);
    const data = res.data||[];
    gridApi?.setGridOption('rowData', data);
    document.getElementById('summaryCards').style.display = 'grid';
    renderMobile(data);
  } catch(e) { Toast.show(e.message, 'error'); }
}

const KQ = {'Sangat Baik':'success','Baik':'primary','Cukup':'warning','Kurang':'danger'};
const FD = v => v ? new Date(v).toLocaleDateString('id-ID',{day:'2-digit',month:'short',year:'numeric'}) : '-';

mobilePager = new MobilePager('riwayatMobile', [], d => `
  <div class="mobile-data-card">
    <div class="mdc-header">
      <div class="mdc-title">${d.komoditas||'-'}</div>
      <span class="badge badge-${KQ[d.kualitas]||'secondary'}">${d.kualitas||'-'}</span>
    </div>
    <div class="mdc-grid">
      <div class="mdc-item"><label>Tanggal</label><span>${FD(d.tanggal_panen)}</span></div>
      <div class="mdc-item"><label>Lahan</label><span>${d.nama_lahan||'-'}</span></div>
      <div class="mdc-item"><label>Jumlah</label><span>${Number(d.jumlah_panen||0).toLocaleString('id-ID')} ${d.satuan||'kg'}</span></div>
      <div class="mdc-item"><label>Total Nilai</label><span style="color:var(--pk-primary);font-weight:700;">Rp ${Number(d.total_nilai||0).toLocaleString('id-ID')}</span></div>
    </div>
    <div class="mdc-actions">
      <a href="/panen/edit/${d.id}" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i> Edit</a>
      <button onclick="hapus(${d.id})" class="btn btn-danger btn-sm btn-icon"><i class="bi bi-trash"></i></button>
    </div>
  </div>`, { pageSize: 8, placeholder: 'Cari riwayat...' });

function renderMobile(data) { mobilePager.setData(data); }

function updateSummary() {
  let count=0, nilai=0;
  const perSatuan = {};
  gridApi?.forEachNodeAfterFilter(n=>{
    count++;
    const s = n.data.satuan || 'kg';
    perSatuan[s] = (perSatuan[s]||0) + (parseFloat(n.data.jumlah_panen)||0);
    nilai += parseFloat(n.data.total_nilai)||0;
  });
  const produksiFmt = Object.entries(perSatuan)
    .map(([s,v]) => v.toLocaleString('id-ID') + ' ' + s).join(' • ') || '0';
  const totalNum = Object.values(perSatuan).reduce((a,b)=>a+b, 0);
  document.getElementById('rowCount').textContent = count+' data';
  document.getElementById('sumCount').textContent = count;
  document.getElementById('sumProduksi').innerHTML = produksiFmt
    .split('•').map(s => `<span>${s.trim()}</span>`).join('');
  document.getElementById('sumNilai').textContent = 'Rp '+nilai.toLocaleString('id-ID');
  document.getElementById('sumAvg').textContent = count>0?(totalNum/count).toLocaleString('id-ID',{maximumFractionDigits:1}):'0';
}

function resetFilter() {
  ['searchInput','filterTanaman','filterLahan','filterKualitas','filterDari','filterSampai'].forEach(id=>{ const el=document.getElementById(id); if(el) el.value=''; });
  loadData();
}

function hapus(id) {
  confirmDialog('Yakin ingin menghapus data ini?',async()=>{
    try{const res=await apiDelete('/panen/delete/'+id);if(res.status==='success'){Toast.show('Data berhasil dihapus.','success');loadData();}else Toast.show(res.message||'Gagal.','error');}
    catch(e){ Toast.show(e.message, 'error'); }
  },{title:'Hapus Data',type:'danger'});
}

document.getElementById('searchInput').addEventListener('keyup',e=>{if(e.key==='Enter')loadData();});
</script>

</body>
</html>