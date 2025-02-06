<?php

if(!defined('CMS_ADMIN')) die("Illegal File Access");


$id = intval(isset($_GET['id']) ? $_GET['id'] : 0);
$text = $menhgia = $serial = $err_serial = $err_cat = $s_content = $err_code= $error ="";
$active = 1;
$err=0;


$s_name = isset($_GET["s_name"]) ? $_GET["s_name"] : '';
$s_content = isset($_GET["s_content"]) ? $_GET["s_content"] : '';
$s_title = isset($_GET["s_title"]) ? $_GET["s_title"] : "";
$s_cat = isset($_GET["s_cat"]) ? $_GET["s_cat"] : "";
$s_quantity=isset($_GET["s_quantity"]) ? $_GET["s_quantity"] : 20;
$s_time=isset($_GET["s_time"]) ? $_GET["s_time"] : '';
$start_date = date('Y-m-d'); // Give in your own start date
$start_day = date('z', strtotime($start_date)); // 6th of June

	echo "<script language=\"javascript\" type=\"text/javascript\">\n";
	echo "function check_uncheck(){\n";
	echo "	var f=document.frm;\n";
	echo "	if(f.checkall.checked){\n";
	echo "		CheckAllCheckbox(f,'id[]');\n";
	echo "	}else{\n";
	echo "		UnCheckAllCheckbox(f,'id[]');\n";
	echo "	}			\n";
	echo "}\n";
	echo "	function checkQuick(f) {\n";
	echo "		if(f.fc.value =='') {\n";
	echo "			f.fc.focus();\n";
	echo "			return false;\n";
	echo "		}\n";
	echo "		f.submit.disabled = true; \n";
	echo "		return true;		\n";
	echo "	}	\n";
	echo "	function checkQuickId(f) {\n";
	echo "		if(f.id.value =='') {\n";
	echo "			f.id.focus();\n";
	echo "			return false;\n";
	echo "		}\n";
	echo "		f.submit.disabled = true; \n";
	echo "		return true;		\n";
	echo "	}	\n";
	echo "</script>\n";
	ajaxload_content();
echo "<div class=\"highlight\">";
		$dayup = date('Y-m');
	//dem tong so tien da tai
	//dem tong so tai lieu da tai
	$sqltotallog="SELECT SUM(money) as tmoney, count(*) AS tdownload FROM ".$prefix."_user_log WHERE title='Tải tài liệu' AND action  NOT LIKE '%vinhquangvip%'  AND action  NOT LIKE '%bachngoctung%'  AND action  NOT LIKE '%hanminhcuong%'  AND action  NOT LIKE '%kdoan%' AND '$dayup'= DATE_FORMAT(FROM_UNIXTIME(dateline),'%Y-%m')";
		$resulttotallog = $db->sql_query($sqltotallog);
		if($db->sql_numrows($resulttotallog) > 0) {
			list($tmoney,$tdownload) = $db->sql_fetchrow($resulttotallog);
		echo "<ul class=\"earnings simplelinks\">";
			echo "<li class=\"views\"><h4 id=\"summary-money\">".bsVndDot($tmoney)." EP</h4><h5 id=\"summary-money_copy\">Tổng EP</h5></li>";
			echo "<li class=\"total\"><h4 id=\"summary-download\">".bsVndDot($tdownload)."</h4><h5 id=\"summary-download_copy\">Tổng lượt tải</h5></li>";
			echo "</ul>\n";
			
		}
	//dem tong so tien da tai
	//dem tong so tai lieu da tai
	$sqltotallog="SELECT SUM(money) as tmoney, count(*) AS tdownload FROM ".$prefix."_user_log WHERE title='Tải tài liệu' AND action  NOT LIKE '%vinhquangvip%'  AND action  NOT LIKE '%bachngoctung%'  AND action  NOT LIKE '%hanminhcuong%'  AND action  NOT LIKE '%kdoan%'";
		$resulttotallog = $db->sql_query($sqltotallog);
		if($db->sql_numrows($resulttotallog) > 0) {
			list($tmoney,$tdownload) = $db->sql_fetchrow($resulttotallog);
		echo "<ul class=\"earnings simplelinks totals\">";
			echo "<li class=\"views\"><h4 id=\"total-money\">".bsVndDot($tmoney)." EP</h4><h5 id=\"total-money_copy\">Tổng EP</h5></li>";
			echo "<li class=\"total\"><h4 id=\"total-download\">".bsVndDot($tdownload)."</h4><h5 id=\"total-download_copy\">Tổng lượt tải</h5></li>";
			echo "</ul>\n";
			
		}
	//dem tong so luot view
	//dem tong so luot tai
	//dem tong so tai lieu
	$sqltotal="SELECT SUM(hits) AS tviews, SUM(hits_download) AS tdownload, SUM(price) as tprice, COUNT(*) AS tdocument FROM ".$prefix."_document";
		$resulttotal = $db->sql_query($sqltotal);
		if($db->sql_numrows($resulttotal) > 0) {
			list($tviews, $tdownload,$tprice, $tdocument) = $db->sql_fetchrow($resulttotal);
			
		}
		echo "<p class=\"totals rotate\">Tổng</p>";
	echo "<div class=\"cl\"></div></div>";
include_once("page_header.php");
	echo "<div id=\"".$adm_modname."_main\"><form action=\"modules.php?f=$adm_modname&do=$do\" name=\"frm\" method=\"POST\" onsubmit=\"return checkQuick(this);\">";
	echo "<div id=\"pagecontent\">";
	echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
	echo "<tr><td colspan=\"31\" class=\"header\">Bảng thống kê lượt tải tháng ".date('m-Y')."</td></tr>";
	echo "<tr>\n";
	
	$begindate=date('Y-m-01');
	for ($j = 0; $j <= 31; $j++) {
		$date = strtotime(date("Y-m-d", strtotime($begindate)) . " +$j day");
		
		if(date('m',$date)==date('m'))
		{
			$dates = date('d', $date);
			echo "<td align=\"center\" class=\"row1sd\">";
			echo  $dates;
			echo "</td>\n";
		}
		
	}
	echo "</tr>\n";
		echo "<tr>\n";
		for ($j = 0; $j <= 31; $j++) {
		$dateup = strtotime(date("Y-m-d", strtotime($begindate)) . " +$j day");
		if(date('m',$dateup)==date('m')){
			$dayup = date('Y-m-d', $dateup);
		
		$sqlup="SELECT count(*) AS dem FROM ".$prefix."_user_log WHERE title='Tải tài liệu' AND '$dayup'=DATE(FROM_UNIXTIME(dateline))";
		//$die($sqlup);
		$resultup = $db->sql_query($sqlup);
		if($db->sql_numrows($resultup) > 0) {
			list($dem) = $db->sql_fetchrow($resultup);
			if($dem=="0"){$dem="-";}
			else{$dem=$dem;}
			echo "<td align=\"center\" class=\"row1\">$dem</td>\n";
		}
		}
	}
		echo "</tr>\n";
	echo "</table>";
	echo "</div>";
	echo "<br/>";
	echo "<div id=\"pagecontent\">";
	echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
	echo "<tr><td colspan=\"31\" class=\"header\">Bảng thống kê chi tiết lượt tải tháng ".date('m-Y')."</td></tr>";
	echo "<tr>\n";
			echo "<td align=\"center\" class=\"row1sd\">STT</td>\n";
			echo "<td align=\"center\" class=\"row1sd\">Người đăng</td>\n";
			echo "<td align=\"center\" class=\"row1sd\">Số tài liệu</td>\n";
			echo "<td align=\"center\" class=\"row1sd\">EP</td>\n";
	echo "</tr>\n";
		echo "<tr>\n";
		$ic=1;
		for ($j = 0; $j <= 31; $j++) {
		$dateup = strtotime(date("Y-m-d", strtotime($begindate)) . " +$j day");
		if(date('m',$dateup)==date('m')){
			$dayup = date('Y-m-d', $dateup);
		
		$sqlup="SELECT d.user_id, ul.id, ul.user_id, ul.dateline, ul.money,  COUNT(ul.action) as countaction, SUM(ul.money) as summoney FROM ".$prefix."_user_log as ul, ".$prefix."_document as d WHERE d.title=ul.action AND ul.title='Tải tài liệu' AND '$dayup'=DATE(FROM_UNIXTIME(ul.dateline)) GROUP BY d.user_id";
		//$die($sqlup);
		$resultup = $db->sql_query($sqlup);
		//$action_arr[]="";
		
		//$total_money=0;
		if($db->sql_numrows($resultup) > 0) {
			while(list($duser_id, $id, $user_id, $dateline, $money,  $countaction, $summoney) = $db->sql_fetchrow($resultup)){
			$action2 = trim(preg_replace("/.*? tải tài liệu/", "", $action));
			//$db->sql_query("UPDATE ".$prefix."_user_log SET action='$action2' WHERE title='Tải tài liệu' AND id=$id");
			echo "<tr>\n";
			echo "<td align=\"center\" class=\"row1\">$ic</td>\n";
			echo "<td class=\"row1\">".show_user($duser_id)."</td>\n";
			echo "<td class=\"row1\">$countaction</td>\n";
			echo "<td class=\"row1\">$summoney</td>\n";
			echo "</tr>\n";
			$total_money=$total_money+$summoney;
			$ic++;
			}
			
			
		}
		}
	}
		echo "<tr>\n";
			echo "<td align=\"center\" class=\"row1\"></td>\n";
			echo "<td class=\"row1\"></td>\n";
			echo "<td class=\"row1\"></td>\n";
			echo "<td class=\"row1\">$total_money</td>\n";
			echo "</tr>\n";
	echo "</table>";
	echo "</div>";
	echo "<br>";
	$start_date = date('Y-m-01'); // Give in your own start date
	echo "<div id=\"pagecontent\">";
	echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
	echo "<tr><td colspan=\"12\" class=\"header\">Bảng thống kê lượt tải theo tháng</td></tr>";
	echo "<tr>\n";
	for ($j = 0; $j < 12; $j++) {
		$date = strtotime(date("Y-m", strtotime($start_date)) . " -$j month");
		echo "<td align=\"center\" class=\"row1sd\">";
		echo date('m', $date) .'';
		echo "</td>\n";
	}

	echo "</tr>\n";
		echo "<tr>\n";
		for ($j = 0; $j < 12; $j++) {
		$dateup = strtotime(date("Y-m", strtotime($start_date)) . " -$j month");
		$dayup = date('Y-m', $dateup);
		$sqlup="SELECT count(*) AS dem FROM ".$prefix."_user_log WHERE title='Tải tài liệu' AND '$dayup'= DATE_FORMAT(FROM_UNIXTIME(dateline),'%Y-%m')";
		//$die($sqlup);
		$resultup = $db->sql_query($sqlup);
		if($db->sql_numrows($resultup) > 0) {
			list($dem) = $db->sql_fetchrow($resultup);
			echo "<td align=\"center\" class=\"row1\">$dem</td>\n";
		}
	}
		echo "</tr>\n";
	echo "</table>";
	echo "</div>";
	echo "<br>";
	$start_date = date('Y-01-01'); // Give in your own start date
	echo "<div id=\"pagecontent\">";
	echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
	echo "<tr><td colspan=\"12\" class=\"header\">Bảng thống kê lượt tải theo năm</td></tr>";
	echo "<tr>\n";
	for ($j = 0; $j < 3; $j++) {
		$date = strtotime(date("Y", strtotime($start_date)) . " -$j year");
		echo "<td align=\"center\" class=\"row1sd\">";
		echo date('Y', $date) .'';
		echo "</td>\n";
	}

	echo "</tr>\n";
		echo "<tr>\n";
		for ($j = 0; $j < 3; $j++) {
		$dateup = strtotime(date("Y", strtotime($start_date)) . " -$j year");
		$dayup = date('Y', $dateup);
		$sqlup="SELECT count(*) AS dem FROM ".$prefix."_user_log WHERE title='Tải tài liệu' AND '$dayup'= DATE_FORMAT(FROM_UNIXTIME(dateline),'%Y')";
		//$die($sqlup);
		$resultup = $db->sql_query($sqlup);
		if($db->sql_numrows($resultup) > 0) {
			list($dem) = $db->sql_fetchrow($resultup);
			echo "<td align=\"center\" class=\"row1\">$dem</td>\n";
		}
	}
		echo "</tr>\n";
	echo "</table>";
	echo "</div>";
		
$startDate = strtotime($start_date);
$endDate   = strtotime("$endYear/$endMonth/01");

$currentDate = $endDate;

while ($currentDate >= $startDate) {
    echo date('Y/m',$currentDate);
    $currentDate = strtotime( date('Y/m/01/',$currentDate).' -1 month');
}
//}else{
//	
//}

include_once("page_footer.php");
?>