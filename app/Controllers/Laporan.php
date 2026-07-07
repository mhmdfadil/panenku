<?php

namespace App\Controllers;

use App\Models\PanenModel;
use App\Models\TanamanModel;
use App\Models\LahanModel;
use App\Models\UserModel;

class Laporan extends BaseController
{
    public function index()
    {
        $userId       = $this->userId();
        $tanamanModel = new TanamanModel();
        $lahanModel   = new LahanModel();

        return view('laporan/index', [
            'title'   => 'Laporan Panen',
            'tanaman' => $tanamanModel->getForSelect($userId),
            'lahan'   => $lahanModel->getForSelect($userId),
        ]);
    }

    public function getData()
    {
        $userId  = $this->userId();
        $model   = new PanenModel();
        $filters = [
            'tanaman_id' => $this->request->getGet('tanaman_id'),
            'lahan_id'   => $this->request->getGet('lahan_id'),
            'dari'       => $this->request->getGet('dari'),
            'sampai'     => $this->request->getGet('sampai'),
        ];

        $data = $model->getWithRelations($userId, $filters);

        $totalProduksi = array_sum(array_column($data, 'jumlah_panen'));
        $totalNilai    = array_sum(array_column($data, 'total_nilai'));

        // Hitung total per satuan untuk label yang akurat
        $perSatuan = [];
        foreach ($data as &$row) {
            $row['tanggal_panen_fmt'] = date('d M Y', strtotime($row['tanggal_panen']));
            $row['jumlah_panen_fmt']  = number_format($row['jumlah_panen'], 0, ',', '.') . ' ' . ($row['satuan'] ?? 'kg');
            $row['harga_per_kg_fmt']  = 'Rp ' . number_format($row['harga_per_kg'], 0, ',', '.');
            $row['total_nilai_fmt']   = 'Rp ' . number_format($row['total_nilai'], 0, ',', '.');
            $s = $row['satuan'] ?? 'kg';
            $perSatuan[$s] = ($perSatuan[$s] ?? 0) + (float)$row['jumlah_panen'];
        }

        // Label ringkas satuan: "1.200 kg • 2 ton"
        $totalProduksiFmt = implode(' • ', array_map(
            fn($s, $v) => number_format($v, 0, ',', '.') . ' ' . $s,
            array_keys($perSatuan), array_values($perSatuan)
        )) ?: '0';

        return $this->jsonResponse([
            'data'               => $data,
            'total'              => count($data),
            'total_produksi'     => $totalProduksi,
            'total_nilai'        => $totalNilai,
            'total_produksi_fmt' => $totalProduksiFmt,
            'total_nilai_fmt'    => 'Rp ' . number_format($totalNilai, 0, ',', '.'),
            'per_satuan'         => $perSatuan,
        ]);
    }

    public function cetak()
    {
        $userId       = $this->userId();
        $model        = new PanenModel();
        $userModel    = new UserModel();
        $tanamanModel = new TanamanModel();
        $lahanModel   = new LahanModel();

        $filters = [
            'tanaman_id' => $this->request->getGet('tanaman_id'),
            'lahan_id'   => $this->request->getGet('lahan_id'),
            'dari'       => $this->request->getGet('dari'),
            'sampai'     => $this->request->getGet('sampai'),
        ];

        $data          = $model->getWithRelations($userId, $filters);
        $totalNilai    = array_sum(array_column($data, 'total_nilai'));
        $user          = $userModel->find($userId);

        // Hitung total per satuan
        $perSatuan = [];
        foreach ($data as $row) {
            $s = $row['satuan'] ?? 'kg';
            $perSatuan[$s] = ($perSatuan[$s] ?? 0) + (float)$row['jumlah_panen'];
        }
        $totalProduksi    = array_sum($perSatuan);
        $totalProduksiFmt = implode(' • ', array_map(
            fn($s, $v) => number_format($v, 0, ',', '.') . ' ' . $s,
            array_keys($perSatuan), array_values($perSatuan)
        )) ?: '0';

        return view('laporan/cetak', [
            'title'            => 'Laporan Panen',
            'data'             => $data,
            'filters'          => $filters,
            'totalProduksi'    => $totalProduksi,
            'totalProduksiFmt' => $totalProduksiFmt,
            'perSatuan'        => $perSatuan,
            'totalNilai'       => $totalNilai,
            'user'             => $user,
            'tanaman'          => $tanamanModel->getForSelect($userId),
            'lahan'            => $lahanModel->getForSelect($userId),
        ]);
    }

}