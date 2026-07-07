<?php

namespace App\Controllers;

use Config\Database;

class Grafik extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    /* ─── Halaman utama ─── */
    public function index()
    {
        return view('grafik/index', ['title' => 'Grafik & Analisis']);
    }

}