<?php
/********************************************************/
/* Giới thiệu công ty PCCC T-M - www.tm-pccc.com     	*/
/* ============================================         */
/* Module Shop NukeViet RC1 - http://nukeviet.vn		*/
/********************************************************/

if ((!defined('NV_SYSTEM')) AND (!defined('NV_ADMIN'))) {
    Header("Location: ../index.php");
    exit;
}

global $prefix, $db;
$pic_dir = "".INCLUDE_PATH."uploads/Shop/pic";
$pic_thumbn_dir = "".INCLUDE_PATH."uploads/Shop/trumb_pic";
$gheight = 50;

$gbo = $db->sql_query("select * from ".$prefix."_shop where status='1' AND pic1!='' ORDER BY pid DESC LIMIT 5");
$content = "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\">"; 
while ($agbo = $db->sql_fetchrow($gbo)) {
    $pidgg = $agbo[pid];
    $titlegg = $agbo[title];
    $picgg = $agbo[pic1];
    $gaddition = $agbo[addition];
    $gprice = $agbo[price];
if (file_exists("$pic_thumbn_dir/$picgg")) {
   $picggg = "<img border=\"0\" src=\"$pic_thumbn_dir/$picgg\" height=\"$gheight\" style=\"float: right\">";
   }
   elseif (file_exists("$pic_dir/$picgg")) {
   $picggg = "<img border=\"0\" src=\"$pic_dir/$picgg\" height=\"$gheight\" style=\"float: right\">";
   } else {
   $picggg = "";
   }
    
$content .= "<tr><td><a href=\"".INCLUDE_PATH."modules.php?name=Shop&go=goods&pid=$pidgg\">$picggg<b>$titlegg</b></a><br>&nbsp;&nbsp;&nbsp;&nbsp;M&#227; s&#7843;n ph&#7849;m: $gaddition&nbsp;&nbsp;|&nbsp;&nbsp;Gi&#225;: ".number_format($gprice, 0, '.', ' ')." VNĐ</td></tr>";
}
$content .= "</table>";

?>