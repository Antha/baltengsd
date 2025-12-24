<?php

namespace App\Controllers;

class St_nota_vf_byu extends BaseController
{
    public function index()
    {
        //parsing $pic variable
        $pic = "FATIR FATAHILA ILHAM";
        //parsing monitoring variable (1D,14D,30D,ALL)
        $monitoring = "ALL";
        //parsing hari_pjp variable (ALL, SENIN - SABTU)
        $hari_pjp = "SENIN";

        //$query_omset_trx_all = $model->get_omset_trx_all_sf($pic,$periode,$hari_pjp);
        $data['monitoring_type'] = $monitoring;

        return view('st_nota_vf_byu_page',$data);
        
    }
}
