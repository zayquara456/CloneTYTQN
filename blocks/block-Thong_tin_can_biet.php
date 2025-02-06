<?php
if (!defined('CMS_SYSTEM')) exit;
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
global $Default_Temp;
$content = "";
$content .= "<table style=\"background:#dee3e7 url(templates/$Default_Temp/images/bg_title.gif)  no-repeat\" width=\"100%\">";
$content .= "<tr><td><h4>&nbsp;{$correctArr[1]}</h4></td></tr>";
$content .= "<tr><td><img src=\"templates/{$Default_Temp}/images/spacer.gif\" height=\"3\" /></td></tr>";
$content .= "<tr><td style=\"background:#dee3e7\" align=\"center\"><div style=\"border-bottom:1px solid #4b565a; padding:4px 0px 4px 0px\"><a href=\"http://www.giavangonline.com/gold_price.php\" target=\"_blank\" class=\"style8\" style=\"text-decoration:none\">Gi&aacute; v&agrave;ng</a></div><div style=\"border-bottom:1px solid #4b565a; padding:4px 0px 4px 0px\"><a href=\"http://vietbao.vn/vn/thoitiet/Ha-Noi/\" target=\"_blank\" class=\"style8\" style=\"text-decoration:none\">Th&#7901;i ti&#7871;t </a></div><div style=\"border-bottom:1px solid #4b565a; padding:4px 0px 4px 0px\"><a href=\"http://www.tuoitre.com.vn/tianyon/transweb/TyGia.htm\" target=\"_blank\" class=\"style8\" style=\"text-decoration:none\">T&#7881; gi&aacute; ngo&#7841;i t&#7879;</a></div><div style=\"border-bottom:1px solid #4b565a; padding:4px 0px 4px 0px\"><a href=\"http://vnexpress.net/User/ck/hcm/\" target=\"_blank\" class=\"style8\" style=\"text-decoration:none\">Ch&#7913;ng kho&#225;n</a></div></td></tr>";
$content .= "</table>";
?>
