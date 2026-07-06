<?= $this->extend('layouts/auth') ?>
<?= $this->section('content') ?>

<div class="auth-card" style="max-width:800px;">
  <div class="auth-brand">
    <div class="auth-brand-icon">🌾</div>
    <div>
      <div class="auth-brand-name">PanenKu</div>
      <div class="auth-brand-tag">Catat Hasil Panen</div>
    </div>
  </div>

  <h2 class="auth-title">Buat Akun Baru</h2>
  <p class="auth-subtitle">Daftarkan diri untuk mulai mencatat hasil panen</p>

  <?php if (session()->getFlashdata('errors') || session('errors')): ?>
    <div class="alert alert-danger" style="margin-bottom:16px;">
      <i class="bi bi-exclamation-circle-fill"></i>
      <div>
        <?php $errs = session()->getFlashdata('errors') ?: session('errors'); ?>
        <?php if (is_array($errs)): ?>
          <ul style="margin:4px 0 0; padding-left:16px;">
            <?php foreach ($errs as $e): ?><li><?= esc($e) ?></li><?php endforeach; ?>
          </ul>
        <?php else: ?>
          <?= esc($errs) ?>
        <?php endif; ?>
      </div>
    </div>
  <?php endif; ?>

  <form action="<?= base_url('register') ?>" method="post" autocomplete="off">
    <?= csrf_field() ?>

    <!-- Nama -->
    <div class="form-group">
      <label class="form-label">Nama Lengkap <span style="color:var(--pk-danger)">*</span></label>
      <div class="input-icon-wrap">
        <i class="bi bi-person"></i>
        <input type="text" name="nama" class="form-control"
               placeholder="Nama Anda"
               value="<?= old('nama') ?>" required>
      </div>
    </div>

    <!-- Seksi Lokasi -->
    <div style="font-size:12px;font-weight:500;color:var(--pk-muted);
                text-transform:uppercase;letter-spacing:.5px;
                border-top:1px solid var(--pk-border);
                padding-top:14px;margin:18px 0 12px;">
      <i class="bi bi-geo-alt"></i> Lokasi
      <span id="gpsStatus" style="display:none;margin-left:6px;
            font-size:11px;font-weight:400;color:var(--pk-success);">
        <i class="bi bi-broadcast"></i> Mendeteksi...
      </span>
    </div>

    <div class="grid-2" style="gap:12px;">
      <div class="form-group">
        <label class="form-label">Desa / Kelurahan</label>
        <div class="input-icon-wrap">
          <i class="bi bi-house"></i>
          <input type="text" id="inputDesa" name="desa" class="form-control"
                placeholder="Nama desa..." value="<?= old('desa') ?>">
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Kecamatan</label>
        <div class="input-icon-wrap">
          <i class="bi bi-buildings"></i>
          <input type="text" id="inputKecamatan" name="kecamatan" class="form-control"
                placeholder="Nama kecamatan..." value="<?= old('kecamatan') ?>">
        </div>
      </div>
    </div>

    <div class="grid-2" style="gap:12px;">
      <div class="form-group">
        <label class="form-label">Kabupaten / Kota</label>
        <div class="input-icon-wrap">
          <i class="bi bi-building-check"></i>
          <input type="text" id="inputKabupaten" name="kabupaten" class="form-control"
                placeholder="Kab. / Kota..." value="<?= old('kabupaten') ?>">
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Provinsi</label>
        <div class="input-icon-wrap">
          <i class="bi bi-map"></i>
          <input type="text" id="inputProvinsi" name="provinsi" class="form-control"
                placeholder="Nama provinsi..." value="<?= old('provinsi') ?>">
        </div>
      </div>
    </div>

    <div class="form-group">
      <label class="form-label">
        Alamat Lengkap
        <span style="font-size:11px;font-weight:400;color:var(--pk-muted)">(diisi otomatis)</span>
      </label>
      <div class="input-icon-wrap">
        <i class="bi bi-geo-alt"></i>
        <input type="text" id="inputAlamat" name="alamat" class="form-control"
              placeholder="Desa, Kec., Kabupaten, Provinsi"
              value="<?= old('alamat') ?>">
      </div>
      <small id="gpsMessage" style="display:none;color:var(--pk-warning);margin-top:5px;"></small>
    </div>

    
    <!-- Email -->
    <div class="form-group">
      <label class="form-label">Alamat Email <span style="color:var(--pk-danger)">*</span></label>
      <div class="input-icon-wrap">
        <i class="bi bi-envelope"></i>
        <input type="email" name="email" class="form-control"
               placeholder="email@contoh.com"
               value="<?= old('email') ?>" required>
      </div>
    </div>

    <!-- Password -->
    <div class="grid-2" style="gap:12px;">
      <div class="form-group">
        <label class="form-label">Password <span style="color:var(--pk-danger)">*</span></label>
        <div class="input-icon-wrap">
          <i class="bi bi-lock"></i>
          <input type="password" name="password" class="form-control"
                 placeholder="Min. 6 karakter" required>
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Konfirmasi Password <span style="color:var(--pk-danger)">*</span></label>
        <div class="input-icon-wrap">
          <i class="bi bi-lock-fill"></i>
          <input type="password" name="konfirmasi_password" class="form-control"
                 placeholder="Ulangi password" required>
        </div>
      </div>
    </div>

    <button type="submit" class="btn btn-primary w-100"
            style="padding:11px; margin-top:4px;">
      <i class="bi bi-person-plus"></i>
      Daftar Sekarang
    </button>
  </form>

  <div class="auth-footer">
    Sudah punya akun? <a href="<?= base_url('login') ?>">Masuk di sini</a>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
  const elDesa      = document.getElementById("inputDesa");
  const elKecamatan = document.getElementById("inputKecamatan");
  const elKabupaten = document.getElementById("inputKabupaten");
  const elProvinsi  = document.getElementById("inputProvinsi");
  const elAlamat    = document.getElementById("inputAlamat");
  const elStatus    = document.getElementById("gpsStatus");
  const elMessage   = document.getElementById("gpsMessage");

  function showError(msg) {
    elStatus.style.display  = "none";
    elMessage.style.display = "block";
    elMessage.innerHTML     = '<i class="bi bi-exclamation-triangle"></i> ' + msg;
  }

  if (!navigator.geolocation) { showError("Browser tidak mendukung GPS."); return; }

  const alreadyFilled = elDesa.value || elKecamatan.value
                      || elKabupaten.value || elProvinsi.value;
  if (alreadyFilled) return;

  elStatus.style.display = "inline";

  navigator.geolocation.getCurrentPosition(
    async function (position) {
      const { latitude: lat, longitude: lon } = position.coords;
      try {
        const res  = await fetch(
          `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}&accept-language=id`
        );
        const data = await res.json();
        const a    = data.address || {};

        // ── Desa / Kelurahan ──────────────────────────────────────────
        elDesa.value =
          a.village     ||   // desa
          a.hamlet      ||   // dusun
          a.suburb      ||   // kelurahan (kadang masuk sini)
          a.quarter     ||
          a.neighbourhood || "";

        // ── Kecamatan ─────────────────────────────────────────────────
        // Nominatim Indonesia sangat tidak konsisten untuk kecamatan.
        // Urutan ini berdasarkan pengujian aktual di berbagai kota.
        elKecamatan.value =
          a.city_district ||  // paling sering untuk kecamatan kota
          a.subdistrict   ||  // kadang muncul di daerah tertentu
          a.district      ||  // alternatif
          a.town          ||  // kota kecil / kecamatan
          a.municipality  ||  // variasi lain
          "";

        // ── Kabupaten / Kota ──────────────────────────────────────────
        elKabupaten.value =
          a.county   ||       // kabupaten
          a.regency  ||       // kabupaten (tag alternatif)
          a.city     ||       // kota madya
          "";

        // ── Provinsi ──────────────────────────────────────────────────
        elProvinsi.value =
          a.state    ||
          a.province ||
          "";

        // ── Alamat lengkap (desa, kec, kab, prov) ────────────────────
        elAlamat.value = [
          elDesa.value,
          elKecamatan.value ? "Kec. " + elKecamatan.value : "",
          elKabupaten.value,
          elProvinsi.value,
        ].filter(Boolean).join(", ");

        elStatus.style.display = "none";

      } catch (err) {
        console.error(err);
        showError("Gagal mendeteksi lokasi.");
      }
    },
    function (err) {
      const pesan = {
        [err.PERMISSION_DENIED]   : "Izin lokasi ditolak.",
        [err.POSITION_UNAVAILABLE]: "Lokasi tidak tersedia.",
        [err.TIMEOUT]             : "Waktu deteksi habis.",
      };
      showError(pesan[err.code] || "Gagal mendeteksi lokasi.");
    },
    { enableHighAccuracy: true, timeout: 10000 }
  );
});
</script>

<?= $this->endSection() ?>