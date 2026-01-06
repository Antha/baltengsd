<?php

namespace App\Controllers;

use App\Models\StNotaVfSimModel;
helper(['custom_helper']);

class St_nota_vf_sim_dashboard extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new StNotaVfSimModel();
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
             return redirect()->to('/st_nota_vf_sim');
        }

        $data['tap_list'] = query_tap_list(); 

        $monitoring_list = ['ALL','1D','2D','3D','5D','7D','30D'];
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

        //parsing monitoring variable (1D,2D,3D,5D,7D,30D,ALL)
        $parse_validity = $this->request->getPost('filter_monitoring') ?? 'ALL';
        if($parse_validity == "ALL"){
            $validity = "";
        }else{
            $validity = "AND validity = '".$parse_validity."'";
        }

        //parsing hari_pjp variable (ALL, SENIN - SABTU)
        $parse_hari_pjp = $this->request->getPost('filter_hari_pjp') ?? 'ALL';
        if($parse_hari_pjp == "ALL"){
            $hari_pjp = "UPPER(hari_pjp) IN ('SENIN','SELASA','RABU','KAMIS','JUMAT','SABTU')";
        }else{
            $hari_pjp = "UPPER(hari_pjp) = '".$parse_hari_pjp."'";
        }

        $data['selected_pt'] = $parse_validity;
        $data['selected_hp'] = $parse_hari_pjp;
        $data['selected_pic'] = $parse_pic;
        $data['selected_tap'] = $parse_tap;

        $query_update_date_st_digipos = $this->model->get_latest_update_date_st_digipos();
        $dm = date("d",strtotime($query_update_date_st_digipos['update_date_m']));
        $dm1 = date("d",strtotime($query_update_date_st_digipos['update_date_m1']));
        $data['tgl_update'] = $dm." ".showLongBln(date("F",strtotime($query_update_date_st_digipos['update_date_m'])));
        $data['parse_type'] = $parse_validity;
        $data['hari_pjp'] = $parse_hari_pjp;

        $data['result_trx_summary'] = $this->model->data_summary_dashboard($tap,$pic,$validity,$hari_pjp,$dm,$dm1);
        $data['result_trx_detail'] = $this->model->data_detail_dashboard($tap,$pic,$validity,$hari_pjp,$dm,$dm1);

        return view('st_nota_vf_sim_dashboard_page',$data);
        
    }
}