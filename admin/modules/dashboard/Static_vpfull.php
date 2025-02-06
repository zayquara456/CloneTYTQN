<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");
$id = intval(isset($_GET['user_sale']) ? $_GET['user_sale'] : 0);
$ckmonth = isset($_GET["time"]) ? $_GET["time"] : date('Y-m');
$where=$vlink ="";
include_once("popup_header.php");

if(!empty($ckmonth))
{
	$where.="'$ckmonth'=DATE_FORMAT(FROM_UNIXTIME(time),'%Y-%m') AND ";
	$vlink.="&time=$ckmonth";
}
$sumdowndoc = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_document_order WHERE '$ckmonth'=DATE_FORMAT(FROM_UNIXTIME(time),'%Y-%m') AND price > 0"));
list($sumnapthethuc) = $db->sql_fetchrow($db->sql_query("SELECT SUM(price) as sumprice FROM ".$prefix."_napthe WHERE '$ckmonth'=DATE_FORMAT(FROM_UNIXTIME(time),'%Y-%m') AND price > 0"));
list($sumnapthe) = $db->sql_fetchrow($db->sql_query("SELECT SUM(price) as sumprice FROM ".$prefix."_document_order WHERE '$ckmonth'=DATE_FORMAT(FROM_UNIXTIME(time),'%Y-%m') AND price > 0"));
$sumnapthethuc=$sumnapthethuc*1000;
$sumnapthe=$sumnapthe*1000;
$sumtechno=$sumnapthe*20/100;
$summember=$sumnapthe*40/100;
$sumtvxd=$sumnapthe*40/100;
//thong tin du lieu bang 2
//list($sumnapthe) = $db->sql_fetchrow($db->sql_query("SELECT SUM(price) as sumprice FROM ".$prefix."_napthe WHERE '$ckmonth'=DATE_FORMAT(FROM_UNIXTIME(time),'%Y-%m') AND price > 0"));

//$sumnapthe = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_napthe WHERE '$ckmonth'=DATE_FORMAT(FROM_UNIXTIME(time),'%Y-%m')"));
$sqlup="SELECT DATE_FORMAT(FROM_UNIXTIME(time),'%Y-%m') as dateline FROM ".$prefix."_document_order WHERE user_sale=$id GROUP BY dateline order by time DESC";
$resultup = $db->sql_query($sqlup);
		if($db->sql_numrows($resultup) > 0) {
echo '<div style="padding:10px"><div class="fl"><form action="" name="frmtool" method="get">
	<input type="hidden" name="f" value="dashboard" /><input type="hidden" name="do" value="static_user_epdetail" /><input type="hidden" name="user_sale" value="'.$id .'" />';
echo "Tra cứu theo tháng: <select id=\"time\" name=\"time\">";
//echo "<option value=\"\">Tất cả</option>\n";
while(list($dateline) = $db->sql_fetchrow($resultup)){
	if($ckmonth==$dateline)
		echo "<option value=\"$dateline\" selected>$dateline</option>\n";
	else
		echo "<option value=\"$dateline\" >$dateline</option>\n";
	}
echo '</select><input type="submit" class="button2" value="Tìm kiếm"  name="subs" /></form></div>';
echo '<div class="fr">';
//dem tong so tien da tai
	//dem tong so tai lieu da tai
	$sqltotallog="SELECT SUM(price) as sumprice, count(*) AS totaldownload FROM ".$prefix."_document_order WHERE $where user_sale=$id";
		$resulttotallog = $db->sql_query($sqltotallog);
		if($db->sql_numrows($resulttotallog) > 0) {
			list($sumprice,$totaldownload) = $db->sql_fetchrow($resulttotallog);
		echo "<h4 id=\"total-download\"><i class=\"fa fa-cloud-download\"></i> ".bsVndDot($totaldownload)." Lượt tải | <i class=\"fa fa-money\"></i> ".bsVndDot($sumprice)." VP</h4>";	
		}
echo '</div><div class="cl"></div></div>';
}
?>
<?php
	$totaldown='';
	$totalvp ='';
	$totalthuclinh='';
	$totaldoanhthu='';
	echo "<div id=\"pagecontent\">";
	echo "<div style=\"text-align:center; font-weight:bold; font-size:16px; padding-bottom:20px\"><h1>THỐNG KÊ TẢI TÀI LIỆU WEBSITE THUVIENXAYDUNG.NET</h1><span>Tháng ".date('m',strtotime($ckmonth))." năm ".date('Y',strtotime($ckmonth))."</div>";
	echo "<table border=\"1\" width=\"100%\" cellspacing=\"4\"  cellpadding=\"4\"  class=\"tableborder\">\n";
	echo "<tr><td colspan=\"31\" class=\"header\">I - Tổng lượng giao dịch</td></tr>";
	echo "<tr>\n";
			echo "<td align=\"center\" class=\"row1sd\" width=\"10px\">STT</td>\n";
			echo "<td align=\"center\" class=\"row1sd\">Tổng giao dịch</td>\n";
			echo "<td align=\"center\" class=\"row1sd\">Tổng sản lượng trên nhà mạng(vnđ)</td>\n";
			echo "<td align=\"center\" class=\"row1sd\">Tổng sản lượng đã giao dich(vnđ)</td>\n";
			echo "<td align=\"center\" class=\"row1sd\">Tổng sản lượng trả nhà mạng (vnđ)</td>\n";
			echo "<td align=\"center\" class=\"row1sd\">Tổng sản lượng thanh toán cho thành viên (vnđ)</td>\n";
			echo "<td align=\"center\" class=\"row1sd\">Tổng sản lượng thu về (vnđ)</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
			echo "<td align=\"center\" class=\"row1\">1</td>\n";
			echo "<td align=\"center\" class=\"row1\">".bsVndDot($sumdowndoc)."</td>\n";
			echo "<td align=\"center\" class=\"row1\">".bsVndDot($sumnapthethuc)."</td>\n";
			echo "<td align=\"center\" class=\"row1\">".bsVndDot($sumnapthe)."</td>\n";
			echo "<td align=\"center\" class=\"row1\">".bsVndDot($sumtechno)."</td>\n";
			echo "<td align=\"center\" class=\"row1\">".bsVndDot($summember)."</td>\n";
			echo "<td align=\"center\" class=\"row1\">".bsVndDot($sumtvxd)."</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
			echo "<td align=\"left\" colspan=\"31\"><a href=\"#bang11\">Chi tiết xem tại bảng 1.1</a></td>\n";
			echo "</tr>\n";
	echo "</table>";
	echo "<br>";
	echo "<table border=\"1\" width=\"100%\" cellspacing=\"4\"  cellpadding=\"4\" class=\"tableborder\">\n";
	echo "<tr><td colspan=\"31\" class=\"header\">II - Doanh thu thành viên</td></tr>";
	echo "<tr>\n";
			echo "<td align=\"center\" class=\"row1sd\" width=\"10px\">STT</td>\n";
			echo "<td align=\"center\" class=\"row1sd\">Tài khoản</td>\n";
			echo "<td align=\"center\" class=\"row1sd\" width=\"60px\">Lượt tải</td>\n";
			echo "<td align=\"center\" class=\"row1sd\" width=\"60px\">Sản lượng (VP)</td>\n";
			echo "<td align=\"center\" class=\"row1sd\" width=\"60px\">Chiết khấu (%)</td>\n";
			echo "<td align=\"center\" class=\"row1sd\" width=\"60px\">Thực thu (vnđ)</td>\n";
			echo "<td align=\"center\" class=\"row1sd\" width=\"60px\">Thống kê chi tiết</td>\n";
	echo "</tr>\n";
		$resultup = $db->sql_query("SELECT user_sale, documentid, SUM(price) as sumprice, COUNT(documentid) AS count_documentid FROM ".$prefix."_document_order WHERE price> 0 and '$ckmonth'=DATE_FORMAT(FROM_UNIXTIME(time),'%Y-%m') GROUP BY user_sale order by sumprice desc");
		if($db->sql_numrows($resultup) > 0) {
			$i=1;
		while(list($user_sale, $documentid, $sumprice, $count_documentid) = $db->sql_fetchrow($resultup)){
	echo "<tr>\n";
			$thuclinh=$sumprice*40000/100;
			echo "<td align=\"center\" class=\"row1\">$i</td>\n";
			echo "<td align=\"left\" class=\"row1\">".show_user($user_sale)."</td>\n";
			echo "<td align=\"center\" class=\"row1\">$count_documentid</td>\n";
			echo "<td align=\"center\" class=\"row1\">$sumprice</td>\n";
			echo "<td align=\"center\" class=\"row1\">40</td>\n";
			echo "<td align=\"right\" class=\"row1\">".bsVndDot($thuclinh)."</td>\n";
			echo "<td align=\"center\" class=\"row1\"><a href=\"#bang2$i\">Bảng 2.$i</a></td>\n";
	echo "</tr>\n";
	$i++;
	$totaldown=$totaldown+$count_documentid;
	$totalvp=$totalvp+$sumprice;
	$totalthuclinh=$thuclinh+$totalthuclinh;
		}
	}
		echo "<tr>\n";
			echo "<td align=\"right\" colspan=\"2\"><strong>Tổng</strong></td>\n";
			echo "<td align=\"center\" class=\"row1\"><strong>$totaldown</strong></td>\n";
			echo "<td align=\"center\" class=\"row1\"><strong>$totalvp</strong></td>\n";
			echo "<td align=\"center\" class=\"row1\"></td>\n";
			echo "<td align=\"right\" class=\"row1\"><strong>".bsVndDot($totalthuclinh)."</strong></td>\n";
			echo "</tr>\n";
	echo "</table>";
	echo "<br>";
	$totalthuclinh=0;
	$totaldoanhthu=0;
	echo "<table  id=\"bang11\" border=\"1\" width=\"100%\" cellspacing=\"4\"  cellpadding=\"4\" class=\"tableborder\">\n";
	echo "<tr><td colspan=\"31\" class=\"header\">Bảng 1.1 Thống kê chi tiết tổng lượng giao dịch</td></tr>";
	echo "<tr>\n";
			echo "<td align=\"center\" class=\"row1sd\" width=\"10px\">STT</td>\n";
			echo "<td align=\"center\" class=\"row1sd\">Loại thẻ cào</td>\n";
			echo "<td align=\"center\" class=\"row1sd\">Giá trị thẻ cào</td>\n";
			echo "<td align=\"center\" class=\"row1sd\" width=\"60px\">Số lượng</td>\n";
			echo "<td align=\"center\" class=\"row1sd\" width=\"60px\">Sản lượng</td>\n";
			echo "<td align=\"center\" class=\"row1sd\" width=\"60px\">Chiết khấu (%)</td>\n";
			echo "<td align=\"center\" class=\"row1sd\" width=\"60px\">Thực thu (vnđ)</td>\n";
	echo "</tr>\n";
		$resultup = $db->sql_query("SELECT telecom, SUM(price) as sumprice, COUNT(price), price FROM ".$prefix."_napthe WHERE price > 0 AND '$ckmonth'=DATE_FORMAT(FROM_UNIXTIME(time),'%Y-%m') GROUP BY price order by telecom, price desc");
		//die("SELECT telecom, SUM(price) as sumprice FROM ".$prefix."_napthe WHERE price > 0 AND '$ckmonth'=DATE_FORMAT(FROM_UNIXTIME(time),'%Y-%m') GROUP BY telecom order by telecom desc");
		if($db->sql_numrows($resultup) > 0) {
			$i=1;
		while(list($telecom, $sumprice, $countprice, $price) = $db->sql_fetchrow($resultup)){
	echo "<tr>\n";
			$sumprice=$sumprice*1000;
			$price=$price*1000;
			$chietkhau=show_chietkhau(show_telecom($telecom));
			$thuclinh=$sumprice*$chietkhau/100;
			echo "<td align=\"center\" class=\"row1\">$i</td>\n";
			echo "<td align=\"left\" class=\"row1\">".show_telecom($telecom)."</td>\n";
			echo "<td align=\"right\" class=\"row1\">".bsVndDot($price)."</td>\n";
			echo "<td align=\"right\" class=\"row1\">".bsVndDot($countprice)."</td>\n";
			echo "<td align=\"right\" class=\"row1\">".bsVndDot($sumprice)."</td>\n";
			echo "<td align=\"right\" class=\"row1\">$chietkhau</td>\n";
			echo "<td align=\"right\" class=\"row1\">".bsVndDot($thuclinh)."</td>\n";
	echo "</tr>\n";
	$i++;
	//$totaldown=$totaldown+$count_documentid;
	$totaldoanhthu=$totaldoanhthu+$sumprice;
	$totalthuclinh=$thuclinh+$totalthuclinh;
		}
	}
		echo "<tr>\n";
			echo "<td align=\"right\" colspan=\"4\"><strong>Tổng</strong></td>\n";
			//echo "<td align=\"center\" class=\"row1\"><strong>$totaldown</strong></td>\n";
			//echo "<td align=\"center\" class=\"row1\"><strong>$totalvp</strong></td>\n";
			echo "<td align=\"right\" class=\"row1\"><strong>".bsVndDot($totaldoanhthu)."</strong></td>\n";
			echo "<td align=\"center\" class=\"row1\"></td>\n";
			echo "<td align=\"right\" class=\"row1\"><strong>".bsVndDot($totalthuclinh)."</strong></td>\n";
			echo "</tr>\n";
	echo "</table>";
	echo "<br>";
	//danh sach bang thong ke chi tiet thanh vien
	$resultupsub = $db->sql_query("SELECT user_sale, documentid, SUM(price) as sumprice, COUNT(documentid) AS count_documentid FROM ".$prefix."_document_order WHERE price> 0 and '$ckmonth'=DATE_FORMAT(FROM_UNIXTIME(time),'%Y-%m') GROUP BY user_sale order by sumprice desc");
		if($db->sql_numrows($resultupsub) > 0) {
			$i=1;
		while(list($user_sale, $documentid, $sumprice, $count_documentid) = $db->sql_fetchrow($resultupsub)){
	echo "<table  id=\"bang2$i\" border=\"1\" width=\"100%\" cellspacing=\"4\"  cellpadding=\"4\" class=\"tableborder\">\n";
	echo "<tr><td colspan=\"31\" class=\"header\">Bảng 2.$i Thống kê chi tiết thực thu thành viên ".show_user($user_sale)."</td></tr>";
	echo "<tr>\n";
			echo "<td align=\"center\" class=\"row1sd\">STT</td>\n";
			echo "<td align=\"center\" class=\"row1sd\">Người tải</td>\n";
			echo "<td align=\"center\" class=\"row1sd\">Tài liệu</td>\n";
			echo "<td align=\"center\" class=\"row1sd\">Thời gian</td>\n";
			echo "<td align=\"center\" class=\"row1sd\">Sản lượng (VP)</td>\n";
			echo "<td align=\"center\" class=\"row1sd\">Chiết khấu (%)</td>\n";
			echo "<td align=\"center\" class=\"row1sd\">Thực thu (vnđ)</td>\n";
	echo "</tr>\n";
	$totalthucthu=0;
	$totalprice=0;
	$icount=1;
		$sqlup="SELECT id, user_buy, documentid, price, time FROM ".$prefix."_document_order WHERE price> 0 and '$ckmonth'=DATE_FORMAT(FROM_UNIXTIME(time),'%Y-%m') AND user_sale=$user_sale order by price DESC";
		//die($sqlup);
		$resultup = $db->sql_query($sqlup);
		if($db->sql_numrows($resultup) > 0) {
			while(list($id, $user_buy, $documentid, $price, $time) = $db->sql_fetchrow($resultup)){
				$thucthu=$price*40000/100;
			echo "<tr>\n";
			echo "<td align=\"center\" class=\"row1\" width=\"10px\">$icount</td>\n";
			echo "<td class=\"row1\" width=\"160\">".show_user($user_buy)."</td>\n";
			echo "<td class=\"row1\">".show_document($documentid)."</td>\n";
			echo "<td class=\"row1\" align=\"center\" width=\"120px\">".ext_time($time,2)."</td>\n";
			echo "<td class=\"row1\" align=\"right\"  width=\"60px\">".$price."</td>\n";
			echo "<td class=\"row1\" align=\"center\" width=\"60px\">40</td>\n";
			echo "<td class=\"row1\" align=\"right\" width=\"60px\">".bsVndDot($thucthu)."</td>\n";
			$icount++;
			$totalprice=$price+$totalprice;
			$totalthucthu=$thucthu+$totalthucthu;
			}	
		}
		//}
	//}
		echo "<tr>\n";
			echo "<td align=\"right\" class=\"row1\" colspan=\"4\"><strong>Tổng</strong></td>\n";
			echo "<td align=\"right\" class=\"row1\"><strong>".bsVndDot($totalprice)."</strong></td>\n";
			echo "<td align=\"right\" class=\"row1\"></td>\n";
			echo "<td align=\"right\" class=\"row1\"><strong>".bsVndDot($totalthucthu)."</strong></td>\n";
			echo "</tr>\n";
	echo "</table>";
	echo "<br>";
	$i++;
	}
	}
	echo "</div>";
include_once("popup_footer.php");
?>