<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}
$id = intval($_GET['id']);

$db->sql_query("DELETE FROM ".$prefix."_thecao_promotion WHERE id='$id'");
updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, "Xóa khuyến mại thẻ cào");
header("Location: modules.php?f=$adm_modname&do=promotion");
?>