<?php
/********************************************************/
/* Gi&#7899;i thi&#7879;u công ty PCCC T-M - www.tm-pccc.com     	*/
/* ============================================         */
/* Module Shop NukeViet RC1 - http://nukeviet.vn		*/
/********************************************************/

if ((!defined('NV_SYSTEM')) AND (!defined('NV_ADMIN'))) {
    Header("Location: ../index.php");
    exit;
}

global $Default_Theme, $prefix, $db, $cur;

$gtotal = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_shop where action='1'"));
if ($gtotal > 0) {
$gtotal = $gtotal-1;
mt_srand((double)microtime()*1000000);
$pidrand = mt_rand(0, $gtotal);
list($g_title, $g_price) = $db->sql_fetchrow($db->sql_query("select title, action_price from ".$prefix."_shop where action='1' LIMIT $pidrand,1"));
$content  =  "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">";
$content  .= "<tr>";
$content  .= "<td align=\"center\"><strong><span style=\"letter-spacing: -1pt\">";
$content  .= "<a href=\"".INCLUDE_PATH."modules.php?name=Shop&go=action\">";
$content  .= "<font face=\"Times New Roman\" style=\"font-size: 20px\" color=\"#000000\">";
$content  .= "<span style=\"text-decoration: none\">$g_title</span></font></a></span></strong></td>";
$content  .= "</tr>";
$content  .= "<tr>";
$content  .= "<td align=\"center\">";
$content  .= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">";
$content  .= "<tr>";
$content  .= "<td>";
$content  .= "<a href=\"".INCLUDE_PATH."modules.php?name=Shop&go=action\">";
$content  .= "<img border=\"0\" src=\"".INCLUDE_PATH."modules/Shop/themes/images/khuyenmai_01.gif\" width=\"153\" height=\"74\"></a></td>";
$content  .= "</tr>";
$content  .= "<tr>";
$content  .= "<td>";
$content  .= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">";
$content  .= "<tr>";
$content  .= "<td>";
$content  .= "<a href=\"".INCLUDE_PATH."modules.php?name=Shop&go=action\">";
$content  .= "<img border=\"0\" src=\"".INCLUDE_PATH."modules/Shop/themes/images/khuyenmai_02.gif\" width=\"35\" height=\"72\"></a></td>";
$content  .= "<td width=\"100%\" background=\"".INCLUDE_PATH."modules/Shop/themes/images/khuyenmai_03.gif\" style=\"background-position:left top; background-repeat:repeat-y\" valign=\"top\" align=\"center\">";
$content  .= "<a href=\"".INCLUDE_PATH."modules.php?name=Shop&go=action\">";
$content  .= "<strong><span style=\"text-decoration: none\">";
$content  .= "<font face=\"Tahoma\" style=\"font-size: 20px; letter-spacing: -1px\" color=\"#000000\">".number_format($g_price, 0, '.', ' ')."<br>$cur</font></span></strong></a></td>";
$content  .= "</tr>";
$content  .= "</table>";
$content  .= "</td>";
$content  .= "</tr>";
$content  .= "</table>";
$content  .= "</td>";
$content  .= "</tr>";
$content  .= "</table>";
}

?>