<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');

// ============================================================
// AUTH ROUTES (guest only)
// ============================================================
$routes->group('', ['filter' => 'guest'], function ($routes) {
    $routes->get('register',    'Auth::register');
    $routes->post('register',   'Auth::doRegister');
});
