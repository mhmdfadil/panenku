<?php

namespace App\Controllers;

use Config\Database;

class Grafik extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    /* ─── Halaman utama ─── */
    public function index()
    {
        return view('grafik/index', ['title' => 'Grafik & Analisis']);
    }

    /* ─── /grafik/produksi?bulan=N ─── */
    public function produksi()
    {
        $userId = $this->userId();
        $bulan  = max(1, (int)($this->request->getGet('bulan') ?? 6));
        $data   = [];

        for ($i = $bulan - 1; $i >= 0; $i--) {
            $ts  = strtotime("-{$i} months");
            $y   = date('Y', $ts);
            $m   = date('m', $ts);

            // Ambil semua baris bulan ini beserta satuannya
            $rows = $this->db->table('panen')
                ->select('jumlah_panen, satuan')
                ->where('user_id', $userId)
                ->where('YEAR(tanggal_panen)', $y)
                ->where('MONTH(tanggal_panen)', $m)
                ->get()->getResultArray();

            // Kelompokkan total per satuan
            $perSatuan = [];
            foreach ($rows as $r) {
                $s = $r['satuan'] ?: 'kg';
                $perSatuan[$s] = ($perSatuan[$s] ?? 0) + (float)$r['jumlah_panen'];
            }

            $data[] = [
                'label'      => date('M Y', $ts),
                'total'      => array_sum(array_values($perSatuan)),  // total numerik untuk fallback
                'per_satuan' => $perSatuan,                           // breakdown per satuan
                // Label ringkas: "500 kg • 2 ton" dll
                'label_satuan' => implode(' • ', array_map(
                    fn($s, $v) => number_format($v, 0, ',', '.') . ' ' . $s,
                    array_keys($perSatuan), array_values($perSatuan)
                )),
            ];
        }

        return $this->jsonResponse(['data' => $data]);
    }

    /* ─── /grafik/nilai?bulan=N ─── */
    public function nilai()
    {
        $userId = $this->userId();
        $bulan  = max(1, (int)($this->request->getGet('bulan') ?? 6));
        $data   = [];

        for ($i = $bulan - 1; $i >= 0; $i--) {
            $ts = strtotime("-{$i} months");
            $y  = date('Y', $ts);
            $m  = date('m', $ts);

            $row = $this->db->table('panen')
                ->selectSum('total_nilai', 'nilai')
                ->where('user_id', $userId)
                ->where('YEAR(tanggal_panen)', $y)
                ->where('MONTH(tanggal_panen)', $m)
                ->get()->getRowArray();

            $data[] = [
                'label' => date('M Y', $ts),
                'nilai' => (float)($row['nilai'] ?? 0),
            ];
        }

        return $this->jsonResponse(['data' => $data]);
    }

    /* ─── /grafik/komoditas ─── */
    public function komoditas()
    {
        $userId = $this->userId();

        // Ambil detail per komoditas per satuan
        $rows = $this->db->table('panen p')
            ->select('t.nama_tanaman, p.satuan, SUM(p.jumlah_panen) as total, SUM(p.total_nilai) as nilai')
            ->join('tanaman t', 'p.tanaman_id = t.id')
            ->where('p.user_id', $userId)
            ->groupBy('p.tanaman_id, t.nama_tanaman, p.satuan')
            ->orderBy('total', 'DESC')
            ->get()->getResultArray();

        // Gabungkan per nama_tanaman, simpan breakdown satuan
        $map = [];
        foreach ($rows as $r) {
            $nama = $r['nama_tanaman'];
            if (!isset($map[$nama])) {
                $map[$nama] = [
                    'nama_tanaman' => $nama,
                    'total'        => 0,
                    'nilai'        => 0,
                    'per_satuan'   => [],
                ];
            }
            $s = $r['satuan'] ?: 'kg';
            $map[$nama]['per_satuan'][$s] = ($map[$nama]['per_satuan'][$s] ?? 0) + (float)$r['total'];
            $map[$nama]['total']  += (float)$r['total'];
            $map[$nama]['nilai']  += (float)$r['nilai'];
        }

        // Buat label ringkas per komoditas
        foreach ($map as &$item) {
            $item['label_satuan'] = implode(' • ', array_map(
                fn($s, $v) => number_format($v, 0, ',', '.') . ' ' . $s,
                array_keys($item['per_satuan']), array_values($item['per_satuan'])
            ));
            // Satuan dominan (yang terbesar volumenya)
            arsort($item['per_satuan']);
            $item['satuan_dominan'] = array_key_first($item['per_satuan']) ?? 'kg';
        }

        return $this->jsonResponse(['data' => array_values($map)]);
    }

    /* ─── /grafik/lahan ─── */
    public function lahan()
    {
        $userId = $this->userId();

        $rows = $this->db->table('panen p')
            ->select('l.nama_lahan, p.satuan, SUM(p.jumlah_panen) as total, SUM(p.total_nilai) as nilai')
            ->join('lahan l', 'p.lahan_id = l.id')
            ->where('p.user_id', $userId)
            ->groupBy('p.lahan_id, l.nama_lahan, p.satuan')
            ->orderBy('total', 'DESC')
            ->get()->getResultArray();

        // Gabungkan per nama_lahan
        $map = [];
        foreach ($rows as $r) {
            $nama = $r['nama_lahan'];
            if (!isset($map[$nama])) {
                $map[$nama] = [
                    'nama_lahan'  => $nama,
                    'total'       => 0,
                    'nilai'       => 0,
                    'per_satuan'  => [],
                ];
            }
            $s = $r['satuan'] ?: 'kg';
            $map[$nama]['per_satuan'][$s] = ($map[$nama]['per_satuan'][$s] ?? 0) + (float)$r['total'];
            $map[$nama]['total']  += (float)$r['total'];
            $map[$nama]['nilai']  += (float)$r['nilai'];
        }

        foreach ($map as &$item) {
            $item['label_satuan'] = implode(' • ', array_map(
                fn($s, $v) => number_format($v, 0, ',', '.') . ' ' . $s,
                array_keys($item['per_satuan']), array_values($item['per_satuan'])
            ));
            arsort($item['per_satuan']);
            $item['satuan_dominan'] = array_key_first($item['per_satuan']) ?? 'kg';
        }

        return $this->jsonResponse(['data' => array_values($map)]);
    }
}