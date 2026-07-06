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
});

// Redirect root to login
$routes->get('/', 'Auth::login');