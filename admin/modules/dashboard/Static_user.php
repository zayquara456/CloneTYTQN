<?php

if(!defined('CMS_ADMIN')) die("Illegal File Access");


$id = intval(isset($_GET['id']) ? $_GET['id'] : 0);
$text = $menhgia = $serial = $err_serial = $err_cat = $s_content = $err_code= $error ="";
$active = 1;
$err=0;

$total_money = $total_download = '';
$s_name = isset($_GET["s_name"]) ? $_GET["s_name"] : '';
$s_content = isset($_GET["s_content"]) ? $_GET["s_content"] : '';
$s_title = isset($_GET["s_title"]) ? $_GET["s_title"] : "";
$s_cat = isset($_GET["s_cat"]) ? $_GET["s_cat"] : "";
$s_quantity=isset($_GET["s_quantity"]) ? $_GET["s_quantity"] : 20;
$s_time=isset($_GET["s_time"]) ? $_GET["s_time"] : '';
$start_date = date('Y-m-d'); // Give in your own start date
$start_day = date('z', strtotime($start_date)); // 6th of June
$where="WHERE id > 0 ";
$vlink="";
if(!empty($s_name))
{
	$s_name=trim($s_name);
	$where.="AND user_sale IN (SELECT id FROM {$prefix}_user WHERE fullname='$s_name')";
	$vlink.="&name=$s_name";
}
if(!empty($from))
{
	if(preg_match("/^([0-9]{1,2})\-([0-9]{1,2})\-([0-9]{4})$/",$from,$match)){
		$from=mktime(0,0,0,$match[2],$match[1],$match[3]);
	}
	$where.="AND dateline >= $from ";
	$vlink.="&from=$from";
}
if(!empty($to))
{
	if(preg_match("/^([0-9]{1,2})\-([0-9]{1,2})\-([0-9]{4})$/",$to,$match)){
		$to=mktime(0,0,0,$match[2],$match[1],$match[3]);
	}
	$where.="AND dateline < $to ";
	$vlink.="&to=$to";
}
include_once("page_header.php");
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
	$doc='';
	$sqltotal="SELECT COUNT(*) AS doc FROM ".$prefix."_document";
		$resulttotal = $db->sql_query($sqltotal);
		if($db->sql_numrows($resulttotal) > 0) {
			list($doc) = $db->sql_fetchrow($resulttotal);
			$doc=$doc;
		}
	$sqltotallog="SELECT SUM(price) as sumprice, count(*) AS totaldownload FROM ".$prefix."_document_order";
		$resulttotallog = $db->sql_query($sqltotallog);
		if($db->sql_numrows($resulttotallog) > 0) {
			list($sumprice,$totaldownload) = $db->sql_fetchrow($resulttotallog);
		echo "<ul class=\"earnings simplelinks totals\">";
		echo "<li class=\"views\"><h4 id=\"total-money\"><i class=\"fa fa-book\"></i> ".bsVndDot($doc)."</h4><h5 id=\"total-money_copy\">Tổng tài liệu</h5></li>";
		echo "<li class=\"total\"><h4 id=\"total-download\"><i class=\"fa fa-cloud-download\"></i> ".bsVndDot($totaldownload)."</h4><h5 id=\"total-download_copy\">Tổng lượt tải</h5></li>";
			echo "<li class=\"views\"><h4 id=\"total-money\"><i class=\"fa fa-money\"></i> ".bsVndDot($sumprice)." VP</h4><h5 id=\"total-money_copy\">Tổng VP</h5></li>";
			
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
	echo "<div class=\"cl\"></div></div>";
?>
<script language="javascript" type="text/javascript">
	function check_uncheck(){
		var f= document.frm;
		if(f.checkall.checked){
			CheckAllCheckbox(f,'id[]');
		}else{
			UnCheckAllCheckbox(f,'id[]');
		}			
	}
		function checkQuick(f) {
			if(f.f.value =='') {
				f.f.focus();
				return false;
			}
			f.submit.disabled = true; 
			return true;		
		}	
		function checkQuickId(f) {
			if(f.id.value =='') {
				f.id.focus();
				return false;
			}
			f.submit.disabled = true; 
			return true;		
		}	
	$(function() {
		$( "#from" ).datepicker({
			defaultDate: "+1w",
			changeMonth: true,
			numberOfMonths: 1,
			dateFormat: "dd-mm-yy",
			onSelect: function( selectedDate ) {
				$( "#to" ).datepicker( "option", "minDate", selectedDate );
				
			}
		});
		$( "#to" ).datepicker({
			defaultDate: "+1w",
			changeMonth: true,
			numberOfMonths: 1,
			dateFormat: "dd-mm-yy",
			onSelect: function( selectedDate ) {
				$( "#from" ).datepicker( "option", "maxDate", selectedDate );
			}
		});
	});
	</script>
<div class="toolbar"><div class="fl">
<form action="" name="frmtool" method="get">
	<input type="hidden" name="f" value="dashboard" />
	<input type="hidden" name="do" value="static_user" />
	<label for="action">Tài khoản</label>
	<input type="text" id="s_name" value="" name="s_name"  style="width: 100px"/>
	<!--<label for="action">Số lượng</label>
	<input type="text" id="s_quantity" value="20" style="width: 40px" name="s_quantity" />-->
	<input type="submit" class="button2" value="Tìm kiếm"  name="subs" />

</form>
</div>
<div class="fl">
	<ul style="list-style-type: none"><li class="dropdown"><a href="#" class="button2 dropdown-toggle"  data-toggle="dropdown">Thống kê tháng</a><ul class="dropdown-menu"><li>
<?php
//$ckmonth = isset($_GET["time"]) ? $_GET["time"] : date('Y-m');
//$where=$vlink ="";
//if(!empty($ckmonth))
//{
//	$where.="'$ckmonth'=DATE_FORMAT(FROM_UNIXTIME(time),'%Y-%m') AND ";
//	$vlink.="&time=$ckmonth";
//}
$sqlup="SELECT DATE_FORMAT(FROM_UNIXTIME(time),'%Y-%m') as dateline FROM ".$prefix."_document_order GROUP BY dateline order by time DESC";
$resultup = $db->sql_query($sqlup);
		if($db->sql_numrows($resultup) > 0) {
while(list($dateline) = $db->sql_fetchrow($resultup)){
		//echo "<option value=\"$dateline\" >$dateline</option>\n";
		echo '<a class="fancybox fancybox.iframe hasTooltip" href="modules.php?f=dashboard&do=static_vpfull&time='.$dateline.'" title="">'.$dateline.'</a>';
	}
}
?>
</li></ul></li></ul>
</div>
<div class="cl"></div>
</div><!-- End demo -->
<?php
	echo "<div id=\"".$adm_modname."_main\"><form action=\"modules.php?f=$adm_modname&do=$do\" name=\"frm\" method=\"POST\" onsubmit=\"return checkQuick(this);\">";
	echo "<div id=\"pagecontent\">";
	echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
	echo "<tr><td colspan=\"31\" class=\"header\">Bảng thống kê tổng lượt tải và tổng VP</td></tr>";
	echo "<tr>\n";
			echo "<td align=\"center\" class=\"row1sd\">STT</td>\n";
			echo "<td align=\"center\" class=\"row1sd\">ID</td>\n";
			echo "<td align=\"center\" class=\"row1sd\">Người đăng</td>\n";
			echo "<td align=\"center\" class=\"row1sd\">Tài liệu</td>\n";
			echo "<td align=\"center\" class=\"row1sd\">Lượt tải</td>\n";
			//echo "<td align=\"center\" class=\"row1sd\">Người đăng</td>\n";
			echo "<td align=\"center\" class=\"row1sd\">VP</td>\n";
			echo "<td align=\"center\" class=\"row1sd\"></td>\n";
	echo "</tr>\n";
		echo "<tr>\n";
		$icount=1;
		$sqlup="SELECT user_sale, documentid, SUM(price) as sumprice, COUNT(documentid) AS count_documentid FROM ".$prefix."_document_order $where GROUP BY user_sale order by sumprice desc";
		//die($sqlup);
		$resultup = $db->sql_query($sqlup);
		if($db->sql_numrows($resultup) > 0) {
			while(list($user_sale, $documentid, $sumprice, $count_documentid) = $db->sql_fetchrow($resultup)){
			echo "<tr>\n";
			echo "<td class=\"row1\" align=\"center\" width=\"30\">$icount</td>\n";
			echo "<td class=\"row1\" align=\"center\" width=\"30\">$user_sale</td>\n";
			echo "<td class=\"row1\">".show_user($user_sale)."</td>\n";
			echo "<td class=\"row1\" align=\"right\" width=\"50\"><a class=\"fancybox fancybox.iframe hasTooltip\" href=\"modules.php?f=dashboard&do=static_user_document&user=$user_sale\" title=\"Xem chi tiết\">".count_document_byuserid($user_sale)." <i class=\"fa fa-eye\"></i></a></td>\n";
			echo "<td class=\"row1\" align=\"right\" width=\"50\">".$count_documentid."</td>\n";
			echo "<td class=\"row1\" align=\"right\" width=\"50\">$sumprice</td>\n";
			echo "<td class=\"row1\" align=\"center\" width=\"30\"><a class=\"fancybox fancybox.iframe hasTooltip\" href=\"modules.php?f=dashboard&do=static_user_epdetail&user_sale=$user_sale\" title=\"Xem chi tiết\"><i class=\"fa fa-list fa-lg\"></i></a></td>\n";
			echo "</tr>\n";
			$icount++;
			}
			
			
		}
		//}
	//}
		echo "<tr>\n";
			echo "<td align=\"center\" class=\"row1\"></td>\n";
			echo "<td class=\"row1\"> </td>\n";
			echo "<td class=\"row1\"></td>\n";
			echo "<td class=\"row1\"></td>\n";
			echo "<td class=\"row1\"></td>\n";
			echo "</tr>\n";
	echo "</table>";
	echo "</div>";
include_once("page_footer.php");
?>