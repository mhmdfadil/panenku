<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Dashboard - PanenKu</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<!-- Greeting -->
<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;margin-bottom:20px;">
  <div>
    <h2 style="font-size:20px;font-weight:800;margin:0 0 2px;letter-spacing:-.3px;">
      Selamat datang, Budi! 🌿
    </h2>
    <p style="margin:0;color:var(--text-muted);font-size:13px;">
      Senin, 06 Juli 2026 — Sukamaju
    </p>
  </div>
  <a href="/panen/create" class="btn btn-primary btn-sm">
    <i class="bi bi-plus-lg"></i> Catat Panen
  </a>
</div>

<!-- STAT CARDS -->
<div class="dash-stat-grid">
  <div class="stat-card" style="--stat-color:#2d8a4e;--stat-bg:#e8f5ee;">
    <div class="stat-icon"><i class="bi bi-basket3-fill"></i></div>
    <div class="stat-content">
      <div class="stat-label">Total Panen</div>
      <div class="stat-value" data-counter="128">128</div>
      <div class="stat-sub">Kali Panen</div>
      <div class="stat-change up">
        <i class="bi bi-arrow-up-short"></i> 12% bulan lalu
      </div>
    </div>
  </div>

  <div class="stat-card" style="--stat-color:#3498db;--stat-bg:#d6eaf8;">
    <div class="stat-icon"><i class="bi bi-speedometer2"></i></div>
    <div class="stat-content">
      <div class="stat-label">Total Produksi</div>
      <div class="stat-value stat-value-stack">
        <span>3.240 kg</span>
        <span>85 karung</span>
      </div>
      <div class="stat-sub">Total Hasil Panen</div>
      <div class="stat-change up">
        <i class="bi bi-arrow-up-short"></i> 8% bulan lalu
      </div>
    </div>
  </div>

  <div class="stat-card" style="--stat-color:#e67e22;--stat-bg:#fdebd0;">
    <div class="stat-icon"><i class="bi bi-map-fill"></i></div>
    <div class="stat-content">
      <div class="stat-label">Luas Lahan</div>
      <div class="stat-value">4,50</div>
      <div class="stat-sub">ha Total Lahan</div>
      <div class="stat-change neutral"><i class="bi bi-dash"></i> Stabil</div>
    </div>
  </div>

  <div class="stat-card" style="--stat-color:#8e44ad;--stat-bg:#f3e5f5;">
    <div class="stat-icon"><i class="bi bi-wallet2"></i></div>
    <div class="stat-content">
      <div class="stat-label">Nilai Panen</div>
      <div class="stat-value" style="font-size:16px;">Rp&nbsp;45.200.000</div>
      <div class="stat-sub">Total Estimasi</div>
      <div class="stat-change down">
        <i class="bi bi-arrow-down-short"></i> 3% bulan lalu
      </div>
    </div>
  </div>
</div>

<!-- CHART ROW -->
<div class="dash-charts-row">
  <div class="card">
    <div class="card-header">
      <h3 class="card-title"><i class="bi bi-graph-up" style="color:var(--pk-primary);"></i> Produksi 6 Bulan Terakhir</h3>
      <a href="/grafik" class="btn btn-outline btn-sm">Lihat Analisis</a>
    </div>
    <div class="card-body">
      <div class="chart-container" style="height:220px;"><canvas id="chartProduksi"></canvas></div>
    </div>
  </div>

  <div class="card">
    <div class="card-header"><h3 class="card-title">Per Komoditas</h3></div>
    <div class="card-body">
      <div class="komoditas-chart-inner" style="display:flex;align-items:center;gap:12px;">
        <div class="chart-container" style="height:150px;width:150px;flex-shrink:0;"><canvas id="chartKomoditas"></canvas></div>
        <div style="flex:1;min-width:0;" id="komoditasLegend">
          <!-- diisi otomatis oleh JS dari data contoh -->
        </div>
      </div>
    </div>
  </div>
</div>

<!-- BOTTOM ROW -->
<div class="dash-bottom-row">

  <!-- Riwayat terbaru -->
  <div class="card">
    <div class="card-header">
      <h3 class="card-title"><i class="bi bi-clock-history" style="color:var(--pk-primary);"></i> Panen Terbaru</h3>
      <a href="/panen/create" class="btn btn-primary btn-sm"><i class="bi bi-plus"></i> Catat</a>
    </div>

    <!-- Desktop table -->
    <div class="ag-grid-wrapper" style="border-radius:0 0 16px 16px;">
      <table class="dash-table" style="width:100%;border-collapse:collapse;font-size:13px;">
        <thead>
          <tr style="background:var(--bg-body);">
            <th style="padding:9px 12px;text-align:left;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--text-muted);white-space:nowrap;">#</th>
            <th style="padding:9px 12px;text-align:left;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--text-muted);white-space:nowrap;">Tanggal</th>
            <th style="padding:9px 12px;text-align:left;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--text-muted);white-space:nowrap;">Komoditas</th>
            <th style="padding:9px 12px;text-align:left;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--text-muted);white-space:nowrap;">Lahan</th>
            <th style="padding:9px 12px;text-align:left;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--text-muted);white-space:nowrap;">Jumlah</th>
            <th style="padding:9px 12px;text-align:left;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--text-muted);white-space:nowrap;">Total Nilai</th>
            <th style="padding:9px 12px;text-align:left;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--text-muted);white-space:nowrap;">Aksi</th>
          </tr>
        </thead>
        <tbody id="recentPanenBody">
          <!-- diisi otomatis oleh JS dari data contoh -->
        </tbody>
      </table>
    </div>

    <!-- Mobile card list -->
    <div class="mobile-card-list" id="dashMobile" style="padding:12px;"></div>

    <div style="padding:10px 14px;border-top:1px solid var(--border-color);text-align:center;">
      <a href="/riwayat" style="color:var(--pk-primary);font-size:13px;font-weight:600;">
        Lihat semua riwayat →
      </a>
    </div>
  </div>

  <!-- Right column -->
  <div class="dash-right-col">
    <!-- Pengingat -->
    <div class="card">
      <div class="card-header">
        <h3 class="card-title"><i class="bi bi-bell-fill" style="color:var(--pk-warning);"></i> Pengingat Panen</h3>
      </div>
      <div class="card-body" style="padding:10px 14px;" id="upcomingList">
        <!-- diisi otomatis oleh JS dari data contoh -->
      </div>
    </div>

    <!-- Cuaca -->
    <div class="card">
      <div class="card-header">
        <h3 class="card-title"><i class="bi bi-cloud-sun-fill" style="color:var(--pk-warning);"></i> Cuaca Hari Ini</h3>
      </div>
      <div class="card-body">
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:10px;">
          <img id="weatherIcon" src="" alt="" width="50" height="50" style="display:none;">
          <div>
            <div id="weatherTemp" style="font-size:26px;font-weight:800;color:var(--text-primary);line-height:1;">--</div>
            <div id="weatherDesc" style="font-size:12px;color:var(--text-muted);margin-top:2px;">Mengambil lokasi...</div>
          </div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;font-size:12px;">
          <div class="nm-box" style="padding:8px 10px;"><i class="bi bi-droplet" style="color:var(--pk-info);"></i> <span id="weatherHumidity">--</span></div>
          <div class="nm-box" style="padding:8px 10px;"><i class="bi bi-wind" style="color:var(--pk-secondary);"></i> <span id="weatherWind">--</span></div>
        </div>
        <div style="margin-top:8px;font-size:11px;color:var(--text-muted);">
          <i class="bi bi-geo-alt"></i> <span id="weatherLocation">Mendeteksi...</span>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
/* =========================================================
   DATA CONTOH (statis) — ganti / hubungkan ke API sesuai kebutuhan
   ========================================================= */
const grafik6Bulan = [
  { label: 'Feb', total: 420, label_satuan: '420 kg' },
  { label: 'Mar', total: 510, label_satuan: '510 kg' },
  { label: 'Apr', total: 380, label_satuan: '380 kg' },
  { label: 'Mei', total: 610, label_satuan: '610 kg' },
  { label: 'Jun', total: 700, label_satuan: '700 kg' },
  { label: 'Jul', total: 620, label_satuan: '620 kg' },
];

const perKomoditas = [
  { nama_tanaman: 'Padi',     total: 1200, label_satuan: '1.200 kg' },
  { nama_tanaman: 'Jagung',   total: 800,  label_satuan: '800 kg' },
  { nama_tanaman: 'Cabai',    total: 450,  label_satuan: '450 kg' },
  { nama_tanaman: 'Tomat',    total: 390,  label_satuan: '390 kg' },
  { nama_tanaman: 'Kedelai',  total: 250,  label_satuan: '250 kg' },
  { nama_tanaman: 'Singkong', total: 150,  label_satuan: '150 kg' },
];

const recentPanen = [
  { id: 1, tanggal_panen: '2026-07-02', nama_tanaman: 'Padi',   nama_lahan: 'Sawah Utara', jumlah_panen: 500, satuan: 'kg', total_nilai: 3500000 },
  { id: 2, tanggal_panen: '2026-06-28', nama_tanaman: 'Jagung', nama_lahan: 'Ladang Timur', jumlah_panen: 300, satuan: 'kg', total_nilai: 1800000 },
  { id: 3, tanggal_panen: '2026-06-20', nama_tanaman: 'Cabai',  nama_lahan: 'Kebun Belakang', jumlah_panen: 120, satuan: 'kg', total_nilai: 2400000 },
  { id: 4, tanggal_panen: '2026-06-14', nama_tanaman: 'Tomat',  nama_lahan: 'Kebun Belakang', jumlah_panen: 200, satuan: 'kg', total_nilai: 1600000 },
  { id: 5, tanggal_panen: '2026-06-05', nama_tanaman: 'Kedelai',nama_lahan: 'Ladang Timur', jumlah_panen: 150, satuan: 'kg', total_nilai: 900000 },
];

const upcoming = [
  { nama_tanaman: 'Padi',   nama_lahan: 'Sawah Utara',   sisa_hari: 5,  perkiraan_panen: '2026-07-11' },
  { nama_tanaman: 'Jagung', nama_lahan: 'Ladang Timur',  sisa_hari: 12, perkiraan_panen: '2026-07-18' },
  { nama_tanaman: 'Cabai',  nama_lahan: 'Kebun Belakang',sisa_hari: 25, perkiraan_panen: '2026-07-31' },
];

/* ========================================================= */

const colors = ['#2d8a4e','#e67e22','#e74c3c','#3498db','#8e44ad','#1abc9c'];

// Chart Produksi
(function(){
  const labels = grafik6Bulan.map(g => g.label);
  const data   = grafik6Bulan.map(g => g.total);
  const lblSatuans = grafik6Bulan.map(g => g.label_satuan);
  const ctx  = document.getElementById('chartProduksi').getContext('2d');
  const grad = ctx.createLinearGradient(0,0,0,220);
  grad.addColorStop(0,'rgba(45,138,78,.2)'); grad.addColorStop(1,'rgba(45,138,78,.01)');
  new Chart(ctx, {
    type:'line',
    data:{ labels, datasets:[{ label:'Produksi', data, borderColor:'#2d8a4e', backgroundColor:grad, borderWidth:2.5, tension:.4, fill:true, pointBackgroundColor:'#2d8a4e', pointRadius:4, pointHoverRadius:7 }] },
    options:{ responsive:true, maintainAspectRatio:false,
      plugins:{ legend:{display:false},
        tooltip:{ callbacks:{
          title: ctx => 'Pada ' + ctx[0].label + ':',
          label: ctx => {
            const lbl = lblSatuans[ctx.dataIndex];
            if (!lbl) return Number(ctx.raw).toLocaleString('id-ID');
            return lbl.split('•').map(s => s.trim());
        }}}
      },
      scales:{ x:{grid:{display:false}}, y:{beginAtZero:true, ticks:{callback:v=>v.toLocaleString('id-ID')}} }
    }
  });
})();

// Chart Komoditas + legend
(function(){
  const labels = perKomoditas.map(k => k.nama_tanaman);
  const data   = perKomoditas.map(k => k.total);
  const lblSatuans = perKomoditas.map(k => k.label_satuan);

  new Chart(document.getElementById('chartKomoditas').getContext('2d'), {
    type:'doughnut',
    data:{ labels, datasets:[{ data, backgroundColor:colors, borderWidth:0, hoverOffset:4 }] },
    options:{ responsive:true, maintainAspectRatio:false, cutout:'68%',
      plugins:{ legend:{display:false},
        tooltip:{ callbacks:{
          title: c => 'Tn. ' + c[0].label + ':',
          label: c => {
            const lbl = lblSatuans[c.dataIndex];
            if (!lbl) return Number(c.raw).toLocaleString('id-ID');
            return lbl.split('•').map(s => s.trim());
          }
        }}}
    }
  });

  const tot = perKomoditas.reduce((s,k) => s + k.total, 0);
  const legendEl = document.getElementById('komoditasLegend');
  legendEl.innerHTML = perKomoditas.map((k,i) => {
    const pct = tot > 0 ? Math.round((k.total/tot)*1000)/10 : 0;
    const c = colors[i % colors.length];
    return `
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px;">
        <div style="display:flex;align-items:center;gap:6px;min-width:0;">
          <span style="width:8px;height:8px;background:${c};border-radius:50%;flex-shrink:0;"></span>
          <span style="font-size:12px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${k.nama_tanaman}</span>
        </div>
        <span style="font-size:11px;font-weight:700;color:var(--text-muted);margin-left:4px;flex-shrink:0;">${pct}%</span>
      </div>`;
  }).join('');
})();

// Tabel Panen Terbaru (desktop)
(function(){
  const tbody = document.getElementById('recentPanenBody');
  if (!recentPanen.length) {
    tbody.innerHTML = `<tr><td colspan="7" style="padding:28px;text-align:center;color:var(--text-muted);">Belum ada data panen.</td></tr>`;
    return;
  }
  tbody.innerHTML = recentPanen.map((p,i) => `
    <tr style="border-top:1px solid var(--border-color);${i%2 ? 'background:var(--bg-table-odd);' : ''}">
      <td style="padding:9px 12px;color:var(--text-muted);">${i+1}</td>
      <td style="padding:9px 12px;white-space:nowrap;">${new Date(p.tanggal_panen).toLocaleDateString('id-ID',{day:'2-digit',month:'short',year:'numeric'})}</td>
      <td style="padding:9px 12px;font-weight:600;">${p.nama_tanaman}</td>
      <td style="padding:9px 12px;color:var(--text-secondary);">${p.nama_lahan}</td>
      <td style="padding:9px 12px;font-weight:600;">${Number(p.jumlah_panen).toLocaleString('id-ID')} ${p.satuan}</td>
      <td style="padding:9px 12px;font-weight:700;color:var(--pk-primary);">Rp&nbsp;${Number(p.total_nilai).toLocaleString('id-ID')}</td>
      <td style="padding:9px 12px;">
        <div style="display:flex;gap:4px;">
          <a href="/panen/edit/${p.id}" class="btn btn-icon btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
          <button onclick="hapusPanen(${p.id})" class="btn btn-icon btn-sm btn-danger"><i class="bi bi-trash"></i></button>
        </div>
      </td>
    </tr>`).join('');
})();

// Pengingat Panen
(function(){
  const icons = { Padi:'🌾', Jagung:'🌽', Cabai:'🌶️', Tomat:'🍅', Kedelai:'🫘', Singkong:'🥔' };
  const list = document.getElementById('upcomingList');
  if (!upcoming.length) {
    list.innerHTML = `<p style="color:var(--text-muted);font-size:13px;text-align:center;padding:6px 0;">Tidak ada jadwal 30 hari ke depan.</p>`;
    return;
  }
  list.innerHTML = upcoming.map(u => {
    const color = u.sisa_hari <= 7 ? 'var(--pk-danger)' : (u.sisa_hari <= 14 ? 'var(--pk-warning)' : 'var(--pk-success)');
    const icon = icons[u.nama_tanaman] || '🌱';
    const tgl = new Date(u.perkiraan_panen).toLocaleDateString('id-ID',{day:'2-digit',month:'short'});
    return `
      <div style="display:flex;align-items:center;gap:9px;padding:7px 0;border-bottom:1px solid var(--border-color);">
        <span style="font-size:18px;flex-shrink:0;">${icon}</span>
        <div style="flex:1;min-width:0;">
          <div style="font-size:12px;font-weight:700;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${u.nama_tanaman} — ${u.nama_lahan}</div>
          <div style="font-size:11px;color:var(--text-muted);">${u.sisa_hari} hari lagi</div>
        </div>
        <div style="font-size:11px;font-weight:700;color:${color};white-space:nowrap;">${tgl}</div>
      </div>`;
  }).join('');
})();

// Mobile dashboard cards
(function(){
  const pager = new MobilePager('dashMobile', recentPanen, (p, i) => `
    <div class="mobile-data-card">
      <div class="mdc-header">
        <div class="mdc-title">${p.nama_tanaman||'-'}</div>
        <span style="font-size:11px;font-weight:600;color:var(--text-muted);">${p.tanggal_panen ? new Date(p.tanggal_panen).toLocaleDateString('id-ID',{day:'2-digit',month:'short'}) : '-'}</span>
      </div>
      <div class="mdc-grid">
        <div class="mdc-item"><label>Lahan</label><span>${p.nama_lahan||'-'}</span></div>
        <div class="mdc-item"><label>Jumlah</label><span>${Number(p.jumlah_panen||0).toLocaleString('id-ID')} ${p.satuan||'kg'}</span></div>
        <div class="mdc-item" style="grid-column:1/-1;"><label>Total Nilai</label><span style="color:var(--pk-primary);font-size:14px;">Rp ${Number(p.total_nilai||0).toLocaleString('id-ID')}</span></div>
      </div>
      <div class="mdc-actions">
        <a href="/panen/edit/${p.id}" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i> Edit</a>
        <button onclick="hapusPanen(${p.id})" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i> Hapus</button>
      </div>
    </div>`, { pageSize: 5, placeholder: 'Cari panen...' });
  pager.render();
})();

// Cuaca
(function(){
  const KEY = '60e63de6f2ec5f1f7ec968fc2e7af4bc';
  if (!navigator.geolocation) { document.getElementById('weatherDesc').textContent='GPS tidak didukung'; return; }
  navigator.geolocation.getCurrentPosition(async pos => {
    try {
      const r = await fetch(`https://api.openweathermap.org/data/2.5/weather?lat=${pos.coords.latitude}&lon=${pos.coords.longitude}&units=metric&lang=id&appid=${KEY}`);
      const w = await r.json();
      if (w.cod!==200) throw new Error();
      document.getElementById('weatherTemp').textContent = Math.round(w.main.temp)+'°C';
      document.getElementById('weatherDesc').textContent = w.weather[0].description;
      document.getElementById('weatherHumidity').textContent = w.main.humidity+'%';
      document.getElementById('weatherWind').textContent = w.wind.speed+' km/j';
      document.getElementById('weatherLocation').textContent = w.name;
      const ic=document.getElementById('weatherIcon');
      ic.src=`https://openweathermap.org/img/wn/${w.weather[0].icon}@2x.png`;
      ic.style.display='block';
    } catch(e){ document.getElementById('weatherDesc').textContent='Gagal memuat cuaca'; }
  }, ()=>{ document.getElementById('weatherDesc').textContent='Izin lokasi ditolak'; });
})();

function hapusPanen(id){
  if (typeof confirmDialog === 'function') {
    confirmDialog('Yakin ingin menghapus data panen ini?', async ()=>{
      alert('Contoh: hapus data panen ID ' + id + ' (hubungkan ke API sesuai kebutuhan)');
    },{ title:'Hapus Panen', type:'danger', confirmText:'Ya, Hapus' });
  } else {
    if (confirm('Yakin ingin menghapus data panen ini?')) {
      alert('Contoh: hapus data panen ID ' + id + ' (hubungkan ke API sesuai kebutuhan)');
    }
  }
}
</script>

</body>
</html>