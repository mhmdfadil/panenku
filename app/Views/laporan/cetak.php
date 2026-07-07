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
    <?php if (!empty($filters['dari']) || !empty($filters['sampai'])): ?>
      <div>Periode: <?= $filters['dari'] ? date('d M Y', strtotime($filters['dari'])) : '—' ?> s/d <?= $filters['sampai'] ? date('d M Y', strtotime($filters['sampai'])) : '—' ?></div>
    <?php else: ?>
      <div>Semua Periode</div>
    <?php endif; ?>
    <div>Petani: <?= esc($user['nama']) ?></div>
    <div>Lokasi: <?= esc($user['desa'] ?? '-') ?></div>
    <div>Dicetak: <?= date('d M Y H:i') ?></div>
  </div>
</div>

<!-- Summary -->
<div class="summary">
  <div class="sum-box">
    <div class="sum-label">Total Panen</div>
    <div class="sum-value"><?= count($data) ?> kali</div>
  </div>
  <div class="sum-box">
    <div class="sum-label">Total Produksi</div>
    <div class="sum-value sum-value-stack">
      <?php foreach(explode('•', $totalProduksiFmt) as $line): ?>
        <div><?= esc(trim($line)) ?></div>
      <?php endforeach; ?>
    </div>
  </div>
  <div class="sum-box">
    <div class="sum-label">Total Nilai</div>
    <div class="sum-value" style="font-size:13px;">Rp <?= number_format($totalNilai, 0, ',', '.') ?></div>
  </div>
  <div class="sum-box">
    <div class="sum-label">Rata-rata/Panen</div>
    <div class="sum-value"><?= count($data) > 0 ? number_format($totalProduksi / count($data), 1, ',', '.') : 0 ?></div>
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
  <tbody>
    <?php if (empty($data)): ?>
      <tr><td colspan="8" style="text-align:center; color:#888; padding:20px;">Tidak ada data</td></tr>
    <?php else: ?>
      <?php foreach ($data as $i => $d):
        $badgeCls = match($d['kualitas']) { 'Sangat Baik' => 'badge-success', 'Baik' => 'badge-primary', 'Cukup' => 'badge-warning', 'Kurang' => 'badge-danger', default => '' };
      ?>
      <tr>
        <td><?= $i + 1 ?></td>
        <td style="white-space:nowrap;"><?= date('d M Y', strtotime($d['tanggal_panen'])) ?></td>
        <td><?= esc($d['nama_tanaman']) ?><?= $d['varietas'] ? ' <small style="color:#888;">(' . esc($d['varietas']) . ')</small>' : '' ?></td>
        <td><?= esc($d['nama_lahan']) ?></td>
        <td style="text-align:right;"><?= number_format($d['jumlah_panen'], 0, ',', '.') ?> <?= esc($d['satuan']) ?></td>
        <td style="text-align:right;">Rp <?= number_format($d['harga_per_kg'], 0, ',', '.') ?></td>
        <td style="text-align:right; font-weight:600;">Rp <?= number_format($d['total_nilai'], 0, ',', '.') ?></td>
        <td><span class="badge <?= $badgeCls ?>"><?= esc($d['kualitas']) ?></span></td>
      </tr>
      <?php endforeach; ?>
    <?php endif; ?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan="4" style="text-align:right;">TOTAL</td>
      <td style="text-align:right;">
        <?= implode('<br>', array_map('trim', explode('•', $totalProduksiFmt))) ?>
      </td>
      <td></td>
      <td style="text-align:right;">Rp <?= number_format($totalNilai, 0, ',', '.') ?></td>
      <td></td>
    </tr>
  </tfoot>
</table>

<div class="footer">
  <div>
    <div>Dicetak oleh: <strong><?= esc($user['nama']) ?></strong></div>
    <div><?= esc($user['desa'] ?? '') ?></div>
  </div>
  <div class="ttd">
    <div><?= esc($user['desa'] ?? '') ?>, <?= date('d M Y') ?></div>
    <div class="ttd-line"></div>
    <div><strong><?= esc($user['nama']) ?></strong></div>
    <div style="font-size:11px; color:#666;">Petani</div>
  </div>
</div>

<div style="text-align:center; margin-top:16px;" class="no-print">
  <button onclick="window.print()" style="background:#2d8a4e;color:#fff;border:none;padding:10px 24px;border-radius:8px;cursor:pointer;font-size:14px;font-weight:600;">
    🖨️ Cetak Laporan
  </button>
</div>

<script>
  // Auto-print on load
  // window.onload = () => window.print();
</script>
</body>
</html>