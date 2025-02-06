<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

$id = intval($_GET['id']);
$load_hf = 1;

$db->sql_query("DELETE FROM {$prefix}_user WHERE id=$id");
updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _USER_DELETE_USER);
if ($ajax_active == 0) {
	$load_hf = 1;
	header("Location: modules.php?f=$adm_modname");
} else {
	header("Location: modules.php?f=$adm_modname");
}
?>
