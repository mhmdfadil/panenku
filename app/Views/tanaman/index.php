<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="card">
  <div class="card-header">
    <div style="display:flex;align-items:center;gap:8px;">
      <h3 class="card-title"><i class="bi bi-flower1" style="color:var(--pk-primary);"></i> Data Tanaman</h3>
      <span id="rowCount" class="badge badge-primary"></span>
    </div>
    <button onclick="openAdd()" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Tambah Tanaman</button>
  </div>
  <div class="card-body" style="padding:0;">
    <!-- Desktop: toolbar + AG Grid -->
    <div class="ag-grid-wrapper" id="gridWrapper">
      <div class="ag-grid-toolbar">
        <div class="ag-quick-search">
          <i class="bi bi-search"></i>
          <input type="text" id="quickSearch" placeholder="Cari nama, jenis, varietas..." autocomplete="off">
        </div>
        <div class="ag-toolbar-right">
          <button onclick="gridApi?.exportDataAsCsv({fileName:'tanaman.csv'})" class="btn btn-outline btn-sm"><i class="bi bi-download"></i> CSV</button>
        </div>
      </div>
      <div id="tanamanGrid" class="ag-theme-panenku" style="width:100%;height:430px;"></div>
    </div>
    <!-- Mobile: search + cards + pagination -->
    <div class="mobile-card-list" id="tanamanMobile" style="padding:12px;"></div>
  </div>
</div>

<!-- Modal -->
<div id="modalForm" class="modal-backdrop" style="display:none;">
  <div class="modal">
    <div class="modal-header">
      <h5 class="modal-title" id="modalTitle">Tambah Tanaman</h5>
      <button class="btn-close" data-modal-close>&times;</button>
    </div>
    <div class="modal-body">
      <input type="hidden" id="editId">
      <div class="grid-2" style="gap:12px;">
        <div class="form-group mb-0">
          <label class="form-label">Nama Tanaman <span style="color:var(--pk-danger)">*</span></label>
          <input type="text" id="fNama" class="form-control" placeholder="Padi, Jagung, Cabai...">
        </div>
        <div class="form-group mb-0">
          <label class="form-label">Jenis</label>
          <select id="fJenis" class="form-control">
            <option value="">-- Pilih Jenis --</option>
            <option>Serealia</option><option>Hortikultura</option><option>Kacang-kacangan</option>
            <option>Umbi-umbian</option><option>Buah-buahan</option><option>Perkebunan</option><option>Lainnya</option>
          </select>
        </div>
        <div class="form-group mb-0">
          <label class="form-label">Varietas</label>
          <input type="text" id="fVarietas" class="form-control" placeholder="IR64, Pioneer 27...">
        </div>
        <div class="form-group mb-0">
          <label class="form-label">Masa Tanam (hari)</label>
          <input type="number" id="fMasaTanam" class="form-control" placeholder="90" min="1">
        </div>
        <div class="form-group mb-0">
          <label class="form-label">Satuan <span style="color:var(--pk-danger)">*</span></label>
          <select id="fSatuan" class="form-control">
            <option value="kg">kg</option><option value="ton">ton</option><option value="kuintal">kuintal</option>
            <option value="ikat">ikat</option><option value="buah">buah</option><option value="liter">liter</option>
          </select>
        </div>
      </div>
      <div class="form-group" style="margin-top:12px;margin-bottom:0;">
        <label class="form-label">Keterangan</label>
        <textarea id="fKet" class="form-control" rows="2" placeholder="Keterangan tambahan..."></textarea>
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
let allData = [];
const gridApiRef = { current: null };

const colDefs = [
  { headerName:'No', valueGetter: p => p.node.rowIndex + 1, width:65, sortable:false, suppressSizeToFit:true },
  { headerName:'Nama Tanaman', field:'nama_tanaman', minWidth:160, flex:1, cellStyle:{ fontWeight:'600' } },
  { headerName:'Jenis', field:'jenis', minWidth:120 },
  { headerName:'Varietas', field:'varietas', minWidth:120 },
  { headerName:'Masa Tanam', field:'masa_tanam_fmt', minWidth:130 },
  { headerName:'Satuan', field:'satuan', width:90 },
  {
    headerName:'Aksi', sortable:false, width:110, suppressSizeToFit:true, pinned:'right',
    cellRenderer: p => {
      const div = document.createElement('div');
      div.style.cssText = 'display:flex;align-items:center;gap:4px;height:100%;';
      div.innerHTML = `<button class="btn btn-icon btn-sm btn-warning" title="Edit"><i class="bi bi-pencil"></i></button>
                       <button class="btn btn-icon btn-sm btn-danger" title="Hapus"><i class="bi bi-trash"></i></button>`;
      div.querySelector('.btn-warning').addEventListener('click', () => openEdit(p.data.id));
      div.querySelector('.btn-danger').addEventListener('click', () => hapus(p.data.id));
      return div;
    }
  }
];

const _tanamanEl = document.getElementById('tanamanGrid');
_tanamanEl.classList.add('ag-theme-panenku');
agGrid.createGrid(_tanamanEl, {
  theme: 'legacy',
  columnDefs: colDefs, rowData: [],
  pagination: true, paginationPageSize: 14,
      paginationPageSizeSelector: [10, 14, 25, 50, 100],
  suppressMovableColumns: true, animateRows: true, domLayout: 'normal',
  overlayNoRowsTemplate: '<div style="padding:32px;color:#7a95a5;text-align:center"><div style="font-size:40px;margin-bottom:8px">🌱</div><div style="font-weight:700">Belum ada data tanaman</div></div>',
  defaultColDef: { resizable: true, sortable: true, filter: false },
  onGridReady: p => { gridApi = p.api; gridApiRef.current = p.api; loadData(); },
  onModelUpdated: () => { if (gridApi) document.getElementById('rowCount').textContent = gridApi.getDisplayedRowCount() + ' data'; }
});

// Quick search
document.getElementById('quickSearch')?.addEventListener('input', debounce(e => {
  gridApi?.setGridOption('quickFilterText', e.target.value);
}, 300));

// Mobile pager
mobilePager = new MobilePager('tanamanMobile', [], (d) => `
  <div class="mobile-data-card">
    <div class="mdc-header">
      <div class="mdc-title">${d.nama_tanaman||'-'}</div>
      <span class="badge badge-primary">${d.satuan||'-'}</span>
    </div>
    <div class="mdc-grid">
      <div class="mdc-item"><label>Jenis</label><span>${d.jenis||'-'}</span></div>
      <div class="mdc-item"><label>Varietas</label><span>${d.varietas||'-'}</span></div>
      <div class="mdc-item"><label>Masa Tanam</label><span>${d.masa_tanam_fmt||'-'}</span></div>
      <div class="mdc-item"><label>Ket.</label><span>${d.keterangan||'-'}</span></div>
    </div>
    <div class="mdc-actions">
      <button class="btn btn-warning btn-sm" onclick="openEdit(${d.id})"><i class="bi bi-pencil"></i> Edit</button>
      <button class="btn btn-danger btn-sm" onclick="hapus(${d.id})"><i class="bi bi-trash"></i> Hapus</button>
    </div>
  </div>`, { pageSize: 8, placeholder: 'Cari tanaman...' });

async function loadData() {
  try {
    const res = await apiFetch('/tanaman/data');
    allData = res.data || [];
    gridApi?.setGridOption('rowData', allData);
    mobilePager.setData(allData);
  } catch(e) { Toast.show(e.message, 'error'); }
}

function openAdd() {
  document.getElementById('modalTitle').textContent = 'Tambah Tanaman';
  document.getElementById('editId').value = '';
  ['fNama','fVarietas','fKet'].forEach(id => document.getElementById(id).value = '');
  document.getElementById('fMasaTanam').value = '';
  document.getElementById('fJenis').value = '';
  document.getElementById('fSatuan').value = 'kg';
  Modal.open('modalForm');
}

async function openEdit(id) {
  try {
    const res = await apiFetch('/tanaman/show/' + id);
    const d = res.data;
    document.getElementById('modalTitle').textContent = 'Edit Tanaman';
    document.getElementById('editId').value    = d.id;
    document.getElementById('fNama').value     = d.nama_tanaman || '';
    document.getElementById('fJenis').value    = d.jenis || '';
    document.getElementById('fVarietas').value = d.varietas || '';
    document.getElementById('fMasaTanam').value= d.masa_tanam || '';
    document.getElementById('fSatuan').value   = d.satuan || 'kg';
    document.getElementById('fKet').value      = d.keterangan || '';
    Modal.open('modalForm');
  } catch(e) { Toast.show(e.message, 'error'); }
}

async function simpan() {
  const id = document.getElementById('editId').value;
  const body = new URLSearchParams({
    nama_tanaman: document.getElementById('fNama').value,
    jenis:        document.getElementById('fJenis').value,
    varietas:     document.getElementById('fVarietas').value,
    masa_tanam:   document.getElementById('fMasaTanam').value,
    satuan:       document.getElementById('fSatuan').value,
    keterangan:   document.getElementById('fKet').value,
  });
  try {
    const res = await apiFetch(id ? '/tanaman/update/' + id : '/tanaman/store', {
      method: 'POST', headers: { 'Content-Type':'application/x-www-form-urlencoded', 'X-CSRF-TOKEN': getCsrfToken() }, body
    });
    if (res.status === 'success') { Modal.close('modalForm'); Toast.show(res.message, 'success'); loadData(); }
    else Toast.show(Object.values(res.errors || {}).join('\n') || res.message, 'error');
  } catch(e) { Toast.show(e.message, 'error'); }
}

function hapus(id) {
  confirmDialog('Hapus data tanaman ini?', async () => {
    try {
      const res = await apiDelete('/tanaman/delete/' + id);
      if (res.status === 'success') { Toast.show('Tanaman berhasil dihapus.', 'success'); loadData(); }
      else Toast.show(res.message, 'error');
    } catch(e) { Toast.show(e.message, 'error'); }
  }, { title:'Hapus Tanaman', type:'danger', confirmText:'Ya, Hapus' });
}
</script>

<?= $this->endSection() ?>