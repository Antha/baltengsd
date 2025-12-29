<?php

namespace App\Controllers;

use App\Models\StNotaVfSimModel;
helper(['custom_helper']);

class St_nota_vf_sim extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new StNotaVfSimModel();
    }
    public function index()
    {
        //parsing $pic variable
        $pic = "I MADE KURNIAWAN HERNAWANTA";
        //parsing monitoring variable (1D,2D,3D,5D,7D,30D,ALL)
        $parse_validity = "3D";
        if($parse_validity == "ALL"){
            $validity = "";
        }else{
            $validity = "AND validity = '".$parse_validity."'";
        }

        //parsing hari_pjp variable (ALL, SENIN - SABTU)
        $parse_hari_pjp = "SENIN";
        if($parse_hari_pjp == "ALL"){
            $hari_pjp = "UPPER(hari_pjp) IN ('SENIN','SELASA','RABU','KAMIS','JUMAT','SABTU')";
        }else{
            $hari_pjp = "UPPER(hari_pjp) = '".$parse_hari_pjp."'";
        }

        $query_update_date_st_digipos = $this->model->get_latest_update_date_st_digipos();
        $dm = date("d",strtotime($query_update_date_st_digipos['update_date_m']));
        $dm1 = date("d",strtotime($query_update_date_st_digipos['update_date_m1']));
        $data['tgl_update'] = $dm." ".showLongBln(date("F",strtotime($query_update_date_st_digipos['update_date_m'])));
        $data['parse_type'] = $parse_validity;
        $data['hari_pjp'] = $parse_hari_pjp;

        $data['result_trx_summary'] = $this->model->data_summary($pic,$validity,$hari_pjp,$dm,$dm1);
        $data['result_trx_detail'] = $this->model->data_detail($pic,$validity,$hari_pjp,$dm,$dm1);

        return view('st_nota_vf_sim_page',$data);
        
    }
}