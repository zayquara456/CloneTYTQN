<?php
if(!defined('CMS_ADMIN')) {
	die();
}

include("page_header.php");

$ckmonth = isset($_GET["time"]) ? $_GET["time"] : date('Y-m');
$start_date = date('Y-m-d'); // Give in your own start date
$start_day = date('z', strtotime($start_date)); // 6th of June
?>
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
		echo '<a class="fancybox fancybox.iframe hasTooltip" href="modules.php?f=dashboard&do=static_vnfull&time='.$dateline.'" title="">'.$dateline.'</a>';
	}
}
?>
</li></ul></li></ul>
</div>
<div class="cl"></div>
</div>
<?php
//begin update cache
$i==0;
if(defined('iS_SADMIN'))
{
	$sql="SELECT adacc, adname, email, permission, mods, last_login FROM ".$prefix."_admin WHERE permission<>0 ORDER BY permission DESC";
}
else
{
	$sql="SELECT id, adacc, adname, email, permission, mods, last_login FROM ".$prefix."_admin ORDER BY permission ASC";
}
	$result = $db->sql_query($sql);
	if($db->sql_numrows($result) > 0) {
while(list($idadmin, $adacc, $adname, $email, $permission, $mods, $last_login) = $db->sql_fetchrow($result)) {
	 //echo $adname;
for ($j = 0; $j < 30; $j++) {
    $date = strtotime(date("Y-m-d", strtotime($start_date)) . " -$j day");
   // echo date('d-m', $date) .'';
  
	$dayup = date('Y-m-d', $date);
	$sqlup="SELECT count(*) AS dem FROM ".$prefix."_news WHERE active=1 AND user_id=$idadmin AND '$dayup'=DATE(FROM_UNIXTIME(time)) ";
	
		$resultup = $db->sql_query($sqlup);
		if($db->sql_numrows($resultup) > 0) {
			list($dem) = $db->sql_fetchrow($resultup);
			//$value[$i][$j]=$dem;
			//echo $value[$i][$j];
			if($dem==0)
				$value[$i][$j]= "-";
			else
				$value[$i][$j]= $dem;
//			echo $value[$i][$j];
		}
	
	
}
//$key[$i] = $adname.'|'.$value[$i][$j];
//echo $key[$i];
$i++;
}
}
$static = $key;
//$db->sql_query("UPDATE ".$prefix."_cache SET key='member_post' value='$value' WHERE catid='$catid[$i]'");
//$db->sql_query("INSERT INTO `".$prefix."_cache` (`key`, `value`, `time`) VALUES ('member_post','$value','".time()."')");
//end update cache
//$sqlup="SELECT DATE_FORMAT(FROM_UNIXTIME(time),'%Y-%m') as dateline FROM ".$prefix."_document GROUP BY dateline order by time DESC";
////die("SELECT DATE_FORMAT(FROM_UNIXTIME(time),'%Y-%m') as dateline FROM ".$prefix."_document");
//$resultup = $db->sql_query($sqlup);
//		if($db->sql_numrows($resultup) > 0) {
//echo '<div style="padding:10px"><div class="fl"><form action="" name="frmtool" method="get">
//	<input type="hidden" name="f" value="dashboard" /><input type="hidden" name="do" value="static" /><input type="hidden" name="user_sale" value="" />';
//echo "Tra cứu theo tháng: <select id=\"time\" name=\"time\">";
////echo "<option value=\"\">Tất cả</option>\n";
//while(list($dateline) = $db->sql_fetchrow($resultup)){
//	if($ckmonth==$dateline)
//		echo "<option value=\"$dateline\" selected>$dateline</option>\n";
//	else
//		echo "<option value=\"$dateline\" >$dateline</option>\n";
//	}
//echo '</select><input type="submit" class="button2" value="Tìm kiếm"  name="subs" /></form></div>';
//		}
$sql="";
if(defined('iS_SADMIN'))
{
	$sql="SELECT adacc, adname, email, permission, mods, last_login FROM ".$prefix."_admin WHERE permission<>0  ORDER BY permission DESC";
}
else
{
	$sql="SELECT id, adacc, adname, email, permission, mods, last_login FROM ".$prefix."_admin ORDER BY permission ASC";
}
	$result = $db->sql_query($sql);
	if($db->sql_numrows($result) > 0) {
echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
echo "<tr>\n";
echo "<td class=\"row1sd\">Thành viên quản trị</td>\n";
$sqlup="SELECT DATE_FORMAT(FROM_UNIXTIME(time),'%Y-%m') as dateline FROM ".$prefix."_document GROUP BY dateline order by time DESC";
//die("SELECT DATE_FORMAT(FROM_UNIXTIME(time),'%Y-%m') as dateline FROM ".$prefix."_document");
$resultup = $db->sql_query($sqlup);
		if($db->sql_numrows($resultup) > 0) {
while(list($dateline) = $db->sql_fetchrow($resultup)){
echo "<td align=\"center\" class=\"row1sd\">";
 echo $dateline;
	echo "</td>\n";
	}
		}

//for ($j = 1; $j <= 12; $j++) {
//    //$date = strtotime(date("Y-m-d", strtotime($start_date)) . " -$j day");
//	echo "<td align=\"center\" class=\"row1sd\">";
//   // echo date('d-m', $date) .'';
//   echo $j;
//	echo "</td>\n";
//}
echo "</tr>\n";
$i =0;

while(list($idadmin, $adacc, $adname, $email, $permission, $mods, $last_login) = $db->sql_fetchrow($result)) {
	echo "<tr>\n";
	echo "<td class=\"row1\" width=\"300px\"><span class=\"\">$adname ($adacc)</span></td>\n";
$sqlup="SELECT DATE_FORMAT(FROM_UNIXTIME(time),'%Y-%m') as dateline FROM ".$prefix."_document GROUP BY dateline order by time DESC";
//die("SELECT DATE_FORMAT(FROM_UNIXTIME(time),'%Y-%m') as dateline FROM ".$prefix."_document");
$resultup = $db->sql_query($sqlup);
		if($db->sql_numrows($resultup) > 0) {
while(list($dateline) = $db->sql_fetchrow($resultup)){
	echo "<td align=\"center\" class=\"row1\">";
	if(countdoc($dateline,$idadmin)!=0)
	echo	countdoc($dateline,$idadmin);
	echo"</td>";
		//$dateup = strtotime(date("Y-m-d", strtotime($start_date)) . " -$j month");
		//$dayup = date('Y-m-d', $dateup);
		//$sqlup="SELECT count(*) AS dem FROM ".$prefix."_document WHERE uadmin=$idadmin AND '$dateline'=DATE_FORMAT(FROM_UNIXTIME(time),'%Y-%m')";
		//////$die($sqlup);
		//$resultup = $db->sql_query($sqlup);
		//if($db->sql_numrows($resultup) > 0) {
		//	list($dem) = $db->sql_fetchrow($resultup);
		//	echo "<td align=\"center\" class=\"row1\">";
		//	if($dem==0)
		//		echo "-";
		//	else
		//		echo $dem;
		//	echo "</td>\n";
		//}
	}
		}
		else
		{
			echo "<td align=\"center\" class=\"row1\"> </td>";
		}
	echo "</tr>\n";
	$i++;
}
echo "<td align=\"center\" class=\"row4\">&nbsp;</td>\n";
echo "</table>\n";
}
else
{
	echo "khong co gi";
}
function countdoc($time,$id)
{
	global $prefix,$db;
	$sqlup="SELECT count(*) AS dem FROM ".$prefix."_document WHERE uadmin=$id AND '$time'=DATE_FORMAT(FROM_UNIXTIME(time),'%Y-%m')";
	//die($sqlup);
	$resultup = $db->sql_query($sqlup);
	if($db->sql_numrows($resultup) > 0) {
		list($dem) = $db->sql_fetchrow($resultup);
		$dems=$dem;
		return $dems;
	}
}
//$dateup = strtotime(date("Y-m-d", strtotime($start_date)) . " -$j month");
		//$dayup = date('Y-m-d', $dateup);
		//$sqlup="SELECT count(*) AS dem FROM ".$prefix."_document WHERE uadmin=$idadmin AND '$dateline'=DATE_FORMAT(FROM_UNIXTIME(time),'%Y-%m')";
		//////$die($sqlup);
		//$resultup = $db->sql_query($sqlup);
		//if($db->sql_numrows($resultup) > 0) {
		//	list($dem) = $db->sql_fetchrow($resultup);
		//	echo "<td align=\"center\" class=\"row1\">";
		//	if($dem==0)
		//		echo "-";
		//	else
		//		echo $dem;
		//	echo "</td>\n";
		//}
include_once("page_footer.php");
?>