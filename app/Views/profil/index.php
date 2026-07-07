<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div style="max-width:960px;margin:0 auto;">

  <!-- Tab Nav -->
  <div class="nm-box" style="display:inline-flex;gap:4px;margin-bottom:22px;padding:6px;" id="tabNav">
    <button class="btn btn-primary btn-sm tab-btn" data-tab="profil">
      <i class="bi bi-person-circle"></i> Profil
    </button>
    <button class="btn btn-ghost btn-sm tab-btn" data-tab="password">
      <i class="bi bi-shield-lock"></i> Password
    </button>
    <button class="btn btn-ghost btn-sm tab-btn" data-tab="tampilan">
      <i class="bi bi-palette"></i> Tampilan
    </button>
  </div>

  <!-- ========== TAB PROFIL ========== -->
  <div id="tab-profil" class="tab-panel">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title"><i class="bi bi-person-circle" style="color:var(--pk-primary);"></i> Informasi Profil</h3>
      </div>
      <div class="card-body">

        <!-- Contoh alert (biasanya tampil dari flashdata) -->
        <div class="alert alert-success" style="margin-bottom:20px;display:none;" id="successAlert">
          <i class="bi bi-check-circle-fill"></i> Contoh pesan berhasil
        </div>
        <div class="alert alert-danger" style="margin-bottom:20px;display:none;" id="errorAlert">
          <i class="bi bi-x-circle-fill"></i> Contoh pesan error
        </div>

        <!-- Avatar -->
        <div style="display:flex;align-items:center;gap:20px;margin-bottom:24px;padding-bottom:22px;border-bottom:1px solid var(--border-color);flex-wrap:wrap;">
          <div style="position:relative;">
            <div style="width:84px;height:84px;border-radius:50%;overflow:hidden;background:var(--pk-primary-light);display:flex;align-items:center;justify-content:center;box-shadow:var(--nm-shadow);">
              <span id="avatar-preview" style="font-size:34px;font-weight:800;color:var(--pk-primary);">B</span>
              <!-- Jika user punya foto, ganti <span> di atas dengan:
              <img id="avatar-preview" src="/uploads/avatars/nama-file.jpg" alt="Avatar" style="width:100%;height:100%;object-fit:cover;"> -->
            </div>
          </div>
          <div>
            <h4 style="margin:0 0 3px;font-size:17px;font-weight:800;" id="profileNama">Budi Santoso</h4>
            <p style="margin:0 0 12px;color:var(--text-muted);font-size:13px;" id="profileEmail">budi@panenku.id</p>
            <form action="/profil/avatar" method="post" enctype="multipart/form-data" id="avatarForm" style="display:inline-flex;gap:8px;align-items:center;">
              <label class="btn btn-outline btn-sm" style="cursor:pointer;">
                <i class="bi bi-camera"></i> Ganti Foto
                <input type="file" id="avatar-input" name="avatar" accept="image/*" style="display:none;" onchange="this.form.submit()">
              </label>
              <span style="font-size:11px;color:var(--text-muted);">JPG/PNG maks. 2MB</span>
            </form>
          </div>
        </div>

        <form action="/profil/update" method="post">
          <div class="grid-2" style="gap:16px;">
            <div class="form-group">
              <label class="form-label">Nama Lengkap <span style="color:var(--pk-danger)">*</span></label>
              <input type="text" name="nama" class="form-control" value="Budi Santoso" required>
            </div>
            <div class="form-group">
              <label class="form-label">Email</label>
              <input type="email" class="form-control" value="budi@panenku.id" readonly style="opacity:.6;cursor:not-allowed;">
            </div>
            <div class="form-group">
              <label class="form-label">No. Telepon</label>
              <input type="tel" name="telepon" class="form-control" value="081234567890" placeholder="08xxxxxxxxxx">
            </div>
            <div class="form-group">
              <label class="form-label">Desa / Kelurahan</label>
              <input type="text" name="desa" class="form-control" value="Sukamaju" placeholder="Desa Sukamaju">
            </div>
            <div class="form-group">
              <label class="form-label">Kecamatan</label>
              <input type="text" name="kecamatan" class="form-control" value="Ciawi" placeholder="Kec. Ciawi">
            </div>
            <div class="form-group">
              <label class="form-label">Kabupaten / Kota</label>
              <input type="text" name="kabupaten" class="form-control" value="Bogor" placeholder="Kab. Bogor">
            </div>
          </div>
          <div class="form-group">
            <label class="form-label">Alamat Lengkap</label>
            <textarea name="alamat" class="form-control" rows="2" placeholder="Jl. Sawah Indah No. 12...">Jl. Sawah Indah No. 12, Sukamaju</textarea>
          </div>
          <div style="display:flex;justify-content:flex-end;">
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Simpan Perubahan</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- ========== TAB PASSWORD ========== -->
  <div id="tab-password" class="tab-panel" style="display:none">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title"><i class="bi bi-shield-lock" style="color:var(--pk-primary);"></i> Ubah Password</h3>
      </div>
      <div class="card-body" style="max-width:480px;">
        <!-- Contoh alert error validasi -->
        <div class="alert alert-danger" style="margin-bottom:20px;display:none;" id="pwErrorAlert">
          <i class="bi bi-x-circle-fill"></i>
          <ul style="margin:0;padding-left:16px;">
            <li>Contoh pesan error password</li>
          </ul>
        </div>
        <form action="/profil/password" method="post">
          <div class="form-group">
            <label class="form-label">Password Lama <span style="color:var(--pk-danger)">*</span></label>
            <input type="password" name="password_lama" class="form-control" placeholder="Masukkan password saat ini" required>
          </div>
          <div class="form-group">
            <label class="form-label">Password Baru <span style="color:var(--pk-danger)">*</span></label>
            <input type="password" name="password_baru" id="pwBaru" class="form-control" placeholder="Minimal 6 karakter" required minlength="6">
            <!-- Strength bar -->
            <div style="margin-top:8px;">
              <div style="height:5px;background:var(--nm-inset);border-radius:3px;overflow:hidden;box-shadow:var(--nm-inset-sm);">
                <div id="pwStrengthBar" style="height:100%;width:0;border-radius:3px;transition:.3s;"></div>
              </div>
              <div id="pwStrengthText" style="font-size:11px;margin-top:4px;color:var(--text-muted);font-weight:600;"></div>
            </div>
          </div>
          <div class="form-group">
            <label class="form-label">Konfirmasi Password Baru <span style="color:var(--pk-danger)">*</span></label>
            <input type="password" name="konfirmasi" id="pwKonfirm" class="form-control" placeholder="Ulangi password baru" required>
            <div id="pwMatch" style="font-size:12px;margin-top:5px;font-weight:600;"></div>
          </div>
          <div style="display:flex;justify-content:flex-end;">
            <button type="submit" class="btn btn-primary"><i class="bi bi-key"></i> Ubah Password</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- ========== TAB TAMPILAN ========== -->
  <div id="tab-tampilan" class="tab-panel" style="display:none">
    <div class="grid-2" style="gap:20px;margin-bottom:20px;">

      <!-- Theme -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title"><i class="bi bi-sun" style="color:var(--pk-warning);"></i> Mode Tampilan</h3>
        </div>
        <div class="card-body">
          <p style="color:var(--text-muted);font-size:13px;margin-bottom:16px;">Pilih tema yang nyaman. Disimpan otomatis.</p>
          <div style="display:flex;flex-direction:column;gap:10px;" id="themeOptionsList">
            <!-- diisi otomatis oleh JS dari data contoh -->
          </div>
        </div>
      </div>

      <!-- Aksesibilitas -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title"><i class="bi bi-universal-access" style="color:var(--pk-info);"></i> Aksesibilitas</h3>
        </div>
        <div class="card-body">
          <p style="color:var(--text-muted);font-size:13px;margin-bottom:16px;">Pengaturan kenyamanan membaca.</p>

          <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 16px;background:var(--bg-body);border-radius:var(--border-radius-sm);box-shadow:var(--nm-inset-sm);margin-bottom:14px;">
            <div style="display:flex;align-items:center;gap:12px;">
              <span style="font-size:22px;">📖</span>
              <div>
                <div style="font-weight:700;font-size:14px;">Mode Baca</div>
                <div style="font-size:12px;color:var(--text-muted);">Font lebih besar & spasi lebih lebar</div>
              </div>
            </div>
            <label class="nm-toggle">
              <input type="checkbox" id="readModeToggle" data-read-mode-toggle>
              <span class="nm-slider"></span>
            </label>
          </div>

          <div class="nm-box" style="font-size:12px;color:var(--text-muted);line-height:1.8;">
            <strong>Mode Baca mengaktifkan:</strong>
            <ul style="margin:4px 0 0;padding-left:16px;">
              <li>Ukuran font diperbesar (18px)</li>
              <li>Tinggi baris lebih longgar (1.9)</li>
              <li>Kontras teks ditingkatkan</li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <!-- Preview -->
    <div class="card">
      <div class="card-header"><h3 class="card-title"><i class="bi bi-eye"></i> Pratinjau Tampilan</h3></div>
      <div class="card-body">
        <div class="nm-box">
          <h4 style="margin:0 0 8px;font-size:var(--font-size-lg);">Contoh Judul Halaman</h4>
          <p style="margin:0 0 10px;color:var(--text-secondary);font-size:var(--font-size-base);line-height:var(--line-height);">
            Ini adalah contoh teks untuk melihat bagaimana tampilan terlihat dengan pengaturan yang Anda pilih.
          </p>
          <div style="display:flex;gap:8px;flex-wrap:wrap;">
            <span class="badge badge-success">Sangat Baik</span>
            <span class="badge badge-primary">Padi</span>
            <span class="badge badge-warning">Cukup</span>
            <span class="badge badge-info">Berawan</span>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>

<style>
/* Neumorphic Toggle Switch */
.nm-toggle { position:relative; display:inline-block; width:46px; height:26px; }
.nm-toggle input { opacity:0; width:0; height:0; }
.nm-slider { position:absolute; cursor:pointer; inset:0; background:var(--nm-shadow-dark); border-radius:26px; transition:var(--transition); box-shadow:var(--nm-inset-sm); }
.nm-slider:before { position:absolute; content:''; height:18px; width:18px; left:4px; bottom:4px; background:var(--nm-shadow-light); border-radius:50%; transition:var(--transition); box-shadow:2px 2px 5px var(--nm-shadow-dark); }
.nm-toggle input:checked + .nm-slider { background:var(--pk-primary); box-shadow:inset 2px 2px 6px rgba(0,0,0,.2); }
.nm-toggle input:checked + .nm-slider:before { transform:translateX(20px); }

/* Theme option selected */
.theme-option.selected { border-color:var(--pk-primary) !important; background:var(--pk-primary-light); }
.theme-option.selected .radio-inner { display:block !important; }
.theme-option.selected .radio-dot { border-color:var(--pk-primary); }
</style>

<script>
/* =========================================================
   DATA CONTOH (statis) — pengganti data user dari session/PHP
   ========================================================= */
const user = {
  nama: 'Budi Santoso',
  email: 'budi@panenku.id',
  avatar: null,
  theme_mode: 'system', // 'light' | 'dark' | 'system'
  read_mode: false,
};

const themeModes = [
  { mode:'light',  emoji:'☀️', label:'Light Mode', desc:'Tampilan terang, cocok siang hari' },
  { mode:'dark',   emoji:'🌙', label:'Dark Mode',  desc:'Nyaman untuk malam hari' },
  { mode:'system', emoji:'💻', label:'System',     desc:'Mengikuti sistem operasi' },
];

/* ========================================================= */

(function renderThemeOptions() {
  const list = document.getElementById('themeOptionsList');
  list.innerHTML = themeModes.map(t => `
    <label class="theme-option nm-box" data-mode="${t.mode}" style="display:flex;align-items:center;gap:14px;cursor:pointer;border:2px solid transparent;transition:all var(--transition);">
      <input type="radio" name="theme_mode" value="${t.mode}" class="theme-radio" style="display:none;" ${user.theme_mode === t.mode ? 'checked' : ''}>
      <span style="font-size:22px;flex-shrink:0;">${t.emoji}</span>
      <div style="flex:1;">
        <div style="font-weight:700;font-size:14px;">${t.label}</div>
        <div style="font-size:12px;color:var(--text-muted);">${t.desc}</div>
      </div>
      <div class="radio-dot" style="width:20px;height:20px;border-radius:50%;border:2px solid var(--border-color);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
        <div class="radio-inner" style="width:10px;height:10px;border-radius:50%;background:var(--pk-primary);display:none;"></div>
      </div>
    </label>`).join('');

  document.getElementById('readModeToggle').checked = !!user.read_mode;

  // Attach listeners setelah render
  document.querySelectorAll('.theme-option').forEach(opt => {
    const mode = opt.dataset.mode;
    const radio = opt.querySelector('.theme-radio');
    if (radio.checked) opt.classList.add('selected');
    opt.addEventListener('click', () => {
      document.querySelectorAll('.theme-option').forEach(o=>o.classList.remove('selected'));
      opt.classList.add('selected'); radio.checked=true;
      if (typeof Theme !== 'undefined') Theme.set(mode);
      if (typeof Toast !== 'undefined') Toast.show('Tema berhasil diubah.','success');
    });
  });
})();

// Tab switching
document.querySelectorAll('.tab-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    const tab = btn.dataset.tab;
    document.querySelectorAll('.tab-btn').forEach(b => { b.classList.remove('btn-primary'); b.classList.add('btn-ghost'); });
    btn.classList.remove('btn-ghost'); btn.classList.add('btn-primary');
    document.querySelectorAll('.tab-panel').forEach(p => p.style.display='none');
    document.getElementById('tab-'+tab).style.display='block';
  });
});

// Password strength
document.getElementById('pwBaru')?.addEventListener('input', function() {
  const v=this.value; let s=0;
  if(v.length>=6)s++; if(v.length>=10)s++; if(/[A-Z]/.test(v))s++; if(/[0-9]/.test(v))s++; if(/[^A-Za-z0-9]/.test(v))s++;
  const lvs=[{w:'20%',c:'#e74c3c',t:'Sangat Lemah'},{w:'40%',c:'#e67e22',t:'Lemah'},{w:'60%',c:'#f39c12',t:'Cukup'},{w:'80%',c:'#2ecc71',t:'Kuat'},{w:'100%',c:'#27ae60',t:'Sangat Kuat'}];
  const lv=lvs[Math.min(s,lvs.length)-1]||{w:'0%',c:'transparent',t:''};
  const bar=document.getElementById('pwStrengthBar'); const txt=document.getElementById('pwStrengthText');
  bar.style.width=lv.w; bar.style.background=lv.c; txt.textContent=lv.t; txt.style.color=lv.c;
});

// Password match
document.getElementById('pwKonfirm')?.addEventListener('input', function() {
  const baru=document.getElementById('pwBaru').value; const el=document.getElementById('pwMatch');
  if(!this.value){el.textContent='';return;}
  if(this.value===baru){el.innerHTML='<i class="bi bi-check-circle-fill" style="color:var(--pk-success);"></i> Password cocok';el.style.color='var(--pk-success)';}
  else{el.innerHTML='<i class="bi bi-x-circle-fill" style="color:var(--pk-danger);"></i> Password tidak cocok';el.style.color='var(--pk-danger)';}
});
</script>

<?= $this->endSection() ?>
