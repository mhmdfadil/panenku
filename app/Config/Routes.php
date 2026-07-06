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
