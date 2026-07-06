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

    public function getData()
    {
        $data = $this->model->getByUser($this->userId());
        foreach ($data as &$row) {
            $row['masa_tanam_fmt'] = $row['masa_tanam'] ? $row['masa_tanam'] . ' hari' : '-';
        }
        return $this->jsonResponse(['data' => $data, 'total' => count($data)]);
    }
}