<?php

if(!defined('CMS_ADMIN')) die("Illegal File Access");

include_once("page_header.php");
$id = intval(isset($_GET['id']) ? $_GET['id'] : 0);
$text = $menhgia = $serial = $err_serial = $err_cat = $s_content = $err_code= $error ="";
$active = 1;
$err=0;

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
<div class="toolbar"><div>
<form action="" name="frmtool" method="get">
	<input type="hidden" name="f" value="dashboard" />
	<input type="hidden" name="do" value="static_user_download" />
	<label for="action">Tài khoản</label>
	<input type="text" id="s_name" value="" name="s_name"  style="width: 100px"/>
	<label for="action">Số lượng</label>
	<input type="text" id="s_quantity" value="20" style="width: 40px" name="s_quantity" />
	<input type="submit" class="button2" value="Tìm kiếm"  name="subs" />
	
</form>
</div></div><!-- End demo -->
<?php
echo "<div id=\"pagecontent\">";
$s_name = isset($_GET["s_name"]) ? $_GET["s_name"] : '';
$s_content = isset($_GET["s_content"]) ? $_GET["s_content"] : '';
$s_title = isset($_GET["s_title"]) ? $_GET["s_title"] : "";
$s_cat = isset($_GET["s_cat"]) ? $_GET["s_cat"] : "";
$s_quantity=isset($_GET["s_quantity"]) ? $_GET["s_quantity"] : 20;
$s_time=isset($_GET["s_time"]) ? $_GET["s_time"] : '';

$where="WHERE id > 0 ";
$vlink="";
if(!empty($s_name))
{
	$s_name=trim($s_name);
	$where.="AND user_id IN (SELECT id FROM {$prefix}_user WHERE fullname='$s_name')";
	$vlink.="&name=$s_name";
}
if(!empty($s_content))
{
	$s_content=trim($s_content);
	$where.="AND action LIKE '%$s_content%'";
	$vlink.="&content=$s_content";
}
if(!empty($s_title))
{
	$s_title=trim($s_title);
	$where.="AND title LIKE '%$s_title%'";
	$vlink.="&title=$s_title";
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
//if($s_time==0)
//{
//	$where.=" ORDER BY dateline DESC";
//	$vlink.="&$s_time=$s_time";
//}
//elseif($s_time==1)
//{	
//	$where.=" ORDER BY dateline ASC";
//	$vlink.="&$s_time=$s_time";
//}
	ajaxload_content();

	echo "<div id=\"".$adm_modname."_main\"><form action=\"modules.php?f=$adm_modname&do=$do\" name=\"frm\" method=\"POST\" onsubmit=\"return checkQuick(this);\">";
	echo "<div id=\"pagecontent\">";
	echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
	echo "<tr><td colspan=\"30\" class=\"header\">Bảng thống kê thành viên tải nhiều nhất</td></tr>";
	echo "<tr>\n";
	echo "<td align=\"center\" width=\"200\" class=\"row1sd\">Tên thành viên</td>\n";
	echo "<td align=\"center\" class=\"row1sd\">Lượt tải</td>\n";
	echo "<td align=\"center\" class=\"row1sd\">Ghi chú</td>\n";
	echo "</tr>\n";
	
	$result = $db->sql_query("SELECT  COUNT(id) as x, user_id, title FROM ".$prefix."_user_log $where AND title='tải tài liệu' GROUP BY user_id order by x desc LIMIT $s_quantity");
	if($db->sql_numrows($result) > 0 ) 
	{
		while(list($total, $user_id, $title) = $db->sql_fetchrow($result))
		{
			echo "<tr>\n";
			echo "<td>".show_user($user_id)."</td>";
		echo "<td><strong><a target=\"_blank\" href=\"modules.php?f=user&do=history&s_title=Tải+tài+liệu&s_name=".show_user($user_id)."&s_quantity=1000\" >$total lượt tải</a></strong></td>";
		echo "<td>".$title."</td>";
		echo "</tr>\n";
		}
		
	}
	
	
	//	echo "<tr>\n";
	//	for ($j = 0; $j < 30; $j++) {
	//	$dateup = strtotime(date("Y-m-d", strtotime($start_date)) . " -$j day");
	//	$dayup = date('Y-m-d', $dateup);
	//	$sqlup="SELECT count(*) AS dem FROM ".$prefix."_user_log WHERE title='Tải tài liệu' AND '$dayup'=DATE(FROM_UNIXTIME(dateline))";
	//	//$die($sqlup);
	//	$resultup = $db->sql_query($sqlup);
	//	if($db->sql_numrows($resultup) > 0) {
	//		list($dem) = $db->sql_fetchrow($resultup);
	//		echo "<td align=\"center\" class=\"row1\">$dem</td>\n";
	//	}
	//}
	//	echo "</tr>\n";
	echo "</table>";
	echo "</div>";
include_once("page_footer.php");
?>