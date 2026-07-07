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

}
