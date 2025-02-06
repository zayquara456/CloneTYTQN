<?php

if(!defined('CMS_ADMIN')) die("Illegal File Access");

include_once("page_header.php");
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

	echo "<div id=\"".$adm_modname."_main\"><form action=\"modules.php?f=$adm_modname&do=$do\" name=\"frm\" method=\"POST\" onsubmit=\"return checkQuick(this);\">";
	echo "<div id=\"pagecontent\">";
	echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
	echo "<tr><td colspan=\"30\" class=\"header\">Bảng thống kê lượt tải theo ngày</td></tr>";
	echo "<tr>\n";
	for ($j = 0; $j < 30; $j++) {
		$date = strtotime(date("Y-m-d", strtotime($start_date)) . " -$j day");
		echo "<td align=\"center\" class=\"row1sd\">";
		echo date('d-m', $date) .'';
		echo "</td>\n";
	}

	echo "</tr>\n";
		echo "<tr>\n";
		for ($j = 0; $j < 30; $j++) {
		$dateup = strtotime(date("Y-m-d", strtotime($start_date)) . " -$j day");
		$dayup = date('Y-m-d', $dateup);
		$sqlup="SELECT count(*) AS dem FROM ".$prefix."_user_log WHERE title='Tải tài liệu' AND '$dayup'=DATE(FROM_UNIXTIME(dateline))";
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