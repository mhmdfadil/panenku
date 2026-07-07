<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<!-- Period selector -->
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:22px;flex-wrap:wrap;gap:12px;">
  <div class="nm-box" style="display:flex;gap:6px;padding:6px;" id="periodSwitcher">
    <button class="btn btn-outline btn-sm period-btn" data-period="3">3 Bln</button>
    <button class="btn btn-outline btn-sm period-btn" data-period="6">6 Bln</button>
    <button class="btn btn-primary btn-sm period-btn" data-period="12">12 Bln</button>
  </div>
  <span style="font-size:13px;color:var(--text-muted);"><i class="bi bi-calendar3"></i> Data per <span id="todayLabel">—</span></span>
</div>

<style>
.chart-type-btn {
  background: none;
  border: 1px solid var(--border-color);
  border-radius: 6px;
  padding: 3px 8px;
  font-size: 11px;
  cursor: pointer;
  color: var(--text-muted);
  transition: all .2s;
  display: flex;
  align-items: center;
  gap: 4px;
}
.chart-type-btn:hover,
.chart-type-btn.active {
  background: var(--pk-primary);
  border-color: var(--pk-primary);
  color: #fff;
}
.chart-type-switcher {
  display: flex;
  gap: 4px;
  flex-wrap: wrap;
}
.chart-wrapper {
  position: relative;
  width: 100%;
}
</style>

<!-- Row 1: Tren -->
<div class="grid-2" style="margin-bottom:20px;">
  <div class="card">
    <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;">
      <h3 class="card-title"><i class="bi bi-graph-up" style="color:var(--pk-primary);"></i> Tren Produksi</h3>
      <div class="chart-type-switcher" id="switcherTren">
        <button class="chart-type-btn" data-chart="tren" data-type="line" title="Line"><i class="bi bi-graph-up-arrow"></i> Line</button>
        <button class="chart-type-btn" data-chart="tren" data-type="bar" title="Bar"><i class="bi bi-bar-chart"></i> Bar</button>
        <button class="chart-type-btn" data-chart="tren" data-type="radar" title="Radar"><i class="bi bi-pentagon"></i> Radar</button>
        <button class="chart-type-btn" data-chart="tren" data-type="doughnut" title="Doughnut"><i class="bi bi-circle-half"></i> Donut</button>
        <button class="chart-type-btn" data-chart="tren" data-type="polarArea" title="Polar"><i class="bi bi-bullseye"></i> Polar</button>
        <button class="chart-type-btn" data-chart="tren" data-type="scatter" title="Scatter"><i class="bi bi-diagram-3"></i> Scatter</button>
        <button class="chart-type-btn" data-chart="tren" data-type="bubble" title="Bubble"><i class="bi bi-circle"></i> Bubble</button>
      </div>
    </div>
    <div class="card-body">
      <div class="chart-wrapper" style="height:260px;">
        <canvas id="chartTren"></canvas>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;">
      <h3 class="card-title"><i class="bi bi-cash-coin" style="color:var(--pk-warning);"></i> Tren Nilai Panen</h3>
      <div class="chart-type-switcher" id="switcherNilai">
        <button class="chart-type-btn" data-chart="nilai" data-type="line" title="Line"><i class="bi bi-graph-up-arrow"></i> Line</button>
        <button class="chart-type-btn" data-chart="nilai" data-type="bar" title="Bar"><i class="bi bi-bar-chart"></i> Bar</button>
        <button class="chart-type-btn" data-chart="nilai" data-type="radar" title="Radar"><i class="bi bi-pentagon"></i> Radar</button>
        <button class="chart-type-btn" data-chart="nilai" data-type="doughnut" title="Doughnut"><i class="bi bi-circle-half"></i> Donut</button>
        <button class="chart-type-btn" data-chart="nilai" data-type="polarArea" title="Polar"><i class="bi bi-bullseye"></i> Polar</button>
        <button class="chart-type-btn" data-chart="nilai" data-type="scatter" title="Scatter"><i class="bi bi-diagram-3"></i> Scatter</button>
        <button class="chart-type-btn" data-chart="nilai" data-type="bubble" title="Bubble"><i class="bi bi-circle"></i> Bubble</button>
      </div>
    </div>
    <div class="card-body">
      <div class="chart-wrapper" style="height:260px;">
        <canvas id="chartNilai"></canvas>
      </div>
    </div>
  </div>
</div>

<!-- Row 2: Distribusi -->
<div class="grid-2">
  <div class="card">
    <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;">
      <h3 class="card-title"><i class="bi bi-pie-chart" style="color:var(--pk-info);"></i> Distribusi per Komoditas</h3>
      <div class="chart-type-switcher" id="switcherKomoditas">
        <button class="chart-type-btn" data-chart="komoditas" data-type="line" title="Line"><i class="bi bi-graph-up-arrow"></i> Line</button>
        <button class="chart-type-btn" data-chart="komoditas" data-type="bar" title="Bar"><i class="bi bi-bar-chart"></i> Bar</button>
        <button class="chart-type-btn" data-chart="komoditas" data-type="radar" title="Radar"><i class="bi bi-pentagon"></i> Radar</button>
        <button class="chart-type-btn" data-chart="komoditas" data-type="doughnut" title="Doughnut"><i class="bi bi-circle-half"></i> Donut</button>
        <button class="chart-type-btn" data-chart="komoditas" data-type="polarArea" title="Polar"><i class="bi bi-bullseye"></i> Polar</button>
        <button class="chart-type-btn" data-chart="komoditas" data-type="scatter" title="Scatter"><i class="bi bi-diagram-3"></i> Scatter</button>
        <button class="chart-type-btn" data-chart="komoditas" data-type="bubble" title="Bubble"><i class="bi bi-circle"></i> Bubble</button>
      </div>
    </div>
    <div class="card-body">
      <div id="komoditasLayout" style="display:grid;grid-template-columns:1fr 1fr;gap:16px;align-items:center;">
        <div class="chart-wrapper" style="height:220px;">
          <canvas id="chartKomoditas"></canvas>
        </div>
        <div id="komoditasLegend"></div>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;">
      <h3 class="card-title"><i class="bi bi-bar-chart-fill" style="color:var(--pk-success);"></i> Produksi per Lahan</h3>
      <div class="chart-type-switcher" id="switcherLahan">
        <button class="chart-type-btn" data-chart="lahan" data-type="line" title="Line"><i class="bi bi-graph-up-arrow"></i> Line</button>
        <button class="chart-type-btn" data-chart="lahan" data-type="bar" title="Bar"><i class="bi bi-bar-chart"></i> Bar</button>
        <button class="chart-type-btn" data-chart="lahan" data-type="radar" title="Radar"><i class="bi bi-pentagon"></i> Radar</button>
        <button class="chart-type-btn" data-chart="lahan" data-type="doughnut" title="Doughnut"><i class="bi bi-circle-half"></i> Donut</button>
        <button class="chart-type-btn" data-chart="lahan" data-type="polarArea" title="Polar"><i class="bi bi-bullseye"></i> Polar</button>
        <button class="chart-type-btn" data-chart="lahan" data-type="scatter" title="Scatter"><i class="bi bi-diagram-3"></i> Scatter</button>
        <button class="chart-type-btn" data-chart="lahan" data-type="bubble" title="Bubble"><i class="bi bi-circle"></i> Bubble</button>
      </div>
    </div>
    <div class="card-body">
      <div class="chart-wrapper" style="height:220px;">
        <canvas id="chartLahan"></canvas>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

  // Tanggal hari ini (pengganti PHP date('d M Y'))
  document.getElementById('todayLabel').textContent =
    new Date().toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });

  /* =========================================================
     DATA CONTOH (statis) — pengganti apiFetch ke endpoint PHP
     ========================================================= */
  const SAMPLE_TREN_12 = [
    { label:'Agu', total:380, label_satuan:'380 kg' },
    { label:'Sep', total:410, label_satuan:'410 kg' },
    { label:'Okt', total:350, label_satuan:'350 kg' },
    { label:'Nov', total:460, label_satuan:'460 kg' },
    { label:'Des', total:520, label_satuan:'520 kg' },
    { label:'Jan', total:390, label_satuan:'390 kg' },
    { label:'Feb', total:420, label_satuan:'420 kg' },
    { label:'Mar', total:510, label_satuan:'510 kg' },
    { label:'Apr', total:380, label_satuan:'380 kg' },
    { label:'Mei', total:610, label_satuan:'610 kg' },
    { label:'Jun', total:700, label_satuan:'700 kg' },
    { label:'Jul', total:620, label_satuan:'620 kg' },
  ];
  const SAMPLE_NILAI_12 = SAMPLE_TREN_12.map(d => ({ label: d.label, nilai: d.total * 7000 }));

  const SAMPLE_KOMODITAS = [
    { nama_tanaman:'Padi',     total:1200, label_satuan:'1.200 kg' },
    { nama_tanaman:'Jagung',   total:800,  label_satuan:'800 kg' },
    { nama_tanaman:'Cabai',    total:450,  label_satuan:'450 kg' },
    { nama_tanaman:'Tomat',    total:390,  label_satuan:'390 kg' },
    { nama_tanaman:'Kedelai',  total:250,  label_satuan:'250 kg' },
    { nama_tanaman:'Singkong', total:150,  label_satuan:'150 kg' },
  ];

  const SAMPLE_LAHAN = [
    { nama_lahan:'Sawah Utara',    total:1400, label_satuan:'1.400 kg' },
    { nama_lahan:'Ladang Timur',   total:1050, label_satuan:'1.050 kg' },
    { nama_lahan:'Kebun Belakang', total:790,  label_satuan:'790 kg' },
  ];

  function sliceByPeriod(fullData, months) {
    return fullData.slice(-months);
  }

  /* ========================================================= */

  const COLORS = ['#2d8a4e','#e67e22','#e74c3c','#3498db','#8e44ad','#1abc9c','#f39c12','#2ecc71'];
  const COLORS_ALPHA = COLORS.map(c => c + 'bb');

  // Cache data per endpoint agar saat ganti tipe tidak perlu fetch ulang
  let cache = { tren: null, nilai: null, komoditas: null, lahan: null };

  // Instance Chart.js aktif
  let charts = { tren: null, nilai: null, komoditas: null, lahan: null };

  // Tipe aktif per chart (default berbeda-beda)
  let activeType = { tren: 'line', nilai: 'bar', komoditas: 'doughnut', lahan: 'radar' };

  let currentPeriod = 12;

  // ============================================================
  // UTIL: Destroy & recreate canvas (Chart.js kadang masih error
  // jika hanya destroy() saat ganti tipe drastis seperti ke radar)
  // ============================================================
  function freshCanvas(id) {
    const old = document.getElementById(id);
    if (!old) return null;
    const wrapper = old.parentElement;
    old.remove();
    const canvas = document.createElement('canvas');
    canvas.id = id;
    canvas.style.cssText = 'display:block;width:100%;height:100%;';
    wrapper.appendChild(canvas);
    return canvas;
  }

  // ============================================================
  // UTIL: Apakah tipe ini pakai scales (x/y)?
  // ============================================================
  function hasScales(type) {
    return ['bar','line','scatter','bubble'].includes(type);
  }

  // Scatter/bubble pakai {x,y} / {x,y,r} — tidak perlu categorical labels
  function isXY(type) {
    return ['scatter','bubble'].includes(type);
  }

  // Konversi array nilai biasa ke format scatter/bubble
  // x = index, y = nilai, r = radius proporsional (bubble)
  // _xyLabels dipakai oleh renderChart untuk mapping tick x → label
  let _xyLabels = [];
  function toXYData(values, labels, type) {
    _xyLabels = labels || [];
    const max = Math.max(...values) || 1;
    return values.map((v, i) => {
      if (type === 'bubble') return { x: i, y: v, r: Math.max(4, Math.round((v / max) * 22)) };
      return { x: i, y: v };
    });
  }

  // ============================================================
  // UTIL: Build dataset sesuai tipe
  // ============================================================
  function buildDataset(type, label, values, primaryColor, labels) {
    const base = { label };
    const data = isXY(type) ? toXYData(values, labels, type) : values;

    if (type === 'line') {
      return { ...base, data,
        borderColor: primaryColor,
        backgroundColor: primaryColor + '33',
        borderWidth: 3,
        tension: 0.4,
        fill: true,
        pointBackgroundColor: primaryColor,
        pointBorderColor: '#fff',
        pointBorderWidth: 2,
        pointRadius: 5,
        pointHoverRadius: 8,
      };
    }
    if (type === 'bar') {
      return { ...base, data,
        backgroundColor: primaryColor + 'bb',
        borderColor: primaryColor,
        borderWidth: 2,
        borderRadius: 6,
      };
    }
    if (type === 'scatter') {
      return { ...base, data,
        backgroundColor: COLORS_ALPHA.slice(0, values.length),
        borderColor: COLORS.slice(0, values.length),
        borderWidth: 2,
        pointRadius: 7,
        pointHoverRadius: 10,
      };
    }
    if (type === 'bubble') {
      return { ...base, data,
        backgroundColor: COLORS_ALPHA.slice(0, values.length),
        borderColor: COLORS.slice(0, values.length),
        borderWidth: 2,
        hoverBorderWidth: 3,
      };
    }
    // doughnut / polarArea / radar — multi color
    return { ...base, data,
      backgroundColor: COLORS_ALPHA.slice(0, values.length),
      borderColor: COLORS.slice(0, values.length),
      borderWidth: 2,
      hoverOffset: 6,
    };
  }

  // ============================================================
  // RENDER GENERIK
  // ============================================================
  function renderChart(id, type, labels, values, primaryColor, extraOptions) {
    const canvas = freshCanvas(id);
    if (!canvas) return null;

    const dataset = buildDataset(type, '', values, primaryColor, labels);

    // Merge plugins: legend always hidden, merge tooltip from extraOptions
    const options = {
      responsive: true,
      maintainAspectRatio: false,
      animation: { duration: 400 },
      plugins: {
        legend: { display: false },
        tooltip: extraOptions?.plugins?.tooltip || {},
      },
    };

    if (hasScales(type)) {
      if (isXY(type)) {
        // Scatter/Bubble: x numerik, tapi label harus muncul dari capturedLabels
        // Batasi tick hanya pada index integer yang valid (0 .. labels.length-1)
        const capturedLabels = [...labels];
        const maxIdx = capturedLabels.length - 1;
        options.scales = {
          x: {
            type: 'linear',
            min: 0,
            max: maxIdx,
            ticks: {
              stepSize: 1,
              callback: function(val) {
                const idx = Math.round(val);
                if (idx < 0 || idx > maxIdx) return '';
                return capturedLabels[idx] ?? '';
              },
              maxRotation: 45,
              autoSkip: true,
              maxTicksLimit: Math.min(capturedLabels.length, 12),
            },
            grid: { display: false },
          },
          y: {
            beginAtZero: true,
            ...(extraOptions?.scales?.y || {}),
          },
        };
      } else {
        // Line/Bar: x categorical
        options.scales = {
          x: {
            type: 'category',
            grid: { display: false },
            ...(extraOptions?.scales?.x || {}),
          },
          y: {
            beginAtZero: true,
            ...(extraOptions?.scales?.y || {}),
          },
        };
      }
    }

    // Bar horizontal (lahan)
    if (extraOptions?.indexAxis) options.indexAxis = extraOptions.indexAxis;

    return new Chart(canvas, {
      type,
      data: { labels: isXY(type) ? [] : labels, datasets: [dataset] },
      options,
    });
  }

  // ============================================================
  // RENDER TREN PRODUKSI
  // ============================================================
  function renderTren(data, type) {
    if (!data || !data.length) return;
    type = type || activeType.tren;
    const labels = data.map(d => d.label || '');
    const values = data.map(d => parseFloat(d.total) || 0);

    // Buat label tooltip yang lengkap dengan satuan per bulan
    const tooltipLabels = data.map(d => {
      if (d.label_satuan && d.label_satuan !== '') return d.label_satuan;
      return (parseFloat(d.total)||0).toLocaleString('id-ID');
    });

    charts.tren = renderChart('chartTren', type, labels, values, '#2d8a4e', {
      plugins: {
        tooltip: { callbacks: {
          title: ctx => {
            const idx = isXY(type) ? Math.round(ctx[0].parsed?.x ?? ctx[0].dataIndex) : ctx[0].dataIndex;
            return 'Pada ' + (labels[idx] ?? '') + ':';
          },
          label: ctx => {
            const idx = isXY(type) ? Math.round(ctx.parsed?.x ?? ctx.dataIndex) : ctx.dataIndex;
            const lbl = tooltipLabels[idx] ?? '';
            return lbl ? lbl.split('•').map(s => s.trim()) : [];
          }
        }}
      },
      scales: {
        x: { grid: { display: false } },
        y: { beginAtZero: true, ticks: { callback: v => v.toLocaleString('id-ID') } },
      }
    });
    markActive('tren', type);
  }

  // ============================================================
  // RENDER TREN NILAI
  // ============================================================
  function renderNilai(data, type) {
    if (!data || !data.length) return;
    type = type || activeType.nilai;
    const labels = data.map(d => d.label || '');
    const values = data.map(d => parseFloat(d.nilai) || 0);

    const fmtNilai = v => {
      if (v >= 1e9) return 'Rp ' + (v/1e9).toFixed(1) + 'M';
      if (v >= 1e6) return 'Rp ' + (v/1e6).toFixed(1) + 'jt';
      return 'Rp ' + v.toLocaleString('id-ID');
    };

    charts.nilai = renderChart('chartNilai', type, labels, values, '#f39c12', {
      plugins: {
        tooltip: { callbacks: { label: ctx => {
          const val = hasScales(type) ? (ctx.parsed?.y ?? ctx.parsed) : (ctx.parsed ?? 0);
          const idx = ctx.parsed?.x ?? ctx.dataIndex;
          const labelName = labels[Math.round(idx)] ?? ctx.label ?? '';
          const prefix = isXY(type) ? (labelName ? labelName + ': ' : '') : '';
          return prefix + 'Nilai: ' + fmtNilai(val ?? 0);
        }}}
      },
      scales: {
        x: { grid: { display: false } },
        y: { beginAtZero: true, ticks: { callback: fmtNilai } },
      }
    });
    markActive('nilai', type);
  }

  // ============================================================
  // RENDER KOMODITAS
  // ============================================================
  function renderKomoditas(data, type) {
    if (!data || !data.length) {
      document.getElementById('komoditasLegend').innerHTML =
        '<div style="color:var(--text-muted);text-align:center;padding:20px;">Belum ada data</div>';
      return;
    }
    type = type || activeType.komoditas;
    const labels = data.map(d => d.nama_tanaman || 'Unknown');
    const values = data.map(d => parseFloat(d.total) || 0);
    const total  = values.reduce((a, b) => a + b, 0);
    const isCircular = ['doughnut','polarArea'].includes(type);

    const layout = document.getElementById('komoditasLayout');
    if (isCircular) {
      layout.style.gridTemplateColumns = '1fr 1fr';
    } else {
      layout.style.gridTemplateColumns = '1fr';
      document.getElementById('komoditasLegend').innerHTML = '';
    }

    charts.komoditas = renderChart('chartKomoditas', type, labels, values, '#3498db', {
      plugins: { tooltip: { callbacks: {
        title: ctx => {
          const idx = isXY(type) ? Math.round(ctx[0].parsed?.x ?? ctx[0].dataIndex) : ctx[0].dataIndex;
          return 'Pada ' + (data[idx]?.nama_tanaman || '') + ':';
        },
        label: ctx => {
          const idx = isXY(type) ? Math.round(ctx.parsed?.x ?? ctx.dataIndex) : ctx.dataIndex;
          const d = data[idx];
          if (!d) return [];
          const lines = (d.label_satuan || (parseFloat(d.total)||0).toLocaleString('id-ID')).split('•').map(s => s.trim());
          if (isCircular) {
            const t = values.reduce((a,b)=>a+b,0);
            const pct = t > 0 ? ((values[idx]/t)*100).toFixed(1) : 0;
            lines.push('(' + pct + '%)');
          }
          return lines;
        }
      }}},
    });

    if (type === 'doughnut') { charts.komoditas.options.cutout = '65%'; charts.komoditas.update(); }

    if (isCircular) {
      document.getElementById('komoditasLegend').innerHTML = data.map((d, i) => {
        const val = parseFloat(d.total) || 0;
        const pct = total > 0 ? ((val/total)*100).toFixed(1) : 0;
        const lbl = d.label_satuan || val.toLocaleString('id-ID');
        return `<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;padding:4px 0;">
          <div style="display:flex;align-items:center;gap:8px;">
            <span style="width:10px;height:10px;background:${COLORS[i%COLORS.length]};border-radius:50%;flex-shrink:0;"></span>
            <span style="font-size:13px;font-weight:500;color:var(--text-primary);">${d.nama_tanaman||'Unknown'}</span>
          </div>
          <div style="text-align:right;">
            <div style="font-weight:700;font-size:13px;color:var(--text-primary);">${lbl}</div>
            <div style="color:var(--text-muted);font-size:11px;">${pct}%</div>
          </div>
        </div>`;
      }).join('');
    }
    markActive('komoditas', type);
  }

  // ============================================================
  // RENDER LAHAN
  // ============================================================
  function renderLahan(data, type) {
    if (!data || !data.length) return;
    type = type || activeType.lahan;
    const labels = data.map(d => d.nama_lahan || 'Unknown');
    const values = data.map(d => parseFloat(d.total) || 0);

    const isHorizontalBar = type === 'bar';

    charts.lahan = renderChart('chartLahan', type, labels, values, '#2d8a4e', {
      ...(isHorizontalBar ? { indexAxis: 'y' } : {}),
      scales: isHorizontalBar ? {
        x: { beginAtZero: true, ticks: { callback: v => v.toLocaleString('id-ID') } },
        y: { grid: { display: false } }
      } : {
        x: { grid: { display: false } },
        y: { beginAtZero: true, ticks: { callback: v => v.toLocaleString('id-ID') } }
      },
      plugins: {
        tooltip: { callbacks: {
          title: ctx => {
            const idx = isXY(type) ? Math.round(ctx[0].parsed?.x ?? ctx[0].dataIndex) : ctx[0].dataIndex;
            return 'Pada ' + (data[idx]?.nama_lahan || '') + ':';
          },
          label: ctx => {
            const idx = isXY(type) ? Math.round(ctx.parsed?.x ?? ctx.dataIndex) : ctx.dataIndex;
            const d = data[idx];
            if (!d) return [];
            const lbl = d.label_satuan || (parseFloat(d.total)||0).toLocaleString('id-ID');
            return lbl.split('•').map(s => s.trim());
          }
        }}
      },
    });
    markActive('lahan', type);
  }

  // ============================================================
  // MARK ACTIVE BUTTON
  // ============================================================
  function markActive(chartKey, type) {
    activeType[chartKey] = type;
    const switchers = {
      tren: 'switcherTren', nilai: 'switcherNilai',
      komoditas: 'switcherKomoditas', lahan: 'switcherLahan'
    };
    const container = document.getElementById(switchers[chartKey]);
    if (!container) return;
    container.querySelectorAll('.chart-type-btn').forEach(btn => {
      btn.classList.toggle('active', btn.dataset.type === type);
    });
  }

  // ============================================================
  // LOAD ALL DATA
  // (versi statis: pakai data contoh, bukan fetch ke backend)
  // ============================================================
  async function loadAll(period) {
    try {
      cache.tren      = sliceByPeriod(SAMPLE_TREN_12, period);
      cache.nilai     = sliceByPeriod(SAMPLE_NILAI_12, period);
      cache.komoditas = SAMPLE_KOMODITAS;
      cache.lahan     = SAMPLE_LAHAN;

      renderTren(cache.tren);
      renderNilai(cache.nilai);
      renderKomoditas(cache.komoditas);
      renderLahan(cache.lahan);

    } catch (err) {
      console.error('Error loadAll:', err);
      if (typeof Toast !== 'undefined') Toast.show('Gagal memuat data: ' + err.message, 'error');
    }
  }

  // ============================================================
  // CHART TYPE SWITCHER CLICK
  // ============================================================
  document.querySelectorAll('.chart-type-btn').forEach(btn => {
    btn.addEventListener('click', function () {
      const chartKey = this.dataset.chart;
      const type     = this.dataset.type;
      activeType[chartKey] = type;

      switch (chartKey) {
        case 'tren':      renderTren(cache.tren, type);           break;
        case 'nilai':     renderNilai(cache.nilai, type);         break;
        case 'komoditas': renderKomoditas(cache.komoditas, type); break;
        case 'lahan':     renderLahan(cache.lahan, type);         break;
      }
    });
  });

  // ============================================================
  // PERIOD BUTTON
  // ============================================================
  document.querySelectorAll('.period-btn').forEach(btn => {
    btn.addEventListener('click', function () {
      document.querySelectorAll('.period-btn').forEach(b => {
        b.classList.remove('btn-primary');
        b.classList.add('btn-outline');
      });
      this.classList.remove('btn-outline');
      this.classList.add('btn-primary');
      currentPeriod = parseInt(this.dataset.period);
      loadAll(currentPeriod);
    });
  });

  // ============================================================
  // THEME OBSERVER
  // ============================================================
  new MutationObserver(mutations => {
    mutations.forEach(m => {
      if (m.attributeName === 'data-theme') {
        setTimeout(() => loadAll(currentPeriod), 100);
      }
    });
  }).observe(document.documentElement, { attributes: true });

  // ============================================================
  // INIT — set default active buttons sebelum load
  // ============================================================
  Object.entries(activeType).forEach(([key, type]) => markActive(key, type));
  loadAll(currentPeriod);
});
</script>

<?= $this->endSection() ?>