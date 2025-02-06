<?php
if (!defined('CMS_SYSTEM')) exit;
global $Default_Temp, $path_upload, $urlsite;
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

$content="";
$result_advlogo = $db->sql_query("SELECT id, target, images, imgtext, module FROM ".$prefix."_advertise WHERE bnid='7' AND alanguage='$currentlang' AND active='1' ORDER BY weight");
$numrows = $db->sql_numrows($result_advlogo);
if($numrows > 0) {
	$a=0;
	$content .= "<div class=\"div-block\">";
	$content .= "<div class=\"div-tblock\"><a href=\"http://acud.vn/document/\">{$correctArr[1]}</a></div><div class=\"div-cblock\" style=\"padding:3px 0px 3px 6px\">";
	while(list($id, $target, $images, $imgtext, $module) = $db->sql_fetchrow($result_advlogo)) {
		if($a<3)
		$content .= "<div class=\"fl\" style=\"padding:2px 8px 5px 2px\">";
		else
		$content .= "<div class=\"fl\" style=\"padding:0px 8px 2px 2px\">";
		$path_upload_img = "$path_upload/adv/$images";
		$a++;
		if(file_exists("$path_upload_img") && $images !="") {
			$content .= "<a href=\"".url_sid("$urlsite/click.php?id=$id")."\" target=\"$target\" title=\"$imgtext\"><img border=\"0\" src=\"$urlsite/$path_upload_img\"></a>";
		}
		$content .= "</div>";
		if ($a ==3) $content .= "<div class=\"cl\"></div>";
	}
	$content .= "<div class=\"cl\"></div>";
	$content .= "</div></div>";
}

?>
