<?php 
    /*region */
    function branchInCluster(){
        $region = array("dps" => "'BALI BARAT','BALI TENGAH','BALI TIMUR'",
                "kpg" => "'ENDE SIKKA','FLORES TIMUR','KUPANG ROTE','MALAKA TIMTIM BELU','MANGGARAI','SUMBA'",
                "mtr" => "'LOMBOK','SUMBAWA BARAT','SUMBAWA TIMUR'");

        return $region;
    }
    function branchInCity(){
        $region = array("dps" => "'BULELENG','JEMBRANA','TABANAN','BADUNG','KOTA DENPASAR','BANGLI','GIANYAR','KARANG ASEM','KLUNGKUNG'",
                "kpg" => "'ENDE','SIKKA','ALOR','FLORES TIMUR','LEMBATA','KOTA KUPANG','KUPANG','ROTE NDAO','BELU','MALAKA','TIMOR TENGAH SELATAN',	'TIMOR TENGAH UTARA','MANGGARAI','MANGGARAI BARAT','MANGGARAI TIMUR','NAGEKEO','NGADA','SABU RAIJUA','SUMBA BARAT','SUMBA BARAT DAYA','SUMBA TENGAH','SUMBA TIMUR'",
                "mtr" => "'KOTA MATARAM','LOMBOK BARAT','LOMBOK TENGAH','LOMBOK TIMUR','LOMBOK UTARA','SUMBAWA','SUMBAWA BARAT','BIMA','DOMPU','KOTA BIMA'");

        return $region;
    }
    function clusterInCity(){
        $region = array("bali_barat" => "'BULELENG','JEMBRANA','TABANAN'",
                "bali_tengah" => "'BADUNG','KOTA DENPASAR'",
                "bali_timur" => "'BANGLI','GIANYAR','KARANG ASEM','KLUNGKUNG'",
                "ende_sikka" => "'ENDE','SIKKA','ALOR','FLORES TIMUR','LEMBATA','KOTA KUPANG','KUPANG','ROTE NDAO','BELU','MALAKA','TIMOR TENGAH SELATAN',	'TIMOR TENGAH UTARA','MANGGARAI','MANGGARAI BARAT','MANGGARAI TIMUR','NAGEKEO','NGADA','SABU RAIJUA','SUMBA BARAT','SUMBA BARAT DAYA','SUMBA TENGAH','SUMBA TIMUR'",
                "flores_timur" => "'ALOR','FLORES TIMUR','LEMBATA'",
                "kupang_rote" => "'KOTA KUPANG','KUPANG','ROTE NDAO'",
                "malaka_timtim_belu" => "'BELU','MALAKA','TIMOR TENGAH SELATAN','TIMOR TENGAH UTARA'",
                "manggarai" => "'MANGGARAI','MANGGARAI BARAT','MANGGARAI TIMUR','NAGEKEO','NGADA'",
                "sumba" => "'SABU RAIJUA','SUMBA BARAT','SUMBA BARAT DAYA','SUMBA TENGAH','SUMBA TIMUR'",
                "lombok" => "'KOTA MATARAM','LOMBOK BARAT','LOMBOK TENGAH','LOMBOK TIMUR','LOMBOK UTARA'",
                "sumbawa_barat" => "'SUMBAWA','SUMBAWA BARAT'",
                "sumbawa_timur" => "'BIMA','DOMPU','KOTA BIMA'");
        return $region;
    }

    /*formatting*/
    function nf0($value)
	{
		if($value == null){
			$value = 0;
		}
		return number_format($value,0,'.',',');
	}
    function nf2($value)
	{
		if($value == null){
			$value = 0;
		}
		return number_format($value,2,'.',',');
	}
	
	function nf1($value)
	{
		if($value == null){
			$value = 0;
		}
		return number_format($value,1,'.',',');
	}
	
	function nfx($value)
	{
		if($value == null){
			$value = 0;
		}
		return number_format($value,0,',','');
	}
	
	function nfb($value)
	{
		if($value == null){
			$value = 0;
		}
		return  number_format($value / 1000000000, 2);
	}
	function nfb1($value)
	{
		if($value == null){
			$value = 0;
		}
		return  number_format($value / 1000000000, 1);
	}

	function nfb5($value)
	{
		if($value == null){
			$value = 0;
		}
		$value = round($value/1000000000,1);
		return number_format($value,1,'.',',');
	}

	function nfb5_v2($value)
	{
		if($value == null){
			$value = 0;
		}
		$value = round($value/1000000000,1);
		return number_format($value,1,'.','');
	}

	function nfb5_bn($value)
	{
		if($value == null){
			$value = 0;
		}
		$value = round($value/1000000000,2);
		return number_format($value,2,'.',',')." bn";
	}

	function nfb5_tn($value)
	{
		if($value == null){
			$value = 0;
		}
		$value = round($value/1000000000,1);
		return number_format($value,1,'.',',')." Tn";
	}

	function mio($value)
	{
		if($value == null){
			$value = 0;
		}
		$value = round($value/1000000,2);
		return number_format($value,2,'.',',')." mio";
	}

	function mio2($value)
	{
		if($value == null){
			$value = 0;
		}
		$value = round($value/1000000,0);
		return number_format($value,0,'.',',');
	}

	function mio3($value)
	{
		if($value == null){
			$value = 0;
		}
		$value = round($value/1000000,2);
		return number_format($value,2,'.',',');
	}

	function mio4($value)
	{
		if($value == null){
			$value = 0;
		}
		$value = round($value/1000000,4);
		return number_format($value,4,'.',',');
	}

	function mio5($value)
	{
		if($value == null){
			$value = 0;
		}
		$value = round($value/1000000,2);
		return number_format($value,0,'.',',')." mio";
	}
	
	function shorter($string)
	{
		$string = str_replace('INTERNATIONAL','INTL',$string);
		return $string;
	}

	function hilangnol($x)
	{
		if($x <= 12)
		{
			$data = array('01'=>'1','02'=>'2','03'=>'3','04'=>'4','05'=>'5','06'=>'6','07'=>'7','08'=>'8','09'=>'9','10'=>'10','11'=>'11','12'=>'12');
			return $data[$x];	
		}
		else
		{
			return $x;
		}	
	}
	
	function tambahnol($x)
	{
		if($x <= 12)
		{
			$data = array('1'=>'01','2'=>'02','3'=>'03','4'=>'04','5'=>'05','6'=>'06','7'=>'07','8'=>'08','9'=>'09','10'=>'10','11'=>'11','12'=>'12');
			return $data[$x];
		}
		else
		{
			return $x;
		}
	}

	#Day in Indonesian
	function day_indo($date)
	{
		$hari = array('1' => 'SENIN',
					'2' => 'SELASA',
					'3' => 'RABU',
					'4' => 'KAMIS',
					'5' => 'JUMAT',
					'6' => 'SABTU',
					'7' => 'MINGGU');

		$date_number = date('N',strtotime($date));

		return $hari[$date_number];
	}

	#Months 3 Letters
	function showbln($bln)
	{
		$bulan = array('1'=>'JAN','2'=>'FEB','3'=>'MAR','4'=>'APR','5'=>'MAY','6'=>'JUN',
		'7'=>'JUL','8'=>'AUG','9'=>'SEP','10'=>'OCT','11'=>'NOV','12'=>'DEC','13'=>'');
		return $bulan[$bln];
	}

	function showLongBln($bln){
		$bulan = [
			'January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret',
			'April' => 'April', 'May' => 'Mei', 'June' => 'Juni',
			'July' => 'Juli', 'August' => 'Agustus', 'September' => 'September',
			'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'
		];
		return $bulan[$bln];		
	}
	//safe division by 0
	if (!function_exists('safe_percent')) {
		function safe_percent($numerator, $denominator, $decimals = 2, $suffix = '%')
		{
			if ($denominator == 0 || $denominator === null) {
				return '0' . $suffix;
			}
	
			$percent = ($numerator / $denominator) * 100;
			return number_format($percent, $decimals) . $suffix;
		}
	}
	function query_pic_list($tap){
		$db = \Config\Database::connect();
        $query = "SELECT 'ALL' pic FROM db_outlet 
                UNION 
                SELECT pic FROM db_outlet WHERE channel = 'SF CHANNELING' AND $tap GROUP BY pic";

        $resultQuery = $db->query($query);
		if($resultQuery){
			return $resultQuery->getResultArray();
		}
    }

    function query_tap_list(){
		$db = \Config\Database::connect('default');
        $query = "SELECT 'ALL' tap FROM db_outlet 
                UNION SELECT tap FROM db_outlet GROUP BY tap";

        $resultQuery = $db->query($query);
		if($resultQuery){
			return $resultQuery->getResultArray();
		}
    }
?>