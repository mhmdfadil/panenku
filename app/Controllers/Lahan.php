<?php

namespace App\Controllers;

use App\Models\LahanModel;

class Lahan extends BaseController
{
    protected LahanModel $model;

    public function __construct()
    {
        $this->model = new LahanModel();
    }

    public function index()
    {
        return view('lahan/index', ['title' => 'Data Lahan']);
    }

}
