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
	echo "<tr><td colspan=\"31\" class=\"header\">Bảng thống kê tổng lượt tải và tổng ep</td></tr>";
	echo "<tr>\n";
			echo "<td align=\"center\" class=\"row1sd\">STT</td>\n";
			echo "<td align=\"center\" class=\"row1sd\">ID</td>\n";
			echo "<td align=\"center\" class=\"row1sd\">Người đăng</td>\n";
			echo "<td align=\"center\" class=\"row1sd\">Số lượt tải</td>\n";
			//echo "<td align=\"center\" class=\"row1sd\">Người đăng</td>\n";
			echo "<td align=\"center\" class=\"row1sd\">EP</td>\n";
			echo "<td align=\"center\" class=\"row1sd\">Chức năng</td>\n";
	echo "</tr>\n";
		echo "<tr>\n";
		$icount=1;
		$datebe = strtotime($begindate);
		$dateup = strtotime(date("Y-m-d", strtotime($begindate)) . " +1 month");
		$sqlup="SELECT user_sale, documentid, SUM(price) as sumprice, COUNT(documentid) AS count_documentid FROM ".$prefix."_document_order WHERE user_buy<>1 AND user_buy<>94 AND user_buy<>167 AND user_buy<>116 GROUP BY user_sale order by sumprice desc";
		//die($sqlup);
		$resultup = $db->sql_query($sqlup);
		if($db->sql_numrows($resultup) > 0) {
			while(list($user_sale, $documentid, $sumprice, $count_documentid) = $db->sql_fetchrow($resultup)){
			echo "<tr>\n";
			echo "<td align=\"center\" class=\"row1\">$icount</td>\n";
			echo "<td class=\"row1\">$user_sale</td>\n";
			echo "<td class=\"row1\">".show_user($user_sale)."</td>\n";
			echo "<td class=\"row1\">".$count_documentid."</td>\n";
			echo "<td class=\"row1\">$sumprice</td>\n";
			echo "<td class=\"row1\"><a target=\"_blank\" href=\"modules.php?f=dashboard&do=static_user_epdetail&user_sale=$user_sale\">Xem chi tiết</a></td>\n";
			echo "</tr>\n";
			$total_money=$total_money+$sumprice;
			$total_download=$total_download+$count_documentid;
			$icount++;
			}
			
			
		}
		//}
	//}
		echo "<tr>\n";
			echo "<td align=\"center\" class=\"row1\"></td>\n";
			echo "<td class=\"row1\"></td>\n";
			echo "<td class=\"row1\"></td>\n";
			echo "<td class=\"row1\">$total_download</td>\n";
			echo "<td class=\"row1\">$total_money</td>\n";
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