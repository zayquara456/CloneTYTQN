<?php
if(!defined('CMS_ADMIN')) die();

$fc = intval($_POST['fc']);
$id = $_POST['id'];

if ($fc == 1) for ($i = 0; $i < count($id); $i++) $db->sql_query("DELETE FROM {$prefix}_products_order WHERE id=".intval($id[$i]));

updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _QUICKDO);

header("Location: modules.php?f=$adm_modname&do=orders");
?>