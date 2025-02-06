<?php
if (!defined('CMS_SYSTEM')) header("Location: index.php");
if(file_exists("data/config_document.php")) require("data/config_document.php");
global $path_upload, $mod_name, $id, $Default_Temp, $urlsite;

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
$margin="";
$content ="";
$result_lastnew = $db->sql_query("SELECT n.id, n.title, n.images, n.time, n.hometext, n.hits, n.hits_download, u.folder, u.fullname FROM ".$prefix."_document AS n, ".$prefix."_user AS u WHERE  n.active=1 AND n.alanguage='$currentlang'  AND n.user_id=u.id ORDER BY n.hits DESC LIMIT 18");
$numrows = $db->sql_numrows($result_lastnew);

	$a=0;
	$content .= "<div class=\"div-block\">";
	$content .= "<div class=\"div-tblock\"><div class=\"fl\">{$correctArr[1]}</div><div class=\"pagination fr\" id=\"foo223_pag\"></div><div class=\"cl\"></div></div>";
	$content .= "<div class=\"div-cblock\">";
if($db->sql_numrows($result_lastnew) > 0)  {
	$content .= "<div class=\"document-block\" >";
	$content .= "<div id=\"foo223\">";
	$content .= "<div class=\"document-group\">";
	while(list($idlast, $titlelast, $imageslast, $time, $hometext, $hits, $hits_download, $folder, $fullname) = $db->sql_fetchrow($result_lastnew)) 
	{
		$rwtitlelast = utf8_to_ascii(url_optimization($titlelast));
		$url_news_detail =url_sid("index.php?f=document&do=detail&id=$idlast");
		$path_upload_img = "$path_upload/document/$folder";
		$path_upload_noimg = "$path_upload/document";
		$a++;
		if(file_exists("$path_upload_img/$imageslast") && $imageslast !="") {
			$imageslast = resize_image($titlelast,$imageslast,$path_upload_img,$path_upload_img,60,80);
		}
		else
		{
			$imageslast = resize_image($titlelast,'no_image.gif','images',$path_upload_noimg,60,80);
		}
		$content .= "<div class=\"document-item fl\" style=\"$margin\"><div class=\"document-img fl\"><a href=\"$url_news_detail\">$imageslast</a></div>";
			$content .= "<div  class=\"document-title fl\"><a href=\"$url_news_detail\" title=\"$titlelast\">".CutString($titlelast,60)."</a>
			</div><div class=\"cl\"></div></div>";
			//<p>".show_money($price)."</p>
		if($a==6 || $a==12){$content .= "</div><div class=\"document-group\">";}
		
	}
	$content .= "</div>";
	$content .= "</div>";
	$content .= "</div>";
	 $content .= "<div class=\"cl\"></div>";
}
else
{
$content .= "Đang cập nhật";	
}
	$content .= "</div></div>";



///////////=== san pham khac cung loai

?>