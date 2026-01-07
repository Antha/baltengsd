<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Auth::index');
$routes->get('/auth', 'Auth::index');
$routes->post('auth/cek_ajax', 'Auth::cekAjax'); // AJAX route
$routes->group('',['filter' => 'auth'], function($routes){
    $routes->get('logout', 'Auth::logout');
    $routes->get('home', 'Home::index');
    //admin dashboard
    $routes->get('omset_trx_dashboard', 'Omset_trx_dashboard::index');
    $routes->post('omset_trx_dashboard', 'Omset_trx_dashboard::index');
    $routes->post('omset_trx_dashboard/getPicByTap', 'Omset_trx_dashboard::getPicByTap');

    $routes->get('st_nota_sa_dashboard', 'St_nota_sa_dashboard::index');
    $routes->post('st_nota_sa_dashboard', 'St_nota_sa_dashboard::index');
    $routes->post('st_nota_sa_dashboard/getPicByTap', 'St_nota_sa_dashboard::getPicByTap');

    $routes->get('st_nota_vf_byu_dashboard', 'St_nota_vf_byu_dashboard::index');
    $routes->post('st_nota_vf_byu_dashboard', 'St_nota_vf_byu_dashboard::index');
    $routes->post('st_nota_vf_byu_dashboard/getPicByTap', 'St_nota_vf_byu_dashboard::getPicByTap');

    $routes->get('st_nota_vf_sim_dashboard', 'St_nota_vf_sim_dashboard::index');
    $routes->post('st_nota_vf_sim_dashboard', 'St_nota_vf_sim_dashboard::index');
    $routes->post('st_nota_vf_sim_dashboard/getPicByTap', 'St_nota_vf_sim_dashboard::getPicByTap');
    
    //telegram bot page capture
    $routes->get('omset_trx', 'Omset_trx::index');
    $routes->get('st_nota_sa', 'St_nota_sa::index');
    $routes->get('st_nota_vf_byu', 'St_nota_vf_byu::index');
    $routes->get('st_nota_vf_sim', 'St_nota_vf_sim::index');
});

$routes->group('replace', ['filter' => 'admin'], function ($routes) {
    $routes->get('upload', 'ReplaceData::upload');
    $routes->post('preview', 'ReplaceData::preview');
    $routes->post('confirm', 'ReplaceData::confirm');
});
