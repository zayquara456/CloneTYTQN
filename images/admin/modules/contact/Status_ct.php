<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

$id = intval($_POST['id']);
$stat = intval($_POST['stat']);

$result = $db->sql_query("SELECT * FROM {$prefix}_contact WHERE id=$id");
if ($db->sql_numrows($result) != 1) header("Location: modules.php?f={$adm_modname}");

$db->sql_query("UPDATE {$prefix}_contact SET status=$stat WHERE id=$id");

updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _STATUS);

header("Location: modules.php?f={$adm_modname}");
?>