<?php

namespace App\Controllers;

use App\Models\StNotaSaModel;
helper(['custom_helper']);

class St_nota_sa extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new StNotaSaModel();
    }

    public function index()
    {
        //$idtel = '1026383114';//hapus & ganti dengan data idtsel dari user yg mengakses modul
        $idtel = $this->request->getGet('parse_idtel');

        //parsing $pic variable
        //$pic = "FATIR FATAHILA ILHAM";//hapus & ganti dengan data pic dari input user
        $pic = $this->request->getGet('parse_sf_name');

        //parsing tipe produk variable (SIMPATI,ESIM,BYU,ALL DENO)
        $tipe_produk = "ALL DENO";
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
        $parse_hari_pjp = 'ALL';
        //$parse_hari_pjp =  $this->request->getGet('parse_hari');
        if($parse_hari_pjp == "ALL"){
            $hari_pjp = "UPPER(hari_pjp) IN ('SENIN','SELASA','RABU','KAMIS','JUMAT','SABTU')";
        }else{
            $hari_pjp = "UPPER(hari_pjp) = '".$parse_hari_pjp."'";
        }

        $query_update_date_st_digipos = $this->model->get_latest_update_date_st_nota();
        $dm = date("d",strtotime($query_update_date_st_digipos['update_date_m']));
        $dm1 = date("d",strtotime($query_update_date_st_digipos['update_date_m1']));
        $data['tgl_update'] = $dm." ".showLongBln(date("F",strtotime($query_update_date_st_digipos['update_date_m'])));
        $data['parse_type'] = $tipe_produk;
        $data['hari_pjp'] = $parse_hari_pjp;

        $data['result_trx_summary'] = $this->model->data_summary($nama_produk,$hari_pjp,$dm,$dm1,$idtel);
        $data['result_trx_detail'] = $this->model->data_detail($nama_produk,$hari_pjp,$dm,$dm1,$idtel);

        return view('st_nota_sa_page',$data);
        
    }

    public function summary_all()
    {
        //$idtel = '1026383114';//hapus & ganti dengan data idtsel dari user yg mengakses modul
        $idtel = $this->request->getGet('parse_idtel');

        //parsing tipe produk variable (SIMPATI,ESIM,BYU,ALL DENO)
        #$tipe_produk = "SIMPATI";//hapus & ganti dengan data tipe report dari input user
        $tipe_produk = $this->request->getGet('parse_type');
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
        $parse_hari_pjp = "ALL";//hapus & ganti dengan data hari dari input user
        //$parse_hari_pjp =  $this->request->getGet('parse_hari');
        if($parse_hari_pjp == "ALL"){
            $hari_pjp = "UPPER(hari_pjp) IN ('SENIN','SELASA','RABU','KAMIS','JUMAT','SABTU')";
        }else{
            $hari_pjp = "UPPER(hari_pjp) = '".$parse_hari_pjp."'";
        }

        $query_update_date_st_digipos = $this->model->get_latest_update_date_st_nota();
        $dm = date("d",strtotime($query_update_date_st_digipos['update_date_m']));
        $dm1 = date("d",strtotime($query_update_date_st_digipos['update_date_m1']));
        $data['tgl_update'] = $dm." ".showLongBln(date("F",strtotime($query_update_date_st_digipos['update_date_m'])));
        $data['parse_type'] = $tipe_produk;
        $data['hari_pjp'] = $parse_hari_pjp;

        $result = $this->model->data_summary_all($nama_produk,$hari_pjp,$dm,$dm1,$idtel);
        $data['cek_result'] = empty($result) ? 'no result' : 'result available';
        $data['result_trx_summary'] =  $result;
        
        return view('st_nota_sa_page_v2',$data);
        
    }

    public function summary_by_outlet()
    {
        //$idtel = '1026383114';//hapus & ganti dengan data idtsel dari user yg mengakses modul
        $idtel = $this->request->getGet('parse_idtel');

        //parsing $id_outlet
        //$idOutlet = "3300013682";//hapus & ganti dengan  data id outlet dari input user
        $idOutlet = $this->request->getGet('parse_idoutlet');

        //parsing tipe produk variable (SIMPATI,ESIM,BYU,ALL DENO)
        #$tipe_produk = "ALL DENO";//hapus & ganti dengan data tipe report dari input user
        $tipe_produk = $this->request->getGet('parse_type');
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
        $parse_hari_pjp = "ALL";//FIX
        
        $parse_hari_pjp =  $this->request->getGet('parse_hari');
        $query_update_date_st_digipos = $this->model->get_latest_update_date_st_nota();
        $dm = date("d",strtotime($query_update_date_st_digipos['update_date_m']));
        $dm1 = date("d",strtotime($query_update_date_st_digipos['update_date_m1']));
        $data['tgl_update'] = $dm." ".showLongBln(date("F",strtotime($query_update_date_st_digipos['update_date_m'])));
        $data['parse_type'] = $tipe_produk;
        $data['hari_pjp'] = $parse_hari_pjp;

        $result = $this->model->data_summary_by_outlet($nama_produk,$dm,$dm1,$idtel,$idOutlet);
        $data['cek_result'] = empty($result) ? 'no result' : 'result available';
        $data['result_trx_summary'] =  $result;
        return view('st_nota_sa_page_v3',$data);
        
    }
    
    public function summary_by_sf()
    {
        //$idtel = '8025950853';//hapus & ganti dengan data idtsel dari user yg mengakses modul
        $idtel = $this->request->getGet('parse_idtel');

        //parsing $pic variable
        //$pic = "FATIR FATAHILA ILHAM";//hapus & ganti dengan data pic dari input user
        $pic = $this->request->getGet('parse_sf');

        //parsing tipe produk variable (SIMPATI,ESIM,BYU,ALL DENO)
        //$tipe_produk = "ALL DENO";//hapus & ganti dengan data tipe report dari input user
        $tipe_produk = $this->request->getGet('parse_tipe_produk');
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
        //$parse_hari_pjp = "SENIN";//hapus & ganti dengan data hari dari input user
        $parse_hari_pjp =  $this->request->getGet('parse_hari');
        if($parse_hari_pjp == "ALL"){
            $hari_pjp = "UPPER(hari_pjp) IN ('SENIN','SELASA','RABU','KAMIS','JUMAT','SABTU')";
        }else{
            $hari_pjp = "UPPER(hari_pjp) = '".$parse_hari_pjp."'";
        }

        $query_update_date_st_digipos = $this->model->get_latest_update_date_st_nota();
        $dm = date("d",strtotime($query_update_date_st_digipos['update_date_m']));
        $dm1 = date("d",strtotime($query_update_date_st_digipos['update_date_m1']));
        $data['tgl_update'] = $dm." ".showLongBln(date("F",strtotime($query_update_date_st_digipos['update_date_m'])));
        $data['parse_type'] = $tipe_produk;
        $data['hari_pjp'] = $parse_hari_pjp;


        $result = $this->model->data_summary_by_sf($pic,$nama_produk,$hari_pjp,$dm,$dm1,$idtel);
        $data['cek_result'] = empty($result) ? 'no result' : 'result available';
        $data['result_trx_summary'] =  $result;
        return view('st_nota_sa_page_v3',$data);
        
    }
}