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

    public function getData()
    {
        $data = $this->model->getByUser($this->userId());
        foreach ($data as &$row) {
            $row['luas_fmt'] = $row['luas'] ? number_format($row['luas'], 2) . ' ha' : '-';
        }
        return $this->jsonResponse(['data' => $data, 'total' => count($data)]);
    }
}
