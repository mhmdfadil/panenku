<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Catat Panen Baru - PanenKu</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>

<div style="max-width:860px;margin:0 auto;">
  <!-- Breadcrumb -->
  <div style="display:flex;align-items:center;gap:8px;margin-bottom:20px;font-size:13px;color:var(--text-muted);">
    <a href="/panen" style="color:var(--pk-primary);font-weight:600;">Pencatatan Panen</a>
    <i class="bi bi-chevron-right" style="font-size:11px;"></i>
    <span id="breadcrumbLabel">Catat Panen Baru</span>
  </div>

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">
        <i class="bi bi-plus-circle" id="titleIcon" style="color:var(--pk-primary);"></i>
        <span id="formTitle">Catat Panen Baru</span>
      </h3>
    </div>
    <div class="card-body">
      <!-- Contoh alert error (biasanya tampil dari session/validasi) -->
      <div class="alert alert-danger" style="margin-bottom:20px;display:none;" id="errorAlert">
        <i class="bi bi-exclamation-circle-fill"></i>
        <ul style="margin:0;padding-left:16px;">
          <li>Contoh pesan error</li>
        </ul>
      </div>

      <form action="/panen/store" method="post" enctype="multipart/form-data" id="panenForm">

        <div class="grid-2" style="gap:16px;margin-bottom:16px;">
          <div class="form-group mb-0">
            <label class="form-label">Tanaman / Komoditas <span style="color:var(--pk-danger)">*</span></label>
            <select name="tanaman_id" id="selectTanaman" class="form-control" required>
              <option value="">-- Pilih Tanaman --</option>
              <!-- diisi otomatis oleh JS dari data contoh -->
            </select>
          </div>
          <div class="form-group mb-0">
            <label class="form-label">Lahan <span style="color:var(--pk-danger)">*</span></label>
            <select name="lahan_id" class="form-control" required>
              <option value="">-- Pilih Lahan --</option>
              <!-- diisi otomatis oleh JS dari data contoh -->
            </select>
          </div>
        </div>

        <div class="grid-2" style="gap:16px;margin-bottom:16px;">
          <div class="form-group mb-0">
            <label class="form-label">Tanggal Panen <span style="color:var(--pk-danger)">*</span></label>
            <input type="date" name="tanggal_panen" id="tanggalInput" class="form-control" required>
          </div>
          <div class="form-group mb-0">
            <label class="form-label">Satuan
              <span style="font-size:11px;color:var(--text-muted);font-weight:400;">(otomatis dari tanaman, bisa diubah)</span>
            </label>
            <select name="satuan" id="selectSatuan" class="form-control">
              <!-- diisi otomatis oleh JS -->
            </select>
          </div>
        </div>

        <div class="grid-2" style="gap:16px;margin-bottom:16px;">
          <div class="form-group mb-0">
            <label class="form-label">Jumlah Panen <span style="color:var(--pk-danger)">*</span></label>
            <div style="position:relative;">
              <input type="number" name="jumlah_panen" id="jmlInput" class="form-control" step="0.01" min="0.01" placeholder="Misal: 500" required style="padding-right:60px;">
              <span id="satuanBadge" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);font-size:12px;font-weight:600;color:var(--pk-primary);background:var(--pk-primary-light);padding:2px 8px;border-radius:20px;">kg</span>
            </div>
          </div>
          <div class="form-group mb-0">
            <label class="form-label">Harga per <span id="labelSatuan">kg</span> (Rp) <span style="color:var(--pk-danger)">*</span></label>
            <input type="number" name="harga_per_kg" id="hrgInput" class="form-control" step="1" min="0" placeholder="Misal: 5500" required>
          </div>
        </div>

        <!-- Preview Total -->
        <div class="nm-box" style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;background:var(--pk-primary-light);border:1px solid rgba(45,138,78,.2);">
          <span style="color:var(--text-secondary);font-size:13px;font-weight:600;"><i class="bi bi-calculator"></i> Estimasi Total Nilai</span>
          <span id="previewNilai" style="font-size:20px;font-weight:800;color:var(--pk-primary);">Rp 0</span>
        </div>

        <div class="grid-2" style="gap:16px;margin-bottom:16px;">
          <div class="form-group mb-0">
            <label class="form-label">Kualitas Panen</label>
            <select name="kualitas" id="selectKualitas" class="form-control">
              <!-- diisi otomatis oleh JS -->
            </select>
          </div>
          <div class="form-group mb-0">
            <label class="form-label">Kondisi Cuaca</label>
            <select name="cuaca" id="selectCuaca" class="form-control">
              <option value="">-- Pilih Cuaca --</option>
              <!-- diisi otomatis oleh JS -->
            </select>
          </div>
        </div>

        <div class="form-group" style="margin-bottom:16px;">
          <label class="form-label">Catatan</label>
          <textarea name="catatan" class="form-control" rows="3" placeholder="Catatan tambahan..."></textarea>
        </div>

        <div class="form-group" style="margin-bottom:24px;">
          <label class="form-label">Foto Panen (opsional)</label>
          <input type="file" name="foto" class="form-control" accept="image/*">
          <!-- Contoh preview foto lama (muncul saat mode edit) -->
          <div style="margin-top:10px;display:none;" id="fotoLamaPreview">
            <img src="" alt="Foto Panen" style="height:90px;border-radius:var(--border-radius-sm);box-shadow:var(--nm-shadow-sm);">
          </div>
        </div>

        <div style="display:flex;gap:12px;justify-content:flex-end;flex-wrap:wrap;">
          <a href="/panen" class="btn btn-outline"><i class="bi bi-x-lg"></i> Batal</a>
          <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> <span id="submitLabel">Simpan Data</span></button>
        </div>
      </form>
    </div>
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

const satuanOptions = ['kg','ton','kuintal','gram','ikat','buah','liter','karung'];
const kualitasOptions = ['Sangat Baik','Baik','Cukup','Kurang'];
const cuacaOptions = ['Cerah','Cerah Berawan','Berawan','Hujan Ringan','Hujan','Hujan Lebat'];

// Contoh mapping tanaman -> satuan default (biasanya diambil dari /tanaman/satuan-map)
const satuanMap = {
  1: 'kg',     // Padi
  2: 'kg',     // Jagung
  3: 'kg',     // Cabai
  4: 'kg',     // Tomat
  5: 'kg',     // Kedelai
  6: 'kg',     // Singkong
};

/* =========================================================
   MODE FORM: ganti nilai ini untuk simulasi mode "edit"
   ========================================================= */
const panenData = null; // ganti dengan objek data panen untuk mode edit, contoh:
// const panenData = {
//   id: 1, tanaman_id: 1, lahan_id: 1, tanggal_panen: '2026-07-01',
//   satuan: 'kg', jumlah_panen: 500, harga_per_kg: 5500,
//   kualitas: 'Baik', cuaca: 'Cerah', catatan: '', foto: null
// };

/* ========================================================= */

function populateSelect(el, values, selectedValue) {
  values.forEach(v => {
    const opt = document.createElement('option');
    opt.value = v; opt.textContent = v;
    if (v === selectedValue) opt.selected = true;
    el.appendChild(opt);
  });
}

(function initForm() {
  const selTanaman  = document.getElementById('selectTanaman');
  const selLahan    = document.querySelector('select[name="lahan_id"]');
  const selSatuan   = document.getElementById('selectSatuan');
  const selKualitas = document.getElementById('selectKualitas');
  const selCuaca    = document.getElementById('selectCuaca');
  const tanggalInput= document.getElementById('tanggalInput');

  // Isi dropdown Tanaman
  Object.entries(tanamanOptions).forEach(([id, nama]) => {
    const opt = document.createElement('option');
    opt.value = id; opt.textContent = nama;
    if (panenData && String(panenData.tanaman_id) === id) opt.selected = true;
    selTanaman.appendChild(opt);
  });

  // Isi dropdown Lahan
  Object.entries(lahanOptions).forEach(([id, nama]) => {
    const opt = document.createElement('option');
    opt.value = id; opt.textContent = nama;
    if (panenData && String(panenData.lahan_id) === id) opt.selected = true;
    selLahan.appendChild(opt);
  });

  // Isi dropdown Satuan, Kualitas, Cuaca
  populateSelect(selSatuan, satuanOptions, panenData ? panenData.satuan : 'kg');
  populateSelect(selKualitas, kualitasOptions, panenData ? panenData.kualitas : 'Baik');
  const cuacaOpt = document.createElement('option'); // placeholder sudah ada di HTML
  populateSelect(selCuaca, cuacaOptions, panenData ? panenData.cuaca : '');

  // Tanggal default: hari ini, atau data panen jika mode edit
  tanggalInput.value = panenData ? panenData.tanggal_panen : new Date().toISOString().slice(0,10);

  // Jika mode edit, isi field lainnya + ubah judul form
  if (panenData) {
    document.getElementById('breadcrumbLabel').textContent = 'Edit Data Panen';
    document.getElementById('formTitle').textContent = 'Edit Data Panen';
    document.getElementById('submitLabel').textContent = 'Perbarui Data';
    document.getElementById('titleIcon').className = 'bi bi-pencil';
    document.getElementById('panenForm').action = '/panen/update/' + panenData.id;

    document.getElementById('jmlInput').value = panenData.jumlah_panen || '';
    document.getElementById('hrgInput').value = panenData.harga_per_kg || '';
    document.querySelector('textarea[name="catatan"]').value = panenData.catatan || '';
    updateSatuanUI(panenData.satuan || 'kg');

    if (panenData.foto) {
      const preview = document.getElementById('fotoLamaPreview');
      preview.style.display = 'block';
      preview.querySelector('img').src = '/uploads/panen/' + panenData.foto;
    }
  }

  updatePreview();
})();

const jml   = document.getElementById('jmlInput');
const hrg   = document.getElementById('hrgInput');
const prev  = document.getElementById('previewNilai');
const selTanaman = document.getElementById('selectTanaman');
const selSatuan  = document.getElementById('selectSatuan');
const badge      = document.getElementById('satuanBadge');
const lblSatuan  = document.getElementById('labelSatuan');

/**
 * Set satuan select ke nilai dari tanaman.
 * forceOverwrite=true : selalu ganti satuan ke satuan tanaman (saat user ganti tanaman)
 * forceOverwrite=false: hanya ganti jika user belum memilih satuan secara eksplisit (mode create)
 */
function applyTanamanSatuan(tanamanId, forceOverwrite) {
  if (!tanamanId || !satuanMap[tanamanId]) return;
  const satuanTanaman = satuanMap[tanamanId];
  if (forceOverwrite) {
    setSatuan(satuanTanaman);
  }
}

function setSatuan(val) {
  let found = false;
  for (const opt of selSatuan.options) {
    if (opt.value === val) { found = true; break; }
  }
  if (!found) {
    const opt = document.createElement('option');
    opt.value = val; opt.textContent = val;
    selSatuan.appendChild(opt);
  }
  selSatuan.value = val;
  updateSatuanUI(val);
}

function updateSatuanUI(val) {
  if (badge)     badge.textContent = val;
  if (lblSatuan) lblSatuan.textContent = val;
}

// Saat user ganti tanaman → auto-fill satuan
selTanaman?.addEventListener('change', function () {
  applyTanamanSatuan(this.value, /*forceOverwrite=*/true);
});

// Saat user ganti satuan → update badge & label
selSatuan?.addEventListener('change', function () {
  updateSatuanUI(this.value);
});

function updatePreview() {
  const v = (parseFloat(jml.value)||0) * (parseFloat(hrg.value)||0);
  prev.textContent = 'Rp ' + v.toLocaleString('id-ID');
}
jml?.addEventListener('input', updatePreview);
hrg?.addEventListener('input', updatePreview);
</script>

</body>
</html>