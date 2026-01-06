<?php

namespace App\Models;

use CodeIgniter\Model;

class StNotaVfSimModel extends Model
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    function get_latest_update_date_st_digipos(){

        $query = "SELECT MAX(CASE WHEN grouping_periode = 'M' THEN periode_date END) update_date_m, 
                MAX(CASE WHEN grouping_periode = 'M-1' THEN periode_date END) update_date_m1
                FROM db_st_digipos";
        
        $resultQuery = $this->db->query($query);
        if($resultQuery)return $resultQuery->getRowArray();
    }

    function data_detail($pic,$validity,$hari_pjp,$dm,$dm1){
        $pic = $this->db->escape($pic);

        $query = "SELECT id_digipos,outlet,tap,channel,pic,hari_pjp,
                COUNT(CASE WHEN grouping_periode = 'M' THEN hari_pjp END) or_trx,
                COUNT(CASE WHEN grouping_periode = 'M-1' AND qty > 0 THEN qty END) oa_trx_m1,
                IFNULL(ROUND((COUNT(CASE WHEN grouping_periode = 'M-1' AND qty > 0 THEN qty END)/COUNT(CASE WHEN grouping_periode = 'M-1' THEN hari_pjp END))*100,0),0) percent_oa_trx_m1,  
                IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN qty END),0) trx_m1,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN rev END),0) rev_m1,
                IFNULL(COUNT(CASE WHEN grouping_periode = 'M' AND qty_target > 0 THEN qty_target END),0) oa_trx_target,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty_target END),0) target_trx,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev_target END),0) target_rev,
                COUNT(CASE WHEN grouping_periode = 'M' AND qty > 0 THEN qty END) oa_trx_mtd,
                IFNULL(ROUND((COUNT(CASE WHEN grouping_periode = 'M' AND qty > 0 THEN qty END)/COUNT(CASE WHEN grouping_periode = 'M' THEN hari_pjp END))*100,0),0) percent_oa_trx_mtd,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0) trx_mtd,
                IFNULL(ROUND((IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0)/IFNULL(qty_target,0))*100,0),0) ach_trx,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0) rev_mtd,
                IFNULL(ROUND((IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0)/IFNULL(rev_target,0))*100,0),0) ach_rev,
                IFNULL(ROUND(((IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0)/$dm)/(IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN qty END),0)/$dm1))*100,0),0) rr_trx,
                IFNULL(ROUND(((IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0)/$dm)/(IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN rev END),0)/$dm1))*100,0),0) rr_rev,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0) - IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty_target END),0) gap_trx,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0) - IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev_target END),0) gap_rev
                FROM
                (SELECT id_digipos,outlet,tap,channel,pic,hari_pjp
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING' AND $hari_pjp AND pic = $pic)A
                JOIN 
                (SELECT id_outlet, grouping_periode, SUM(qty) qty, SUM(rev) rev
                FROM db_st_nota
                WHERE jenis_produk = 'VOUCHER FISIK' AND nama_produk = 'SIMPATI' $validity
                GROUP BY id_outlet, grouping_periode)B
                ON A.id_digipos = B.id_outlet
                JOIN
                (SELECT id_outlet, IFNULL(SUM(qty),0) qty_target, IFNULL(SUM(rev),0) rev_target, COUNT(CASE WHEN qty > 0 THEN qty END) oa_qty_target
                FROM db_sales_plan
                WHERE jenis_produk = 'VOUCHER FISIK' AND nama_produk = 'SIMPATI' $validity
                GROUP BY id_outlet)C
                ON  B.id_outlet = C.id_outlet
                GROUP BY id_digipos,outlet,tap,channel,pic,hari_pjp";

        $resultQuery = $this->db->query($query);
		if($resultQuery){
			return $resultQuery->getResultArray();
		}
    }

    function data_summary($pic,$validity,$hari_pjp,$dm,$dm1){
        $pic = $this->db->escape($pic);

        $query = "SELECT tap,channel,pic,hari_pjp,
                COUNT(CASE WHEN grouping_periode = 'M' THEN hari_pjp END) or_trx,
                COUNT(CASE WHEN grouping_periode = 'M-1' AND qty > 0 THEN qty END) oa_trx_m1,
                IFNULL(ROUND((COUNT(CASE WHEN grouping_periode = 'M-1' AND qty > 0 THEN qty END)/COUNT(CASE WHEN grouping_periode = 'M-1' THEN hari_pjp END))*100,0),0) percent_oa_trx_m1,  
                IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN qty END),0) trx_m1,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN rev END),0) rev_m1,
                IFNULL(COUNT(CASE WHEN grouping_periode = 'M' AND qty_target > 0 THEN qty_target END),0) oa_trx_target,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty_target END),0) target_trx,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev_target END),0) target_rev,
                COUNT(CASE WHEN grouping_periode = 'M' AND qty > 0 THEN qty END) oa_trx_mtd,
                IFNULL(ROUND((COUNT(CASE WHEN grouping_periode = 'M' AND qty > 0 THEN qty END)/COUNT(CASE WHEN grouping_periode = 'M' THEN hari_pjp END))*100,0),0) percent_oa_trx_mtd,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0) trx_mtd,
                IFNULL(ROUND((IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0)/IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty_target END),0))*100,0),0) ach_trx,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0) rev_mtd,
                IFNULL(ROUND((IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0)/IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev_target END),0))*100,0),0) ach_rev,
                IFNULL(ROUND(((IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0)/$dm)/(IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN qty END),0)/$dm1))*100,0),0) rr_trx,
                IFNULL(ROUND(((IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0)/$dm)/(IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN rev END),0)/$dm1))*100,0),0) rr_rev,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0) - IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty_target END),0) gap_trx,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0) - IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev_target END),0) gap_rev
                FROM
                (SELECT id_digipos,outlet,tap,channel,pic,hari_pjp
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING' AND $hari_pjp AND pic = $pic)A
                JOIN 
                (SELECT id_outlet, grouping_periode, SUM(qty) qty, SUM(rev) rev
                FROM db_st_nota
                WHERE jenis_produk = 'VOUCHER FISIK' AND nama_produk = 'SIMPATI' $validity
                GROUP BY id_outlet, grouping_periode)B
                ON A.id_digipos = B.id_outlet
                JOIN
                (SELECT id_outlet, SUM(qty) qty_target, SUM(rev) rev_target
                FROM db_sales_plan
                WHERE jenis_produk = 'VOUCHER FISIK' AND nama_produk = 'SIMPATI' $validity
                GROUP BY id_outlet)C
                ON  B.id_outlet = C.id_outlet
                GROUP BY tap,channel,pic,hari_pjp";

        $resultQuery = $this->db->query($query);
		if($resultQuery){
			return $resultQuery->getResultArray();
		}
    }

    /*Dashboard*/
    function data_detail_dashboard($tap,$pic,$validity,$hari_pjp,$dm,$dm1){

        $query = "SELECT id_digipos,outlet,tap,channel,pic,hari_pjp,
                COUNT(CASE WHEN grouping_periode = 'M' THEN hari_pjp END) or_trx,
                COUNT(CASE WHEN grouping_periode = 'M-1' AND qty > 0 THEN qty END) oa_trx_m1,
                IFNULL(ROUND((COUNT(CASE WHEN grouping_periode = 'M-1' AND qty > 0 THEN qty END)/COUNT(CASE WHEN grouping_periode = 'M-1' THEN hari_pjp END))*100,0),0) percent_oa_trx_m1,  
                IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN qty END),0) trx_m1,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN rev END),0) rev_m1,
                IFNULL(COUNT(CASE WHEN grouping_periode = 'M' AND qty_target > 0 THEN qty_target END),0) oa_trx_target,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty_target END),0) target_trx,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev_target END),0) target_rev,
                COUNT(CASE WHEN grouping_periode = 'M' AND qty > 0 THEN qty END) oa_trx_mtd,
                IFNULL(ROUND((COUNT(CASE WHEN grouping_periode = 'M' AND qty > 0 THEN qty END)/COUNT(CASE WHEN grouping_periode = 'M' THEN hari_pjp END))*100,0),0) percent_oa_trx_mtd,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0) trx_mtd,
                IFNULL(ROUND((IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0)/IFNULL(qty_target,0))*100,0),0) ach_trx,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0) rev_mtd,
                IFNULL(ROUND((IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0)/IFNULL(rev_target,0))*100,0),0) ach_rev,
                IFNULL(ROUND(((IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0)/$dm)/(IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN qty END),0)/$dm1))*100,0),0) rr_trx,
                IFNULL(ROUND(((IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0)/$dm)/(IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN rev END),0)/$dm1))*100,0),0) rr_rev,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0) - IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty_target END),0) gap_trx,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0) - IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev_target END),0) gap_rev
                FROM
                (SELECT id_digipos,outlet,tap,channel,pic,hari_pjp
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING' AND $hari_pjp AND $tap AND $pic)A
                JOIN 
                (SELECT id_outlet, grouping_periode, SUM(qty) qty, SUM(rev) rev
                FROM db_st_nota
                WHERE jenis_produk = 'VOUCHER FISIK' AND nama_produk = 'SIMPATI' $validity
                GROUP BY id_outlet, grouping_periode)B
                ON A.id_digipos = B.id_outlet
                JOIN
                (SELECT id_outlet, IFNULL(SUM(qty),0) qty_target, IFNULL(SUM(rev),0) rev_target, COUNT(CASE WHEN qty > 0 THEN qty END) oa_qty_target
                FROM db_sales_plan
                WHERE jenis_produk = 'VOUCHER FISIK' AND nama_produk = 'SIMPATI' $validity
                GROUP BY id_outlet)C
                ON  B.id_outlet = C.id_outlet
                GROUP BY id_digipos,outlet,tap,channel,pic,hari_pjp
                
                UNION ALL
                
                SELECT 'TOTAL' AS id_digipos,'' AS outlet,'' AS tap,'' AS channel,'' AS pic, '' AS hari_pjp,
                COUNT(CASE WHEN grouping_periode = 'M' THEN hari_pjp END) or_trx,
                COUNT(CASE WHEN grouping_periode = 'M-1' AND qty > 0 THEN qty END) oa_trx_m1,
                IFNULL(ROUND((COUNT(CASE WHEN grouping_periode = 'M-1' AND qty > 0 THEN qty END)/COUNT(CASE WHEN grouping_periode = 'M-1' THEN hari_pjp END))*100,0),0) percent_oa_trx_m1,  
                IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN qty END),0) trx_m1,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN rev END),0) rev_m1,
                IFNULL(COUNT(CASE WHEN grouping_periode = 'M' AND qty_target > 0 THEN qty_target END),0) oa_trx_target,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty_target END),0) target_trx,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev_target END),0) target_rev,
                COUNT(CASE WHEN grouping_periode = 'M' AND qty > 0 THEN qty END) oa_trx_mtd,
                IFNULL(ROUND((COUNT(CASE WHEN grouping_periode = 'M' AND qty > 0 THEN qty END)/COUNT(CASE WHEN grouping_periode = 'M' THEN hari_pjp END))*100,0),0) percent_oa_trx_mtd,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0) trx_mtd,
                IFNULL(ROUND((IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0)/IFNULL(qty_target,0))*100,0),0) ach_trx,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0) rev_mtd,
                IFNULL(ROUND((IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0)/IFNULL(rev_target,0))*100,0),0) ach_rev,
                IFNULL(ROUND(((IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0)/$dm)/(IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN qty END),0)/$dm1))*100,0),0) rr_trx,
                IFNULL(ROUND(((IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0)/$dm)/(IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN rev END),0)/$dm1))*100,0),0) rr_rev,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0) - IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty_target END),0) gap_trx,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0) - IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev_target END),0) gap_rev
                FROM
                (SELECT id_digipos,outlet,tap,channel,pic,hari_pjp
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING' AND $hari_pjp AND $tap AND $pic)A
                JOIN 
                (SELECT id_outlet, grouping_periode, SUM(qty) qty, SUM(rev) rev
                FROM db_st_nota
                WHERE jenis_produk = 'VOUCHER FISIK' AND nama_produk = 'SIMPATI' $validity
                GROUP BY id_outlet, grouping_periode)B
                ON A.id_digipos = B.id_outlet
                JOIN
                (SELECT id_outlet, IFNULL(SUM(qty),0) qty_target, IFNULL(SUM(rev),0) rev_target, COUNT(CASE WHEN qty > 0 THEN qty END) oa_qty_target
                FROM db_sales_plan
                WHERE jenis_produk = 'VOUCHER FISIK' AND nama_produk = 'SIMPATI' $validity
                GROUP BY id_outlet)C
                ON  B.id_outlet = C.id_outlet";

        $resultQuery = $this->db->query($query);
		if($resultQuery){
			return $resultQuery->getResultArray();
		}
    }

    function data_summary_dashboard($tap,$pic,$validity,$hari_pjp,$dm,$dm1){
      
        $query = "SELECT tap,channel,pic,hari_pjp,
                COUNT(CASE WHEN grouping_periode = 'M' THEN hari_pjp END) or_trx,
                COUNT(CASE WHEN grouping_periode = 'M-1' AND qty > 0 THEN qty END) oa_trx_m1,
                IFNULL(ROUND((COUNT(CASE WHEN grouping_periode = 'M-1' AND qty > 0 THEN qty END)/COUNT(CASE WHEN grouping_periode = 'M-1' THEN hari_pjp END))*100,0),0) percent_oa_trx_m1,  
                IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN qty END),0) trx_m1,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN rev END),0) rev_m1,
                IFNULL(COUNT(CASE WHEN grouping_periode = 'M' AND qty_target > 0 THEN qty_target END),0) oa_trx_target,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty_target END),0) target_trx,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev_target END),0) target_rev,
                COUNT(CASE WHEN grouping_periode = 'M' AND qty > 0 THEN qty END) oa_trx_mtd,
                IFNULL(ROUND((COUNT(CASE WHEN grouping_periode = 'M' AND qty > 0 THEN qty END)/COUNT(CASE WHEN grouping_periode = 'M' THEN hari_pjp END))*100,0),0) percent_oa_trx_mtd,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0) trx_mtd,
                IFNULL(ROUND((IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0)/IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty_target END),0))*100,0),0) ach_trx,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0) rev_mtd,
                IFNULL(ROUND((IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0)/IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev_target END),0))*100,0),0) ach_rev,
                IFNULL(ROUND(((IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0)/$dm)/(IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN qty END),0)/$dm1))*100,0),0) rr_trx,
                IFNULL(ROUND(((IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0)/$dm)/(IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN rev END),0)/$dm1))*100,0),0) rr_rev,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0) - IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty_target END),0) gap_trx,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0) - IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev_target END),0) gap_rev
                FROM
                (SELECT id_digipos,outlet,tap,channel,pic,hari_pjp
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING' AND $hari_pjp AND $tap)A
                JOIN 
                (SELECT id_outlet, grouping_periode, SUM(qty) qty, SUM(rev) rev
                FROM db_st_nota
                WHERE jenis_produk = 'VOUCHER FISIK' AND nama_produk = 'SIMPATI' $validity
                GROUP BY id_outlet, grouping_periode)B
                ON A.id_digipos = B.id_outlet
                JOIN
                (SELECT id_outlet, SUM(qty) qty_target, SUM(rev) rev_target
                FROM db_sales_plan
                WHERE jenis_produk = 'VOUCHER FISIK' AND nama_produk = 'SIMPATI' $validity
                GROUP BY id_outlet)C
                ON  B.id_outlet = C.id_outlet
                GROUP BY tap,channel,pic
                
                UNION ALL
                
                SELECT 'TOTAL' AS tap,'' AS channel, '' AS pic, '' AS hari_pjp,
                COUNT(CASE WHEN grouping_periode = 'M' THEN hari_pjp END) or_trx,
                COUNT(CASE WHEN grouping_periode = 'M-1' AND qty > 0 THEN qty END) oa_trx_m1,
                IFNULL(ROUND((COUNT(CASE WHEN grouping_periode = 'M-1' AND qty > 0 THEN qty END)/COUNT(CASE WHEN grouping_periode = 'M-1' THEN hari_pjp END))*100,0),0) percent_oa_trx_m1,  
                IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN qty END),0) trx_m1,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN rev END),0) rev_m1,
                IFNULL(COUNT(CASE WHEN grouping_periode = 'M' AND qty_target > 0 THEN qty_target END),0) oa_trx_target,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty_target END),0) target_trx,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev_target END),0) target_rev,
                COUNT(CASE WHEN grouping_periode = 'M' AND qty > 0 THEN qty END) oa_trx_mtd,
                IFNULL(ROUND((COUNT(CASE WHEN grouping_periode = 'M' AND qty > 0 THEN qty END)/COUNT(CASE WHEN grouping_periode = 'M' THEN hari_pjp END))*100,0),0) percent_oa_trx_mtd,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0) trx_mtd,
                IFNULL(ROUND((IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0)/IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty_target END),0))*100,0),0) ach_trx,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0) rev_mtd,
                IFNULL(ROUND((IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0)/IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev_target END),0))*100,0),0) ach_rev,
                IFNULL(ROUND(((IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0)/$dm)/(IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN qty END),0)/$dm1))*100,0),0) rr_trx,
                IFNULL(ROUND(((IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0)/$dm)/(IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN rev END),0)/$dm1))*100,0),0) rr_rev,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0) - IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty_target END),0) gap_trx,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0) - IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev_target END),0) gap_rev
                FROM
                (SELECT id_digipos,outlet,tap,channel,pic,hari_pjp
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING' AND $hari_pjp AND $tap)A
                JOIN 
                (SELECT id_outlet, grouping_periode, SUM(qty) qty, SUM(rev) rev
                FROM db_st_nota
                WHERE jenis_produk = 'VOUCHER FISIK' AND nama_produk = 'SIMPATI' $validity
                GROUP BY id_outlet, grouping_periode)B
                ON A.id_digipos = B.id_outlet
                JOIN
                (SELECT id_outlet, SUM(qty) qty_target, SUM(rev) rev_target
                FROM db_sales_plan
                WHERE jenis_produk = 'VOUCHER FISIK' AND nama_produk = 'SIMPATI' $validity
                GROUP BY id_outlet)C
                ON  B.id_outlet = C.id_outlet";

        $resultQuery = $this->db->query($query);
		if($resultQuery){
			return $resultQuery->getResultArray();
		}
    }
    /*End of dashboard*/
}
?>