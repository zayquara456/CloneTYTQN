<?php
if (!defined('CMS_SYSTEM')) header("Location: index.php");

if(file_exists("data/config_news.php")) require("data/config_news.php");

$content ="";

global $path_upload, $mod_name, $id, $Default_Temp,$urlsite;

if($mod_name == "news" && isset($id)) {
	$seld = "AND id!='$id'";
} else {
	$seld ="";
}
$bl_arr = array();
$bl_arr[] = $bl_l;
$bl_arr[] = $bl_r;
$basename = pathinfo(__FILE__, PATHINFO_BASENAME);
$correctArr = array();
for ($i = 0; $i < count($bl_arr); $i++) {
	for ($h = 0; $h < count($bl_arr[$i]); $h++) {
		$temp = explode("@", $bl_arr[$i][$h]);
		if (($temp[5] == $currentlang) && ($temp[6] == $basename)) {
			$correctArr = $temp;
			break;
		}
	}
}
$result_lastnew = $db->sql_query("SELECT id, title, images, time, hometext FROM ".$prefix."_news WHERE active='1' AND catid=4 AND alanguage='$currentlang' $seld ORDER BY time DESC LIMIT 5");
$numrows = $db->sql_numrows($result_lastnew);
if($numrows > 0) {
	$a=0;
	$content .= "<div class=\"div-block\">";
	$content .= "<div class=\"div-tblock\">{$correctArr[1]}</div>";
	$content .= "<div class=\"div-cblock\" style=\"padding-top:5px; text-align:center\">";
	while(list($idlast, $titlelast, $imageslast, $time, $hometext) = $db->sql_fetchrow($result_lastnew)) 
	{
		$get_path = get_path($time);
		$path_upload_img = "$path_upload/news/$get_path";
		$a++;
		if($imageslast !="" && file_exists("$path_upload_img/$imageslast")) 
		{
			$imageslast = $urlsite."/".resizeImages("$path_upload_img/$imageslast", "$path_upload_img/180x150_$imageslast" ,180,150);
			$imageslast= "<img title=\"$titlelast\" alt=\"$titlelast\" width=\"180\" src=\"$imageslast\"/>";
			
		}
		else
		{
			$imageslast ="";
		}
		$url_news_titlelast =url_sid("index.php?f=news&do=detail&id=$idlast");
		if ($a < $numrows) $border_bottom="border-bottom: 1px dotted #f5f5f5";
		$content .= "<div style=\"$border_bottom; padding:4px\"><a href=\"$url_news_titlelast\" class=\"newsothers\">$imageslast<br/>$titlelast</a></div>";
	}
	$content .= "</div></div>";
}
?>