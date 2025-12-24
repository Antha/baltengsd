<?php

namespace App\Controllers;

use App\Models\OmsetTrxModel;

class Omset_trx extends BaseController
{
    public function index()
    {
        $model = new OmsetTrxModel();
        //parsing $pic variable
        $pic = "FATIR FATAHILA ILHAM";
        //parsing monitoring variable (ALL,VAS,REG,CVM,STS)
        $monitoring = "CVM";
        //parsing hari_pjp variable (ALL, SENIN - SABTU)
        $hari_pjp = "SENIN";

        //$query_omset_trx_all = $model->get_omset_trx_all_sf($pic,$periode,$hari_pjp);
        
        if($monitoring == 'CVM'){
            return view('omset_trx_page_cvm');
        }elseif($monitoring == 'STS'){
            return view('omset_trx_page_sts');
        }else{
            return view('omset_trx_page');
        }
    }
}
