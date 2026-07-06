<?php

namespace App\Controllers;

use App\Models\TanamanModel;

class Tanaman extends BaseController
{
    protected TanamanModel $model;

    public function __construct()
    {
        $this->model = new TanamanModel();
    }

    public function index()
    {
        return view('tanaman/index', ['title' => 'Data Tanaman']);
    }

}