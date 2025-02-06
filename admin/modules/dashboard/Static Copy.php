<?php
if(!defined('CMS_ADMIN')) {
	die();
}

include("page_header.php");

?>
<ul id="countrytabs" class="shadetabs">
<li><a href="#" rel="country1" class="selected">Nội dung</a></li>
<li><a href="#" rel="country2">Tài liệu</a></li>
<li><a href="#" rel="country3">Video</a></li>
</ul>

<div style="border:1px solid gray; margin-bottom: 1em; padding: 10px">
<div id="country1" class="tabcontent">

<?php

$start_date = date('Y-m-d'); // Give in your own start date
$start_day = date('z', strtotime($start_date)); // 6th of June


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
$key[$i] = $adname.'|'.$value[$i][$j];
echo $key[$i];
$i++;
}
}
$static = $key;
//$db->sql_query("UPDATE ".$prefix."_cache SET key='member_post' value='$value' WHERE catid='$catid[$i]'");
//$db->sql_query("INSERT INTO `".$prefix."_cache` (`key`, `value`, `time`) VALUES ('member_post','$value','".time()."')");
//end update cache

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

for ($j = 0; $j < 30; $j++) {
    $date = strtotime(date("Y-m-d", strtotime($start_date)) . " -$j day");
	echo "<td align=\"center\" class=\"row1sd\">";
    echo date('d-m', $date) .'';
	echo "</td>\n";
}
echo "</tr>\n";
$i =0;

while(list($idadmin, $adacc, $adname, $email, $permission, $mods, $last_login) = $db->sql_fetchrow($result)) {
	echo "<tr>\n";
	echo "<td class=\"row1\"><span class=\"$class\">$adname ($adacc)</span></td>\n";

	for ($j = 0; $j < 30; $j++) {
		$dateup = strtotime(date("Y-m-d", strtotime($start_date)) . " -$j day");
		$dayup = date('Y-m-d', $dateup);
		$sqlup="SELECT count(*) AS dem FROM ".$prefix."_news WHERE active=1 AND user_id=$idadmin AND '$dayup'=DATE(FROM_UNIXTIME(time)) ";
		//$die($sqlup);
		$resultup = $db->sql_query($sqlup);
		if($db->sql_numrows($resultup) > 0) {
			list($dem) = $db->sql_fetchrow($resultup);
			echo "<td align=\"center\" class=\"row1\">";
			if($dem==0)
				echo "-";
			else
				echo $dem;
			echo "</td>\n";
		}
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
?>
</div>

<div id="country2" class="tabcontent">
<?php
	$resultdoc = $db->sql_query($sql);
	if($db->sql_numrows($resultdoc) > 0) {
		
	
echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
echo "<tr>\n";
echo "<td class=\"row1sd\">Thành viên quản trị</td>\n";

for ($j = 0; $j < 30; $j++) {
    $date = strtotime(date("Y-m-d", strtotime($start_date)) . " -$j day");
	echo "<td align=\"center\" class=\"row1sd\">";
    echo date('d-m', $date) .'';
	echo "</td>\n";
}
echo "</tr>\n";
$i =0;

while(list($idadmin, $adacc, $adname, $email, $permission, $mods, $last_login) = $db->sql_fetchrow($resultdoc)) {
	echo "<tr>\n";
	echo "<td class=\"row1\"><span class=\"$class\">$adname ($adacc)</span></td>\n";

	for ($j = 0; $j < 30; $j++) {
		$dateup_doc = strtotime(date("Y-m-d", strtotime($start_date)) . " -$j day");
		$dayup_doc = date('Y-m-d', $dateup_doc);
		$sqlup_doc="SELECT count(*) AS dem_doc FROM ".$prefix."_document WHERE active=1 AND uadmin=$idadmin AND '$dayup_doc'=DATE(FROM_UNIXTIME(time)) ";
		//$die($sqlup);
		$resultup_doc = $db->sql_query($sqlup_doc);
		if($db->sql_numrows($resultup_doc) > 0) {
			list($dem_doc) = $db->sql_fetchrow($resultup_doc);
			echo "<td align=\"center\" class=\"row1\">";
			if($dem_doc==0)
				echo "-";
			else
				echo $dem_doc;
			echo "</td>\n";
		}
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
?>
</div>

<div id="country3" class="tabcontent">
<?php
	$resultvideo = $db->sql_query($sql);
	if($db->sql_numrows($resultvideo) > 0) {
		
	
echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
echo "<tr>\n";
echo "<td class=\"row1sd\">Thành viên quản trị</td>\n";

for ($j = 0; $j < 30; $j++) {
    $date = strtotime(date("Y-m-d", strtotime($start_date)) . " -$j day");
	echo "<td align=\"center\" class=\"row1sd\">";
    echo date('d-m', $date) .'';
	echo "</td>\n";
}
echo "</tr>\n";
$i =0;

while(list($idadmin, $adacc, $adname, $email, $permission, $mods, $last_login) = $db->sql_fetchrow($resultvideo)) {
	echo "<tr>\n";
	echo "<td class=\"row1\"><span class=\"$class\">$adname ($adacc)</span></td>\n";

	for ($j = 0; $j < 30; $j++) {
		$dateup = strtotime(date("Y-m-d", strtotime($start_date)) . " -$j day");
		$dayup = date('Y-m-d', $dateup);
		$sqlup="SELECT count(*) AS dem FROM ".$prefix."_video WHERE active=1 AND user_id=$idadmin AND '$dayup'=DATE(FROM_UNIXTIME(time)) ";
		//$die($sqlup);
		$resultup = $db->sql_query($sqlup);
		if($db->sql_numrows($resultup) > 0) {
			list($dem) = $db->sql_fetchrow($resultup);
			echo "<td align=\"center\" class=\"row1\">";
			if($dem==0)
				echo "-";
			else
				echo $dem;
			echo "</td>\n";
		}
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
?>
</div>

</div>

<script type="text/javascript">

var countries=new ddtabcontent("countrytabs")
countries.setpersist(true)
countries.setselectedClassTarget("link") //"link" or "linkparent"
countries.init()

</script>

	
<?php
include_once("page_footer.php");
?>