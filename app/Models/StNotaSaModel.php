<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\ToolsModel;

class StNotaSaModel extends Model
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    function get_latest_update_date_st_nota(){
        $query = "SELECT MAX(CASE WHEN grouping_periode = 'M' THEN periode_date END) update_date_m, 
                MAX(CASE WHEN grouping_periode = 'M-1' THEN periode_date END) update_date_m1
                FROM db_st_nota";
        
        $resultQuery = $this->db->query($query);
        if($resultQuery)return $resultQuery->getRowArray();
    }

    /*query bot telegram */
    function data_detail_bc($nama_produk,$hari_pjp,$dm,$dm1,$idtel){

        $toolsModel = new ToolsModel();
        $optionFilter = $toolsModel->getFilterByTelegramId($idtel);
        //$optionFilter = "AND pic = 'FATIR FATAHILA ILHAM'";

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
                ROUND((IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0)/IFNULL(qty_target,0))*100,0) ach_trx,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0) rev_mtd,
                ROUND((IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0)/IFNULL(rev_target,0))*100,0) ach_rev,
                ROUND(((IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0)/$dm)/(IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN qty END),0)/$dm1))*100,0) rr_trx,
                ROUND(((IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0)/$dm)/(IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN rev END),0)/$dm1))*100,0) rr_rev,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0) - IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN qty END),0) gap_trx,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0) - IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN rev END),0) gap_rev
                FROM
                (SELECT id_digipos,outlet,tap,channel,pic,hari_pjp
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING' AND $hari_pjp $optionFilter
                )A
                JOIN 
                (SELECT id_outlet, grouping_periode, SUM(qty) qty, SUM(rev) rev
                FROM db_st_nota
                WHERE jenis_produk = 'SMART AKUISISI' $nama_produk
                GROUP BY id_outlet, grouping_periode)B
                ON A.id_digipos = B.id_outlet
                JOIN
                (SELECT id_outlet, qty qty_target, rev rev_target, COUNT(CASE WHEN qty > 0 THEN qty END) oa_qty_target
                FROM db_sales_plan
                WHERE jenis_produk = 'SMART AKUISISI' $nama_produk
                GROUP BY id_outlet)C
                ON  A.id_digipos = C.id_outlet
                GROUP BY id_digipos,outlet,tap,channel,pic,hari_pjp";

        $resultQuery = $this->db->query($query);
		if($resultQuery){
			return $resultQuery->getResultArray();
		}
    }

    function data_detail($nama_produk,$hari_pjp,$dm,$dm1,$idtel){

        $toolsModel = new ToolsModel();
        $optionFilter = $toolsModel->getFilterByTelegramId($idtel);
        //$optionFilter = "AND pic = 'FATIR FATAHILA ILHAM'";

        $query = "SELECT A1.id_digipos id_digipos, A1.outlet outlet, 
                A1.tap tap, A1.channel channel, A1.pic pic, A1.hari_pjp hari_pjp,
                or_trx,oa_trx_m1,
                IFNULL(ROUND((oa_trx_m1/or_trx)*100,0),0) percent_oa_trx_m1,
                trx_m1,rev_m1,
                IFNULL(SUM(oa_qty_target),0) oa_trx_target,
                target_trx,target_rev,oa_trx_mtd,
                IFNULL(ROUND((oa_trx_mtd/or_trx)*100,0),0) percent_oa_trx_mtd,
                trx_mtd,IFNULL(ROUND((trx_mtd/target_trx)*100,1),0) ach_trx,
                rev_mtd,IFNULL(ROUND((rev_mtd/target_rev)*100,1),0) ach_rev,
                ROUND(((trx_mtd/$dm)/(trx_m1/$dm1))*100,1) rr_trx,
                ROUND(((rev_mtd/$dm)/(rev_m1/$dm1))*100,1) rr_rev,
                (trx_mtd - target_trx) gap_trx,
                (rev_mtd - target_rev) gap_rev
                FROM
                (SELECT id_digipos,outlet,tap, channel, pic, hari_pjp, 
                COUNT(hari_pjp) or_trx
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING' AND $hari_pjp $optionFilter
                GROUP BY id_digipos,outlet,tap, channel, pic, hari_pjp) A1
                LEFT JOIN
                (SELECT id_digipos,outlet,tap, channel, pic, hari_pjp, 
                COUNT(CASE WHEN grouping_periode = 'M-1' AND qty > 0 THEN qty END) oa_trx_m1,
                COUNT(CASE WHEN grouping_periode = 'M' AND qty > 0 THEN qty END) oa_trx_mtd,  
                IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN qty END),0) trx_m1,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0) trx_mtd,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN rev END),0) rev_m1,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0) rev_mtd
                FROM
                (SELECT id_digipos,outlet,tap,channel,pic,hari_pjp
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING' AND $hari_pjp $optionFilter)A
                LEFT JOIN 
                (SELECT id_outlet, grouping_periode, SUM(qty) qty, SUM(rev) rev
                FROM db_st_nota
                WHERE jenis_produk = 'SMART AKUISISI' $nama_produk
                GROUP BY id_outlet, grouping_periode)B
                ON A.id_digipos = B.id_outlet
                GROUP BY id_digipos,outlet,tap, channel, pic, hari_pjp)A2
                ON A1.id_digipos = A2.id_digipos AND A1.pic = A2.pic AND A1.hari_pjp = A2.hari_pjp
                LEFT JOIN
                (SELECT id_digipos,outlet,tap, channel, pic, hari_pjp, 
                SUM(target_trx) target_trx,SUM(target_rev) target_rev,SUM(oa_qty_target) oa_qty_target
                FROM
                (SELECT id_digipos,outlet,tap,channel,pic,hari_pjp
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING' AND $hari_pjp $optionFilter)A
                LEFT JOIN
                (SELECT id_outlet,SUM(qty) target_trx, SUM(rev) target_rev, COUNT(CASE WHEN qty > 0 THEN qty END) oa_qty_target
                FROM db_sales_plan
                WHERE jenis_produk = 'SMART AKUISISI' $nama_produk
                GROUP BY id_outlet)B
                ON A.id_digipos = B.id_outlet
                GROUP BY id_digipos,outlet,tap, channel, pic, hari_pjp)A3
                ON A1.id_digipos = A3.id_digipos AND A1.pic = A3.pic AND A1.hari_pjp = A3.hari_pjp
                GROUP BY id_digipos,outlet,tap,channel,pic,hari_pjp

                UNION ALL
                
                SELECT A1.id_digipos id_digipos, A1.outlet outlet, 
                A1.tap tap, A1.channel channel, A1.pic pic, A1.hari_pjp hari_pjp,
                or_trx,oa_trx_m1,
                IFNULL(ROUND((oa_trx_m1/or_trx)*100,0),0) percent_oa_trx_m1,
                trx_m1,rev_m1,
                IFNULL(SUM(oa_qty_target),0) oa_trx_target,
                target_trx,target_rev,oa_trx_mtd,
                IFNULL(ROUND((oa_trx_mtd/or_trx)*100,0),0) percent_oa_trx_mtd,
                trx_mtd,IFNULL(ROUND((trx_mtd/target_trx)*100,1),0) ach_trx,
                rev_mtd,IFNULL(ROUND((rev_mtd/target_rev)*100,1),0) ach_rev,
                ROUND(((trx_mtd/$dm)/(trx_m1/$dm1))*100,1) rr_trx,
                ROUND(((rev_mtd/$dm)/(rev_m1/$dm1))*100,1) rr_rev,
                (trx_mtd - target_trx) gap_trx,
                (rev_mtd - target_rev) gap_rev
                FROM
                (SELECT 'TOTAL' AS id_digipos,'' outlet, '' tap, '' channel, '' pic, '' hari_pjp, 
                COUNT(hari_pjp) or_trx
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING' AND $hari_pjp $optionFilter) A1
                LEFT JOIN
                (SELECT 'TOTAL' AS id_digipos,'' outlet, '' tap, '' channel, '' pic, '' hari_pjp, 
                COUNT(CASE WHEN grouping_periode = 'M-1' AND qty > 0 THEN qty END) oa_trx_m1,
                COUNT(CASE WHEN grouping_periode = 'M' AND qty > 0 THEN qty END) oa_trx_mtd,  
                IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN qty END),0) trx_m1,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0) trx_mtd,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN rev END),0) rev_m1,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0) rev_mtd
                FROM
                (SELECT id_digipos,outlet,tap,channel,pic,hari_pjp
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING' AND $hari_pjp $optionFilter)A
                LEFT JOIN 
                (SELECT id_outlet, grouping_periode, SUM(qty) qty, SUM(rev) rev
                FROM db_st_nota
                WHERE jenis_produk = 'SMART AKUISISI' $nama_produk
                GROUP BY id_outlet, grouping_periode)B
                ON A.id_digipos = B.id_outlet)A2
                ON A1.tap = A2.tap
                LEFT JOIN
                (SELECT 'TOTAL' AS id_digipos,'' outlet, '' tap, '' channel, '' pic, '' hari_pjp, 
                SUM(target_trx) target_trx,SUM(target_rev) target_rev,SUM(oa_qty_target) oa_qty_target
                FROM
                (SELECT id_digipos,outlet,tap,channel,pic,hari_pjp
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING' AND $hari_pjp $optionFilter)A
                LEFT JOIN
                (SELECT id_outlet,SUM(qty) target_trx, SUM(rev) target_rev, COUNT(CASE WHEN qty > 0 THEN qty END) oa_qty_target
                FROM db_sales_plan
                WHERE jenis_produk = 'SMART AKUISISI' $nama_produk
                GROUP BY id_outlet)B
                ON A.id_digipos = B.id_outlet)A3
                ON A1.tap = A3.tap

                ORDER BY FIELD(hari_pjp, 'SENIN','SELASA','RABU','KAMIS','JUMAT','SABTU')";

        $resultQuery = $this->db->query($query);
		if($resultQuery){
			return $resultQuery->getResultArray();
		}
    }

    function data_summary_bc($nama_produk,$hari_pjp,$dm,$dm1,$idtel){
        $toolsModel = new ToolsModel();
        $optionFilter = $toolsModel->getFilterByTelegramId($idtel);
        //$optionFilter = "AND pic = 'FATIR FATAHILA ILHAM'";

        $query = "SELECT tap,channel,pic,hari_pjp,
                COUNT(CASE WHEN grouping_periode = 'M' THEN hari_pjp END) or_trx,
                COUNT(CASE WHEN grouping_periode = 'M-1' AND qty > 0 THEN qty END) oa_trx_m1,
                IFNULL(ROUND((COUNT(CASE WHEN grouping_periode = 'M-1' AND qty > 0 THEN qty END)/COUNT(CASE WHEN grouping_periode = 'M-1' THEN hari_pjp END))*100,1),0) percent_oa_trx_m1,  
                IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN qty END),0) trx_m1,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN rev END),0) rev_m1,
                IFNULL(COUNT(CASE WHEN grouping_periode = 'M' AND qty_target > 0 THEN qty_target END),0) oa_trx_target,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty_target END),0) target_trx,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev_target END),0) target_rev,
                COUNT(CASE WHEN grouping_periode = 'M' AND qty > 0 THEN qty END) oa_trx_mtd,
                IFNULL(ROUND((COUNT(CASE WHEN grouping_periode = 'M' AND qty > 0 THEN qty END)/COUNT(CASE WHEN grouping_periode = 'M' THEN hari_pjp END))*100,1),0) percent_oa_trx_mtd,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0) trx_mtd,
                ROUND((IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0)/IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty_target END),0))*100,1) ach_trx,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0) rev_mtd,
                ROUND((IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0)/IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev_target END),0))*100,1) ach_rev,
                IFNULL(ROUND(((IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0)/$dm)/(IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN qty END),0)/$dm1))*100,0),0) rr_trx,
                IFNULL(ROUND(((IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0)/$dm)/(IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN rev END),0)/$dm1))*100,0),0) rr_rev,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0) - IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN qty END),0) gap_trx,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0) - IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN rev END),0) gap_rev
                FROM
                (SELECT id_digipos,outlet,tap,channel,pic,hari_pjp
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING' AND $hari_pjp $optionFilter
                )A
                JOIN 
                (SELECT id_outlet, grouping_periode, SUM(qty) qty, SUM(rev) rev
                FROM db_st_nota
                WHERE jenis_produk = 'SMART AKUISISI' $nama_produk
                GROUP BY id_outlet, grouping_periode)B
                ON A.id_digipos = B.id_outlet
                JOIN
                (SELECT id_outlet,SUM(qty) qty_target, SUM(rev) rev_target, COUNT(CASE WHEN qty > 0 THEN qty END) oa_qty_target
                FROM db_sales_plan
                WHERE jenis_produk = 'SMART AKUISISI' $nama_produk
                GROUP BY id_outlet)C
                ON  A.id_digipos = C.id_outlet
                GROUP BY tap,channel,pic";

        $resultQuery = $this->db->query($query);
		if($resultQuery){
			return $resultQuery->getResultArray();
		}
    }

    function data_summary($nama_produk,$hari_pjp,$dm,$dm1,$idtel){
        $toolsModel = new ToolsModel();
        $optionFilter = $toolsModel->getFilterByTelegramId($idtel);
        //$optionFilter = " AND 1 ";

        if($optionFilter != " AND 1 "){
            preg_match("/AND\s+TAP\s*=\s*'[^']*'/", $optionFilter, $matches);

            if (!empty($matches)) {
                $optionTap = $matches[0];
            }else{
                $optionTap = '';
            }
        }else{
            $optionTap = '';
        }
        
        $query = "SELECT A1.tap tap,  'ALL' channel, 'ALL' pic,
                or_trx,oa_trx_m1,
                IFNULL(ROUND((oa_trx_m1/or_trx)*100,0),0) percent_oa_trx_m1,
                trx_m1,rev_m1,
                IFNULL(SUM(oa_qty_target),0) oa_trx_target,
                target_trx,target_rev,oa_trx_mtd,
                IFNULL(ROUND((oa_trx_mtd/or_trx)*100,0),0) percent_oa_trx_mtd,
                trx_mtd,IFNULL(ROUND((trx_mtd/target_trx)*100,1),0) ach_trx,
                rev_mtd,IFNULL(ROUND((rev_mtd/target_rev)*100,1),0) ach_rev,
                ROUND(((trx_mtd/$dm)/(trx_m1/$dm1))*100,1) rr_trx,
                ROUND(((rev_mtd/$dm)/(rev_m1/$dm1))*100,1) rr_rev,
                (trx_mtd - target_trx) gap_trx,
                (rev_mtd - target_rev) gap_rev
                FROM
                (SELECT tap,channel,pic, COUNT(hari_pjp) or_trx
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING' AND $hari_pjp $optionTap
                GROUP BY tap) A1
                LEFT JOIN
                (SELECT tap,channel,pic,
                COUNT(CASE WHEN grouping_periode = 'M-1' AND qty > 0 THEN qty END) oa_trx_m1,
                COUNT(CASE WHEN grouping_periode = 'M' AND qty > 0 THEN qty END) oa_trx_mtd,  
                IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN qty END),0) trx_m1,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0) trx_mtd,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN rev END),0) rev_m1,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0) rev_mtd
                FROM
                (SELECT id_digipos,outlet,tap,channel,pic,hari_pjp
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING' AND $hari_pjp $optionTap)A
                LEFT JOIN 
                (SELECT id_outlet, grouping_periode, SUM(qty) qty, SUM(rev) rev
                FROM db_st_nota
                WHERE jenis_produk = 'SMART AKUISISI' $nama_produk
                GROUP BY id_outlet, grouping_periode)B
                ON A.id_digipos = B.id_outlet
                GROUP BY tap)A2
                ON A1.tap = A2.tap
                LEFT JOIN
                (SELECT tap,channel,pic,SUM(target_trx) target_trx,
                SUM(target_rev) target_rev,SUM(oa_qty_target) oa_qty_target
                FROM
                (SELECT id_digipos,outlet,tap,channel,pic,hari_pjp
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING' AND $hari_pjp $optionTap)A
                LEFT JOIN
                (SELECT id_outlet,SUM(qty) target_trx, SUM(rev) target_rev, COUNT(CASE WHEN qty > 0 THEN qty END) oa_qty_target
                FROM db_sales_plan
                WHERE jenis_produk = 'SMART AKUISISI' $nama_produk
                GROUP BY id_outlet)B
                ON A.id_digipos = B.id_outlet
                GROUP BY tap)A3
                ON A1.tap = A3.tap
                GROUP BY A1.tap

                UNION ALL

                SELECT A1.tap, A1.channel, A1.pic,
                or_trx,oa_trx_m1,
                IFNULL(ROUND((oa_trx_m1/or_trx)*100,0),0) percent_oa_trx_m1,
                trx_m1,rev_m1,
                IFNULL(SUM(oa_qty_target),0) oa_trx_target,
                target_trx,target_rev,oa_trx_mtd,
                IFNULL(ROUND((oa_trx_mtd/or_trx)*100,0),0) percent_oa_trx_mtd,
                trx_mtd,IFNULL(ROUND((trx_mtd/target_trx)*100,1),0) ach_trx,
                rev_mtd,IFNULL(ROUND((rev_mtd/target_rev)*100,1),0) ach_rev,
                ROUND(((trx_mtd/$dm)/(trx_m1/$dm1))*100,1) rr_trx,
                ROUND(((rev_mtd/$dm)/(rev_m1/$dm1))*100,1) rr_rev,
                (trx_mtd - target_trx) gap_trx,
                (rev_mtd - target_rev) gap_rev
                FROM
                (SELECT tap,channel,pic, COUNT(hari_pjp) or_trx
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING' AND $hari_pjp $optionFilter
                GROUP BY tap,channel,pic) A1
                LEFT JOIN
                (SELECT tap,channel,pic,
                COUNT(CASE WHEN grouping_periode = 'M-1' AND qty > 0 THEN qty END) oa_trx_m1,
                COUNT(CASE WHEN grouping_periode = 'M' AND qty > 0 THEN qty END) oa_trx_mtd,  
                IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN qty END),0) trx_m1,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0) trx_mtd,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN rev END),0) rev_m1,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0) rev_mtd
                FROM
                (SELECT id_digipos,outlet,tap,channel,pic,hari_pjp
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING' AND $hari_pjp $optionFilter)A
                LEFT JOIN 
                (SELECT id_outlet, grouping_periode, SUM(qty) qty, SUM(rev) rev
                FROM db_st_nota
                WHERE jenis_produk = 'SMART AKUISISI' $nama_produk
                GROUP BY id_outlet, grouping_periode)B
                ON A.id_digipos = B.id_outlet
                GROUP BY tap,channel,pic)A2
                ON A1.pic = A2.pic
                LEFT JOIN
                (SELECT id_digipos,outlet,tap,channel,pic,SUM(target_trx) target_trx,
                SUM(target_rev) target_rev,SUM(oa_qty_target) oa_qty_target
                FROM
                (SELECT id_digipos,outlet,tap,channel,pic,hari_pjp
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING' AND $hari_pjp $optionFilter)A
                LEFT JOIN
                (SELECT id_outlet,SUM(qty) target_trx, SUM(rev) target_rev, COUNT(CASE WHEN qty > 0 THEN qty END) oa_qty_target
                FROM db_sales_plan
                WHERE jenis_produk = 'SMART AKUISISI' $nama_produk
                GROUP BY id_outlet)B
                ON A.id_digipos = B.id_outlet
                GROUP BY tap,channel,pic)A3
                ON  A1.pic = A3.pic
                GROUP BY A1.tap, A1.channel, A1.pic
                
                UNION ALL
                
                SELECT A1.tap, A1.channel, A1.pic,
                or_trx,oa_trx_m1,
                IFNULL(ROUND((oa_trx_m1/or_trx)*100,0),0) percent_oa_trx_m1,
                trx_m1,rev_m1,
                IFNULL(SUM(oa_qty_target),0) oa_trx_target,
                target_trx,target_rev,oa_trx_mtd,
                IFNULL(ROUND((oa_trx_mtd/or_trx)*100,0),0) percent_oa_trx_mtd,
                trx_mtd,IFNULL(ROUND((trx_mtd/target_trx)*100,1),0) ach_trx,
                rev_mtd,IFNULL(ROUND((rev_mtd/target_rev)*100,1),0) ach_rev,
                ROUND(((trx_mtd/$dm)/(trx_m1/$dm1))*100,1) rr_trx,
                ROUND(((rev_mtd/$dm)/(rev_m1/$dm1))*100,1) rr_rev,
                (trx_mtd - target_trx) gap_trx,
                (rev_mtd - target_rev) gap_rev
                FROM
                (SELECT 'TOTAL' AS tap,'' AS channel,'' AS pic, COUNT(hari_pjp) or_trx
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING' AND $hari_pjp) A1
                LEFT JOIN
                (SELECT 'TOTAL' AS tap,'' AS channel,'' AS pic,
                COUNT(CASE WHEN grouping_periode = 'M-1' AND qty > 0 THEN qty END) oa_trx_m1,
                COUNT(CASE WHEN grouping_periode = 'M' AND qty > 0 THEN qty END) oa_trx_mtd,  
                IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN qty END),0) trx_m1,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0) trx_mtd,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN rev END),0) rev_m1,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0) rev_mtd
                FROM
                (SELECT id_digipos,outlet,tap,channel,pic,hari_pjp
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING' AND $hari_pjp)A
                LEFT JOIN 
                (SELECT id_outlet, grouping_periode, SUM(qty) qty, SUM(rev) rev
                FROM db_st_nota
                WHERE jenis_produk = 'SMART AKUISISI' $nama_produk
                GROUP BY id_outlet, grouping_periode)B
                ON A.id_digipos = B.id_outlet)A2
                ON A1.tap = A2.tap
                LEFT JOIN
                (SELECT 'TOTAL' AS tap,'' AS channel,'' AS pic,
                SUM(target_trx) target_trx,SUM(target_rev) target_rev,SUM(oa_qty_target) oa_qty_target
                FROM
                (SELECT id_digipos,outlet,tap,channel,pic,hari_pjp
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING' AND $hari_pjp)A
                LEFT JOIN
                (SELECT id_outlet,SUM(qty) target_trx, SUM(rev) target_rev, COUNT(CASE WHEN qty > 0 THEN qty END) oa_qty_target
                FROM db_sales_plan
                WHERE jenis_produk = 'SMART AKUISISI' $nama_produk
                GROUP BY id_outlet)B
                ON A.id_digipos = B.id_outlet)A3
                ON A1.tap = A3.tap";

        $resultQuery = $this->db->query($query);
		if($resultQuery){
			return $resultQuery->getResultArray();
		}
    }

    function data_summary_all_bc($nama_produk,$hari_pjp,$dm,$dm1,$idtel){
        $toolsModel = new ToolsModel();
        $optionFilter = $toolsModel->getFilterByTelegramId($idtel);

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
                ROUND((IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0)/IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty_target END),0))*100,0) ach_trx,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0) rev_mtd,
                ROUND((IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0)/IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev_target END),0))*100,0) ach_rev,
                IFNULL(ROUND(((IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0)/$dm)/(IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN qty END),0)/$dm1))*100,0),0) rr_trx,
                IFNULL(ROUND(((IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0)/$dm)/(IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN rev END),0)/$dm1))*100,0),0) rr_rev,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0) - IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN qty END),0) gap_trx,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0) - IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN rev END),0) gap_rev
                FROM
                (SELECT id_digipos,outlet,tap,channel,pic,hari_pjp
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING' AND $hari_pjp $optionFilter
                )A
                JOIN 
                (SELECT id_outlet, grouping_periode, SUM(qty) qty, SUM(rev) rev
                FROM db_st_nota
                WHERE jenis_produk = 'SMART AKUISISI' $nama_produk
                GROUP BY id_outlet, grouping_periode)B
                ON A.id_digipos = B.id_outlet
                JOIN
                (SELECT id_outlet,SUM(qty) qty_target, SUM(rev) rev_target, COUNT(CASE WHEN qty > 0 THEN qty END) oa_qty_target
                FROM db_sales_plan
                WHERE jenis_produk = 'SMART AKUISISI' $nama_produk
                GROUP BY id_outlet)C
                ON  A.id_digipos = C.id_outlet
                GROUP BY tap,channel,pic
                
                UNION ALL
                
                SELECT 'TOTAL' tap,'' channel,'' pic,'' hari_pjp,
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
                ROUND((IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0)/IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty_target END),0))*100,0) ach_trx,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0) rev_mtd,
                ROUND((IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0)/IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev_target END),0))*100,0) ach_rev,
                IFNULL(ROUND(((IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0)/$dm)/(IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN qty END),0)/$dm1))*100,0),0) rr_trx,
                IFNULL(ROUND(((IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0)/$dm)/(IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN rev END),0)/$dm1))*100,0),0) rr_rev,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0) - IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN qty END),0) gap_trx,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0) - IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN rev END),0) gap_rev
                FROM
                (SELECT id_digipos,outlet,tap,channel,pic,hari_pjp
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING' AND $hari_pjp $optionFilter
                )A
                JOIN 
                (SELECT id_outlet, grouping_periode, SUM(qty) qty, SUM(rev) rev
                FROM db_st_nota
                WHERE jenis_produk = 'SMART AKUISISI' $nama_produk
                GROUP BY id_outlet, grouping_periode)B
                ON A.id_digipos = B.id_outlet
                JOIN
                (SELECT id_outlet,SUM(qty) qty_target, SUM(rev) rev_target, COUNT(CASE WHEN qty > 0 THEN qty END) oa_qty_target
                FROM db_sales_plan
                WHERE jenis_produk = 'SMART AKUISISI' $nama_produk
                GROUP BY id_outlet)C
                ON  A.id_digipos = C.id_outlet";

        $resultQuery = $this->db->query($query);
		if($resultQuery){
			return $resultQuery->getResultArray();
		}
    }

    function data_summary_all($nama_produk,$hari_pjp,$dm,$dm1,$idtel){
        $toolsModel = new ToolsModel();
        $optionFilter = $toolsModel->getFilterByTelegramId($idtel);
        $tapFilter = $toolsModel->getFilterTapByTelegramId($idtel);
        
        $query = "SELECT A1.tap tap,  'ALL' channel, 'ALL' pic,
                or_trx,oa_trx_m1,
                IFNULL(ROUND((oa_trx_m1/or_trx)*100,0),0) percent_oa_trx_m1,
                trx_m1,rev_m1,
                IFNULL(SUM(oa_qty_target),0) oa_trx_target,
                target_trx,target_rev,oa_trx_mtd,
                IFNULL(ROUND((oa_trx_mtd/or_trx)*100,0),0) percent_oa_trx_mtd,
                trx_mtd,IFNULL(ROUND((trx_mtd/target_trx)*100,1),0) ach_trx,
                rev_mtd,IFNULL(ROUND((rev_mtd/target_rev)*100,1),0) ach_rev,
                ROUND(((trx_mtd/$dm)/(trx_m1/$dm1))*100,1) rr_trx,
                ROUND(((rev_mtd/$dm)/(rev_m1/$dm1))*100,1) rr_rev,
                (trx_mtd - target_trx) gap_trx,
                (rev_mtd - target_rev) gap_rev
                FROM
                (SELECT tap,channel,pic, COUNT(hari_pjp) or_trx
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING' $tapFilter
                GROUP BY tap) A1
                LEFT JOIN
                (SELECT tap,channel,pic,
                COUNT(CASE WHEN grouping_periode = 'M-1' AND qty > 0 THEN qty END) oa_trx_m1,
                COUNT(CASE WHEN grouping_periode = 'M' AND qty > 0 THEN qty END) oa_trx_mtd,  
                IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN qty END),0) trx_m1,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0) trx_mtd,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN rev END),0) rev_m1,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0) rev_mtd
                FROM
                (SELECT id_digipos,outlet,tap,channel,pic,hari_pjp
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING' $tapFilter)A
                LEFT JOIN 
                (SELECT id_outlet, grouping_periode, SUM(qty) qty, SUM(rev) rev
                FROM db_st_nota
                WHERE jenis_produk = 'SMART AKUISISI' $nama_produk
                GROUP BY id_outlet, grouping_periode)B
                ON A.id_digipos = B.id_outlet
                GROUP BY tap)A2
                ON A1.tap = A2.tap
                LEFT JOIN
                (SELECT tap,channel,pic,SUM(target_trx) target_trx,
                SUM(target_rev) target_rev,SUM(oa_qty_target) oa_qty_target
                FROM
                (SELECT id_digipos,outlet,tap,channel,pic,hari_pjp
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING' $tapFilter)A
                LEFT JOIN
                (SELECT id_outlet,SUM(qty) target_trx, SUM(rev) target_rev, COUNT(CASE WHEN qty > 0 THEN qty END) oa_qty_target
                FROM db_sales_plan
                WHERE jenis_produk = 'SMART AKUISISI' $nama_produk
                GROUP BY id_outlet)B
                ON A.id_digipos = B.id_outlet
                GROUP BY tap)A3
                ON A1.tap = A3.tap
                GROUP BY A1.tap

                UNION ALL

                SELECT A1.tap, A1.channel, A1.pic,
                or_trx,oa_trx_m1,
                IFNULL(ROUND((oa_trx_m1/or_trx)*100,0),0) percent_oa_trx_m1,
                trx_m1,rev_m1,
                IFNULL(SUM(oa_qty_target),0) oa_trx_target,
                target_trx,target_rev,oa_trx_mtd,
                IFNULL(ROUND((oa_trx_mtd/or_trx)*100,0),0) percent_oa_trx_mtd,
                trx_mtd,IFNULL(ROUND((trx_mtd/target_trx)*100,1),0) ach_trx,
                rev_mtd,IFNULL(ROUND((rev_mtd/target_rev)*100,1),0) ach_rev,
                ROUND(((trx_mtd/$dm)/(trx_m1/$dm1))*100,1) rr_trx,
                ROUND(((rev_mtd/$dm)/(rev_m1/$dm1))*100,1) rr_rev,
                (trx_mtd - target_trx) gap_trx,
                (rev_mtd - target_rev) gap_rev
                FROM
                (SELECT tap,channel,pic, COUNT(hari_pjp) or_trx
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING' $optionFilter
                GROUP BY tap,channel,pic) A1
                LEFT JOIN
                (SELECT tap,channel,pic,
                COUNT(CASE WHEN grouping_periode = 'M-1' AND qty > 0 THEN qty END) oa_trx_m1,
                COUNT(CASE WHEN grouping_periode = 'M' AND qty > 0 THEN qty END) oa_trx_mtd,  
                IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN qty END),0) trx_m1,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0) trx_mtd,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN rev END),0) rev_m1,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0) rev_mtd
                FROM
                (SELECT id_digipos,outlet,tap,channel,pic,hari_pjp
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING' $optionFilter)A
                LEFT JOIN 
                (SELECT id_outlet, grouping_periode, SUM(qty) qty, SUM(rev) rev
                FROM db_st_nota
                WHERE jenis_produk = 'SMART AKUISISI' $nama_produk
                GROUP BY id_outlet, grouping_periode)B
                ON A.id_digipos = B.id_outlet
                GROUP BY tap,channel,pic)A2
                ON A1.pic = A2.pic
                LEFT JOIN
                (SELECT id_digipos,outlet,tap,channel,pic,SUM(target_trx) target_trx,
                SUM(target_rev) target_rev,SUM(oa_qty_target) oa_qty_target
                FROM
                (SELECT id_digipos,outlet,tap,channel,pic,hari_pjp
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING' $optionFilter)A
                LEFT JOIN
                (SELECT id_outlet,SUM(qty) target_trx, SUM(rev) target_rev, COUNT(CASE WHEN qty > 0 THEN qty END) oa_qty_target
                FROM db_sales_plan
                WHERE jenis_produk = 'SMART AKUISISI' $nama_produk
                GROUP BY id_outlet)B
                ON A.id_digipos = B.id_outlet
                GROUP BY tap,channel,pic)A3
                ON  A1.pic = A3.pic
                GROUP BY A1.tap, A1.channel, A1.pic
                
                UNION ALL
                
                SELECT A1.tap, A1.channel, A1.pic,
                or_trx,oa_trx_m1,
                IFNULL(ROUND((oa_trx_m1/or_trx)*100,0),0) percent_oa_trx_m1,
                trx_m1,rev_m1,
                IFNULL(SUM(oa_qty_target),0) oa_trx_target,
                target_trx,target_rev,oa_trx_mtd,
                IFNULL(ROUND((oa_trx_mtd/or_trx)*100,0),0) percent_oa_trx_mtd,
                trx_mtd,IFNULL(ROUND((trx_mtd/target_trx)*100,1),0) ach_trx,
                rev_mtd,IFNULL(ROUND((rev_mtd/target_rev)*100,1),0) ach_rev,
                ROUND(((trx_mtd/$dm)/(trx_m1/$dm1))*100,1) rr_trx,
                ROUND(((rev_mtd/$dm)/(rev_m1/$dm1))*100,1) rr_rev,
                (trx_mtd - target_trx) gap_trx,
                (rev_mtd - target_rev) gap_rev
                FROM
                (SELECT 'TOTAL' AS tap,'' AS channel,'' AS pic, COUNT(hari_pjp) or_trx
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING') A1
                LEFT JOIN
                (SELECT 'TOTAL' AS tap,'' AS channel,'' AS pic,
                COUNT(CASE WHEN grouping_periode = 'M-1' AND qty > 0 THEN qty END) oa_trx_m1,
                COUNT(CASE WHEN grouping_periode = 'M' AND qty > 0 THEN qty END) oa_trx_mtd,  
                IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN qty END),0) trx_m1,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0) trx_mtd,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN rev END),0) rev_m1,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0) rev_mtd
                FROM
                (SELECT id_digipos,outlet,tap,channel,pic,hari_pjp
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING')A
                LEFT JOIN 
                (SELECT id_outlet, grouping_periode, SUM(qty) qty, SUM(rev) rev
                FROM db_st_nota
                WHERE jenis_produk = 'SMART AKUISISI' $nama_produk
                GROUP BY id_outlet, grouping_periode)B
                ON A.id_digipos = B.id_outlet)A2
                ON A1.tap = A2.tap
                LEFT JOIN
                (SELECT 'TOTAL' AS tap,'' AS channel,'' AS pic,
                SUM(target_trx) target_trx,SUM(target_rev) target_rev,SUM(oa_qty_target) oa_qty_target
                FROM
                (SELECT id_digipos,outlet,tap,channel,pic,hari_pjp
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING')A
                LEFT JOIN
                (SELECT id_outlet,SUM(qty) target_trx, SUM(rev) target_rev, COUNT(CASE WHEN qty > 0 THEN qty END) oa_qty_target
                FROM db_sales_plan
                WHERE jenis_produk = 'SMART AKUISISI' $nama_produk
                GROUP BY id_outlet)B
                ON A.id_digipos = B.id_outlet)A3
                ON A1.tap = A3.tap";

        $resultQuery = $this->db->query($query);
		if($resultQuery){
			return $resultQuery->getResultArray();
		}
    }

    function data_summary_by_outlet_bc($nama_produk,$dm,$dm1,$idtel,$idOutlet){
        $idOutlet = $this->db->escape($idOutlet);

        $toolsModel = new ToolsModel();
        $optionFilter = $toolsModel->getFilterByTelegramId($idtel);

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
                ROUND((IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0)/IFNULL(qty_target,0))*100,0) ach_trx,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0) rev_mtd,
                ROUND((IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0)/IFNULL(rev_target,0))*100,0) ach_rev,
                IFNULL(ROUND(((IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0)/$dm)/(IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN qty END),0)/$dm1))*100,0),0) rr_trx,
                IFNULL(ROUND(((IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0)/$dm)/(IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN rev END),0)/$dm1))*100,0),0) rr_rev,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0) - IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN qty END),0) gap_trx,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0) - IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN rev END),0) gap_rev
                FROM
                (SELECT id_digipos,outlet,tap,channel,pic,hari_pjp
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING' AND id_digipos = $idOutlet $optionFilter
                )A
                JOIN 
                (SELECT id_outlet, grouping_periode, SUM(qty) qty, SUM(rev) rev
                FROM db_st_nota
                WHERE jenis_produk = 'SMART AKUISISI' $nama_produk
                GROUP BY id_outlet, grouping_periode)B
                ON A.id_digipos = B.id_outlet
                JOIN
                (SELECT id_outlet, qty qty_target, rev rev_target, COUNT(CASE WHEN qty > 0 THEN qty END) oa_qty_target
                FROM db_sales_plan
                WHERE jenis_produk = 'SMART AKUISISI' $nama_produk
                GROUP BY id_outlet)C
                ON  A.id_digipos = C.id_outlet
                GROUP BY id_digipos,outlet,tap,channel,pic,hari_pjp
                
                UNION ALL
                
                SELECT 'TOTAL' id_digipos,'' outlet,'' tap,'' channel,'' pic,'' hari_pjp,
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
                ROUND((IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0)/IFNULL(qty_target,0))*100,0) ach_trx,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0) rev_mtd,
                ROUND((IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0)/IFNULL(rev_target,0))*100,0) ach_rev,
                IFNULL(ROUND(((IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0)/$dm)/(IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN qty END),0)/$dm1))*100,0),0) rr_trx,
                IFNULL(ROUND(((IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0)/$dm)/(IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN rev END),0)/$dm1))*100,0),0) rr_rev,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0) - IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN qty END),0) gap_trx,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0) - IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN rev END),0) gap_rev
                FROM
                (SELECT id_digipos,outlet,tap,channel,pic,hari_pjp
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING' AND id_digipos = $idOutlet $optionFilter
                )A
                JOIN 
                (SELECT id_outlet, grouping_periode, SUM(qty) qty, SUM(rev) rev
                FROM db_st_nota
                WHERE jenis_produk = 'SMART AKUISISI' $nama_produk
                GROUP BY id_outlet, grouping_periode)B
                ON A.id_digipos = B.id_outlet
                JOIN
                (SELECT id_outlet, qty qty_target, rev rev_target, COUNT(CASE WHEN qty > 0 THEN qty END) oa_qty_target
                FROM db_sales_plan
                WHERE jenis_produk = 'SMART AKUISISI' $nama_produk
                GROUP BY id_outlet)C
                ON  A.id_digipos = C.id_outlet";

        
        $resultQuery = $this->db->query($query);
		if($resultQuery){
			return $resultQuery->getResultArray();
		}
    }

    function data_summary_by_outlet($nama_produk,$dm,$dm1,$idtel,$idOutlet){
        $idOutlet = $this->db->escape($idOutlet);

        $toolsModel = new ToolsModel();
        $optionFilter = $toolsModel->getFilterByTelegramId($idtel);

        $query = "SELECT A1.id_digipos id_digipos, A1.outlet outlet, 
                A1.tap tap, A1.channel channel, A1.pic pic, A1.hari_pjp hari_pjp,
                or_trx,oa_trx_m1,
                IFNULL(ROUND((oa_trx_m1/or_trx)*100,0),0) percent_oa_trx_m1,
                trx_m1,rev_m1,
                IFNULL(SUM(oa_qty_target),0) oa_trx_target,
                target_trx,target_rev,oa_trx_mtd,
                IFNULL(ROUND((oa_trx_mtd/or_trx)*100,0),0) percent_oa_trx_mtd,
                trx_mtd,IFNULL(ROUND((trx_mtd/target_trx)*100,1),0) ach_trx,
                rev_mtd,IFNULL(ROUND((rev_mtd/target_rev)*100,1),0) ach_rev,
                ROUND(((trx_mtd/$dm)/(trx_m1/$dm1))*100,1) rr_trx,
                ROUND(((rev_mtd/$dm)/(rev_m1/$dm1))*100,1) rr_rev,
                (trx_mtd - target_trx) gap_trx,
                (rev_mtd - target_rev) gap_rev
                FROM
                (SELECT id_digipos,outlet,tap, channel, pic, hari_pjp, 
                COUNT(hari_pjp) or_trx
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING' AND id_digipos = $idOutlet $optionFilter
                GROUP BY id_digipos,outlet,tap, channel, pic, hari_pjp) A1
                LEFT JOIN
                (SELECT id_digipos,outlet,tap, channel, pic, hari_pjp, 
                COUNT(CASE WHEN grouping_periode = 'M-1' AND qty > 0 THEN qty END) oa_trx_m1,
                COUNT(CASE WHEN grouping_periode = 'M' AND qty > 0 THEN qty END) oa_trx_mtd,  
                IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN qty END),0) trx_m1,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0) trx_mtd,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN rev END),0) rev_m1,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0) rev_mtd
                FROM
                (SELECT id_digipos,outlet,tap,channel,pic,hari_pjp
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING' AND id_digipos = $idOutlet $optionFilter)A
                LEFT JOIN 
                (SELECT id_outlet, grouping_periode, SUM(qty) qty, SUM(rev) rev
                FROM db_st_nota
                WHERE jenis_produk = 'SMART AKUISISI' $nama_produk
                GROUP BY id_outlet, grouping_periode)B
                ON A.id_digipos = B.id_outlet
                GROUP BY id_digipos,outlet,tap, channel, pic, hari_pjp)A2
                ON A1.id_digipos = A2.id_digipos AND A1.pic = A2.pic AND A1.hari_pjp = A2.hari_pjp
                LEFT JOIN
                (SELECT id_digipos,outlet,tap, channel, pic, hari_pjp, 
                SUM(target_trx) target_trx,SUM(target_rev) target_rev,SUM(oa_qty_target) oa_qty_target
                FROM
                (SELECT id_digipos,outlet,tap,channel,pic,hari_pjp
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING' AND id_digipos = $idOutlet $optionFilter)A
                LEFT JOIN
                (SELECT id_outlet,SUM(qty) target_trx, SUM(rev) target_rev, COUNT(CASE WHEN qty > 0 THEN qty END) oa_qty_target
                FROM db_sales_plan
                WHERE jenis_produk = 'SMART AKUISISI' $nama_produk
                GROUP BY id_outlet)B
                ON A.id_digipos = B.id_outlet
                GROUP BY id_digipos,outlet,tap, channel, pic, hari_pjp)A3
                ON A1.id_digipos = A3.id_digipos AND A1.pic = A3.pic AND A1.hari_pjp = A3.hari_pjp
                GROUP BY id_digipos,outlet,tap,channel,pic,hari_pjp

                UNION ALL
                
                SELECT A1.id_digipos id_digipos, A1.outlet outlet, 
                A1.tap tap, A1.channel channel, A1.pic pic, A1.hari_pjp hari_pjp,
                or_trx,oa_trx_m1,
                IFNULL(ROUND((oa_trx_m1/or_trx)*100,0),0) percent_oa_trx_m1,
                trx_m1,rev_m1,
                IFNULL(SUM(oa_qty_target),0) oa_trx_target,
                target_trx,target_rev,oa_trx_mtd,
                IFNULL(ROUND((oa_trx_mtd/or_trx)*100,0),0) percent_oa_trx_mtd,
                trx_mtd,IFNULL(ROUND((trx_mtd/target_trx)*100,1),0) ach_trx,
                rev_mtd,IFNULL(ROUND((rev_mtd/target_rev)*100,1),0) ach_rev,
                ROUND(((trx_mtd/$dm)/(trx_m1/$dm1))*100,1) rr_trx,
                ROUND(((rev_mtd/$dm)/(rev_m1/$dm1))*100,1) rr_rev,
                (trx_mtd - target_trx) gap_trx,
                (rev_mtd - target_rev) gap_rev
                FROM
                (SELECT 'TOTAL' AS id_digipos,'' outlet, '' tap, '' channel, '' pic, '' hari_pjp, 
                COUNT(hari_pjp) or_trx
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING' AND id_digipos = $idOutlet $optionFilter) A1
                LEFT JOIN
                (SELECT 'TOTAL' AS id_digipos,'' outlet, '' tap, '' channel, '' pic, '' hari_pjp, 
                COUNT(CASE WHEN grouping_periode = 'M-1' AND qty > 0 THEN qty END) oa_trx_m1,
                COUNT(CASE WHEN grouping_periode = 'M' AND qty > 0 THEN qty END) oa_trx_mtd,  
                IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN qty END),0) trx_m1,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0) trx_mtd,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN rev END),0) rev_m1,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0) rev_mtd
                FROM
                (SELECT id_digipos,outlet,tap,channel,pic,hari_pjp
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING' AND id_digipos = $idOutlet $optionFilter)A
                LEFT JOIN 
                (SELECT id_outlet, grouping_periode, SUM(qty) qty, SUM(rev) rev
                FROM db_st_nota
                WHERE jenis_produk = 'SMART AKUISISI' $nama_produk
                GROUP BY id_outlet, grouping_periode)B
                ON A.id_digipos = B.id_outlet)A2
                ON A1.tap = A2.tap
                LEFT JOIN
                (SELECT 'TOTAL' AS id_digipos,'' outlet, '' tap, '' channel, '' pic, '' hari_pjp, 
                SUM(target_trx) target_trx,SUM(target_rev) target_rev,SUM(oa_qty_target) oa_qty_target
                FROM
                (SELECT id_digipos,outlet,tap,channel,pic,hari_pjp
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING' AND id_digipos = $idOutlet $optionFilter)A
                LEFT JOIN
                (SELECT id_outlet,SUM(qty) target_trx, SUM(rev) target_rev, COUNT(CASE WHEN qty > 0 THEN qty END) oa_qty_target
                FROM db_sales_plan
                WHERE jenis_produk = 'SMART AKUISISI' $nama_produk
                GROUP BY id_outlet)B
                ON A.id_digipos = B.id_outlet)A3
                ON A1.tap = A3.tap

                ORDER BY FIELD(hari_pjp, 'SENIN','SELASA','RABU','KAMIS','JUMAT','SABTU')";

        
        $resultQuery = $this->db->query($query);
		if($resultQuery){
			return $resultQuery->getResultArray();
		}
    }

    function data_summary_by_sf_bc($pic,$nama_produk,$hari_pjp,$dm,$dm1,$idtel){
        $pic = $this->db->escape($pic);

        $toolsModel = new ToolsModel();
        $optionFilter = $toolsModel->getFilterByTelegramId($idtel);

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
                ROUND((IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0)/IFNULL(qty_target,0))*100,0) ach_trx,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0) rev_mtd,
                ROUND((IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0)/IFNULL(rev_target,0))*100,0) ach_rev,
                IFNULL(ROUND(((IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0)/$dm)/(IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN qty END),0)/$dm1))*100,0),0) rr_trx,
                IFNULL(ROUND(((IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0)/$dm)/(IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN rev END),0)/$dm1))*100,0),0) rr_rev,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0) - IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN qty END),0) gap_trx,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0) - IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN rev END),0) gap_rev
                FROM
                (SELECT id_digipos,outlet,tap,channel,pic,hari_pjp
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING' AND pic = $pic AND $hari_pjp $optionFilter
                )A
                JOIN 
                (SELECT id_outlet, grouping_periode, SUM(qty) qty, SUM(rev) rev
                FROM db_st_nota
                WHERE jenis_produk = 'SMART AKUISISI' $nama_produk
                GROUP BY id_outlet, grouping_periode)B
                ON A.id_digipos = B.id_outlet
                JOIN
                (SELECT id_outlet, qty qty_target, rev rev_target, COUNT(CASE WHEN qty > 0 THEN qty END) oa_qty_target
                FROM db_sales_plan
                WHERE jenis_produk = 'SMART AKUISISI' $nama_produk
                GROUP BY id_outlet)C
                ON  A.id_digipos = C.id_outlet
                GROUP BY id_digipos,outlet,tap,channel,pic,hari_pjp
                
                UNION ALL
                
                SELECT 'TOTAL' id_digipos,'' outlet,tap,'' channel,'' pic,'' hari_pjp,
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
                ROUND((IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0)/IFNULL(qty_target,0))*100,0) ach_trx,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0) rev_mtd,
                ROUND((IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0)/IFNULL(rev_target,0))*100,0) ach_rev,
                IFNULL(ROUND(((IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0)/$dm)/(IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN qty END),0)/$dm1))*100,0),0) rr_trx,
                IFNULL(ROUND(((IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0)/$dm)/(IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN rev END),0)/$dm1))*100,0),0) rr_rev,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0) - IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN qty END),0) gap_trx,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0) - IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN rev END),0) gap_rev
                FROM
                (SELECT id_digipos,outlet,tap,channel,pic,hari_pjp
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING' AND pic = $pic AND $hari_pjp $optionFilter
                )A
                JOIN 
                (SELECT id_outlet, grouping_periode, SUM(qty) qty, SUM(rev) rev
                FROM db_st_nota
                WHERE jenis_produk = 'SMART AKUISISI' $nama_produk
                GROUP BY id_outlet, grouping_periode)B
                ON A.id_digipos = B.id_outlet
                JOIN
                (SELECT id_outlet, qty qty_target, rev rev_target, COUNT(CASE WHEN qty > 0 THEN qty END) oa_qty_target
                FROM db_sales_plan
                WHERE jenis_produk = 'SMART AKUISISI' $nama_produk
                GROUP BY id_outlet)C
                ON  A.id_digipos = C.id_outlet
                
                ORDER BY hari_pjp DESC";

        $resultQuery = $this->db->query($query);
		if($resultQuery){
			return $resultQuery->getResultArray();
		}
    }

    function data_summary_by_sf($pic,$nama_produk,$hari_pjp,$dm,$dm1,$idtel){
        $pic = $this->db->escape($pic);

        $toolsModel = new ToolsModel();
        $optionFilter = $toolsModel->getFilterByTelegramId($idtel);

        $query = "SELECT A1.id_digipos id_digipos, A1.outlet outlet, 
                A1.tap tap, A1.channel channel, A1.pic pic, A1.hari_pjp hari_pjp,
                or_trx,oa_trx_m1,
                IFNULL(ROUND((oa_trx_m1/or_trx)*100,0),0) percent_oa_trx_m1,
                trx_m1,rev_m1,
                IFNULL(SUM(oa_qty_target),0) oa_trx_target,
                target_trx,target_rev,oa_trx_mtd,
                IFNULL(ROUND((oa_trx_mtd/or_trx)*100,0),0) percent_oa_trx_mtd,
                trx_mtd,IFNULL(ROUND((trx_mtd/target_trx)*100,1),0) ach_trx,
                rev_mtd,IFNULL(ROUND((rev_mtd/target_rev)*100,1),0) ach_rev,
                ROUND(((trx_mtd/$dm)/(trx_m1/$dm1))*100,1) rr_trx,
                ROUND(((rev_mtd/$dm)/(rev_m1/$dm1))*100,1) rr_rev,
                (trx_mtd - target_trx) gap_trx,
                (rev_mtd - target_rev) gap_rev
                FROM
                (SELECT id_digipos,outlet,tap, channel, pic, hari_pjp, 
                COUNT(hari_pjp) or_trx
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING' AND pic = $pic AND $hari_pjp $optionFilter
                GROUP BY id_digipos,outlet,tap, channel, pic, hari_pjp) A1
                LEFT JOIN
                (SELECT id_digipos,outlet,tap, channel, pic, hari_pjp, 
                COUNT(CASE WHEN grouping_periode = 'M-1' AND qty > 0 THEN qty END) oa_trx_m1,
                COUNT(CASE WHEN grouping_periode = 'M' AND qty > 0 THEN qty END) oa_trx_mtd,  
                IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN qty END),0) trx_m1,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0) trx_mtd,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN rev END),0) rev_m1,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0) rev_mtd
                FROM
                (SELECT id_digipos,outlet,tap,channel,pic,hari_pjp
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING' AND pic = $pic AND $hari_pjp $optionFilter)A
                LEFT JOIN 
                (SELECT id_outlet, grouping_periode, SUM(qty) qty, SUM(rev) rev
                FROM db_st_nota
                WHERE jenis_produk = 'SMART AKUISISI' $nama_produk
                GROUP BY id_outlet, grouping_periode)B
                ON A.id_digipos = B.id_outlet
                GROUP BY id_digipos,outlet,tap, channel, pic, hari_pjp)A2
                ON A1.id_digipos = A2.id_digipos AND A1.pic = A2.pic AND A1.hari_pjp = A2.hari_pjp
                LEFT JOIN
                (SELECT id_digipos,outlet,tap, channel, pic, hari_pjp, 
                SUM(target_trx) target_trx,SUM(target_rev) target_rev,SUM(oa_qty_target) oa_qty_target
                FROM
                (SELECT id_digipos,outlet,tap,channel,pic,hari_pjp
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING' AND pic = $pic AND $hari_pjp $optionFilter)A
                LEFT JOIN
                (SELECT id_outlet,SUM(qty) target_trx, SUM(rev) target_rev, COUNT(CASE WHEN qty > 0 THEN qty END) oa_qty_target
                FROM db_sales_plan
                WHERE jenis_produk = 'SMART AKUISISI' $nama_produk
                GROUP BY id_outlet)B
                ON A.id_digipos = B.id_outlet
                GROUP BY id_digipos,outlet,tap, channel, pic, hari_pjp)A3
                ON A1.id_digipos = A3.id_digipos AND A1.pic = A3.pic AND A1.hari_pjp = A3.hari_pjp
                GROUP BY id_digipos,outlet,tap,channel,pic,hari_pjp

                UNION ALL
                
                SELECT A1.id_digipos id_digipos, A1.outlet outlet, 
                A1.tap tap, A1.channel channel, A1.pic pic, A1.hari_pjp hari_pjp,
                or_trx,oa_trx_m1,
                IFNULL(ROUND((oa_trx_m1/or_trx)*100,0),0) percent_oa_trx_m1,
                trx_m1,rev_m1,
                IFNULL(SUM(oa_qty_target),0) oa_trx_target,
                target_trx,target_rev,oa_trx_mtd,
                IFNULL(ROUND((oa_trx_mtd/or_trx)*100,0),0) percent_oa_trx_mtd,
                trx_mtd,IFNULL(ROUND((trx_mtd/target_trx)*100,1),0) ach_trx,
                rev_mtd,IFNULL(ROUND((rev_mtd/target_rev)*100,1),0) ach_rev,
                ROUND(((trx_mtd/$dm)/(trx_m1/$dm1))*100,1) rr_trx,
                ROUND(((rev_mtd/$dm)/(rev_m1/$dm1))*100,1) rr_rev,
                (trx_mtd - target_trx) gap_trx,
                (rev_mtd - target_rev) gap_rev
                FROM
                (SELECT 'TOTAL' AS id_digipos,'' outlet, '' tap, '' channel, '' pic, '' hari_pjp, 
                COUNT(hari_pjp) or_trx
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING' AND pic = $pic AND $hari_pjp $optionFilter) A1
                LEFT JOIN
                (SELECT 'TOTAL' AS id_digipos,'' outlet, '' tap, '' channel, '' pic, '' hari_pjp, 
                COUNT(CASE WHEN grouping_periode = 'M-1' AND qty > 0 THEN qty END) oa_trx_m1,
                COUNT(CASE WHEN grouping_periode = 'M' AND qty > 0 THEN qty END) oa_trx_mtd,  
                IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN qty END),0) trx_m1,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0) trx_mtd,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN rev END),0) rev_m1,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0) rev_mtd
                FROM
                (SELECT id_digipos,outlet,tap,channel,pic,hari_pjp
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING' AND pic = $pic AND $hari_pjp $optionFilter)A
                LEFT JOIN 
                (SELECT id_outlet, grouping_periode, SUM(qty) qty, SUM(rev) rev
                FROM db_st_nota
                WHERE jenis_produk = 'SMART AKUISISI' $nama_produk
                GROUP BY id_outlet, grouping_periode)B
                ON A.id_digipos = B.id_outlet)A2
                ON A1.tap = A2.tap
                LEFT JOIN
                (SELECT 'TOTAL' AS id_digipos,'' outlet, '' tap, '' channel, '' pic, '' hari_pjp, 
                SUM(target_trx) target_trx,SUM(target_rev) target_rev,SUM(oa_qty_target) oa_qty_target
                FROM
                (SELECT id_digipos,outlet,tap,channel,pic,hari_pjp
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING' AND pic = $pic AND $hari_pjp $optionFilter)A
                LEFT JOIN
                (SELECT id_outlet,SUM(qty) target_trx, SUM(rev) target_rev, COUNT(CASE WHEN qty > 0 THEN qty END) oa_qty_target
                FROM db_sales_plan
                WHERE jenis_produk = 'SMART AKUISISI' $nama_produk
                GROUP BY id_outlet)B
                ON A.id_digipos = B.id_outlet)A3
                ON A1.tap = A3.tap

                ORDER BY FIELD(hari_pjp, 'SENIN','SELASA','RABU','KAMIS','JUMAT','SABTU')";

        $resultQuery = $this->db->query($query);
		if($resultQuery){
			return $resultQuery->getResultArray();
		}
    }
    /*end of query bot telegram */

    /*query dashboard */
    function data_detail_dashboard($tap,$pic,$nama_produk,$hari_pjp,$dm,$dm1){
        $query = "SELECT A1.id_digipos id_digipos, A1.outlet outlet, 
                A1.tap tap, A1.channel channel, A1.pic pic, A1.hari_pjp hari_pjp,
                or_trx,oa_trx_m1,
                IFNULL(ROUND((oa_trx_m1/or_trx)*100,0),0) percent_oa_trx_m1,
                trx_m1,rev_m1,
                IFNULL(SUM(oa_qty_target),0) oa_trx_target,
                target_trx,target_rev,oa_trx_mtd,
                IFNULL(ROUND((oa_trx_mtd/or_trx)*100,0),0) percent_oa_trx_mtd,
                trx_mtd,IFNULL(ROUND((trx_mtd/target_trx)*100,1),0) ach_trx,
                rev_mtd,IFNULL(ROUND((rev_mtd/target_rev)*100,1),0) ach_rev,
                ROUND(((trx_mtd/$dm)/(trx_m1/$dm1))*100,1) rr_trx,
                ROUND(((rev_mtd/$dm)/(rev_m1/$dm1))*100,1) rr_rev,
                (trx_mtd - target_trx) gap_trx,
                (rev_mtd - target_rev) gap_rev
                FROM
                (SELECT id_digipos,outlet,tap, channel, pic, hari_pjp, 
                COUNT(hari_pjp) or_trx
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING' AND $hari_pjp AND $pic AND $tap
                GROUP BY id_digipos,outlet,tap, channel, pic, hari_pjp) A1
                LEFT JOIN
                (SELECT id_digipos,outlet,tap, channel, pic, hari_pjp, 
                COUNT(CASE WHEN grouping_periode = 'M-1' AND qty > 0 THEN qty END) oa_trx_m1,
                COUNT(CASE WHEN grouping_periode = 'M' AND qty > 0 THEN qty END) oa_trx_mtd,  
                IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN qty END),0) trx_m1,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0) trx_mtd,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN rev END),0) rev_m1,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0) rev_mtd
                FROM
                (SELECT id_digipos,outlet,tap,channel,pic,hari_pjp
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING' AND $hari_pjp AND $pic AND $tap)A
                LEFT JOIN 
                (SELECT id_outlet, grouping_periode, SUM(qty) qty, SUM(rev) rev
                FROM db_st_nota
                WHERE jenis_produk = 'SMART AKUISISI' $nama_produk
                GROUP BY id_outlet, grouping_periode)B
                ON A.id_digipos = B.id_outlet
                GROUP BY id_digipos,outlet,tap, channel, pic, hari_pjp)A2
                ON A1.id_digipos = A2.id_digipos AND A1.pic = A2.pic AND A1.hari_pjp = A2.hari_pjp
                LEFT JOIN
                (SELECT id_digipos,outlet,tap, channel, pic, hari_pjp, 
                SUM(target_trx) target_trx,SUM(target_rev) target_rev,SUM(oa_qty_target) oa_qty_target
                FROM
                (SELECT id_digipos,outlet,tap,channel,pic,hari_pjp
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING' AND $hari_pjp AND $pic AND $tap)A
                LEFT JOIN
                (SELECT id_outlet,SUM(qty) target_trx, SUM(rev) target_rev, COUNT(CASE WHEN qty > 0 THEN qty END) oa_qty_target
                FROM db_sales_plan
                WHERE jenis_produk = 'SMART AKUISISI' $nama_produk
                GROUP BY id_outlet)B
                ON A.id_digipos = B.id_outlet
                GROUP BY id_digipos,outlet,tap, channel, pic, hari_pjp)A3
                ON A1.id_digipos = A3.id_digipos AND A1.pic = A3.pic AND A1.hari_pjp = A3.hari_pjp
                GROUP BY id_digipos,outlet,tap,channel,pic,hari_pjp

                UNION ALL
                
                SELECT A1.id_digipos id_digipos, A1.outlet outlet, 
                A1.tap tap, A1.channel channel, A1.pic pic, A1.hari_pjp hari_pjp,
                or_trx,oa_trx_m1,
                IFNULL(ROUND((oa_trx_m1/or_trx)*100,0),0) percent_oa_trx_m1,
                trx_m1,rev_m1,
                IFNULL(SUM(oa_qty_target),0) oa_trx_target,
                target_trx,target_rev,oa_trx_mtd,
                IFNULL(ROUND((oa_trx_mtd/or_trx)*100,0),0) percent_oa_trx_mtd,
                trx_mtd,IFNULL(ROUND((trx_mtd/target_trx)*100,1),0) ach_trx,
                rev_mtd,IFNULL(ROUND((rev_mtd/target_rev)*100,1),0) ach_rev,
                ROUND(((trx_mtd/$dm)/(trx_m1/$dm1))*100,1) rr_trx,
                ROUND(((rev_mtd/$dm)/(rev_m1/$dm1))*100,1) rr_rev,
                (trx_mtd - target_trx) gap_trx,
                (rev_mtd - target_rev) gap_rev
                FROM
                (SELECT 'TOTAL' AS id_digipos,'' outlet, '' tap, '' channel, '' pic, '' hari_pjp, 
                COUNT(hari_pjp) or_trx
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING' AND $hari_pjp AND $pic AND $tap) A1
                LEFT JOIN
                (SELECT 'TOTAL' AS id_digipos,'' outlet, '' tap, '' channel, '' pic, '' hari_pjp, 
                COUNT(CASE WHEN grouping_periode = 'M-1' AND qty > 0 THEN qty END) oa_trx_m1,
                COUNT(CASE WHEN grouping_periode = 'M' AND qty > 0 THEN qty END) oa_trx_mtd,  
                IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN qty END),0) trx_m1,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0) trx_mtd,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN rev END),0) rev_m1,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0) rev_mtd
                FROM
                (SELECT id_digipos,outlet,tap,channel,pic,hari_pjp
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING' AND $hari_pjp AND $pic AND $tap)A
                LEFT JOIN 
                (SELECT id_outlet, grouping_periode, SUM(qty) qty, SUM(rev) rev
                FROM db_st_nota
                WHERE jenis_produk = 'SMART AKUISISI' $nama_produk
                GROUP BY id_outlet, grouping_periode)B
                ON A.id_digipos = B.id_outlet)A2
                ON A1.tap = A2.tap
                LEFT JOIN
                (SELECT 'TOTAL' AS id_digipos,'' outlet, '' tap, '' channel, '' pic, '' hari_pjp, 
                SUM(target_trx) target_trx,SUM(target_rev) target_rev,SUM(oa_qty_target) oa_qty_target
                FROM
                (SELECT id_digipos,outlet,tap,channel,pic,hari_pjp
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING' AND $hari_pjp AND $pic AND $tap)A
                LEFT JOIN
                (SELECT id_outlet,SUM(qty) target_trx, SUM(rev) target_rev, COUNT(CASE WHEN qty > 0 THEN qty END) oa_qty_target
                FROM db_sales_plan
                WHERE jenis_produk = 'SMART AKUISISI' $nama_produk
                GROUP BY id_outlet)B
                ON A.id_digipos = B.id_outlet)A3
                ON A1.tap = A3.tap

                ORDER BY FIELD(hari_pjp, 'SENIN','SELASA','RABU','KAMIS','JUMAT','SABTU')";

        $resultQuery = $this->db->query($query);
		if($resultQuery){
			return $resultQuery->getResultArray();
		}
    }

    function data_summary_dashboard($tap,$nama_produk,$hari_pjp,$dm,$dm1){
        $query = "SELECT A1.tap tap,  'ALL' channel, 'ALL' pic,
                or_trx,oa_trx_m1,
                IFNULL(ROUND((oa_trx_m1/or_trx)*100,0),0) percent_oa_trx_m1,
                trx_m1,rev_m1,
                IFNULL(SUM(oa_qty_target),0) oa_trx_target,
                target_trx,target_rev,oa_trx_mtd,
                IFNULL(ROUND((oa_trx_mtd/or_trx)*100,0),0) percent_oa_trx_mtd,
                trx_mtd,IFNULL(ROUND((trx_mtd/target_trx)*100,1),0) ach_trx,
                rev_mtd,IFNULL(ROUND((rev_mtd/target_rev)*100,1),0) ach_rev,
                ROUND(((trx_mtd/$dm)/(trx_m1/$dm1))*100,1) rr_trx,
                ROUND(((rev_mtd/$dm)/(rev_m1/$dm1))*100,1) rr_rev,
                (trx_mtd - target_trx) gap_trx,
                (rev_mtd - target_rev) gap_rev
                FROM
                (SELECT tap,channel,pic, COUNT(hari_pjp) or_trx
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING' AND $hari_pjp AND $tap
                GROUP BY tap) A1
                LEFT JOIN
                (SELECT tap,channel,pic,
                COUNT(CASE WHEN grouping_periode = 'M-1' AND qty > 0 THEN qty END) oa_trx_m1,
                COUNT(CASE WHEN grouping_periode = 'M' AND qty > 0 THEN qty END) oa_trx_mtd,  
                IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN qty END),0) trx_m1,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0) trx_mtd,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN rev END),0) rev_m1,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0) rev_mtd
                FROM
                (SELECT id_digipos,outlet,tap,channel,pic,hari_pjp
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING' AND $hari_pjp AND $tap)A
                LEFT JOIN 
                (SELECT id_outlet, grouping_periode, SUM(qty) qty, SUM(rev) rev
                FROM db_st_nota
                WHERE jenis_produk = 'SMART AKUISISI' $nama_produk
                GROUP BY id_outlet, grouping_periode)B
                ON A.id_digipos = B.id_outlet
                GROUP BY tap)A2
                ON A1.tap = A2.tap
                LEFT JOIN
                (SELECT tap,channel,pic,SUM(target_trx) target_trx,
                SUM(target_rev) target_rev,SUM(oa_qty_target) oa_qty_target
                FROM
                (SELECT id_digipos,outlet,tap,channel,pic,hari_pjp
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING' AND $hari_pjp AND $tap)A
                LEFT JOIN
                (SELECT id_outlet,SUM(qty) target_trx, SUM(rev) target_rev, COUNT(CASE WHEN qty > 0 THEN qty END) oa_qty_target
                FROM db_sales_plan
                WHERE jenis_produk = 'SMART AKUISISI' $nama_produk
                GROUP BY id_outlet)B
                ON A.id_digipos = B.id_outlet
                GROUP BY tap)A3
                ON A1.tap = A3.tap
                GROUP BY A1.tap

                UNION ALL

                SELECT A1.tap, A1.channel, A1.pic,
                or_trx,oa_trx_m1,
                IFNULL(ROUND((oa_trx_m1/or_trx)*100,0),0) percent_oa_trx_m1,
                trx_m1,rev_m1,
                IFNULL(SUM(oa_qty_target),0) oa_trx_target,
                target_trx,target_rev,oa_trx_mtd,
                IFNULL(ROUND((oa_trx_mtd/or_trx)*100,0),0) percent_oa_trx_mtd,
                trx_mtd,IFNULL(ROUND((trx_mtd/target_trx)*100,1),0) ach_trx,
                rev_mtd,IFNULL(ROUND((rev_mtd/target_rev)*100,1),0) ach_rev,
                ROUND(((trx_mtd/$dm)/(trx_m1/$dm1))*100,1) rr_trx,
                ROUND(((rev_mtd/$dm)/(rev_m1/$dm1))*100,1) rr_rev,
                (trx_mtd - target_trx) gap_trx,
                (rev_mtd - target_rev) gap_rev
                FROM
                (SELECT tap,channel,pic, COUNT(hari_pjp) or_trx
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING' AND $hari_pjp AND $tap
                GROUP BY tap,channel,pic) A1
                LEFT JOIN
                (SELECT tap,channel,pic,
                COUNT(CASE WHEN grouping_periode = 'M-1' AND qty > 0 THEN qty END) oa_trx_m1,
                COUNT(CASE WHEN grouping_periode = 'M' AND qty > 0 THEN qty END) oa_trx_mtd,  
                IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN qty END),0) trx_m1,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0) trx_mtd,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN rev END),0) rev_m1,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0) rev_mtd
                FROM
                (SELECT id_digipos,outlet,tap,channel,pic,hari_pjp
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING' AND $hari_pjp AND $tap)A
                LEFT JOIN 
                (SELECT id_outlet, grouping_periode, SUM(qty) qty, SUM(rev) rev
                FROM db_st_nota
                WHERE jenis_produk = 'SMART AKUISISI' $nama_produk
                GROUP BY id_outlet, grouping_periode)B
                ON A.id_digipos = B.id_outlet
                GROUP BY tap,channel,pic)A2
                ON A1.pic = A2.pic
                LEFT JOIN
                (SELECT id_digipos,outlet,tap,channel,pic,SUM(target_trx) target_trx,
                SUM(target_rev) target_rev,SUM(oa_qty_target) oa_qty_target
                FROM
                (SELECT id_digipos,outlet,tap,channel,pic,hari_pjp
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING' AND $hari_pjp AND $tap)A
                LEFT JOIN
                (SELECT id_outlet,SUM(qty) target_trx, SUM(rev) target_rev, COUNT(CASE WHEN qty > 0 THEN qty END) oa_qty_target
                FROM db_sales_plan
                WHERE jenis_produk = 'SMART AKUISISI' $nama_produk
                GROUP BY id_outlet)B
                ON A.id_digipos = B.id_outlet
                GROUP BY tap,channel,pic)A3
                ON  A1.pic = A3.pic
                GROUP BY A1.tap, A1.channel, A1.pic
                
                UNION ALL
                
                SELECT A1.tap, A1.channel, A1.pic,
                or_trx,oa_trx_m1,
                IFNULL(ROUND((oa_trx_m1/or_trx)*100,0),0) percent_oa_trx_m1,
                trx_m1,rev_m1,
                IFNULL(SUM(oa_qty_target),0) oa_trx_target,
                target_trx,target_rev,oa_trx_mtd,
                IFNULL(ROUND((oa_trx_mtd/or_trx)*100,0),0) percent_oa_trx_mtd,
                trx_mtd,IFNULL(ROUND((trx_mtd/target_trx)*100,1),0) ach_trx,
                rev_mtd,IFNULL(ROUND((rev_mtd/target_rev)*100,1),0) ach_rev,
                ROUND(((trx_mtd/$dm)/(trx_m1/$dm1))*100,1) rr_trx,
                ROUND(((rev_mtd/$dm)/(rev_m1/$dm1))*100,1) rr_rev,
                (trx_mtd - target_trx) gap_trx,
                (rev_mtd - target_rev) gap_rev
                FROM
                (SELECT 'TOTAL' AS tap,'' AS channel,'' AS pic, COUNT(hari_pjp) or_trx
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING' AND $hari_pjp) A1
                LEFT JOIN
                (SELECT 'TOTAL' AS tap,'' AS channel,'' AS pic,
                COUNT(CASE WHEN grouping_periode = 'M-1' AND qty > 0 THEN qty END) oa_trx_m1,
                COUNT(CASE WHEN grouping_periode = 'M' AND qty > 0 THEN qty END) oa_trx_mtd,  
                IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN qty END),0) trx_m1,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN qty END),0) trx_mtd,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M-1' THEN rev END),0) rev_m1,
                IFNULL(SUM(CASE WHEN grouping_periode = 'M' THEN rev END),0) rev_mtd
                FROM
                (SELECT id_digipos,outlet,tap,channel,pic,hari_pjp
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING' AND $hari_pjp)A
                LEFT JOIN 
                (SELECT id_outlet, grouping_periode, SUM(qty) qty, SUM(rev) rev
                FROM db_st_nota
                WHERE jenis_produk = 'SMART AKUISISI' $nama_produk
                GROUP BY id_outlet, grouping_periode)B
                ON A.id_digipos = B.id_outlet)A2
                ON A1.tap = A2.tap
                LEFT JOIN
                (SELECT 'TOTAL' AS tap,'' AS channel,'' AS pic,
                SUM(target_trx) target_trx,SUM(target_rev) target_rev,SUM(oa_qty_target) oa_qty_target
                FROM
                (SELECT id_digipos,outlet,tap,channel,pic,hari_pjp
                FROM db_outlet
                WHERE UPPER(channel) = 'SF CHANNELING' AND $hari_pjp)A
                LEFT JOIN
                (SELECT id_outlet,SUM(qty) target_trx, SUM(rev) target_rev, COUNT(CASE WHEN qty > 0 THEN qty END) oa_qty_target
                FROM db_sales_plan
                WHERE jenis_produk = 'SMART AKUISISI' $nama_produk
                GROUP BY id_outlet)B
                ON A.id_digipos = B.id_outlet)A3
                ON A1.tap = A3.tap";

        $resultQuery = $this->db->query($query);
		if($resultQuery){
			return $resultQuery->getResultArray();
		}
    }
    /*end of query dashboard */
}
?>