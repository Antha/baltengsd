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
    
    $routes->get('st_digipos_sa_dashboard', 'St_digipos_sa_dashboard::index');
    $routes->post('st_digipos_sa_dashboard', 'St_digipos_sa_dashboard::index');
    $routes->post('st_digipos_sa_dashboard/getPicByTap', 'St_digipos_sa_dashboard::getPicByTap');

    $routes->get('st_digipos_vf_byu_dashboard', 'St_digipos_vf_byu_dashboard::index');
    $routes->post('st_digipos_vf_byu_dashboard', 'St_digipos_vf_byu_dashboard::index');
    $routes->post('st_digipos_vf_byu_dashboard/getPicByTap', 'St_digipos_vf_byu_dashboard::getPicByTap');

    $routes->get('st_digipos_vf_sim_dashboard', 'St_digipos_vf_sim_dashboard::index');
    $routes->post('st_digipos_vf_sim_dashboard', 'St_digipos_vf_sim_dashboard::index');
    $routes->post('st_digipos_vf_sim_dashboard/getPicByTap', 'St_digipos_vf_sim_dashboard::getPicByTap');
    
    //telegram bot page capture
    $routes->get('omset_trx', 'Omset_trx::index');
    $routes->get('st_nota_sa', 'St_nota_sa::index');
    $routes->get('st_nota_vf_byu', 'St_nota_vf_byu::index');
    $routes->get('st_nota_vf_sim', 'St_nota_vf_sim::index');
    $routes->get('st_digipos_sa', 'St_digipos_sa::index');
    $routes->get('st_digipos_vf_byu', 'St_digipos_vf_byu::index');
    $routes->get('st_digipos_vf_sim', 'St_digipos_vf_sim::index');

    $routes->get('omset_trx/summary_all', 'Omset_trx::summary_all');
    $routes->get('omset_trx/summary_by_outlet', 'Omset_trx::summary_by_outlet');
    $routes->get('omset_trx/summary_by_sf', 'Omset_trx::summary_by_sf');

    $routes->get('st_nota_sa/summary_all', 'St_nota_sa::summary_all');
    $routes->get('st_nota_sa/summary_by_outlet', 'St_nota_sa::summary_by_outlet');
    $routes->get('st_nota_sa/summary_by_sf', 'St_nota_sa::summary_by_sf');

    $routes->get('st_nota_vf_byu/summary_all', 'St_nota_vf_byu::summary_all');
    $routes->get('st_nota_vf_byu/summary_by_outlet', 'St_nota_vf_byu::summary_by_outlet');
    $routes->get('st_nota_vf_byu/summary_by_sf', 'St_nota_vf_byu::summary_by_sf');

    $routes->get('st_nota_vf_sim/summary_all', 'St_nota_vf_sim::summary_all');
    $routes->get('st_nota_vf_sim/summary_by_outlet', 'St_nota_vf_sim::summary_by_outlet');
    $routes->get('st_nota_vf_sim/summary_by_sf', 'St_nota_vf_sim::summary_by_sf');

    $routes->get('st_digipos_sa/summary_all', 'St_digipos_sa::summary_all');
    $routes->get('st_digipos_sa/summary_by_outlet', 'St_digipos_sa::summary_by_outlet');
    $routes->get('st_digipos_sa/summary_by_sf', 'St_digipos_sa::summary_by_sf');

    $routes->get('st_digipos_vf_byu/summary_all', 'St_digipos_vf_byu::summary_all');
    $routes->get('st_digipos_vf_byu/summary_by_outlet', 'St_digipos_vf_byu::summary_by_outlet');
    $routes->get('st_digipos_vf_byu/summary_by_sf', 'St_digipos_vf_byu::summary_by_sf');

    $routes->get('st_digipos_vf_sim/summary_all', 'St_digipos_vf_sim::summary_all');
    $routes->get('st_digipos_vf_sim/summary_by_outlet', 'St_digipos_vf_sim::summary_by_outlet');
    $routes->get('st_digipos_vf_sim/summary_by_sf', 'St_digipos_vf_sim::summary_by_sf');
});

$routes->group('replace', ['filter' => 'admin'], function ($routes) {
    $routes->get('upload', 'ReplaceData::upload');
    $routes->post('preview', 'ReplaceData::preview');
    $routes->post('confirm', 'ReplaceData::confirm');
    $routes->get('startImport', 'ReplaceData::startImport');
    $routes->post('startimport', 'ReplaceData::startImport');
    $routes->get('processImport/(:num)', 'ReplaceData::processImport/$1');
    $routes->post('download/sample', 'DownloadSample::sampleTablePost');
});

//telegram
$routes->post('api/telegram/verify', 'API\Telegram::verify');
$routes->get('api/telegram/sf_list', 'API\Telegram::sf_list');
$routes->get('api/telegram/sf_list_telegram', 'API\Telegram::sf_list_telegram');
?>