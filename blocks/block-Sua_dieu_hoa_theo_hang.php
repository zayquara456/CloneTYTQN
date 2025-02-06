<?php
if (!defined('CMS_SYSTEM')) header("Location: index.php");

if(file_exists("data/config_news.php")) require("data/config_news.php");

$content ="";

global $path_upload, $mod_name, $id, $Default_Temp;

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
$result_lastnew = $db->sql_query("SELECT id, title, images, time, hometext FROM ".$prefix."_news WHERE active='1' AND catid='4' AND alanguage='$currentlang' $seld ORDER BY time DESC");
$numrows = $db->sql_numrows($result_lastnew);
if($numrows > 0) {
	$a=0;
	$content .= "<div class=\"div-block\">";
	$content .= "<div class=\"div-tblock\">{$correctArr[1]}</div>";
	$content .= "<div class=\"div-cblock\" style=\"padding-top:5px\">";
	while(list($idlast, $titlelast, $imageslast, $time, $hometext) = $db->sql_fetchrow($result_lastnew)) 
	{
		$get_path = get_path($time);
		$path_upload_img = "$path_upload/news/$get_path";
		$a++;
		$rwtitlelast = url_optimization($titlelast);
		$url_news_titlelast =url_sid("index.php?f=news&do=detail&id=$idlast&t=$rwtitlelast");
		//if(file_exists("$path_upload_img/$imageslast") && $imageslast !="") {
			//if (file_exists("$path_upload_img/thumb_".$imageslast)) $imageslast = "thumb_".$imageslast."";
			//$content .= "<table width=\"100%\"><tr><td width=\"50\" valign=\"top\"><a href=\"".url_sid("index.php?f=news&do=detail&id=$idlast")."\">".tj_thumbnail("$path_upload_img/$imageslast","$titlelast",70,50)."</a></td>";
			if ($a < $numrows) $border_bottom="border-bottom: 1px dotted #666";
			$content .= "<div style=\"$border_bottom; padding:4px\"><a href=\"$url_news_titlelast\" class=\"newsothers\">$titlelast</a></div>";
		//} else {
		//	$content .= "<td colspan=\"2\"><a href=\"".url_sid("index.php?f=news&do=detail&id=$idlast")."\" class=\"newsothers\">$titlelast</a></td>";
		//}
		//if ($a < $numrows) $content .= "<tr><td colspan=\"3\"  style=\"border-bottom: 1px dotted #666\"></td></tr>";
		//$content .= "</table>";
	}
	$content .= "</div></div>";
}
?>