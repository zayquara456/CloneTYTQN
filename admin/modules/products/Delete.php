<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

$id = intval($_GET['id']);
$load_hf = isset($_GET['load_hf']) ? 1 : 0;

$result = $db->sql_query("SELECT images FROM ".$prefix."_products WHERE id='$id'");
if(empty($id) || $db->sql_numrows($result) != 1) {
	include("modules/".$adm_modname."/index.php");
	die();
} else {
	list($images) = $db->sql_fetchrow($result);
	@unlink("../".$path_upload."/".$adm_modname."/".$images);
	@unlink("../".$path_upload."/".$adm_modname."/thumb_".$images);
	$db->sql_query("DELETE FROM ".$prefix."_products WHERE id='$id'");
	updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _DELETE);
	fixcount_cat();
	include("modules/".$adm_modname."/index.php");
}
?>