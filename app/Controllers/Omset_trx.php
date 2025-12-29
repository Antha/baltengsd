<?php

namespace App\Controllers;

use App\Models\OmsetTrxModel;
helper(['custom_helper']);

class Omset_trx extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new OmsetTrxModel();
    }

    function fc_get_update_m(){
        //ambil query untuk tanggal update data m now
        $query_update_date_m = $this->model->get_latest_update_date_m();
        $update_date_m = $query_update_date_m['update_date'];
        //ambil periode m
        $pm = $query_update_date_m['periode'];
        //pisahkan string tgl periode m
        $exp_dm = explode("-",$update_date_m);
        $dm = $exp_dm[2];

        return ['pm' => $pm, 'dm' => $dm, 'update_date_m' => $update_date_m];
    }

    function fc_get_update_m1(){
        //ambil query untuk tanggal update data m1
        $query_update_date_m1 = $this->model->get_latest_update_date_m1();
        $update_date_m1 = $query_update_date_m1['update_date'];
        //ambil periode m1
        $pm1 = $query_update_date_m1['periode'];
        //pisahkan string tgl periode m
        $exp_dm1 = explode("-",$update_date_m1);
        $dm1 = $exp_dm1[2];

        return ['pm1' => $pm1, 'dm1' => $dm1, 'update_date_m1' => $update_date_m1];
    }
    
    public function index()
    {
        //parsing $pic variable
        $pic = "IGST NYOMAN PUTRA CHANDRA BUDI";
        //parsing monitoring variable (ALL,VAS,RECHARGE,CVM,ST VF,ST SA,ST ALL,DG)
        $parse_type = "RECHARGE";
        if($parse_type == "VAS"){
            $type = "PA";
        }else{
            $type =  $parse_type;
        }

        //parsing hari_pjp variable (ALL, SENIN - SABTU)
        $parse_hari_pjp = "SELASA";
        if($parse_hari_pjp == "ALL"){
            $hari_pjp = "UPPER(hari_pjp) IN ('SENIN','SELASA','RABU','KAMIS','JUMAT','SABTU')";
        }else{
            $hari_pjp = "UPPER(hari_pjp) = '".$parse_hari_pjp."'";
        }
        
        //panggil fungsi get update date m
        $fc_get_update_m = $this->fc_get_update_m();
        $update_date_m = $fc_get_update_m['update_date_m'];
        $pm = $fc_get_update_m['pm'];
        $dm = $fc_get_update_m['dm'];
        
        $fc_get_update_m1 = $this->fc_get_update_m1();
        $pm1 = $fc_get_update_m1['pm1'];
        $dm1 = $fc_get_update_m1['dm1'];

        //parsing data untuk ditampilkan di view
        $data['tgl_update'] = $dm." ".showLongBln(date("F",strtotime($update_date_m)));
        $data['hari_pjp'] = $parse_hari_pjp;
        $data['parse_type'] = $parse_type; 

        if($type == 'ST VF' || $type == 'ST SA' || $type == 'ST ALL'){
            $query_update_date_st_digipos = $this->model->get_latest_update_date_st_digipos();
            $dm_dg = date("d",strtotime($query_update_date_st_digipos['update_date_m']));
            $dm1_dg = date("d",strtotime($query_update_date_st_digipos['update_date_m1']));
            $data['tgl_update_dg'] = $dm_dg." ".showLongBln(date("F",strtotime($query_update_date_st_digipos['update_date_m'])));
            
            if($type == 'ST SA'){
                $jenis_produk = "jenis_produk = 'SMART AKUISISI'";
            }elseif($type == 'ST VF'){
                $jenis_produk = "jenis_produk = 'VOUCHER FISIK'";
            }else{
                $jenis_produk = "jenis_produk IN('SMART AKUISISI','VOUCHER FISIK')";
            }

            $data['query_omset_trx_detail'] = $this->model->omset_data_detail_t3($pic,$hari_pjp,$dm_dg,$dm1_dg,$jenis_produk);
            $data['query_omset_trx_summary'] = $this->model->omset_data_summary_t3($pic,$hari_pjp,$dm_dg,$dm1_dg,$jenis_produk);
            return view('omset_trx_page_sts',$data);
        }elseif($type == 'CVM'){
            $data['query_omset_trx_detail'] = $this->model->omset_data_detail_t2($pic,strtolower($type),$hari_pjp,$pm,$pm1,$dm,$dm1);
            $data['query_omset_trx_summary'] = $this->model->omset_data_summary_t2($pic,strtolower($type),$hari_pjp,$pm,$pm1,$dm,$dm1);
            return view('omset_trx_page_cvm',$data);
        }elseif($type == 'DG'){
            //tampilkan hasil query di view
            $data['query_omset_trx_detail'] = $this->model->omset_data_detail_t5($pic,$hari_pjp,$pm,$pm1,$dm,$dm1);
            $data['query_omset_trx_summary'] = $this->model->omset_data_summary_t5($pic,$hari_pjp,$pm,$pm1,$dm,$dm1);
            return view('omset_trx_page',$data);
        }else{
            //tampilkan hasil query di view
            $data['query_omset_trx_detail'] = $this->model->omset_data_detail_t1($pic,strtolower($type),$hari_pjp,$pm,$pm1,$dm,$dm1);
            $data['query_omset_trx_summary'] = $this->model->omset_data_summary_t1($pic,strtolower($type),$hari_pjp,$pm,$pm1,$dm,$dm1);
            return view('omset_trx_page',$data);
        }
    }
}
