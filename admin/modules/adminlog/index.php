<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}
$today = isset($_GET['today']) ? $_GET['today'] : "";
if(isset($_POST['delete']) && $_POST['delete'] !="") {
	$idx = $_POST['id'];
	foreach ($idx as $id) {
		$db->sql_query("DELETE FROM ".$prefix."_admin_log WHERE id='$id'");	
	}		
	header("Location: modules.php?f=$adm_modname");
	exit;
}	

if(isset($_POST['deleteall']) && $_POST['deleteall'] !="") {
	$db->sql_query("TRUNCATE TABLE ".$prefix."_admin_log");	
	header("Location: modules.php?f=$adm_modname");
	exit;
}	

include("page_header.php");

$perpage = 15;
$page = isset($_GET['page']) ? intval($_GET['page']) : (isset($_POST['page']) ? intval($_POST['page']) :1);
$from = isset($_GET["from"]) ? $_GET["from"] : "";
$to = isset($_GET["to"]) ? $_GET["to"] : "";
$action = isset($_GET["action"]) ? $_GET["action"] : "";
$offset = ($page-1) * $perpage;
$where="where alanguage='$currentlang' ";
$vlink="";

if(!empty($action))
{
	$where.="AND action LIKE '%$action%' ";
	$vlink.="&action=$action";
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
		$to=mktime(24,59,59,$match[2],$match[1],$match[3]);
	}
	$where.="AND dateline < $to ";
	$vlink.="&to=$to";
}
if($today=="Hôm nay")
{
	$month=date("m",time());
	$day=date("d",time());
	$year=date("Y",time());
	$from=mktime(0,0,0,$month,$day,$year);
	$to=mktime(0,0,0,$month,$day,$year);
	
	$where="where dateline > $from AND dateline < $to ";
	$vlink="&today=Hôm nay";
}
$total = $db->sql_numrows($db->sql_query("SELECT*FROM ".$prefix."_admin_log $where"));
$result = $db->sql_query("SELECT  id, adname, dateline, area, title, action, ip_add, alanguage  FROM ".$prefix."_admin_log $where ORDER BY dateline DESC LIMIT $offset, $perpage");

echo "<script language=\"javascript\" type=\"text/javascript\">\n";
echo "function check_uncheck(){\n";
echo "	var f= document.frm;\n";
echo "	if(f.checkall.checked){\n";
echo "		CheckAllCheckbox(f,'id[]');\n";
echo "	}else{\n";
echo "	UnCheckAllCheckbox(f,'id[]');\n";
echo "	}\n";
echo "}\n";
echo "</script>\n";
ajaxload_content();
?>
<script>
	$(function() {
		$( "#from" ).datepicker({
			defaultDate: "+1w",
			changeMonth: true,
			dateFormat: "dd-mm-yy",
			changeYear: true,
			onSelect: function( selectedDate ) {
				$( "#to" ).datepicker( "option", "minDate", selectedDate );
				
			}
		});
		$( "#to" ).datepicker({
			defaultDate: "+1w",
			changeMonth: true,
			dateFormat: "dd-mm-yy",
			changeYear: true,

			onSelect: function( selectedDate ) {
				$( "#from" ).datepicker( "option", "maxDate", selectedDate );
			}
		});
	});
	</script>
<div class="toolbar"><div>
<form action="" name="frmtool" method="get">
	<input type="hidden" name="f" value="adminlog" />
	<label for="action">Hành động</label>
	<input type="text" id="action" value="" name="action" />
	<label for="from">From</label>
	<input type="text" id="from" name="from"/>
	<label for="to">to</label>
	<input type="text" id="to" name="to"/>
	
	<input type="submit" class="button2" value="Tìm kiếm"  name="subs" />
	<input type="submit" class="button2" value="Hôm nay"  name="today" />
</form>
</div></div><!-- End demo -->
<?php
if($db->sql_numrows($result) > 0) {

echo "<div id=\"pagecontent\">";
echo "<div id=\"".$adm_modname."_main\"><form action=\"modules.php?f=$adm_modname&page=$page\" name=\"frm\" method=\"POST\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
echo "<tr>\n";
echo "<tr><td colspan=\"9\" class=\"header\">"._MODTITLE."</td></tr>";
echo "<td class=\"row1sd\" width=\"10\"><input type=\"checkbox\" name=\"checkall\" onclick=\"javascript:check_uncheck();\" title=\""._CHECKALL."\"></td>\n";
echo "<td class=\"row1sd\"  width=\"80\">Administrators</td>\n";
echo "<td class=\"row1sd\" align=\"center\" width=\"100\">"._DATELINE."</td>\n";
echo "<td class=\"row1sd\" align=\"center\" width=\"70\">Khu vực</td>\n";
echo "<td class=\"row1sd\" align=\"center\">Hành động</td>\n";
echo "<td class=\"row1sd\" align=\"center\">Nội dung</td>\n";
echo "<td class=\"row1sd\" align=\"center\" width=\"80\">Địa chỉ IP</td>\n";
echo "<td class=\"row1sd\" align=\"center\" width=\"80\">"._LANGUAGE."</td>\n";
//echo "<td class=\"row1sd\" align=\"center\" width=\"30\">"._DELETE."</td>\n";
echo "</tr>\n";
$cur_ar = array(_VND,_USD);
$i =0;
while(list($id, $adname, $time, $area, $title, $action, $ip_add, $alanguage) = $db->sql_fetchrow($result)) {
if($i%2 == 1) { $css = "row1"; }	else { $css ="row1"; }	
echo "<tr>\n";
echo "<td class=\"$css\"><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td>";
echo "<td class=\"$css\"><a href=\"modules.php?f=authors&do=edit&acc=$adname\"><b>$adname</b></a></td>\n";
echo "<td class=\"$css\" align=\"center\">".ext_time($time, 2)."</td>\n";
echo "<td class=\"$css\" align=\"center\">".$area."</td>\n";
echo "<td class=\"$css\">$title</td>\n";
echo "<td class=\"$css\"><font color=\"red\">$action</font></td>\n";
echo "<td class=\"$css\" align=\"center\">$ip_add</td>\n";
echo "<td class=\"$css\" align=\"center\">$alanguage</td>\n";
//if($ajax_active == 1) {
//	echo "<td class=\"row2\" align=\"center\" width=\"30\"><a href=\"?f=$adm_modname&do=delete&id=$id\" title=\""._DELETE."\" onclick=\"return aj_base_delete($id,'$adm_modname','"._DELETEASK."','','');\"><img border=\"0\" src=\"images/delete.png\"></td>\n";
//} else {
//	echo "<td class=\"row2\" align=\"center\" width=\"30\"><a href=\"?f=$adm_modname&do=delete&id=$id\" info=\""._DELETE."\" onclick=\"return confirm('"._DELETEASK."');\"><img border=\"0\" src=\"images/delete.png\"></td>\n";
//}	
echo "</tr>\n";
$i ++;	
}

echo "<tr><td class=\"row4\" colspan=\"9\">";
//echo "<input type=\"submit\" class=\"button2\" name=\"delete\" value=\""._QUICKDO_1."\"> <input  class=\"button2\" type=\"submit\" name=\"deleteall\" value=\""._DELETEALL."\">";
if($total > $perpage) {
	echo "<div class=\"fr\">";	
	$pageurl = "modules.php?f=".$adm_modname."$vlink";
	echo paging($total,$pageurl,$perpage,$page);
	echo "</div>";
}	
echo "</td></tr>";
echo "</table></form></div></div>";
	
}else{
	echo "<br/>";
	OpenDiv();
	echo "<center>"._NODATA."</center>";
	CLoseDiv();
}		

include_once("page_footer.php");

?>