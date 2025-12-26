<?php

namespace App\Models;

use CodeIgniter\Model;

class OmsetTrxModel extends Model
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    function get_latest_update_date_m(){

        $query = "SELECT MAX(update_date) update_date, MAX(periode) periode
                FROM baltengsatudata_balteng.db_profile_outlet_m";
        
        $resultQuery = $this->db->query($query);
        if($resultQuery)return $resultQuery->getRowArray();
    }

     function get_latest_update_date_m1(){

        $query = "SELECT MAX(update_date) update_date, MAX(periode) periode
                FROM baltengsatudata_balteng.db_profile_outlet_m1";
        
        $resultQuery = $this->db->query($query);
        if($resultQuery)return $resultQuery->getRowArray();
    }

    function get_latest_update_date_st_digipos(){

        $query = "SELECT MAX(CASE WHEN grouping_periode = 'M' THEN periode_date END) update_date_m, 
                MAX(CASE WHEN grouping_periode = 'M-1' THEN periode_date END) update_date_m1
                FROM baltengsatudata_balteng.db_st_digipos";
        
        $resultQuery = $this->db->query($query);
        if($resultQuery)return $resultQuery->getRowArray();
    }

    function omset_data_detail_t1($pic,$type,$hari_pjp,$pm,$pm1,$dm,$dm1){
        $pic = $this->db->escape($pic);
        $pm = $this->db->escape($pm);
        $pm1 = $this->db->escape($pm1);

        $query = "SELECT update_date,A.id_digipos,outlet,tap,channel,pic,hari_pjp,
                COUNT(CASE WHEN periode = $pm THEN hari_pjp END) or_trx,
                COUNT(CASE WHEN periode = $pm1 AND trx_$type > 0 THEN trx_$type END) oa_trx_m1,
                IFNULL(ROUND((COUNT(CASE WHEN periode = $pm1 AND trx_$type > 0 THEN trx_$type END)/COUNT(CASE WHEN periode = $pm THEN hari_pjp END))*100,0),0) percent_oa_trx_m1,  
                IFNULL(SUM(CASE WHEN periode = $pm1 THEN trx_$type END),0) trx_m1,
                IFNULL(SUM(CASE WHEN periode = $pm1 THEN rev_$type END),0) rev_m1,
                ROUND((IFNULL(SUM(CASE WHEN periode = $pm1 THEN trx_$type END),0)*103)/100,0) target_trx,
                ROUND((IFNULL(SUM(CASE WHEN periode = $pm1 THEN rev_$type END),0)*103)/100,0) target_rev,
                COUNT(CASE WHEN periode = $pm AND trx_$type > 0 THEN trx_$type END) oa_trx_mtd,
                IFNULL(ROUND((COUNT(CASE WHEN periode = $pm AND trx_$type > 0 THEN trx_$type END)/COUNT(CASE WHEN periode = $pm THEN hari_pjp END))*100,0),0) percent_oa_trx_mtd,
                IFNULL(SUM(CASE WHEN periode = $pm THEN trx_$type END),0) trx_mtd,
                ROUND((IFNULL(SUM(CASE WHEN periode = $pm THEN trx_$type END),0)/ROUND((IFNULL(SUM(CASE WHEN periode = $pm1 THEN trx_$type END),0)*103)/100,0))*100,0) ach_trx,
                IFNULL(SUM(CASE WHEN periode = $pm THEN rev_$type END),0) rev_mtd,
                ROUND((IFNULL(SUM(CASE WHEN periode = $pm THEN rev_$type END),0)/ROUND((IFNULL(SUM(CASE WHEN periode = $pm1 THEN rev_$type END),0)*103)/100,0))*100,0) ach_rev,
                ROUND(((IFNULL(SUM(CASE WHEN periode = $pm THEN trx_$type END),0)/$dm)/(IFNULL(SUM(CASE WHEN periode = $pm1 THEN trx_$type END),0)/$dm1))*100,0) rr_trx,
                ROUND(((IFNULL(SUM(CASE WHEN periode = $pm THEN rev_$type END),0)/$dm)/(IFNULL(SUM(CASE WHEN periode = $pm1 THEN rev_$type END),0)/$dm1))*100,0) rr_rev,
                IFNULL(SUM(CASE WHEN periode = $pm THEN trx_$type END),0) - IFNULL(SUM(CASE WHEN periode = $pm1 THEN trx_$type END),0) gap_trx,
                IFNULL(SUM(CASE WHEN periode = $pm THEN rev_$type END),0) - IFNULL(SUM(CASE WHEN periode = $pm1 THEN rev_$type END),0) gap_rev
                FROM
                (SELECT id_digipos,outlet,tap,channel,pic,hari_pjp
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING' AND $hari_pjp AND pic = $pic)A
                JOIN 
                (SELECT periode,update_date,id_outlet,trx_$type,rev_$type
                FROM baltengsatudata_balteng.`db_profile_outlet_m`
                UNION
                SELECT periode,update_date,id_outlet,trx_$type,rev_$type
                FROM baltengsatudata_balteng.`db_profile_outlet_m1`)B
                ON A.`id_digipos` = B.`id_outlet`
                GROUP BY A.id_digipos,outlet,tap,channel,pic,hari_pjp";

        $resultQuery = $this->db->query($query);
		if($resultQuery){
			return $resultQuery->getResultArray();
		}
    }

    function omset_data_summary_t1($pic,$type,$hari_pjp,$pm,$pm1,$dm,$dm1){
        $pic = $this->db->escape($pic);
        $pm = $this->db->escape($pm);
        $pm1 = $this->db->escape($pm1);

        $query = "SELECT tap,channel,pic,
                COUNT(CASE WHEN periode = $pm THEN hari_pjp END) or_trx,
                COUNT(CASE WHEN periode = $pm1 AND trx_$type > 0 THEN trx_$type END) oa_trx_m1,
                IFNULL(ROUND((COUNT(CASE WHEN periode = $pm1 AND trx_$type > 0 THEN trx_$type END)/COUNT(CASE WHEN periode = $pm THEN hari_pjp END))*100,0),0) percent_oa_trx_m1,  
                IFNULL(SUM(CASE WHEN periode = $pm1 THEN trx_$type END),0) trx_m1,
                IFNULL(SUM(CASE WHEN periode = $pm1 THEN rev_$type END),0) rev_m1,
                ROUND((IFNULL(SUM(CASE WHEN periode = $pm1 THEN trx_$type END),0)*103)/100,0) target_trx,
                ROUND((IFNULL(SUM(CASE WHEN periode = $pm1 THEN rev_$type END),0)*103)/100,0) target_rev,
                COUNT(CASE WHEN periode = $pm AND trx_$type > 0 THEN trx_$type END) oa_trx_mtd,
                IFNULL(ROUND((COUNT(CASE WHEN periode = $pm AND trx_$type > 0 THEN trx_$type END)/COUNT(CASE WHEN periode = $pm THEN hari_pjp END))*100,0),0) percent_oa_trx_mtd,
                IFNULL(SUM(CASE WHEN periode = $pm THEN trx_$type END),0) trx_mtd,
                ROUND((IFNULL(SUM(CASE WHEN periode = $pm THEN trx_$type END),0)/ROUND((IFNULL(SUM(CASE WHEN periode = $pm1 THEN trx_$type END),0)*103)/100,0))*100,0) ach_trx,
                IFNULL(SUM(CASE WHEN periode = $pm THEN rev_$type END),0) rev_mtd,
                ROUND((IFNULL(SUM(CASE WHEN periode = $pm THEN rev_$type END),0)/ROUND((IFNULL(SUM(CASE WHEN periode = $pm1 THEN rev_$type END),0)*103)/100,0))*100,0) ach_rev,
                ROUND(((IFNULL(SUM(CASE WHEN periode = $pm THEN trx_$type END),0)/$dm)/(IFNULL(SUM(CASE WHEN periode = $pm1 THEN trx_$type END),0)/$dm1))*100,0) rr_trx,
                ROUND(((IFNULL(SUM(CASE WHEN periode = $pm THEN rev_$type END),0)/$dm)/(IFNULL(SUM(CASE WHEN periode = $pm1 THEN rev_$type END),0)/$dm1))*100,0) rr_rev,
                IFNULL(SUM(CASE WHEN periode = $pm THEN trx_$type END),0) - IFNULL(SUM(CASE WHEN periode = $pm1 THEN trx_$type END),0) gap_trx,
                IFNULL(SUM(CASE WHEN periode = $pm THEN rev_$type END),0) - IFNULL(SUM(CASE WHEN periode = $pm1 THEN rev_$type END),0) gap_rev
                FROM
                (SELECT id_digipos,outlet,tap,channel,pic,hari_pjp
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING' AND $hari_pjp AND pic = $pic)A
                JOIN 
                (SELECT periode,update_date,id_outlet,trx_$type,rev_$type
                FROM baltengsatudata_balteng.`db_profile_outlet_m`
                UNION
                SELECT periode,update_date,id_outlet,trx_$type,rev_$type
                FROM baltengsatudata_balteng.`db_profile_outlet_m1`)B
                ON A.`id_digipos` = B.`id_outlet`
                GROUP BY tap,channel,pic";

        $resultQuery = $this->db->query($query);
		if($resultQuery){
			return $resultQuery->getResultArray();
		}
    }

    function omset_data_detail_t2($pic,$type,$hari_pjp,$pm,$pm1,$dm,$dm1){
        $pic = $this->db->escape($pic);
        $pm = $this->db->escape($pm);
        $pm1 = $this->db->escape($pm1);

        $query = "SELECT update_date,A.id_digipos,outlet,tap,channel,pic,hari_pjp,
                COUNT(CASE WHEN periode = $pm THEN hari_pjp END) or_trx,
                COUNT(CASE WHEN periode = $pm1 AND trx_$type > 0 THEN trx_$type END) oa_trx_m1,
                IFNULL(ROUND((COUNT(CASE WHEN periode = $pm1 AND trx_$type > 0 THEN trx_$type END)/COUNT(CASE WHEN periode = $pm THEN hari_pjp END))*100,0),0) percent_oa_trx_m1,  
                IFNULL(SUM(CASE WHEN periode = $pm1 THEN trx_$type END),0) trx_m1,
                IFNULL(SUM(CASE WHEN periode = $pm1 THEN rev_$type END),0) rev_m1,
                ROUND((IFNULL(CASE WHEN periode = $pm1 THEN trx_$type END,0)*103)/100,0) target_trx,
                ROUND((IFNULL(CASE WHEN periode = $pm1 THEN rev_$type END,0)*103)/100,0) target_rev,
                COUNT(CASE WHEN periode = $pm AND trx_$type > 0 THEN trx_$type END) oa_trx_mtd,
                IFNULL(ROUND((COUNT(CASE WHEN periode = $pm AND trx_$type > 0 THEN trx_$type END)/COUNT(CASE WHEN periode = $pm THEN hari_pjp END))*100,0),0) percent_oa_trx_mtd,
                IFNULL(SUM(CASE WHEN periode = $pm THEN trx_$type END),0) trx_mtd,
                ROUND((IFNULL(SUM(CASE WHEN periode = $pm THEN trx_$type END),0)/ROUND((IFNULL(SUM(CASE WHEN periode = $pm1 THEN trx_$type END),0)*103)/100,0))*100,0) ach_trx,
                IFNULL(SUM(CASE WHEN periode = $pm THEN rev_$type END),0) rev_mtd,
                ROUND((IFNULL(SUM(CASE WHEN periode = $pm THEN rev_$type END),0)/ROUND((IFNULL(SUM(CASE WHEN periode = $pm1 THEN rev_$type END),0)*103)/100,0))*100,0) ach_rev,
                ROUND(((IFNULL(SUM(CASE WHEN periode = $pm THEN trx_$type END),0)/$dm)/(IFNULL(SUM(CASE WHEN periode = $pm1 THEN trx_$type END),0)/$dm1))*100,0) rr_trx,
                ROUND(((IFNULL(SUM(CASE WHEN periode = $pm THEN rev_$type END),0)/$dm)/(IFNULL(SUM(CASE WHEN periode = $pm1 THEN rev_$type END),0)/$dm1))*100,0) rr_rev,
                IFNULL(SUM(CASE WHEN periode = $pm THEN trx_$type END),0) - IFNULL(SUM(CASE WHEN periode = $pm1 THEN trx_$type END),0) gap_trx,
                IFNULL(SUM(CASE WHEN periode = $pm THEN rev_$type END),0) - IFNULL(SUM(CASE WHEN periode = $pm1 THEN rev_$type END),0) gap_rev,
                IFNULL(SUM(CASE WHEN periode = $pm THEN rev_superseru END),0) rev_superseru_mtd,
                IFNULL(SUM(CASE WHEN periode = $pm THEN rev_hotpromo END),0) rev_hotpromo_mtd,
                IFNULL(SUM(CASE WHEN periode = $pm THEN rev_$type END),0) - IFNULL(SUM(CASE WHEN periode = $pm THEN rev_superseru END),0) - IFNULL(SUM(CASE WHEN periode = $pm THEN rev_hotpromo END),0) rev_other_mtd,
                IFNULL(ROUND((IFNULL(SUM(CASE WHEN periode = $pm THEN rev_superseru END),0)/IFNULL(SUM(CASE WHEN periode = $pm THEN rev_$type END),0))*100,0),0) percent_rev_superseru,
                IFNULL(ROUND((IFNULL(SUM(CASE WHEN periode = $pm THEN rev_hotpromo END),0)/IFNULL(SUM(CASE WHEN periode = $pm THEN rev_$type END),0))*100,0),0) percent_rev_hotpromo,
                IFNULL(ROUND(((IFNULL(SUM(CASE WHEN periode = $pm THEN rev_$type END),0) - IFNULL(SUM(CASE WHEN periode = $pm THEN rev_superseru END),0) - IFNULL(SUM(CASE WHEN periode = $pm THEN rev_hotpromo END),0))/IFNULL(SUM(CASE WHEN periode = $pm THEN rev_$type END),0))*100,0),0) percent_rev_other,
                IFNULL(SUM(CASE WHEN periode = $pm THEN rev_pa END),0) rev_vas_mtd,
                IFNULL(ROUND((IFNULL(SUM(CASE WHEN periode = $pm THEN rev_$type END),0)/IFNULL(SUM(CASE WHEN periode = $pm THEN rev_pa END),0))*100,0),0) percent_rev_cvm
                FROM
                (SELECT id_digipos,outlet,tap,channel,pic,hari_pjp
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING' AND $hari_pjp AND pic = $pic)A
                JOIN 
                (SELECT periode,update_date,id_outlet,trx_$type,rev_$type,rev_superseru,rev_hotpromo,rev_pa
                FROM baltengsatudata_balteng.`db_profile_outlet_m`
                UNION
                SELECT periode,update_date,id_outlet,trx_$type,rev_$type,rev_superseru,rev_hotpromo,rev_pa
                FROM baltengsatudata_balteng.`db_profile_outlet_m1`)B
                ON A.`id_digipos` = B.`id_outlet`
                GROUP BY A.id_digipos,outlet,tap,channel,pic,hari_pjp";

        $resultQuery = $this->db->query($query);
		if($resultQuery){
			return $resultQuery->getResultArray();
		}
    }

    function omset_data_summary_t2($pic,$type,$hari_pjp,$pm,$pm1,$dm,$dm1){
        $pic = $this->db->escape($pic);
        $pm = $this->db->escape($pm);
        $pm1 = $this->db->escape($pm1);

        $query = "SELECT tap,channel,pic,hari_pjp,
                COUNT(CASE WHEN periode = $pm THEN hari_pjp END) or_trx,
                COUNT(CASE WHEN periode = $pm1 AND trx_$type > 0 THEN trx_$type END) oa_trx_m1,
                IFNULL(ROUND((COUNT(CASE WHEN periode = $pm1 AND trx_$type > 0 THEN trx_$type END)/COUNT(CASE WHEN periode = $pm THEN hari_pjp END))*100,0),0) percent_oa_trx_m1,  
                IFNULL(SUM(CASE WHEN periode = $pm1 THEN trx_$type END),0) trx_m1,
                IFNULL(SUM(CASE WHEN periode = $pm1 THEN rev_$type END),0) rev_m1,
                ROUND((IFNULL(SUM(CASE WHEN periode = $pm1 THEN trx_$type END),0)*103)/100,0) target_trx,
                ROUND((IFNULL(SUM(CASE WHEN periode = $pm1 THEN rev_$type END),0)*103)/100,0) target_rev,
                COUNT(CASE WHEN periode = $pm AND trx_$type > 0 THEN trx_$type END) oa_trx_mtd,
                IFNULL(ROUND((COUNT(CASE WHEN periode = $pm AND trx_$type > 0 THEN trx_$type END)/COUNT(CASE WHEN periode = $pm THEN hari_pjp END))*100,0),0) percent_oa_trx_mtd,
                IFNULL(SUM(CASE WHEN periode = $pm THEN trx_$type END),0) trx_mtd,
                ROUND((IFNULL(SUM(CASE WHEN periode = $pm THEN trx_$type END),0)/ROUND((IFNULL(SUM(CASE WHEN periode = $pm1 THEN trx_$type END),0)*103)/100,0))*100,0) ach_trx,
                IFNULL(SUM(CASE WHEN periode = $pm THEN rev_$type END),0) rev_mtd,
                ROUND((IFNULL(SUM(CASE WHEN periode = $pm THEN rev_$type END),0)/ROUND((IFNULL(SUM(CASE WHEN periode = $pm1 THEN rev_$type END),0)*103)/100,0))*100,0) ach_rev,
                ROUND(((IFNULL(SUM(CASE WHEN periode = $pm THEN trx_$type END),0)/$dm)/(IFNULL(SUM(CASE WHEN periode = $pm1 THEN trx_$type END),0)/$dm1))*100,0) rr_trx,
                ROUND(((IFNULL(SUM(CASE WHEN periode = $pm THEN rev_$type END),0)/$dm)/(IFNULL(SUM(CASE WHEN periode = $pm1 THEN rev_$type END),0)/$dm1))*100,0) rr_rev,
                IFNULL(SUM(CASE WHEN periode = $pm THEN trx_$type END),0) - IFNULL(SUM(CASE WHEN periode = $pm1 THEN trx_$type END),0) gap_trx,
                IFNULL(SUM(CASE WHEN periode = $pm THEN rev_$type END),0) - IFNULL(SUM(CASE WHEN periode = $pm1 THEN rev_$type END),0) gap_rev,
                IFNULL(SUM(CASE WHEN periode = $pm THEN rev_superseru END),0) rev_superseru_mtd,
                IFNULL(SUM(CASE WHEN periode = $pm THEN rev_hotpromo END),0) rev_hotpromo_mtd,
                IFNULL(SUM(CASE WHEN periode = $pm THEN rev_$type END),0) - IFNULL(SUM(CASE WHEN periode = $pm THEN rev_superseru END),0) - IFNULL(SUM(CASE WHEN periode = $pm THEN rev_hotpromo END),0) rev_other_mtd,
                ROUND((IFNULL(SUM(CASE WHEN periode = $pm THEN rev_superseru END),0)/IFNULL(SUM(CASE WHEN periode = $pm THEN rev_$type END),0))*100,0) percent_rev_superseru,
                ROUND((IFNULL(SUM(CASE WHEN periode = $pm THEN rev_hotpromo END),0)/IFNULL(SUM(CASE WHEN periode = $pm THEN rev_$type END),0))*100,0) percent_rev_hotpromo,
                IFNULL(ROUND(((IFNULL(SUM(CASE WHEN periode = $pm THEN rev_$type END),0) - IFNULL(SUM(CASE WHEN periode = $pm THEN rev_superseru END),0) - IFNULL(SUM(CASE WHEN periode = $pm THEN rev_hotpromo END),0))/IFNULL(SUM(CASE WHEN periode = $pm THEN rev_$type END),0))*100,0),0) percent_rev_other,
                IFNULL(SUM(CASE WHEN periode = $pm THEN rev_pa END),0) rev_vas_mtd,
                ROUND((IFNULL(SUM(CASE WHEN periode = $pm THEN rev_$type END),0)/IFNULL(SUM(CASE WHEN periode = $pm THEN rev_pa END),0))*100,0) percent_rev_cvm
                FROM
                (SELECT id_digipos,outlet,tap,channel,pic,hari_pjp
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING' AND $hari_pjp AND pic = $pic)A
                JOIN 
                (SELECT periode,update_date,id_outlet,trx_$type,rev_$type,rev_superseru,rev_hotpromo,rev_pa
                FROM baltengsatudata_balteng.`db_profile_outlet_m`
                UNION
                SELECT periode,update_date,id_outlet,trx_$type,rev_$type,rev_superseru,rev_hotpromo,rev_pa
                FROM baltengsatudata_balteng.`db_profile_outlet_m1`)B
                ON A.`id_digipos` = B.`id_outlet`
                GROUP BY tap,channel,pic,hari_pjp";

        $resultQuery = $this->db->query($query);
		if($resultQuery){
			return $resultQuery->getResultArray();
		}
    }

    function omset_data_detail_t3($pic,$hari_pjp,$dm,$dm1,$jenis_produk){
        $pic = $this->db->escape($pic);

        $query = "SELECT periode_date,id_digipos,outlet,tap,channel,pic,hari_pjp,
                COUNT(CASE WHEN grouping_periode = 'M' THEN hari_pjp END) or_trx,
                COUNT(CASE WHEN grouping_periode = 'M-1' AND qty > 0 THEN qty END) oa_trx_m1,
                IFNULL(ROUND((COUNT(CASE WHEN grouping_periode = 'M-1' AND qty > 0 THEN qty END)/COUNT(CASE WHEN grouping_periode = 'M' THEN hari_pjp END))*100,0),0) percent_oa_trx_m1,  
                IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN qty END),0) trx_m1,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN rev END),0) rev_m1,
                ROUND((IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN qty END),0)*103)/100,0) target_trx,
                ROUND((IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN rev END),0)*103)/100,0) target_rev,
                COUNT(CASE WHEN grouping_periode = 'M' AND qty > 0 THEN qty END) oa_trx_mtd,
                IFNULL(ROUND((COUNT(CASE WHEN grouping_periode = 'M' AND qty > 0 THEN qty END)/COUNT(CASE WHEN grouping_periode = 'M' THEN hari_pjp END))*100,0),0) percent_oa_trx_mtd,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0) trx_mtd,
                ROUND((IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0)/ROUND((IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN qty END),0)*103)/100,0))*100,0) ach_trx,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0) rev_mtd,
                ROUND((IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0)/ROUND((IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN rev END),0)*103)/100,0))*100,0) ach_rev,
                ROUND(((IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0)/$dm)/(IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN qty END),0)/$dm1))*100,0) rr_trx,
                ROUND(((IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0)/$dm)/(IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN rev END),0)/$dm1))*100,0) rr_rev,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0) - IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN qty END),0) gap_trx,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0) - IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN rev END),0) gap_rev
                FROM
                (SELECT A.id_digipos,outlet,tap,channel,pic,hari_pjp,periode_date,grouping_periode,jenis_produk,nama_produk,tipe_produk
                validity,qty,rev
                FROM db_outlet A
                JOIN  baltengsatudata_balteng.`db_st_digipos` B
                ON A.`id_digipos` = B.`id_outlet`
                WHERE UPPER(channel) = 'SF CHANNELING' AND $hari_pjp AND pic = $pic AND $jenis_produk) source
                GROUP BY id_digipos,outlet,hari_pjp,pic";

        $resultQuery = $this->db->query($query);
		if($resultQuery){
			return $resultQuery->getResultArray();
		}
    }

    function omset_data_summary_t3($pic,$hari_pjp,$dm,$dm1,$jenis_produk){
        $pic = $this->db->escape($pic);

        $query = "SELECT tap,channel,pic,hari_pjp,
                COUNT(CASE WHEN grouping_periode = 'M' THEN hari_pjp END) or_trx,
                COUNT(CASE WHEN grouping_periode = 'M-1' AND qty > 0 THEN qty END) oa_trx_m1,
                IFNULL(ROUND((COUNT(CASE WHEN grouping_periode = 'M-1' AND qty > 0 THEN qty END)/COUNT(CASE WHEN grouping_periode = 'M' THEN hari_pjp END))*100,0),0) percent_oa_trx_m1,  
                IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN qty END),0) trx_m1,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN rev END),0) rev_m1,
                ROUND((IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN qty END),0)*103)/100,0) target_trx,
                ROUND((IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN rev END),0)*103)/100,0) target_rev,
                COUNT(CASE WHEN grouping_periode = 'M' AND qty > 0 THEN qty END) oa_trx_mtd,
                IFNULL(ROUND((COUNT(CASE WHEN grouping_periode = 'M' AND qty > 0 THEN qty END)/COUNT(CASE WHEN grouping_periode = 'M' THEN hari_pjp END))*100,0),0) percent_oa_trx_mtd,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0) trx_mtd,
                ROUND((IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0)/ROUND((IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN qty END),0)*103)/100,0))*100,0) ach_trx,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0) rev_mtd,
                ROUND((IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0)/ROUND((IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN rev END),0)*103)/100,0))*100,0) ach_rev,
                ROUND(((IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0)/$dm)/(IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN qty END),0)/$dm1))*100,0) rr_trx,
                ROUND(((IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0)/$dm)/(IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN rev END),0)/$dm1))*100,0) rr_rev,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0) - IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN qty END),0) gap_trx,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0) - IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN rev END),0) gap_rev
                FROM
                (SELECT A.id_digipos,outlet,tap,channel,pic,hari_pjp,periode_date,grouping_periode,jenis_produk,nama_produk,tipe_produk
                validity,qty,rev
                FROM db_outlet A
                JOIN  baltengsatudata_balteng.`db_st_digipos` B
                ON A.`id_digipos` = B.`id_outlet`
                WHERE UPPER(channel) = 'SF CHANNELING' AND $hari_pjp AND pic = $pic AND $jenis_produk) source
                GROUP BY tap,channel,pic,hari_pjp";

        $resultQuery = $this->db->query($query);
		if($resultQuery){
			return $resultQuery->getResultArray();
		}
    }

    function omset_data_detail_t5($pic,$hari_pjp,$pm,$pm1,$dm,$dm1){
        $pic = $this->db->escape($pic);
        $pm = $this->db->escape($pm);
        $pm1 = $this->db->escape($pm1);

        $query = "SELECT update_date,A.id_digipos,outlet,tap,channel,pic,hari_pjp,
                COUNT(CASE WHEN periode = $pm THEN hari_pjp END) or_trx,
                COUNT(CASE WHEN periode = $pm1 AND trx_dg > 0 THEN trx_dg END) oa_trx_m1,
                IFNULL(ROUND((COUNT(CASE WHEN periode = $pm1 AND trx_dg > 0 THEN trx_dg END)/COUNT(CASE WHEN periode = $pm THEN hari_pjp END))*100,0),0) percent_oa_trx_m1,  
                IFNULL(SUM(CASE WHEN periode = $pm1 THEN trx_dg END),0) trx_m1,
                IFNULL(SUM(CASE WHEN periode = $pm1 THEN rev_dg END),0) rev_m1,
                ROUND((IFNULL(SUM(CASE WHEN periode = $pm1 THEN trx_dg END),0)*103)/100,0) target_trx,
                ROUND((IFNULL(SUM(CASE WHEN periode = $pm1 THEN rev_dg END),0)*103)/100,0) target_rev,
                COUNT(CASE WHEN periode = $pm AND trx_dg > 0 THEN trx_dg END) oa_trx_mtd,
                IFNULL(ROUND((COUNT(CASE WHEN periode = $pm AND trx_dg > 0 THEN trx_dg END)/COUNT(CASE WHEN periode = $pm THEN hari_pjp END))*100,0),0) percent_oa_trx_mtd,
                IFNULL(SUM(CASE WHEN periode = $pm THEN trx_dg END),0) trx_mtd,
                ROUND((IFNULL(SUM(CASE WHEN periode = $pm THEN trx_dg END),0)/ROUND((IFNULL(SUM(CASE WHEN periode = $pm1 THEN trx_dg END),0)*103)/100,0))*100,0) ach_trx,
                IFNULL(SUM(CASE WHEN periode = $pm THEN rev_dg END),0) rev_mtd,
                ROUND((IFNULL(SUM(CASE WHEN periode = $pm THEN rev_dg END),0)/ROUND((IFNULL(SUM(CASE WHEN periode = $pm1 THEN rev_dg END),0)*103)/100,0))*100,0) ach_rev,
                ROUND(((IFNULL(SUM(CASE WHEN periode = $pm THEN trx_dg END),0)/$dm)/(IFNULL(SUM(CASE WHEN periode = $pm1 THEN trx_dg END),0)/$dm1))*100,0) rr_trx,
                ROUND(((IFNULL(SUM(CASE WHEN periode = $pm THEN rev_dg END),0)/$dm)/(IFNULL(SUM(CASE WHEN periode = $pm1 THEN rev_dg END),0)/$dm1))*100,0) rr_rev,
                IFNULL(SUM(CASE WHEN periode = $pm THEN trx_dg END),0) - IFNULL(SUM(CASE WHEN periode = $pm1 THEN trx_dg END),0) gap_trx,
                IFNULL(SUM(CASE WHEN periode = $pm THEN rev_dg END),0) - IFNULL(SUM(CASE WHEN periode = $pm1 THEN rev_dg END),0) gap_rev
                FROM
                (SELECT id_digipos,outlet,tap,channel,pic,hari_pjp
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING' AND $hari_pjp AND pic = $pic)A
                JOIN 
                (SELECT periode,update_date,id_outlet,(trx_recharge + trx_pa) trx_dg, (rev_recharge + rev_pa) rev_dg
                FROM baltengsatudata_balteng.`db_profile_outlet_m`
                UNION
                SELECT periode,update_date,id_outlet,(trx_recharge + trx_pa) trx_dg, (rev_recharge + rev_pa) rev_dg
                FROM baltengsatudata_balteng.`db_profile_outlet_m1`)B
                ON A.`id_digipos` = B.`id_outlet`
                GROUP BY A.id_digipos,outlet,tap,channel,pic,hari_pjp";

        $resultQuery = $this->db->query($query);
		if($resultQuery){
			return $resultQuery->getResultArray();
		}
    }

    function omset_data_summary_t5($pic,$hari_pjp,$pm,$pm1,$dm,$dm1){
        $pic = $this->db->escape($pic);
        $pm = $this->db->escape($pm);
        $pm1 = $this->db->escape($pm1);

        $query = "SELECT tap,channel,pic,
                COUNT(CASE WHEN periode = $pm THEN hari_pjp END) or_trx,
                COUNT(CASE WHEN periode = $pm1 AND trx_dg > 0 THEN trx_dg END) oa_trx_m1,
                IFNULL(ROUND((COUNT(CASE WHEN periode = $pm1 AND trx_dg > 0 THEN trx_dg END)/COUNT(CASE WHEN periode = $pm THEN hari_pjp END))*100,0),0) percent_oa_trx_m1,  
                IFNULL(SUM(CASE WHEN periode = $pm1 THEN trx_dg END),0) trx_m1,
                IFNULL(SUM(CASE WHEN periode = $pm1 THEN rev_dg END),0) rev_m1,
                ROUND((IFNULL(SUM(CASE WHEN periode = $pm1 THEN trx_dg END),0)*103)/100,0) target_trx,
                ROUND((IFNULL(SUM(CASE WHEN periode = $pm1 THEN rev_dg END),0)*103)/100,0) target_rev,
                COUNT(CASE WHEN periode = $pm AND trx_dg > 0 THEN trx_dg END) oa_trx_mtd,
                IFNULL(ROUND((COUNT(CASE WHEN periode = $pm AND trx_dg > 0 THEN trx_dg END)/COUNT(CASE WHEN periode = $pm THEN hari_pjp END))*100,0),0) percent_oa_trx_mtd,
                IFNULL(SUM(CASE WHEN periode = $pm THEN trx_dg END),0) trx_mtd,
                ROUND((IFNULL(SUM(CASE WHEN periode = $pm THEN trx_dg END),0)/ROUND((IFNULL(SUM(CASE WHEN periode = $pm1 THEN trx_dg END),0)*103)/100,0))*100,0) ach_trx,
                IFNULL(SUM(CASE WHEN periode = $pm THEN rev_dg END),0) rev_mtd,
                ROUND((IFNULL(SUM(CASE WHEN periode = $pm THEN rev_dg END),0)/ROUND((IFNULL(SUM(CASE WHEN periode = $pm1 THEN rev_dg END),0)*103)/100,0))*100,0) ach_rev,
                ROUND(((IFNULL(SUM(CASE WHEN periode = $pm THEN trx_dg END),0)/$dm)/(IFNULL(SUM(CASE WHEN periode = $pm1 THEN trx_dg END),0)/$dm1))*100,0) rr_trx,
                ROUND(((IFNULL(SUM(CASE WHEN periode = $pm THEN rev_dg END),0)/$dm)/(IFNULL(SUM(CASE WHEN periode = $pm1 THEN rev_dg END),0)/$dm1))*100,0) rr_rev,
                IFNULL(SUM(CASE WHEN periode = $pm THEN trx_dg END),0) - IFNULL(SUM(CASE WHEN periode = $pm1 THEN trx_dg END),0) gap_trx,
                IFNULL(SUM(CASE WHEN periode = $pm THEN rev_dg END),0) - IFNULL(SUM(CASE WHEN periode = $pm1 THEN rev_dg END),0) gap_rev
                FROM
                (SELECT id_digipos,outlet,tap,channel,pic,hari_pjp
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING' AND $hari_pjp AND pic = $pic)A
                JOIN 
                (SELECT periode,update_date,id_outlet,(trx_recharge + trx_pa) trx_dg, (rev_recharge + rev_pa) rev_dg
                FROM baltengsatudata_balteng.`db_profile_outlet_m`
                UNION
                SELECT periode,update_date,id_outlet,(trx_recharge + trx_pa) trx_dg, (rev_recharge + rev_pa) rev_dg
                FROM baltengsatudata_balteng.`db_profile_outlet_m1`)B
                ON A.`id_digipos` = B.`id_outlet`
                GROUP BY tap,channel,pic";

        $resultQuery = $this->db->query($query);
		if($resultQuery){
			return $resultQuery->getResultArray();
		}
    }
}
?>