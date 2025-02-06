<?php
if (!defined('CMS_SYSTEM')) die();
$page_title="Danh sách tài liệu đăng tải";
if (!defined('iS_USER') || !isset($userInfo)) header("Location: ".url_sid("index.php?f=user&do=login")."");
list($totaldocument) = $db->sql_fetchrow($db->sql_query("SELECT count(id) AS totaldownload FROM ".$prefix."_document WHERE user_id=".$userInfo['id'].""));
if($totaldocument==0)
  header("Location: ".url_sid("index.php")."");
$id = $userInfo['id'];
$text = $menhgia = $serial = $err_serial = $err_cat = $s_content = $err_code= $error ="";
$active = 1;
$err=0;
$perpage = 30;
$page = intval(isset($_GET['page']) ? $_GET['page'] : (isset($_POST['page']) ? $_POST['page']:1));
$offset = ($page-1) * $perpage;
$ckmonth = isset($_GET["time"]) ? $_GET["time"] : date('Y-m');
$s_name = isset($_GET["s_name"]) ? $_GET["s_name"] : '';
$s_content = isset($_GET["s_content"]) ? $_GET["s_content"] : '';
$s_title = isset($_GET["s_title"]) ? $_GET["s_title"] : "";
$s_cat = isset($_GET["s_cat"]) ? $_GET["s_cat"] : "";
$s_quantity=isset($_GET["s_quantity"]) ? $_GET["s_quantity"] : 20;
$s_time=isset($_GET["s_time"]) ? $_GET["s_time"] : '';
$start_date = date('Y-m-d'); // Give in your own start date
$start_day = date('z', strtotime($start_date)); // 6th of June
if(!empty($ckmonth))
{
	$where.="'$ckmonth'=DATE_FORMAT(FROM_UNIXTIME(time),'%Y-%m') AND ";
	$vlink.="&time=$ckmonth";
}
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
include_once("header.php");
//echo "<div class=\"highlight\">";
//		echo "<ul class=\"infouser\">";
//		echo "<li><i class=\"fa fa-user fa-5x\"></i></li><li><strong>".$userInfo['fullname']."</strong></li>";
//		echo "</ul>";
//		$dayup = date('Y-m');
//                $sqlup="SELECT DATE_FORMAT(FROM_UNIXTIME(time),'%Y-%m') as dateline FROM ".$prefix."_document_order WHERE user_sale=$id GROUP BY dateline order by time DESC";
//$resultup = $db->sql_query($sqlup);
//		if($db->sql_numrows($resultup) > 0) {
//         echo '<nav class="sort">
//<ul class="time">';
//echo '<form action="" name="frmtool" method="get">
//	<input type="hidden" name="f" value="user" />';
//echo "Tra cứu theo tháng: <br><select class=\"\" id=\"time\" name=\"time\">";
////echo "<option value=\"\">Tất cả</option>\n";
//while(list($dateline) = $db->sql_fetchrow($resultup)){
//	if($ckmonth==$dateline)
//		echo "<option value=\"$dateline\" selected>$dateline</option>\n";
//	else
//		echo "<option value=\"$dateline\" >$dateline</option>\n";
//	}
//echo '</select> <input type="submit" class="sb_but1" value="Thống kê"  name="subs" /></form>';
//}
//		 echo '</ul>
//			</nav>';
//	//dem tong so tien da tai
//	//dem tong so tai lieu da tai
//	$sqltotallog="SELECT SUM(price) as sumprice, count(*) AS totaldownload FROM ".$prefix."_document_order WHERE user_sale=".$userInfo['id']."";
//		$resulttotallog = $db->sql_query($sqltotallog);
//		if($db->sql_numrows($resulttotallog) > 0) {
//			list($sumprice,$totaldownload) = $db->sql_fetchrow($resulttotallog);
//		echo "<ul class=\"earnings simplelinks totals\">";
//			echo "<li class=\"views\"><h4 id=\"total-money\">".bsVndDot($sumprice)." VP</h4><h5 id=\"total-money_copy\">Tổng VP</h5></li>";
//			echo "<li class=\"total\"><h4 id=\"total-download\">".bsVndDot($totaldownload)."</h4><h5 id=\"total-download_copy\">Tổng lượt tải</h5></li>";
//			echo "</ul>\n";
//			
//		}
//        //dem tong so tien da tai thang
//	//dem tong so tai lieu da tai thang
//        $sqltotallogmonth="SELECT SUM(price) as sumprice, count(*) AS totaldownload FROM ".$prefix."_document_order WHERE '$ckmonth'=DATE_FORMAT(FROM_UNIXTIME(time),'%Y-%m') AND user_sale=".$userInfo['id']."";
//		$resulttotallogmonth = $db->sql_query($sqltotallogmonth);
//		if($db->sql_numrows($resulttotallogmonth) > 0) {
//			list($sumprice,$totaldownload) = $db->sql_fetchrow($resulttotallogmonth);
//		echo "<ul style=\"visibility: visible;\" class=\"earnings simplelinks\">";
//			echo "<li class=\"views\"><h4 id=\"summary-views\">".bsVndDot($sumprice)." VP</h4><h5 id=\"summary-views_copy\">Tổng VP tháng</h5></li>";
//			echo "<li class=\"total\"><h4 id=\"summary-earnings\">".bsVndDot($totaldownload)."</h4><h5 id=\"summary-earnings_copy\">Tổng lượt tải tháng</h5></li>";
//			echo "</ul>\n";
//			
//		}
//        
//	echo "<div class=\"cl\"></div></div><div class=\"cl\" style=\"padding-bottom:10px\"></div>";

?>
<?php
	echo "<div id=\"pagecontent\">";
    OpenTab("Bảng danh sách tài liệu đăng tải");
	echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
	echo "<tr>\n";
			echo "<td align=\"center\" class=\"row1sd\">STT</td>\n";
			//echo "<td align=\"center\" class=\"row1sd\">Người tải</td>\n";
			echo "<td align=\"center\" class=\"row1sd\">Số lượt tải</td>\n";
			echo "<td align=\"center\" class=\"row1sd\">Lượt xem</td>\n";
			echo "<td align=\"center\" class=\"row1sd\">Lượt tải</td>\n";
			echo "<td align=\"center\" class=\"row1sd\">EP</td>\n";
			echo "<td width=\"120\" class=\"row1sd\"  align=\"center\">Thời gian</td>\n";
	echo "</tr>\n";
		echo "<tr>\n";
		$icount=1;
		$datebe = strtotime($begindate);
		$dateup = strtotime(date("Y-m-d", strtotime($begindate)) . " +1 month");
		$total = $db->sql_numrows($db->sql_query("SELECT id FROM {$prefix}_document WHERE user_id=".$userInfo['id'].""));
		$sqlup="SELECT id, hits, hits_download, price, time FROM ".$prefix."_document WHERE user_id=".$userInfo['id']." order by time DESC LIMIT $offset, $perpage";
		//die($sqlup);
		$resultup = $db->sql_query($sqlup);
		if($db->sql_numrows($resultup) > 0) {
			if($page > 1) { $a = $perpage * $page - $perpage + 1;}
			while(list($id, $hits, $hits_download, $price, $time) = $db->sql_fetchrow($resultup)){
			$url_document_detail =url_sid("index.php?f=document&do=detail&id=$id");
			echo "<tr>\n";
			echo "<td align=\"center\" class=\"row1\">$icount</td>\n";
			//echo "<td class=\"row1\">".show_user($user_buy)."</td>\n";
			echo "<td class=\"row1\"><a target=\"_blank\" href=\"$url_document_detail\">".show_document($id)."</a></td>\n";
			echo "<td class=\"row1\"  align=\"right\">".$hits."</td>\n";
			echo "<td class=\"row1\"  align=\"right\">".$hits_download."</td>\n";
			echo "<td class=\"row1\"  align=\"right\">".$price."</td>\n";
			echo "<td class=\"row1\"  align=\"center\">".ext_time($time,2)."</td>\n";
         echo "<tr>\n";
		
			$total_money=$total_money+$price;
			$icount++;
			}
			 echo "<tr><td colspan=\"10\"><div class=\"fr\">";
	if($total > $perpage) {
		$pageurl = "index.php?f=user&do=document_post&sort=$sort";
		echo paging($total,$pageurl,$perpage,$page);
	}
		echo "</div>";
	echo "</td></tr>";
		}
      else
      {
         echo "<tr>\n";
			echo "<td align=\"center\" class=\"row1\">Chưa có thống kê...</td>\n";
         echo "<tr>\n";
      }

	echo "</table>";
    CloseTab();
	echo "</div>";

include_once("footer.php");
?>