<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<!-- Greeting -->
<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;margin-bottom:20px;">
  <div>
    <h2 style="font-size:20px;font-weight:800;margin:0 0 2px;letter-spacing:-.3px;">
      Selamat datang, <?= esc(session()->get('user_nama')) ?>! 🌿
    </h2>
    <p style="margin:0;color:var(--text-muted);font-size:13px;">
      <?= date('l, d F Y') ?> — <?= esc(session()->get('user_desa') ?? 'PanenKu') ?>
    </p>
  </div>
  <a href="<?= base_url('panen/create') ?>" class="btn btn-primary btn-sm">
    <i class="bi bi-plus-lg"></i> Catat Panen
  </a>
</div>

<!-- STAT CARDS -->
<div class="dash-stat-grid">
  <div class="stat-card" style="--stat-color:#2d8a4e;--stat-bg:#e8f5ee;">
    <div class="stat-icon"><i class="bi bi-basket3-fill"></i></div>
    <div class="stat-content">
      <div class="stat-label">Total Panen</div>
      <div class="stat-value" data-counter="<?= $stats['total_panen'] ?>"><?= number_format($stats['total_panen']) ?></div>
      <div class="stat-sub">Kali Panen</div>
      <?php $p = $stats['persen_panen']; ?>
      <div class="stat-change <?= $p>0?'up':($p<0?'down':'neutral') ?>">
        <i class="bi bi-arrow-<?= $p>=0?'up':'down' ?>-short"></i> <?= abs($p) ?>% bulan lalu
      </div>
    </div>
  </div>

  <div class="stat-card" style="--stat-color:#3498db;--stat-bg:#d6eaf8;">
    <div class="stat-icon"><i class="bi bi-speedometer2"></i></div>
    <div class="stat-content">
      <div class="stat-label">Total Produksi</div>
      <div class="stat-value stat-value-stack">
        <?php foreach(explode('•', $stats['total_produksi_fmt']) as $line): ?>
          <span><?= esc(trim($line)) ?></span>
        <?php endforeach; ?>
      </div>
      <div class="stat-sub">Total Hasil Panen</div>
      <?php $pp = $stats['persen_produksi']; ?>
      <div class="stat-change <?= $pp>0?'up':($pp<0?'down':'neutral') ?>">
        <i class="bi bi-arrow-<?= $pp>=0?'up':'down' ?>-short"></i> <?= abs($pp) ?>% bulan lalu
      </div>
    </div>
  </div>

  <div class="stat-card" style="--stat-color:#e67e22;--stat-bg:#fdebd0;">
    <div class="stat-icon"><i class="bi bi-map-fill"></i></div>
    <div class="stat-content">
      <div class="stat-label">Luas Lahan</div>
      <div class="stat-value"><?= number_format($totalLahan,2) ?></div>
      <div class="stat-sub">ha Total Lahan</div>
      <div class="stat-change neutral"><i class="bi bi-dash"></i> Stabil</div>
    </div>
  </div>

  <div class="stat-card" style="--stat-color:#8e44ad;--stat-bg:#f3e5f5;">
    <div class="stat-icon"><i class="bi bi-wallet2"></i></div>
    <div class="stat-content">
      <div class="stat-label">Nilai Panen</div>
      <div class="stat-value" style="font-size:16px;">Rp&nbsp;<?= number_format($stats['total_nilai'],0,',','.') ?></div>
      <div class="stat-sub">Total Estimasi</div>
      <?php $pn = $stats['persen_nilai']; ?>
      <div class="stat-change <?= $pn>0?'up':($pn<0?'down':'neutral') ?>">
        <i class="bi bi-arrow-<?= $pn>=0?'up':'down' ?>-short"></i> <?= abs($pn) ?>% bulan lalu
      </div>
    </div>
  </div>
</div>

<!-- CHART ROW -->
<div class="dash-charts-row">
  <div class="card">
    <div class="card-header">
      <h3 class="card-title"><i class="bi bi-graph-up" style="color:var(--pk-primary);"></i> Produksi 6 Bulan Terakhir</h3>
      <a href="<?= base_url('grafik') ?>" class="btn btn-outline btn-sm">Lihat Analisis</a>
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
        <div style="flex:1;min-width:0;">
          <?php
          $colors=['#2d8a4e','#e67e22','#e74c3c','#3498db','#8e44ad','#1abc9c'];
          $tot = array_sum(array_column($perKomoditas,'total'));
          foreach($perKomoditas as $i=>$k):
            $pct = $tot>0?round($k['total']/$tot*100,1):0;
            $c = $colors[$i%count($colors)];
          ?>
          <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px;">
            <div style="display:flex;align-items:center;gap:6px;min-width:0;">
              <span style="width:8px;height:8px;background:<?=$c?>;border-radius:50%;flex-shrink:0;"></span>
              <span style="font-size:12px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?=esc($k['nama_tanaman'])?></span>
            </div>
            <span style="font-size:11px;font-weight:700;color:var(--text-muted);margin-left:4px;flex-shrink:0;"><?=$pct?>%</span>
          </div>
          <?php endforeach; ?>
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
      <a href="<?= base_url('panen/create') ?>" class="btn btn-primary btn-sm"><i class="bi bi-plus"></i> Catat</a>
    </div>

    <!-- Desktop table -->
    <div class="ag-grid-wrapper" style="border-radius:0 0 16px 16px;">
      <table class="dash-table" style="width:100%;border-collapse:collapse;font-size:13px;">
        <thead>
          <tr style="background:var(--bg-body);">
            <?php foreach(['#','Tanggal','Komoditas','Lahan','Jumlah','Total Nilai','Aksi'] as $h): ?>
            <th style="padding:9px 12px;text-align:left;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--text-muted);white-space:nowrap;"><?=$h?></th>
            <?php endforeach; ?>
          </tr>
        </thead>
        <tbody>
          <?php if(empty($recentPanen)): ?>
            <tr><td colspan="7" style="padding:28px;text-align:center;color:var(--text-muted);">Belum ada data panen.</td></tr>
          <?php else: ?>
            <?php foreach($recentPanen as $i=>$p): ?>
            <tr style="border-top:1px solid var(--border-color);<?=$i%2?'background:var(--bg-table-odd);':''?>">
              <td style="padding:9px 12px;color:var(--text-muted);"><?=$i+1?></td>
              <td style="padding:9px 12px;white-space:nowrap;"><?=date('d M Y',strtotime($p['tanggal_panen']))?></td>
              <td style="padding:9px 12px;font-weight:600;"><?=esc($p['nama_tanaman'])?></td>
              <td style="padding:9px 12px;color:var(--text-secondary);"><?=esc($p['nama_lahan'])?></td>
              <td style="padding:9px 12px;font-weight:600;"><?=number_format($p['jumlah_panen'],0,',','.')?> <?=esc($p['satuan'])?></td>
              <td style="padding:9px 12px;font-weight:700;color:var(--pk-primary);">Rp&nbsp;<?=number_format($p['total_nilai'],0,',','.')?></td>
              <td style="padding:9px 12px;">
                <div style="display:flex;gap:4px;">
                  <a href="<?=base_url('panen/edit/'.$p['id'])?>" class="btn btn-icon btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                  <button onclick="hapusPanen(<?=$p['id']?>)" class="btn btn-icon btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <!-- Mobile card list -->
    <div class="mobile-card-list" id="dashMobile" style="padding:12px;"></div>

    <div style="padding:10px 14px;border-top:1px solid var(--border-color);text-align:center;">
      <a href="<?=base_url('riwayat')?>" style="color:var(--pk-primary);font-size:13px;font-weight:600;">
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
      <div class="card-body" style="padding:10px 14px;">
        <?php if(empty($upcoming)): ?>
          <p style="color:var(--text-muted);font-size:13px;text-align:center;padding:6px 0;">Tidak ada jadwal 30 hari ke depan.</p>
        <?php else: ?>
          <?php $icons=['Padi'=>'🌾','Jagung'=>'🌽','Cabai'=>'🌶️','Tomat'=>'🍅','Kedelai'=>'🫘','Singkong'=>'🥔']; ?>
          <?php foreach($upcoming as $u): ?>
          <div style="display:flex;align-items:center;gap:9px;padding:7px 0;border-bottom:1px solid var(--border-color);">
            <span style="font-size:18px;flex-shrink:0;"><?=$icons[$u['nama_tanaman']]??'🌱'?></span>
            <div style="flex:1;min-width:0;">
              <div style="font-size:12px;font-weight:700;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?=esc($u['nama_tanaman'])?> — <?=esc($u['nama_lahan'])?></div>
              <div style="font-size:11px;color:var(--text-muted);"><?=$u['sisa_hari']?> hari lagi</div>
            </div>
            <div style="font-size:11px;font-weight:700;color:<?=$u['sisa_hari']<=7?'var(--pk-danger)':($u['sisa_hari']<=14?'var(--pk-warning)':'var(--pk-success)')?>; white-space:nowrap;">
              <?=date('d M',strtotime($u['perkiraan_panen']))?>
            </div>
          </div>
          <?php endforeach; ?>
        <?php endif; ?>
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

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
// Chart Produksi
(function(){
  const labels      = <?= json_encode(array_column($grafik6Bulan,'label')) ?>;
  const data        = <?= json_encode(array_column($grafik6Bulan,'total')) ?>;
  const lblSatuans  = <?= json_encode(array_column($grafik6Bulan,'label_satuan')) ?>;
  const ctx         = document.getElementById('chartProduksi').getContext('2d');
  const grad        = ctx.createLinearGradient(0,0,0,220);
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

// Chart Komoditas
(function(){
  const labels      = <?= json_encode(array_column($perKomoditas,'nama_tanaman')) ?>;
  const data        = <?= json_encode(array_column($perKomoditas,'total')) ?>;
  const lblSatuans  = <?= json_encode(array_column($perKomoditas,'label_satuan')) ?>;
  const colors      = ['#2d8a4e','#e67e22','#e74c3c','#3498db','#8e44ad','#1abc9c'];
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
})();

// Mobile dashboard cards
(function(){
  const data = <?= json_encode(array_values($recentPanen)) ?>;
  const pager = new MobilePager('dashMobile', data, (p, i) => `
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
  confirmDialog('Yakin ingin menghapus data panen ini?', async ()=>{
    try{
      const res=await apiDelete('/panen/delete/'+id);
      if(res.status==='success'){ Toast.show('Data panen berhasil dihapus.','success'); setTimeout(()=>location.reload(),700); }
      else Toast.show(res.message||'Gagal menghapus.','error');
    }catch(e){ Toast.show('Terjadi kesalahan.','error'); }
  },{ title:'Hapus Panen', type:'danger', confirmText:'Ya, Hapus' });
}
</script>
<?= $this->endSection() ?>