<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

$id = intval($_GET['id']);
$load_hf = 1;
$moncard = nospatags(htmlspecialchars($_GET['title'], ENT_QUOTES));
$result = $db->sql_query("SELECT moncard FROM {$prefix}_user  WHERE id=$id");
if($db->sql_numrows($result) >0) 
{
	list($mmoncard) = $db->sql_fetchrow($result);
	$xien = $moncard+$mmoncard;
	$db->sql_query("UPDATE {$prefix}_user SET moncard='$xien' WHERE id=$id");
	updateadmlog($admin_ar[0], $adm_modname, 'nap tien', 'nap tien cho nguoi dung');
	include_once("modules/$adm_modname/index.php");
}	


?>
