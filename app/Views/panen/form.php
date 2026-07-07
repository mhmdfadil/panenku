<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div style="max-width:860px;margin:0 auto;">
  <!-- Breadcrumb -->
  <div style="display:flex;align-items:center;gap:8px;margin-bottom:20px;font-size:13px;color:var(--text-muted);">
    <a href="<?= base_url('panen') ?>" style="color:var(--pk-primary);font-weight:600;">Pencatatan Panen</a>
    <i class="bi bi-chevron-right" style="font-size:11px;"></i>
    <span><?= $panen ? 'Edit Data Panen' : 'Catat Panen Baru' ?></span>
  </div>

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">
        <i class="bi bi-<?= $panen ? 'pencil' : 'plus-circle' ?>" style="color:var(--pk-primary);"></i>
        <?= $panen ? 'Edit Data Panen' : 'Catat Panen Baru' ?>
      </h3>
    </div>
    <div class="card-body">
      <?php if (session('errors')): ?>
        <div class="alert alert-danger" style="margin-bottom:20px;">
          <i class="bi bi-exclamation-circle-fill"></i>
          <ul style="margin:0;padding-left:16px;">
            <?php foreach (session('errors') as $err): ?><li><?= esc($err) ?></li><?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <form action="<?= base_url($panen ? 'panen/update/'.$panen['id'] : 'panen/store') ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <div class="grid-2" style="gap:16px;margin-bottom:16px;">
          <div class="form-group mb-0">
            <label class="form-label">Tanaman / Komoditas <span style="color:var(--pk-danger)">*</span></label>
            <select name="tanaman_id" id="selectTanaman" class="form-control" required>
              <option value="">-- Pilih Tanaman --</option>
              <?php foreach ($tanaman as $id => $nama): ?>
                <option value="<?= $id ?>" <?= old('tanaman_id',$panen['tanaman_id']??'')==$id?'selected':'' ?>><?= esc($nama) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group mb-0">
            <label class="form-label">Lahan <span style="color:var(--pk-danger)">*</span></label>
            <select name="lahan_id" class="form-control" required>
              <option value="">-- Pilih Lahan --</option>
              <?php foreach ($lahan as $id => $nama): ?>
                <option value="<?= $id ?>" <?= old('lahan_id',$panen['lahan_id']??'')==$id?'selected':'' ?>><?= esc($nama) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="grid-2" style="gap:16px;margin-bottom:16px;">
          <div class="form-group mb-0">
            <label class="form-label">Tanggal Panen <span style="color:var(--pk-danger)">*</span></label>
            <input type="date" name="tanggal_panen" class="form-control" required value="<?= old('tanggal_panen',$panen['tanggal_panen']??date('Y-m-d')) ?>">
          </div>
          <div class="form-group mb-0">
            <label class="form-label">Satuan
              <span style="font-size:11px;color:var(--text-muted);font-weight:400;">(otomatis dari tanaman, bisa diubah)</span>
            </label>
            <select name="satuan" id="selectSatuan" class="form-control">
              <?php foreach (['kg','ton','kuintal','gram','ikat','buah','liter','karung'] as $s): ?>
                <option value="<?= $s ?>" <?= old('satuan',$panen['satuan']??'kg')===$s?'selected':'' ?>><?= $s ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="grid-2" style="gap:16px;margin-bottom:16px;">
          <div class="form-group mb-0">
            <label class="form-label">Jumlah Panen <span style="color:var(--pk-danger)">*</span></label>
            <div style="position:relative;">
              <input type="number" name="jumlah_panen" id="jmlInput" class="form-control" step="0.01" min="0.01" placeholder="Misal: 500" required value="<?= old('jumlah_panen',$panen['jumlah_panen']??'') ?>" style="padding-right:60px;">
              <span id="satuanBadge" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);font-size:12px;font-weight:600;color:var(--pk-primary);background:var(--pk-primary-light);padding:2px 8px;border-radius:20px;"><?= old('satuan',$panen['satuan']??'kg') ?></span>
            </div>
          </div>
          <div class="form-group mb-0">
            <label class="form-label">Harga per <span id="labelSatuan"><?= old('satuan',$panen['satuan']??'kg') ?></span> (Rp) <span style="color:var(--pk-danger)">*</span></label>
            <input type="number" name="harga_per_kg" id="hrgInput" class="form-control" step="1" min="0" placeholder="Misal: 5500" required value="<?= old('harga_per_kg',$panen['harga_per_kg']??'') ?>">
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
            <select name="kualitas" class="form-control">
              <?php foreach (['Sangat Baik','Baik','Cukup','Kurang'] as $k): ?>
                <option value="<?= $k ?>" <?= old('kualitas',$panen['kualitas']??'Baik')===$k?'selected':'' ?>><?= $k ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group mb-0">
            <label class="form-label">Kondisi Cuaca</label>
            <select name="cuaca" class="form-control">
              <option value="">-- Pilih Cuaca --</option>
              <?php foreach (['Cerah','Cerah Berawan','Berawan','Hujan Ringan','Hujan','Hujan Lebat'] as $c): ?>
                <option value="<?= $c ?>" <?= old('cuaca',$panen['cuaca']??'')===$c?'selected':'' ?>><?= $c ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="form-group" style="margin-bottom:16px;">
          <label class="form-label">Catatan</label>
          <textarea name="catatan" class="form-control" rows="3" placeholder="Catatan tambahan..."><?= old('catatan',$panen['catatan']??'') ?></textarea>
        </div>

        <div class="form-group" style="margin-bottom:24px;">
          <label class="form-label">Foto Panen (opsional)</label>
          <input type="file" name="foto" class="form-control" accept="image/*">
          <?php if (!empty($panen['foto'])): ?>
            <div style="margin-top:10px;">
              <img src="<?= base_url('uploads/panen/'.$panen['foto']) ?>" alt="Foto Panen" style="height:90px;border-radius:var(--border-radius-sm);box-shadow:var(--nm-shadow-sm);">
            </div>
          <?php endif; ?>
        </div>

        <div style="display:flex;gap:12px;justify-content:flex-end;flex-wrap:wrap;">
          <a href="<?= base_url('panen') ?>" class="btn btn-outline"><i class="bi bi-x-lg"></i> Batal</a>
          <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> <?= $panen ? 'Perbarui Data' : 'Simpan Data' ?></button>
        </div>
      </form>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
const jml   = document.getElementById('jmlInput');
const hrg   = document.getElementById('hrgInput');
const prev  = document.getElementById('previewNilai');
const selTanaman = document.getElementById('selectTanaman');
const selSatuan  = document.getElementById('selectSatuan');
const badge      = document.getElementById('satuanBadge');
const lblSatuan  = document.getElementById('labelSatuan');

// Cached map: tanaman_id -> satuan
let satuanMap = {};

// Fetch satuan map from server
fetch('/tanaman/satuan-map')
  .then(r => r.json())
  .then(res => {
    satuanMap = res.data || {};
    // Jika ada tanaman terpilih saat halaman load (edit mode), apply satuan
    applyTanamanSatuan(selTanaman.value, /*forceOverwrite=*/false);
  })
  .catch(() => {});

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
  // Pastikan option ada, kalau tidak ada tambahkan sementara
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
updatePreview();
</script>

<?= $this->endSection() ?>