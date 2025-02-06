<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

$id = intval($_GET['id']);

$db->sql_query("DELETE FROM ".$prefix."_thecao_menhgia WHERE id='$id'");
updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, "Xóa mệnh giá thẻ cào");
header("Location: modules.php?f=$adm_modname&do=menhgia");
?>