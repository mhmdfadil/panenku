<?php

namespace App\Controllers;

use App\Models\PanenModel;
use App\Models\TanamanModel;
use App\Models\LahanModel;

class Riwayat extends BaseController
{
    public function index()
    {
        $userId       = $this->userId();
        $tanamanModel = new TanamanModel();
        $lahanModel   = new LahanModel();

        return view('riwayat/index', [
            'title'   => 'Riwayat Panen',
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
            'kualitas'   => $this->request->getGet('kualitas'),
            'dari'       => $this->request->getGet('dari'),
            'sampai'     => $this->request->getGet('sampai'),
            'search'     => $this->request->getGet('search'),
        ];

        $data = $model->getWithRelations($userId, $filters);
        foreach ($data as &$row) {
            $row['tanggal_panen_fmt'] = date('d M Y', strtotime($row['tanggal_panen']));
            $row['jumlah_panen_fmt']  = number_format($row['jumlah_panen'], 0, ',', '.') . ' ' . $row['satuan'];
            $row['harga_per_kg_fmt']  = 'Rp ' . number_format($row['harga_per_kg'], 0, ',', '.');
            $row['total_nilai_fmt']   = 'Rp ' . number_format($row['total_nilai'], 0, ',', '.');
            $row['komoditas']         = $row['nama_tanaman'];
        }

        return $this->jsonResponse(['data' => $data, 'total' => count($data)]);
    }
}
