<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Auth::index');
$routes->post('auth/cek_ajax', 'Auth::cekAjax'); // AJAX route
$routes->group('',['filter' => 'auth'], function($routes){
    $routes->get('logout', 'Auth::logout');
    $routes->get('/home', 'Home::index');
    $routes->get('/omset_trx', 'Omset_trx::index');
    $routes->get('/st_nota_sa', 'St_nota_sa::index');
    $routes->get('/st_nota_vf_byu', 'St_nota_vf_byu::index');
    $routes->get('/st_nota_vf_sim', 'St_nota_vf_sim::index');
});
