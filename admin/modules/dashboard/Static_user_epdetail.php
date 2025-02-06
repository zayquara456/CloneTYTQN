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
echo "<link rel=\"stylesheet\" href=\"styles/styles.css\" />\n";


	echo "<div id=\"pagecontent\">";
	echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
	echo "<tr><td colspan=\"31\" class=\"header\">Bảng thống kê tổng lượt tải và tổng VP của ".show_user($id)." ($ckmonth) </td></tr>";
	echo "<tr>\n";
			echo "<td align=\"center\" class=\"row1sd\">STT</td>\n";
			echo "<td align=\"center\" class=\"row1sd\">Người tải</td>\n";
			
			echo "<td align=\"center\" class=\"row1sd\">lượt tải</td>\n";
			//echo "<td align=\"center\" class=\"row1sd\">Người đăng</td>\n";
			echo "<td align=\"center\" class=\"row1sd\">VP</td>\n";
			echo "<td width=\"100\" class=\"row1sd\">Thời gian</td>\n";
			echo "<td align=\"center\" class=\"row1sd\"></td>\n";
	echo "</tr>\n";
		echo "<tr>\n";
		$icount=1;
		$sqlup="SELECT id, user_buy, documentid, price, time FROM ".$prefix."_document_order WHERE $where user_sale=$id  order by price DESC";
		//die($sqlup);
		$resultup = $db->sql_query($sqlup);
		if($db->sql_numrows($resultup) > 0) {
			while(list($id, $user_buy, $documentid, $price, $time) = $db->sql_fetchrow($resultup)){
			echo "<tr>\n";
			echo "<td align=\"center\" class=\"row1\">$icount</td>\n";
			echo "<td class=\"row1\" width=\"160\">".show_user($user_buy)."</td>\n";
			
			
			echo "<td class=\"row1\">".show_document($documentid)."</td>\n";
			echo "<td class=\"row1\" align=\"right\" width=\"30\"><span class=\"badge\">".$price."</span></td>\n";
			echo "<td class=\"row1\" align=\"center\" width=\"120\">".ext_time($time,2)."</td>\n";
			echo "<td class=\"row1\" align=\"center\" width=\"30\"><a class=\"hasTooltip\" href=\"modules.php?f=dashboard&do=delete_docorder&id=$id\" title=\"Hủy lượt tải\"><i class=\"fa fa-trash-o fa-lg\"></i></a></td>\n";
			$icount++;
			}
			
			
		}
		else
		{
			echo "<tr>\n";
			echo "<td align=\"center\" colspan=\"10\" class=\"row1\">Thống kê đang cập nhật</td>\n";
			echo "</tr>\n";
		}
		//}
	//}
		echo "<tr>\n";
			echo "<td align=\"center\" class=\"row1\"></td>\n";
			echo "<td class=\"row1\"></td>\n";
			echo "<td class=\"row1\"></td>\n";
			echo "<td class=\"row1\"></td>\n";
			echo "<td class=\"row1\"></td>\n";
			echo "</tr>\n";
	echo "</table>";
	echo "</div>";
include_once("popup_footer.php");
?>