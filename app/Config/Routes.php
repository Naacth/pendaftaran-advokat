<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');

// ─────────────────────────────────────────────────────────────────────────────
// AUTH ADMIN
// ─────────────────────────────────────────────────────────────────────────────
$routes->get('admin/login', 'Admin\Auth::login');
$routes->post('admin/login', 'Admin\Auth::doLogin');
$routes->get('admin/logout', 'Admin\Auth::logout');

// ─────────────────────────────────────────────────────────────────────────────
// PUBLIK — PKPA
// ─────────────────────────────────────────────────────────────────────────────
$routes->group('pkpa', ['namespace' => 'App\Controllers\Pkpa'], static function ($routes) {
    $routes->get('/', 'Home::index');
    $routes->get('daftar', 'Registration::form');
    $routes->post('daftar', 'Registration::store', ['filter' => 'csrf']);
    $routes->get('daftar/sukses/(:segment)', 'Registration::success/$1');
    $routes->get('cek-status', 'Home::checkStatus');
    $routes->post('cek-status', 'Home::checkStatus', ['filter' => 'csrf']);
    $routes->get('perbaikan/(:segment)', 'Registration::perbaikan/$1');
    $routes->post('perbaikan/(:segment)', 'Registration::updatePerbaikan/$1', ['filter' => 'csrf']);
});

// ─────────────────────────────────────────────────────────────────────────────
// ADMIN PKPA (semua dilindungi filter adminAuth)
// ─────────────────────────────────────────────────────────────────────────────
$routes->group('admin/pkpa', [
    'namespace' => 'App\Controllers\Admin',
    'filter'    => 'adminAuth',
], static function ($routes) {
    // Dashboard
    $routes->get('/', 'Pkpa\Dashboard::index');

    // Angkatan
    $routes->get('angkatan', 'Pkpa\Batches::index');
    $routes->post('angkatan/datatable', 'Pkpa\Batches::datatable');
    $routes->post('angkatan/simpan', 'Pkpa\Batches::save');
    $routes->post('angkatan/hapus/(:num)', 'Pkpa\Batches::delete/$1');

    // Pendaftar
    $routes->get('pendaftar', 'Pkpa\Registrations::index');
    $routes->post('pendaftar/datatable', 'Pkpa\Registrations::datatable');
    $routes->get('pendaftar/ekspor', 'Pkpa\Registrations::export');
    $routes->get('pendaftar/(:num)', 'Pkpa\Registrations::show/$1');
    $routes->post('pendaftar/(:num)/status', 'Pkpa\Registrations::changeStatus/$1');
    $routes->post('pendaftar/(:num)/pembayaran', 'Pkpa\Registrations::confirmPayment/$1');
    $routes->post('pendaftar/hapus/(:num)', 'Pkpa\Registrations::delete/$1');
    $routes->get('pendaftar/(:num)/dokumen/(:num)', 'Pkpa\Registrations::document/$1/$2');

    // Pengaturan
    $routes->get('pengaturan', 'Pkpa\Settings::index');
    $routes->post('pengaturan/simpan', 'Pkpa\Settings::save');

    // User admin (super_admin only)
    $routes->get('users', 'Pkpa\Users::index', ['filter' => 'adminAuth:super_admin']);
    $routes->post('users/simpan', 'Pkpa\Users::save', ['filter' => 'adminAuth:super_admin']);
    $routes->post('users/hapus/(:num)', 'Pkpa\Users::delete/$1', ['filter' => 'adminAuth:super_admin']);
});

