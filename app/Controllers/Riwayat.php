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

}
