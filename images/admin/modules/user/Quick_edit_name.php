<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

$id = intval($_GET['id']);
$load_hf = 1;
$name = nospatags(htmlspecialchars($_GET['title'], ENT_QUOTES));

$db->sql_query("UPDATE {$prefix}_user SET fullname='$name' WHERE id=$id");
updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _USER_QUICK_EDIT_NAME);
include_once("modules/$adm_modname/index.php");
?>
