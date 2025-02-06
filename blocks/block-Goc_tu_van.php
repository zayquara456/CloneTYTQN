<?php
if (!defined('CMS_SYSTEM')) header("Location: index.php");
if(file_exists("data/config_news.php")) require("data/config_news.php");
global $path_upload, $mod_name, $id, $Default_Temp, $urlsite;

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
$content ="";
$result_lastnew = $db->sql_query("SELECT id, title, images, time, hometext FROM ".$prefix."_news WHERE  active='1' AND ( catid=31 or catid in (SELECT catid FROM {$prefix}_news_cat WHERE parent=31)) and alanguage='$currentlang' $seld ORDER BY time DESC LIMIT 6");
$numrows = $db->sql_numrows($result_lastnew);

	$a=0;
	$content .= "<div class=\"div-block\">";
	$content .= "<div class=\"div-tblock\"><div class=\"fl\"><a href=\"". url_sid("index.php?f=news&do=categories&id=31")."\">{$correctArr[1]}</a></div><div class=\"pagination fr\" id=\"foo221_pag\"></div><div class=\"cl\"></div></div>";
	$content .= "<div class=\"div-cblock\">";
if($db->sql_numrows($result_lastnew) > 0)  {
	$content .= "<div class=\"div-pother\" >";
	$content .= "<div id=\"foo221\">";
	while(list($idlast, $titlelast, $imageslast, $time, $hometext) = $db->sql_fetchrow($result_lastnew)) 
	{
		$rwtitlelast = utf8_to_ascii(url_optimization($titlelast));
		$url_news_detail =url_sid("index.php?f=news&do=detail&id=$idlast");
		$get_path = get_path($time);
		$path_upload_img = "$path_upload/news/$get_path";
		$path_upload_img2 = "$path_upload/news";
		$a++;
		if($a%2==0){$margin="margin: 0px 0px 0px 0px";}
		else{$margin="margin: 0px 8px 0px 0px";}
		if(file_exists("$path_upload_img/$imageslast") && $imageslast !="") {
			$imageslast= resize_image($titlelast, $imageslast, $path_upload_img, $path_upload_img2, 138,130);
			$content .= "<div class=\"pother-content fl\" style=\"$margin\"><a href=\"$url_news_detail\">$imageslast</a><br>";
			$content .= "<div  class=\"pother-title\"><a href=\"$url_news_detail\">".CutString($titlelast,55)."</a></div></div>";
		} 
	}
	$content .= "</div>";
	$content .= '
	<div class="clearfix"></div>
	</div>';
	 $content .= "<div class=\"cl\"></div>";
}
else
{
$content .= "Đang cập nhật";	
}
	$content .= "</div></div>";



///////////=== san pham khac cung loai

?>