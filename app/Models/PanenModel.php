<?php

namespace App\Models;

use CodeIgniter\Model;

class PanenModel extends Model
{
    protected $table      = 'panen';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'user_id', 'tanaman_id', 'lahan_id', 'tanggal_panen',
        'jumlah_panen', 'satuan', 'harga_per_kg', 'total_nilai',
        'kualitas', 'cuaca', 'catatan', 'foto',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'tanaman_id'    => 'required|is_natural_no_zero',
        'lahan_id'      => 'required|is_natural_no_zero',
        'tanggal_panen' => 'required|valid_date[Y-m-d]',
        'jumlah_panen'  => 'required|decimal|greater_than[0]',
        'harga_per_kg'  => 'required|decimal',
    ];

    public function getWithRelations(int $userId, array $filters = []): array
    {
        $builder = $this->db->table('panen p')
            ->select('p.*, t.nama_tanaman, t.varietas, l.nama_lahan, l.jenis_lahan')
            ->join('tanaman t', 'p.tanaman_id = t.id')
            ->join('lahan l', 'p.lahan_id = l.id')
            ->where('p.user_id', $userId);

        if (!empty($filters['tanaman_id'])) {
            $builder->where('p.tanaman_id', $filters['tanaman_id']);
        }
        if (!empty($filters['lahan_id'])) {
            $builder->where('p.lahan_id', $filters['lahan_id']);
        }
        if (!empty($filters['kualitas'])) {
            $builder->where('p.kualitas', $filters['kualitas']);
        }
        if (!empty($filters['dari'])) {
            $builder->where('p.tanggal_panen >=', $filters['dari']);
        }
        if (!empty($filters['sampai'])) {
            $builder->where('p.tanggal_panen <=', $filters['sampai']);
        }
        if (!empty($filters['search'])) {
            $builder->groupStart()
                ->like('t.nama_tanaman', $filters['search'])
                ->orLike('l.nama_lahan', $filters['search'])
                ->orLike('p.catatan', $filters['search'])
                ->groupEnd();
        }

        return $builder->orderBy('p.tanggal_panen', 'DESC')->get()->getResultArray();
    }

    public function getDashboardStats(int $userId): array
    {
        $total_panen = $this->where('user_id', $userId)->countAllResults();
        $total_nilai = $this->selectSum('total_nilai')->where('user_id', $userId)->first()['total_nilai'] ?? 0;

        // Hitung total produksi per satuan
        $rows = $this->db->table('panen')
            ->select('satuan, SUM(jumlah_panen) as total')
            ->where('user_id', $userId)
            ->groupBy('satuan')
            ->get()->getResultArray();

        $perSatuan = [];
        $total_produksi = 0;
        foreach ($rows as $r) {
            $s = $r['satuan'] ?: 'kg';
            $perSatuan[$s] = (float)$r['total'];
            $total_produksi += (float)$r['total'];
        }

        $total_produksi_fmt = implode(' • ', array_map(
            fn($s, $v) => number_format($v, 0, ',', '.') . ' ' . $s,
            array_keys($perSatuan), array_values($perSatuan)
        )) ?: '0';

        // Bulan ini vs bulan lalu
        $bulanIni  = date('Y-m');
        $bulanLalu = date('Y-m', strtotime('-1 month'));

        $panenBulanIni  = $this->where('user_id', $userId)->where('DATE_FORMAT(tanggal_panen, "%Y-%m")', $bulanIni)->countAllResults();
        $panenBulanLalu = $this->where('user_id', $userId)->where('DATE_FORMAT(tanggal_panen, "%Y-%m")', $bulanLalu)->countAllResults();

        $produksiBulanIni  = (float)($this->selectSum('jumlah_panen')->where('user_id', $userId)->where('DATE_FORMAT(tanggal_panen, "%Y-%m")', $bulanIni)->first()['jumlah_panen'] ?? 0);
        $produksiBulanLalu = (float)($this->selectSum('jumlah_panen')->where('user_id', $userId)->where('DATE_FORMAT(tanggal_panen, "%Y-%m")', $bulanLalu)->first()['jumlah_panen'] ?? 0);
        $nilaiBulanIni     = (float)($this->selectSum('total_nilai')->where('user_id', $userId)->where('DATE_FORMAT(tanggal_panen, "%Y-%m")', $bulanIni)->first()['total_nilai'] ?? 0);
        $nilaiBulanLalu    = (float)($this->selectSum('total_nilai')->where('user_id', $userId)->where('DATE_FORMAT(tanggal_panen, "%Y-%m")', $bulanLalu)->first()['total_nilai'] ?? 0);

        return [
            'total_panen'        => $total_panen,
            'total_produksi'     => $total_produksi,
            'total_produksi_fmt' => $total_produksi_fmt,
            'per_satuan'         => $perSatuan,
            'total_nilai'        => (float)$total_nilai,
            'persen_panen'       => $panenBulanLalu > 0 ? round(($panenBulanIni - $panenBulanLalu) / $panenBulanLalu * 100, 1) : 0,
            'persen_produksi'    => $produksiBulanLalu > 0 ? round(($produksiBulanIni - $produksiBulanLalu) / $produksiBulanLalu * 100, 1) : 0,
            'persen_nilai'       => $nilaiBulanLalu > 0 ? round(($nilaiBulanIni - $nilaiBulanLalu) / $nilaiBulanLalu * 100, 1) : 0,
        ];
    }

    public function getProduksi6Bulan(int $userId): array
    {
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $months[] = date('Y-m', strtotime("-$i months"));
        }

        $result = [];
        foreach ($months as $month) {
            // Ambil breakdown per satuan tiap bulan
            $rows = $this->db->table('panen')
                ->select('satuan, SUM(jumlah_panen) as total')
                ->where('user_id', $userId)
                ->where('DATE_FORMAT(tanggal_panen, "%Y-%m")', $month)
                ->groupBy('satuan')
                ->get()->getResultArray();

            $perSatuan = [];
            $totalNum  = 0;
            foreach ($rows as $r) {
                $s = $r['satuan'] ?: 'kg';
                $perSatuan[$s] = (float)$r['total'];
                $totalNum += (float)$r['total'];
            }

            $labelSatuan = implode(' • ', array_map(
                fn($s, $v) => number_format($v, 0, ',', '.') . ' ' . $s,
                array_keys($perSatuan), array_values($perSatuan)
            ));

            $result[] = [
                'bulan'        => $month,
                'label'        => ucfirst(date('M', strtotime($month . '-01'))),
                'total'        => $totalNum,
                'per_satuan'   => $perSatuan,
                'label_satuan' => $labelSatuan,
            ];
        }
        return $result;
    }

    public function getProduksiPerKomoditas(int $userId): array
    {
        $rows = $this->db->table('panen p')
            ->select('t.nama_tanaman, p.satuan, SUM(p.jumlah_panen) as total, SUM(p.total_nilai) as nilai')
            ->join('tanaman t', 'p.tanaman_id = t.id')
            ->where('p.user_id', $userId)
            ->groupBy('p.tanaman_id, t.nama_tanaman, p.satuan')
            ->orderBy('total', 'DESC')
            ->get()->getResultArray();

        // Gabung per nama tanaman
        $map = [];
        foreach ($rows as $r) {
            $nama = $r['nama_tanaman'];
            if (!isset($map[$nama])) {
                $map[$nama] = ['nama_tanaman' => $nama, 'total' => 0, 'nilai' => 0, 'per_satuan' => []];
            }
            $s = $r['satuan'] ?: 'kg';
            $map[$nama]['per_satuan'][$s] = ($map[$nama]['per_satuan'][$s] ?? 0) + (float)$r['total'];
            $map[$nama]['total'] += (float)$r['total'];
            $map[$nama]['nilai'] += (float)$r['nilai'];
        }

        foreach ($map as &$item) {
            $item['label_satuan'] = implode(' • ', array_map(
                fn($s, $v) => number_format($v, 0, ',', '.') . ' ' . $s,
                array_keys($item['per_satuan']), array_values($item['per_satuan'])
            ));
            arsort($item['per_satuan']);
            $item['satuan_dominan'] = array_key_first($item['per_satuan']) ?? 'kg';
        }

        return array_values($map);
    }

    public function getRecentPanen(int $userId, int $limit = 5): array
    {
        return $this->db->table('panen p')
            ->select('p.*, t.nama_tanaman, l.nama_lahan')
            ->join('tanaman t', 'p.tanaman_id = t.id')
            ->join('lahan l', 'p.lahan_id = l.id')
            ->where('p.user_id', $userId)
            ->orderBy('p.tanggal_panen', 'DESC')
            ->limit($limit)
            ->get()->getResultArray();
    }

    public function getUpcomingPanen(int $userId): array
    {
        // Perkiraan panen berikutnya berdasarkan masa tanam tanaman
        return $this->db->table('panen p')
            ->select('p.tanggal_panen, t.nama_tanaman, t.masa_tanam, l.nama_lahan,
                      DATE_ADD(p.tanggal_panen, INTERVAL t.masa_tanam DAY) as perkiraan_panen,
                      DATEDIFF(DATE_ADD(p.tanggal_panen, INTERVAL t.masa_tanam DAY), CURDATE()) as sisa_hari')
            ->join('tanaman t', 'p.tanaman_id = t.id')
            ->join('lahan l', 'p.lahan_id = l.id')
            ->where('p.user_id', $userId)
            ->where('t.masa_tanam IS NOT NULL')
            ->having('sisa_hari >=', 0)
            ->having('sisa_hari <=', 30)
            ->orderBy('sisa_hari', 'ASC')
            ->limit(3)
            ->get()->getResultArray();
    }
}