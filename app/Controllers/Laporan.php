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

}