<?php

namespace App\Controllers;

use App\Models\StNotaVfByuModel;
helper(['custom_helper']);

class St_nota_vf_byu extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new StNotaVfByuModel();
    }

    public function index()
    {
        //$idtel = '8025950853';//hapus & ganti dengan data idtsel dari user yg mengakses modul
        $idtel = $this->request->getGet('parse_idtel');

        //parsing monitoring variable (1D,14D,30D,ALL)
        //$parse_validity = "30D";//hapus & ganti dengan data validity dari input user
        $parse_validity = $this->request->getGet('parse_sf_name');
        if($parse_validity == "ALL"){
            $validity = "";
        }else{
            $validity = "AND validity = '".$parse_validity."'";
        }

        //parsing hari_pjp variable (ALL, SENIN - SABTU)
        //$parse_hari_pjp = "SENIN";//hapus & ganti dengan data hari pjp dari input user
        $parse_hari_pjp =  $this->request->getGet('parse_hari');
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

        $data['result_trx_summary'] = $this->model->data_summary($validity,$hari_pjp,$dm,$dm1,$idtel);
        $data['result_trx_detail'] = $this->model->data_detail($validity,$hari_pjp,$dm,$dm1,$idtel);

        return view('st_nota_vf_byu_page',$data);
    }

    public function summary_all()
    {
        #$idtel = '1026383114';//hapus & ganti dengan data idtsel dari user yg mengakses modul
        $idtel = $this->request->getGet('parse_idtel');

        //parsing monitoring variable (1D,14D,30D,ALL)
        #$parse_validity = "30D";//hapus & ganti dengan data validity dari input user
        $parse_validity =  $this->request->getGet('parse_type');
        if($parse_validity == "ALL"){
            $validity = "";
        }else{
            $validity = "AND validity = '".$parse_validity."'";
        }

        //parsing hari_pjp variable (ALL, SENIN - SABTU)
        #$parse_hari_pjp = "SENIN";//hapus & ganti dengan data hari dari input user
        $parse_hari_pjp =  $this->request->getGet('parse_hari');
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

        $result = $this->model->data_summary_all($validity,$hari_pjp,$dm,$dm1,$idtel);
        $data['cek_result'] = empty($result) ? 'no result' : 'result available';
        $data['result_trx_summary'] =  $result;

        return view('st_nota_vf_byu_page_v2',$data);
        
    }

    public function summary_by_outlet()
    {
        //$idtel = '1026383114';//hapus & ganti dengan data idtsel dari user yg mengakses modul
        $idtel = $this->request->getGet('parse_idtel');

         //parsing $id_outlet
        //$idOutlet = "3300013682";//hapus & ganti dengan  data id outlet dari input user
        $idOutlet = $this->request->getGet('parse_idoutlet');

        //parsing monitoring variable (1D,14D,30D,ALL)
        #$parse_validity = "30D";//hapus & ganti dengan data validity dari input user
        $parse_validity =  $this->request->getGet('parse_type');
        if($parse_validity == "ALL"){
            $validity = "";
        }else{
            $validity = "AND validity = '".$parse_validity."'";
        }

        //parsing hari_pjp variable (ALL, SENIN - SABTU)
        $parse_hari_pjp = "ALL";//FIX


        $query_update_date_st_digipos = $this->model->get_latest_update_date_st_digipos();
        $dm = date("d",strtotime($query_update_date_st_digipos['update_date_m']));
        $dm1 = date("d",strtotime($query_update_date_st_digipos['update_date_m1']));
        $data['tgl_update'] = $dm." ".showLongBln(date("F",strtotime($query_update_date_st_digipos['update_date_m'])));
        $data['parse_type'] = $parse_validity;
        $data['hari_pjp'] = $parse_hari_pjp;

        $result = $this->model->data_summary_outlet($validity,$dm,$dm1,$idtel,$idOutlet);
        $data['cek_result'] = empty($result) ? 'no result' : 'result available';
        $data['result_trx_summary'] =  $result;

        return view('st_nota_vf_byu_page_v3',$data);
        
    }

     public function summary_by_sf()
    {
        //$idtel = '1026383114';//hapus & ganti dengan data idtsel dari user yg mengakses modul
        $idtel = $this->request->getGet('parse_idtel');

        //parsing $pic variable
        //$pic = "FATIR FATAHILA ILHAM";//hapus & ganti dengan data pic dari input user
        $pic = $this->request->getGet('parse_sf');

        //parsing monitoring variable (1D,14D,30D,ALL)
        //$parse_validity = "30D";//hapus & ganti dengan data validity dari input user
        $parse_validity =  $this->request->getGet('parse_type');
        if($parse_validity == "ALL"){
            $validity = "";
        }else{
            $validity = "AND validity = '".$parse_validity."'";
        }

        //parsing hari_pjp variable (ALL, SENIN - SABTU)
        //$parse_hari_pjp = "ALL";//hapus & ganti dengan data hari dari input user
        $parse_hari_pjp =  $this->request->getGet('parse_hari');
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

        $result = $this->model->data_summary_sf($pic,$validity,$hari_pjp,$dm,$dm1,$idtel);
        $data['cek_result'] = empty($result) ? 'no result' : 'result available';
        $data['result_trx_summary'] =  $result;

        return view('st_nota_vf_byu_page_v3',$data);
        
    }
}