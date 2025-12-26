<?php

namespace App\Controllers;

class St_nota_sa extends BaseController
{
    public function index()
    {
        //parsing $pic variable
        $pic = "FATIR FATAHILA ILHAM";
        //parsing monitoring variable (SIMPATI,ESIM,ALL)
        $monitoring = "ALL";
        //parsing hari_pjp variable (ALL, SENIN - SABTU)
        $hari_pjp = "SENIN";

        //$query_omset_trx_all = $model->get_omset_trx_all_sf($pic,$periode,$hari_pjp);
        $data['monitoring_type'] = $monitoring;

        return view('st_nota_sa_page',$data);
        
    }
}
