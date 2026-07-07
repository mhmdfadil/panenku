<?php

namespace App\Controllers;

use App\Models\PanenModel;
use App\Models\LahanModel;
use App\Models\TanamanModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $panenModel   = new PanenModel();
        $lahanModel   = new LahanModel();
        $userId       = $this->userId();

        $stats        = $panenModel->getDashboardStats($userId);
        $totalLahan   = $lahanModel->getTotalLuas($userId);
        $recentPanen  = $panenModel->getRecentPanen($userId);
        $upcoming     = $panenModel->getUpcomingPanen($userId);
        $grafik6Bulan = $panenModel->getProduksi6Bulan($userId);
        $perKomoditas = $panenModel->getProduksiPerKomoditas($userId);

        return view('dashboard/index', [
            'title'         => 'Dashboard',
            'stats'         => $stats,
            'totalLahan'    => $totalLahan,
            'recentPanen'   => $recentPanen,
            'upcoming'      => $upcoming,
            'grafik6Bulan'  => $grafik6Bulan,
            'perKomoditas'  => $perKomoditas,
        ]);
    }
}
