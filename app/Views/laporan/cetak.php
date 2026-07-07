<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Laporan Panen — PanenKu</title>
  <style>
    * { box-sizing: border-box; }
    body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 13px; color: #222; margin: 0; padding: 24px; background: #fff; }
    .header { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 20px; padding-bottom: 16px; border-bottom: 2px solid #2d8a4e; }
    .brand { display: flex; align-items: center; gap: 10px; }
    .brand-icon { width: 40px; height: 40px; background: #2d8a4e; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 20px; }
    .brand-name { font-size: 20px; font-weight: 700; color: #2d8a4e; }
    .brand-tag { font-size: 11px; color: #666; }
    .report-info { text-align: right; font-size: 12px; color: #555; }
    .report-title { font-size: 16px; font-weight: 700; color: #222; margin-bottom: 2px; }
    h2 { font-size: 15px; margin: 16px 0 8px; color: #333; }
    .summary { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-bottom: 20px; }
    .sum-box { background: #f5f5f5; border-radius: 8px; padding: 12px; text-align: center; }
    .sum-label { font-size: 11px; color: #666; margin-bottom: 4px; }
    .sum-value { font-size: 16px; font-weight: 700; color: #2d8a4e; }
    .sum-value-stack div { font-size: 13px; line-height: 1.4; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
    thead th { background: #2d8a4e; color: #fff; padding: 8px 10px; text-align: left; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: .04em; }
    tbody tr:nth-child(even) { background: #f8f8f8; }
    tbody td { padding: 7px 10px; border-bottom: 1px solid #eee; }
    tfoot td { font-weight: 700; background: #e8f5ee; padding: 8px 10px; color: #2d8a4e; }
    .badge { display: inline-block; padding: 2px 8px; border-radius: 12px; font-size: 10px; font-weight: 600; }
    .badge-success { background: #d4edda; color: #155724; }
    .badge-primary { background: #cce5ff; color: #004085; }
    .badge-warning { background: #fff3cd; color: #856404; }
    .badge-danger  { background: #f8d7da; color: #721c24; }
    .footer { margin-top: 32px; display: flex; justify-content: space-between; font-size: 12px; color: #666; border-top: 1px solid #ddd; padding-top: 12px; }
    .ttd { text-align: center; }
    .ttd-line { width: 160px; border-top: 1px solid #333; margin: 40px auto 4px; }
    @media print {
      body { padding: 12px; }
      .no-print { display: none; }
    }
  </style>
</head>
<body>

<div class="header">
  <div class="brand">
    <div class="brand-icon">🌾</div>
    <div>
      <div class="brand-name">PanenKu</div>
      <div class="brand-tag">Catat Hasil Panen</div>
    </div>
  </div>
  <div class="report-info">
    <div class="report-title">Laporan Hasil Panen</div>
    <div id="periodeInfo">Semua Periode</div>
    <div>Petani: <strong id="userNama">Budi Santoso</strong></div>
    <div>Lokasi: <span id="userDesa">Sukamaju</span></div>
    <div id="tanggalCetak">Dicetak: —</div>
  </div>
</div>

<!-- Summary -->
<div class="summary">
  <div class="sum-box">
    <div class="sum-label">Total Panen</div>
    <div class="sum-value" id="sumCount">— kali</div>
  </div>
  <div class="sum-box">
    <div class="sum-label">Total Produksi</div>
    <div class="sum-value sum-value-stack" id="sumProduksi"></div>
  </div>
  <div class="sum-box">
    <div class="sum-label">Total Nilai</div>
    <div class="sum-value" id="sumNilai" style="font-size:13px;">Rp 0</div>
  </div>
  <div class="sum-box">
    <div class="sum-label">Rata-rata/Panen</div>
    <div class="sum-value" id="sumAvg">0</div>
  </div>
</div>

<h2>Rincian Data Panen</h2>
<table>
  <thead>
    <tr>
      <th style="width:40px;">No</th>
      <th>Tanggal</th>
      <th>Komoditas</th>
      <th>Lahan</th>
      <th style="text-align:right;">Jumlah</th>
      <th style="text-align:right;">Harga/Satuan</th>
      <th style="text-align:right;">Total Nilai</th>
      <th>Kualitas</th>
    </tr>
  </thead>
  <tbody id="tableBody">
    <!-- diisi otomatis oleh JS dari data contoh -->
  </tbody>
  <tfoot>
    <tr>
      <td colspan="4" style="text-align:right;">TOTAL</td>
      <td style="text-align:right;" id="footProduksi"></td>
      <td></td>
      <td style="text-align:right;" id="footNilai">Rp 0</td>
      <td></td>
    </tr>
  </tfoot>
</table>

<div class="footer">
  <div>
    <div>Dicetak oleh: <strong id="footerNama">Budi Santoso</strong></div>
    <div id="footerDesa">Sukamaju</div>
  </div>
  <div class="ttd">
    <div id="ttdLokasiTanggal">Sukamaju, —</div>
    <div class="ttd-line"></div>
    <div><strong id="ttdNama">Budi Santoso</strong></div>
    <div style="font-size:11px; color:#666;">Petani</div>
  </div>
</div>

<div style="text-align:center; margin-top:16px;" class="no-print">
  <button onclick="window.print()" style="background:#2d8a4e;color:#fff;border:none;padding:10px 24px;border-radius:8px;cursor:pointer;font-size:14px;font-weight:600;">
    🖨️ Cetak Laporan
  </button>
</div>

<script>
/* =========================================================
   DATA CONTOH (statis) — pengganti data dari controller PHP
   ========================================================= */
const user = { nama: 'Budi Santoso', desa: 'Sukamaju' };

const filters = { dari: '2026-06-01', sampai: '2026-07-06' }; // kosongkan ('') untuk "Semua Periode"

const data = [
  { tanggal_panen: '2026-07-02', nama_tanaman: 'Padi',    varietas: 'IR64',      nama_lahan: 'Sawah Utara',    jumlah_panen: 500, satuan: 'kg', harga_per_kg: 7000, total_nilai: 3500000, kualitas: 'Sangat Baik' },
  { tanggal_panen: '2026-06-28', nama_tanaman: 'Jagung',  varietas: 'Pioneer 27',nama_lahan: 'Ladang Timur',   jumlah_panen: 300, satuan: 'kg', harga_per_kg: 6000, total_nilai: 1800000, kualitas: 'Baik' },
  { tanggal_panen: '2026-06-20', nama_tanaman: 'Cabai',   varietas: '',          nama_lahan: 'Kebun Belakang', jumlah_panen: 120, satuan: 'kg', harga_per_kg: 20000,total_nilai: 2400000, kualitas: 'Baik' },
  { tanggal_panen: '2026-06-14', nama_tanaman: 'Tomat',   varietas: '',          nama_lahan: 'Kebun Belakang', jumlah_panen: 200, satuan: 'kg', harga_per_kg: 8000, total_nilai: 1600000, kualitas: 'Cukup' },
  { tanggal_panen: '2026-06-05', nama_tanaman: 'Kedelai', varietas: '',          nama_lahan: 'Ladang Timur',   jumlah_panen: 150, satuan: 'kg', harga_per_kg: 6000, total_nilai: 900000,  kualitas: 'Kurang' },
];

/* ========================================================= */

const badgeCls = {
  'Sangat Baik': 'badge-success',
  'Baik': 'badge-primary',
  'Cukup': 'badge-warning',
  'Kurang': 'badge-danger',
};

const fmtDate = (v) => v ? new Date(v).toLocaleDateString('id-ID', { day:'2-digit', month:'short', year:'numeric' }) : '—';
const fmtRp   = (v) => 'Rp ' + Number(v||0).toLocaleString('id-ID');
const fmtNum  = (v) => Number(v||0).toLocaleString('id-ID');

// Total produksi per satuan, misal "500 kg • 300 kg" -> digabung per satuan
function buildTotalProduksiFmt(rows) {
  const bySatuan = {};
  rows.forEach(d => {
    bySatuan[d.satuan] = (bySatuan[d.satuan] || 0) + Number(d.jumlah_panen || 0);
  });
  return Object.entries(bySatuan).map(([satuan, total]) => `${fmtNum(total)} ${satuan}`);
}

(function render() {
  // Info periode
  const periodeEl = document.getElementById('periodeInfo');
  if (filters.dari || filters.sampai) {
    const dari   = filters.dari ? new Date(filters.dari).toLocaleDateString('id-ID',{day:'2-digit',month:'short',year:'numeric'}) : '—';
    const sampai = filters.sampai ? new Date(filters.sampai).toLocaleDateString('id-ID',{day:'2-digit',month:'short',year:'numeric'}) : '—';
    periodeEl.textContent = `Periode: ${dari} s/d ${sampai}`;
  } else {
    periodeEl.textContent = 'Semua Periode';
  }

  // Info user
  document.getElementById('userNama').textContent = user.nama;
  document.getElementById('userDesa').textContent = user.desa || '-';
  document.getElementById('footerNama').textContent = user.nama;
  document.getElementById('footerDesa').textContent = user.desa || '';
  document.getElementById('ttdNama').textContent = user.nama;

  const now = new Date();
  document.getElementById('tanggalCetak').textContent =
    'Dicetak: ' + now.toLocaleDateString('id-ID',{day:'2-digit',month:'short',year:'numeric'}) + ' ' + now.toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit'});
  document.getElementById('ttdLokasiTanggal').textContent =
    (user.desa || '') + ', ' + now.toLocaleDateString('id-ID',{day:'2-digit',month:'short',year:'numeric'});

  // Summary
  const totalNilai = data.reduce((s,d) => s + Number(d.total_nilai||0), 0);
  const totalProduksiFmtParts = buildTotalProduksiFmt(data);
  const totalProduksiSum = data.reduce((s,d) => s + Number(d.jumlah_panen||0), 0);

  document.getElementById('sumCount').textContent = data.length + ' kali';
  document.getElementById('sumProduksi').innerHTML = totalProduksiFmtParts.map(l => `<div>${l}</div>`).join('');
  document.getElementById('sumNilai').textContent = fmtRp(totalNilai);
  document.getElementById('sumAvg').textContent = data.length > 0
    ? (totalProduksiSum / data.length).toLocaleString('id-ID', { maximumFractionDigits: 1 })
    : '0';

  // Tabel
  const tbody = document.getElementById('tableBody');
  if (!data.length) {
    tbody.innerHTML = `<tr><td colspan="8" style="text-align:center; color:#888; padding:20px;">Tidak ada data</td></tr>`;
  } else {
    tbody.innerHTML = data.map((d, i) => `
      <tr>
        <td>${i + 1}</td>
        <td style="white-space:nowrap;">${fmtDate(d.tanggal_panen)}</td>
        <td>${d.nama_tanaman}${d.varietas ? ` <small style="color:#888;">(${d.varietas})</small>` : ''}</td>
        <td>${d.nama_lahan}</td>
        <td style="text-align:right;">${fmtNum(d.jumlah_panen)} ${d.satuan}</td>
        <td style="text-align:right;">${fmtRp(d.harga_per_kg)}</td>
        <td style="text-align:right; font-weight:600;">${fmtRp(d.total_nilai)}</td>
        <td><span class="badge ${badgeCls[d.kualitas] || ''}">${d.kualitas}</span></td>
      </tr>`).join('');
  }

  // Footer tabel (total)
  document.getElementById('footProduksi').innerHTML = totalProduksiFmtParts.join('<br>');
  document.getElementById('footNilai').textContent = fmtRp(totalNilai);
})();

// Auto-print on load
// window.onload = () => window.print();
</script>
</body>
</html>