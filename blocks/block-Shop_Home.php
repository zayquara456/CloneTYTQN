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

global $prefix, $db;

$totalhomeg = $db->sql_query("select * from ".$prefix."_shop where status='1' AND action='0' AND show_home='1'");
$g = 0;
$content = "";
while ($drow = $db->sql_fetchrow($totalhomeg)) {
    $pidhome = $drow[pid];
    $titlehome = $drow[title];
    $additionhome = $drow[addition];
if ($g > 0) {
$content  .= "<hr width=\"50%\" align=\"left\" style=\"border-style: dotted; border-width: 1px; margin-left: 15\">";
}
$content .=  "<div><li style=\"list-style-type: circle\" type=\"square\" class=\"storytitle\"><a href=\"".INCLUDE_PATH."modules.php?name=Shop&go=goods&pid=$pidhome\" class=\"storytitle\">$titlehome</a></li></div>";
$content .= "<div style=\"margin-left: 15\">$additionhome</div>";
$g++;
}

?>