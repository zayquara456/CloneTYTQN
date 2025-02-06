<?php
if (!defined('CMS_SYSTEM')) die();
if (!defined('iS_USER') || !isset($userInfo)) header("Location: ".url_sid("index.php?f=user&do=login")."");
$page_title="Lịch sử giao dịch";
include("header.php");
OpenTab("Lịch sử giao dịch");
$today = isset($_GET['today']) ? $_GET['today'] : 0;
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
echo "<div class=\"content\">";

$perpage = 15;
$page = isset($_GET['page']) ? intval($_GET['page']) : (isset($_POST['page']) ? intval($_POST['page']) :1);
$from = isset($_GET["from"]) ? $_GET["from"] : "";
$to = isset($_GET["to"]) ? $_GET["to"] : "";
$action = isset($_GET["action"]) ? $_GET["action"] : "";
$offset = ($page-1) * $perpage;

$where=" where user_id=".$userInfo['id']." ";
$vlink="";

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
if($today=="Hôm nay")
{
	$month=date("m",time());
	$day=date("d",time());
	$year=date("Y",time());
	$from=mktime(0,0,24,$month,$day-1,$year);
	$to=mktime(0,0,24,$month,$day+1,$year);
	
	$where="where dateline > $from AND dateline < $to ";
	$vlink="&today=Hôm nay";
}
$total = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_user_log WHERE user_id=".$userInfo['id']." "));
$result = $db->sql_query("SELECT  id, user_id, dateline, area, title, action, money, status, ip_add, alanguage  FROM ".$prefix."_user_log WHERE user_id=".$userInfo['id']." ORDER BY dateline DESC LIMIT $offset, $perpage");
?>

<?php
if($db->sql_numrows($result) > 0) {

echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
echo "<tr style=\"background:#f9f9f9\">\n";
echo "<td class=\"row1sd\" width=\"50\">Thời gian giao dịch</td>\n";
echo "<td class=\"row1sd\" width=\"50\">Hành động</td>\n";
echo "<td class=\"row1sd\"  align=\"right\" width=\"10\">(+/-)</td>\n";
echo "<td class=\"row1sd\" align=\"right\" width=\"50\">Số tiền (VNĐ)</td>\n";
echo "<td class=\"row1sd\"  width=\"200\" >Mô tả</td>\n";
echo "</tr>\n";
$cur_ar = array(_VND,_USD);
$i =0;
while(list($id, $user_id, $time, $area, $title, $action, $money, $status, $ip_add, $alanguage) = $db->sql_fetchrow($result)) {
if($i%2 == 1) {
	$css = "row1";
	$style_css="style=\"background:#f9f9f9;\"";
	}
else {
	$css ="row3";
	$style_css="style=\"background:#ffffff;\"";
}	
echo "<tr $style_css>\n";
echo "<td class=\"$css\">".ext_time($time, 2)."</td>\n";
echo "<td class=\"$css\">$title</td>\n";
echo "<td class=\"$css\"  align=\"right\">$status</td>\n";
echo "<td class=\"$css\" align=\"right\"><font color=\"red\">".bsVndDot($money)."</font></td>\n";
echo "<td class=\"$css\">$action</td>\n";
echo "</tr>\n";
$i ++;	
}


echo "<tr><td class=\"row4\" colspan=\"9\">";
if($total > $perpage) {
	echo "<div class=\"fr\">";	
	$pageurl = "index.php?f=napthe&do=history";
	echo paging($total,$pageurl,$perpage,$page);
	echo "</div>";
}	
echo "</td></tr>";
echo "</table></form>";
}else{
	echo "<center>Chưa phát sinh giao dịch.</center>";
}
echo "</div>";
CloseTab();
include("footer.php");
?>
