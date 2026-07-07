<?php

namespace App\Controllers;

use App\Models\PanenModel;
use App\Models\TanamanModel;
use App\Models\LahanModel;

class Panen extends BaseController
{
    protected PanenModel   $panenModel;
    protected TanamanModel $tanamanModel;
    protected LahanModel   $lahanModel;

    public function __construct()
    {
        $this->panenModel   = new PanenModel();
        $this->tanamanModel = new TanamanModel();
        $this->lahanModel   = new LahanModel();
    }

    public function index()
    {
        $userId = $this->userId();
        return view('panen/index', [
            'title'    => 'Pencatatan Panen',
            'tanaman'  => $this->tanamanModel->getForSelect($userId),
            'lahan'    => $this->lahanModel->getForSelect($userId),
        ]);
    }

    // AG Grid data endpoint
    public function getData()
    {
        $userId  = $this->userId();
        $filters = [
            'tanaman_id' => $this->request->getGet('tanaman_id'),
            'lahan_id'   => $this->request->getGet('lahan_id'),
            'kualitas'   => $this->request->getGet('kualitas'),
            'dari'       => $this->request->getGet('dari'),
            'sampai'     => $this->request->getGet('sampai'),
            'search'     => $this->request->getGet('search'),
        ];

        $data = $this->panenModel->getWithRelations($userId, $filters);

        // Format for AG Grid
        foreach ($data as &$row) {
            $row['tanggal_panen_fmt']  = date('d M Y', strtotime($row['tanggal_panen']));
            $row['jumlah_panen_fmt']   = number_format($row['jumlah_panen'], 0, ',', '.') . ' ' . $row['satuan'];
            $row['harga_per_kg_fmt']   = 'Rp ' . number_format($row['harga_per_kg'], 0, ',', '.');
            $row['total_nilai_fmt']    = 'Rp ' . number_format($row['total_nilai'], 0, ',', '.');
            $row['komoditas']          = $row['nama_tanaman'] . ($row['varietas'] ? ' - ' . $row['varietas'] : '');
        }

        return $this->jsonResponse(['data' => $data, 'total' => count($data)]);
    }
}
