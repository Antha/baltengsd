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
        $idtel = $this->request->getGet('parse_idtel');
        //parsing $pic variable
        $pic = "FATIR FATAHILA ILHAM";
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
        $data['parse_type'] = $tipe_produk;
        $data['hari_pjp'] = $parse_hari_pjp;

        $data['result_trx_summary'] = $this->model->data_summary($pic,$nama_produk,$hari_pjp,$dm,$dm1,$idtel);
        $data['result_trx_detail'] = $this->model->data_detail($pic,$nama_produk,$hari_pjp,$dm,$dm1,$idtel);

        return view('st_nota_sa_page',$data);
        
    }
}
