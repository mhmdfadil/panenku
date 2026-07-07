<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Data Lahan - PanenKu</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script src="https://cdn.jsdelivr.net/npm/ag-grid-community/dist/ag-grid-community.min.js"></script>
</head>
<body>

<div class="card">
  <div class="card-header">
    <div style="display:flex;align-items:center;gap:8px;">
      <h3 class="card-title"><i class="bi bi-map" style="color:var(--pk-primary);"></i> Data Lahan</h3>
      <span id="rowCount" class="badge badge-primary"></span>
    </div>
    <button onclick="openAdd()" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Tambah Lahan</button>
  </div>
  <div class="card-body" style="padding:0;">
    <div class="ag-grid-wrapper">
      <div class="ag-grid-toolbar">
        <div class="ag-quick-search">
          <i class="bi bi-search"></i>
          <input type="text" id="quickSearch" placeholder="Cari nama, lokasi, jenis..." autocomplete="off">
        </div>
        <div class="ag-toolbar-right">
          <button onclick="gridApi?.exportDataAsCsv({fileName:'lahan.csv'})" class="btn btn-outline btn-sm"><i class="bi bi-download"></i> CSV</button>
        </div>
      </div>
      <div id="lahanGrid" class="ag-theme-panenku" style="width:100%;height:430px;"></div>
    </div>
    <div class="mobile-card-list" id="lahanMobile" style="padding:12px;"></div>
  </div>
</div>

<div id="modalForm" class="modal-backdrop" style="display:none;">
  <div class="modal">
    <div class="modal-header">
      <h5 class="modal-title" id="modalTitle">Tambah Lahan</h5>
      <button class="btn-close" data-modal-close>&times;</button>
    </div>
    <div class="modal-body">
      <input type="hidden" id="editId">
      <div class="grid-2" style="gap:12px;">
        <div class="form-group mb-0">
          <label class="form-label">Nama Lahan <span style="color:var(--pk-danger)">*</span></label>
          <input type="text" id="fNama" class="form-control" placeholder="Lahan Sawah 1...">
        </div>
        <div class="form-group mb-0">
          <label class="form-label">Jenis Lahan</label>
          <select id="fJenis" class="form-control">
            <option>Sawah</option><option>Ladang</option><option>Kebun</option><option>Tegalan</option><option>Lainnya</option>
          </select>
        </div>
        <div class="form-group mb-0">
          <label class="form-label">Luas (hektar)</label>
          <input type="number" id="fLuas" class="form-control" step="0.0001" placeholder="0.75">
        </div>
        <div class="form-group mb-0">
          <label class="form-label">Status</label>
          <select id="fStatus" class="form-control">
            <option value="aktif">Aktif</option><option value="tidak aktif">Tidak Aktif</option>
          </select>
        </div>
      </div>
      <div class="form-group" style="margin-top:12px;">
        <label class="form-label">Lokasi</label>
        <input type="text" id="fLokasi" class="form-control" placeholder="Blok A, Desa Sukamaju...">
      </div>
      <div class="form-group" style="margin-bottom:0;">
        <label class="form-label">Keterangan</label>
        <textarea id="fKet" class="form-control" rows="2"></textarea>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-outline" data-modal-close>Batal</button>
      <button class="btn btn-primary" onclick="simpan()"><i class="bi bi-check-lg"></i> Simpan</button>
    </div>
  </div>
</div>

<script>
let gridApi;
let mobilePager;

function statusRenderer(p) {
  const span = document.createElement('span');
  span.className = 'badge badge-' + (p.value === 'aktif' ? 'success' : 'secondary');
  span.textContent = p.value === 'aktif' ? 'Aktif' : 'Tidak Aktif';
  return span;
}

const colDefs = [
  { headerName:'No', valueGetter: p => p.node.rowIndex + 1, width:65, sortable:false, suppressSizeToFit:true },
  { headerName:'Nama Lahan', field:'nama_lahan', minWidth:160, flex:1, cellStyle:{ fontWeight:'600' } },
  { headerName:'Jenis', field:'jenis_lahan', minWidth:100 },
  { headerName:'Luas (ha)', field:'luas', minWidth:110,
    valueFormatter: p => p.value ? Number(p.value).toLocaleString('id-ID', {minimumFractionDigits:2,maximumFractionDigits:4}) + ' ha' : '-' },
  { headerName:'Lokasi', field:'lokasi', minWidth:160, cellStyle:{ color:'#4a6070' } },
  { headerName:'Status', field:'status', cellRenderer: statusRenderer, width:110 },
  {
    headerName:'Aksi', sortable:false, width:110, suppressSizeToFit:true, pinned:'right',
    cellRenderer: p => {
      const div = document.createElement('div');
      div.style.cssText = 'display:flex;align-items:center;gap:4px;height:100%;';
      div.innerHTML = `<button class="btn btn-icon btn-sm btn-warning"><i class="bi bi-pencil"></i></button>
                       <button class="btn btn-icon btn-sm btn-danger"><i class="bi bi-trash"></i></button>`;
      div.querySelector('.btn-warning').addEventListener('click', () => openEdit(p.data.id));
      div.querySelector('.btn-danger').addEventListener('click', () => hapus(p.data.id));
      return div;
    }
  }
];

const _lahanEl = document.getElementById('lahanGrid');
_lahanEl.classList.add('ag-theme-panenku');
agGrid.createGrid(_lahanEl, {
  theme: 'legacy',
  columnDefs: colDefs, rowData: [],
  pagination: true, paginationPageSize: 14,
      paginationPageSizeSelector: [10, 14, 25, 50, 100],
      cellStyle: params => {
        if (typeof params.value === 'number') {
            return { textAlign: 'right' };
        }
    },
  suppressMovableColumns: true, animateRows: true, domLayout: 'normal',
  overlayNoRowsTemplate: '<div style="padding:32px;color:#7a95a5;text-align:center"><div style="font-size:40px;margin-bottom:8px">🗺️</div><div style="font-weight:700">Belum ada data lahan</div></div>',
  defaultColDef: { resizable: true, sortable: true, filter: false },
  onGridReady: p => { gridApi = p.api; loadData(); },
  onModelUpdated: () => { if (gridApi) document.getElementById('rowCount').textContent = gridApi.getDisplayedRowCount() + ' data'; }
});

document.getElementById('quickSearch')?.addEventListener('input', debounce(e => {
  gridApi?.setGridOption('quickFilterText', e.target.value);
}, 200));

mobilePager = new MobilePager('lahanMobile', [], d => `
  <div class="mobile-data-card">
    <div class="mdc-header">
      <div class="mdc-title">${d.nama_lahan||'-'}</div>
      <span class="badge badge-${d.status==='aktif'?'success':'secondary'}">${d.status==='aktif'?'Aktif':'Tidak Aktif'}</span>
    </div>
    <div class="mdc-grid">
      <div class="mdc-item"><label>Jenis</label><span>${d.jenis_lahan||'-'}</span></div>
      <div class="mdc-item"><label>Luas</label><span>${d.luas ? Number(d.luas).toLocaleString('id-ID',{maximumFractionDigits:4})+' ha' : '-'}</span></div>
      <div class="mdc-item" style="grid-column:1/-1;"><label>Lokasi</label><span>${d.lokasi||'-'}</span></div>
    </div>
    <div class="mdc-actions">
      <button class="btn btn-warning btn-sm" onclick="openEdit(${d.id})"><i class="bi bi-pencil"></i> Edit</button>
      <button class="btn btn-danger btn-sm" onclick="hapus(${d.id})"><i class="bi bi-trash"></i> Hapus</button>
    </div>
  </div>`, { pageSize: 8, placeholder: 'Cari lahan...' });

async function loadData() {
  try {
    const res = await apiFetch('/lahan/data');
    const data = res.data || [];
    gridApi?.setGridOption('rowData', data);
    mobilePager.setData(data);
  } catch(e) { Toast.show(e.message, 'error'); }
}

function openAdd() {
  document.getElementById('modalTitle').textContent = 'Tambah Lahan';
  document.getElementById('editId').value = '';
  ['fNama','fLuas','fLokasi','fKet'].forEach(id => document.getElementById(id).value = '');
  document.getElementById('fJenis').value = 'Sawah';
  document.getElementById('fStatus').value = 'aktif';
  Modal.open('modalForm');
}

async function openEdit(id) {
  try {
    const res = await apiFetch('/lahan/show/' + id);
    const d = res.data;
    document.getElementById('modalTitle').textContent = 'Edit Lahan';
    document.getElementById('editId').value  = d.id;
    document.getElementById('fNama').value   = d.nama_lahan || '';
    document.getElementById('fJenis').value  = d.jenis_lahan || 'Sawah';
    document.getElementById('fLuas').value   = d.luas || '';
    document.getElementById('fLokasi').value = d.lokasi || '';
    document.getElementById('fStatus').value = d.status || 'aktif';
    document.getElementById('fKet').value    = d.keterangan || '';
    Modal.open('modalForm');
  } catch(e) { Toast.show(e.message, 'error'); }
}

async function simpan() {
  const id = document.getElementById('editId').value;
  const body = new URLSearchParams({
    nama_lahan: document.getElementById('fNama').value, jenis_lahan: document.getElementById('fJenis').value,
    luas: document.getElementById('fLuas').value, lokasi: document.getElementById('fLokasi').value,
    status: document.getElementById('fStatus').value, keterangan: document.getElementById('fKet').value,
  });
  try {
    const res = await apiFetch(id ? '/lahan/update/'+id : '/lahan/store', {
      method:'POST', headers:{'Content-Type':'application/x-www-form-urlencoded','X-CSRF-TOKEN':getCsrfToken()}, body
    });
    if (res.status==='success') { Modal.close('modalForm'); Toast.show(res.message,'success'); loadData(); }
    else Toast.show(Object.values(res.errors||{}).join('\n')||res.message,'error');
  } catch(e) { Toast.show(e.message, 'error'); }
}

function hapus(id) {
  confirmDialog('Hapus data lahan ini?', async () => {
    try {
      const res = await apiDelete('/lahan/delete/'+id);
      if (res.status==='success') { Toast.show('Lahan berhasil dihapus.','success'); loadData(); }
      else Toast.show(res.message,'error');
    } catch(e) { Toast.show(e.message, 'error'); }
  }, { title:'Hapus Lahan', type:'danger', confirmText:'Ya, Hapus' });
}
</script>

</body>
</html>