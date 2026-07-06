<?= $this->extend('layouts/auth') ?>
<?= $this->section('content') ?>

<div class="auth-card">
  <div class="auth-brand">
    <div class="auth-brand-icon">🌾</div>
    <div>
      <div class="auth-brand-name">PanenKu</div>
      <div class="auth-brand-tag">Catat Hasil Panen</div>
    </div>
  </div>

  <h2 class="auth-title">Selamat Datang!</h2>
  <p class="auth-subtitle">Masuk untuk mengelola data panen Anda</p>

  <!-- Contoh alert error (biasanya tampil dari flashdata) -->
  <div class="alert alert-danger mb-4" style="margin-bottom:16px; display:none;" id="errorAlert">
    <i class="bi bi-exclamation-circle-fill"></i>
    Contoh pesan error
  </div>

  <!-- Contoh alert success (biasanya tampil dari flashdata) -->
  <div class="alert alert-success" style="margin-bottom:16px; display:none;" id="successAlert">
    <i class="bi bi-check-circle-fill"></i>
    Contoh pesan berhasil
  </div>

  <form action="/login" method="post" autocomplete="on">

    <div class="form-group">
      <label class="form-label">Alamat Email</label>
      <div class="input-icon-wrap">
        <i class="bi bi-envelope"></i>
        <input type="email" name="email" class="form-control"
               placeholder="email@contoh.com" value="" required autofocus>
      </div>
      <!-- Contoh pesan error validasi email -->
      <div class="invalid-feedback" style="display:none;">Contoh pesan error email</div>
    </div>

    <div class="form-group" style="margin-bottom:20px;">
      <label class="form-label">Password</label>
      <div class="input-icon-wrap" style="position:relative;">
        <i class="bi bi-lock"></i>
        <input type="password" name="password" id="password" class="form-control"
               placeholder="Masukkan password" required>
        <button type="button" onclick="togglePwd()" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--text-muted);cursor:pointer;padding:2px;">
          <i class="bi bi-eye" id="pwd-icon"></i>
        </button>
      </div>
      <!-- Contoh pesan error validasi password -->
      <div class="invalid-feedback" style="display:none;">Contoh pesan error password</div>
    </div>

    <button type="submit" class="btn btn-primary w-100" style="padding:11px;">
      <i class="bi bi-box-arrow-in-right"></i>
      Masuk
    </button>
  </form>

  <div class="auth-footer">
    Belum punya akun? <a href="/register">Daftar Sekarang</a>
  </div>

  <div style="margin-top:20px; padding:12px 14px; background:var(--pk-primary-light); border-radius:8px; font-size:12px; color:var(--text-secondary);">
    <strong>Demo Login:</strong><br>
    Email: <code>budi@panenku.id</code> | Password: <code>password123</code>
  </div>
</div>

<script>
function togglePwd() {
  const inp = document.getElementById('password');
  const ico = document.getElementById('pwd-icon');
  if (inp.type === 'password') { inp.type = 'text'; ico.className = 'bi bi-eye-slash'; }
  else { inp.type = 'password'; ico.className = 'bi bi-eye'; }
}
</script>

<?= $this->endSection() ?>