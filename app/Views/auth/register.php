<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Buat Akun Baru - PanenKu</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>

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

  <!-- Contoh tampilan pesan error (biasanya muncul jika validasi gagal) -->
  <div class="alert alert-danger" style="margin-bottom:16px; display:none;" id="errorAlert">
    <i class="bi bi-exclamation-circle-fill"></i>
    <div>
      <ul style="margin:4px 0 0; padding-left:16px;">
        <li>Contoh pesan error 1</li>
        <li>Contoh pesan error 2</li>
      </ul>
    </div>
  </div>

  <form action="/register" method="post" autocomplete="off">

    <!-- Nama -->
    <div class="form-group">
      <label class="form-label">Nama Lengkap <span style="color:var(--pk-danger)">*</span></label>
      <div class="input-icon-wrap">
        <i class="bi bi-person"></i>
        <input type="text" name="nama" class="form-control"
               placeholder="Nama Anda"
               value="" required>
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
                placeholder="Nama desa..." value="">
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Kecamatan</label>
        <div class="input-icon-wrap">
          <i class="bi bi-buildings"></i>
          <input type="text" id="inputKecamatan" name="kecamatan" class="form-control"
                placeholder="Nama kecamatan..." value="">
        </div>
      </div>
    </div>

    <div class="grid-2" style="gap:12px;">
      <div class="form-group">
        <label class="form-label">Kabupaten / Kota</label>
        <div class="input-icon-wrap">
          <i class="bi bi-building-check"></i>
          <input type="text" id="inputKabupaten" name="kabupaten" class="form-control"
                placeholder="Kab. / Kota..." value="">
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Provinsi</label>
        <div class="input-icon-wrap">
          <i class="bi bi-map"></i>
          <input type="text" id="inputProvinsi" name="provinsi" class="form-control"
                placeholder="Nama provinsi..." value="">
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
              value="">
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
               value="" required>
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
    Sudah punya akun? <a href="/login">Masuk di sini</a>
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
        elKecamatan.value =
          a.city_district ||
          a.subdistrict   ||
          a.district      ||
          a.town          ||
          a.municipality  ||
          "";

        // ── Kabupaten / Kota ──────────────────────────────────────────
        elKabupaten.value =
          a.county   ||
          a.regency  ||
          a.city     ||
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

</body>
</html>