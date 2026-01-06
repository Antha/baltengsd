<?php

namespace App\Controllers;

use App\Models\StNotaSaModel;
helper(['custom_helper']);

class St_nota_sa_dashboard extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new StNotaSaModel();
    }

    public function getPicByTap()
    {
        $tap = $this->request->getPost('tap');

        // aman dari SQL Injection
        if ($tap == 'ALL' || empty($tap)) {
            $tapCondition = "1=1";
        } else {
            $tapCondition = "tap = '".$tap."'";
        }

        $data = query_pic_list($tapCondition);

        return $this->response->setJSON($data);
    }

    public function index()
    {
        //cek apakah user tipe = Admin
        $user_type = session()->get('userType');
        //jika bukan, rediret ke halaman bot telegram capture
        if($user_type == "user"){
             return redirect()->to('/st_nota_sa');
        }

        $data['tap_list'] = query_tap_list(); 

        $monitoring_list = ['ALL DENO','SIMPATI','ESIM','BYU'];
        //parsing list monitoring ke view
        $data['monitoring_list'] = $monitoring_list;

        $hari_pjp_list = ['ALL','SENIN','SELASA','RABU','KAMIS','JUMAT','SABTU'];
        //parsing list hari pjp ke view
        $data['hari_pjp_list'] = $hari_pjp_list;

        //ambil value dari request yg sudah di submit
        $parse_tap = $this->request->getPost('filter_tap') ?? 'ALL';
        if($parse_tap == "ALL"){
            $tap = "tap != ''";
        }else{
            $tap =  "tap = '".$parse_tap."'";
        }

        //parsing $pic variable
        $data['pic_list'] = query_pic_list($tap); 
        $parse_pic = $this->request->getPost('filter_pic') ?? 'ALL';
         if($parse_pic == "ALL"){
            $pic = "pic != ''";
        }else{
            $pic =  "pic = '".$parse_pic."'";
        }

        //parsing monitoring variable ('SIMPATI','ESIM','BYU','ALL DENO')
        $parse_type = $this->request->getPost('filter_monitoring') ?? 'ALL DENO';
        //parsing tipe produk variable (SIMPATI,ESIM,BYU,ALL DENO)
        $tipe_produk = $parse_type;
        if($tipe_produk == "SIMPATI"){
            $nama_produk = "AND nama_produk = 'SIMPATI' AND tipe_produk = 'SIM'";
        }elseif($tipe_produk == "ESIM"){
            $nama_produk = "AND nama_produk = 'SIMPATI' AND tipe_produk = 'ESIM'";
        }elseif($tipe_produk == "BYU"){
            $nama_produk = "AND nama_produk = 'BYU' AND tipe_produk = 'SIM'";
        }else{
            $nama_produk = "";
        }

        //parsing hari_pjp variable (ALL, SENIN - SABTU)
        $parse_hari_pjp = $this->request->getPost('filter_hari_pjp') ?? 'ALL';
        if($parse_hari_pjp == "ALL"){
            $hari_pjp = "UPPER(hari_pjp) IN ('SENIN','SELASA','RABU','KAMIS','JUMAT','SABTU')";
        }else{
            $hari_pjp = "UPPER(hari_pjp) = '".$parse_hari_pjp."'";
        }

        $data['selected_pt'] = $parse_type;
        $data['selected_hp'] = $parse_hari_pjp;
        $data['selected_pic'] = $parse_pic;
        $data['selected_tap'] = $parse_tap;

        $query_update_date_st_digipos = $this->model->get_latest_update_date_st_digipos();
        $dm = date("d",strtotime($query_update_date_st_digipos['update_date_m']));
        $dm1 = date("d",strtotime($query_update_date_st_digipos['update_date_m1']));
        $data['tgl_update'] = $dm." ".showLongBln(date("F",strtotime($query_update_date_st_digipos['update_date_m'])));
        $data['parse_type'] = $tipe_produk;
        $data['hari_pjp'] = $parse_hari_pjp;

        $data['result_trx_summary'] = $this->model->data_summary_dashboard($tap,$nama_produk,$hari_pjp,$dm,$dm1);
        $data['result_trx_detail'] = $this->model->data_detail_dashboard($tap,$pic,$nama_produk,$hari_pjp,$dm,$dm1);

        return view('st_nota_sa_dashboard_page',$data);
        
    }
}