<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');

// ============================================================
// AUTH ROUTES (guest only)
// ============================================================
$routes->group('', ['filter' => 'guest'], function ($routes) {
    $routes->get('/',           'Auth::login');
    $routes->get('login',       'Auth::login');
    $routes->post('login',      'Auth::doLogin');
    $routes->get('register',    'Auth::register');
    $routes->post('register',   'Auth::doRegister');
});

// ============================================================
// APP ROUTES (auth required)
// ============================================================
$routes->group('', ['filter' => 'auth'], function ($routes) {
    $routes->get('logout',      'Auth::logout');

    // Dashboard
    $routes->get('dashboard',   'Dashboard::index');

    // Data Tanaman
    $routes->get('tanaman',             'Tanaman::index');
    $routes->get('tanaman/data',        'Tanaman::getData');
    $routes->post('tanaman/store',      'Tanaman::store');
    $routes->post('tanaman/update/(:num)', 'Tanaman::update/$1');
    $routes->delete('tanaman/delete/(:num)', 'Tanaman::delete/$1');
    $routes->get('tanaman/show/(:num)', 'Tanaman::show/$1');
    $routes->get('tanaman/satuan-map',  'Tanaman::satuanMap');

    // Data Lahan
    $routes->get('lahan',               'Lahan::index');
    $routes->get('lahan/data',          'Lahan::getData');
    $routes->post('lahan/store',        'Lahan::store');
    $routes->post('lahan/update/(:num)', 'Lahan::update/$1');
    $routes->delete('lahan/delete/(:num)', 'Lahan::delete/$1');
    $routes->get('lahan/show/(:num)',   'Lahan::show/$1');

    // Pencatatan Panen
    $routes->get('panen',               'Panen::index');
    $routes->get('panen/data',          'Panen::getData');
    $routes->get('panen/create',        'Panen::create');
    $routes->post('panen/store',        'Panen::store');
    $routes->get('panen/edit/(:num)',   'Panen::edit/$1');
    $routes->post('panen/update/(:num)', 'Panen::update/$1');
    $routes->delete('panen/delete/(:num)', 'Panen::delete/$1');
    $routes->get('panen/show/(:num)',   'Panen::show/$1');

    // Riwayat Panen
    $routes->get('riwayat',             'Riwayat::index');
    $routes->get('riwayat/data',        'Riwayat::getData');

    // Laporan
    $routes->get('laporan',             'Laporan::index');

});

// Redirect root to login
$routes->get('/', 'Auth::login');