<?php

if(!defined('CMS_ADMIN')) die("Illegal File Access");

$id = intval($_GET['id']);
$stat = intval($_GET['stat']);
$load_hf = isset($_GET['load_hf']) ? 1 : 0;

$db->sql_query("UPDATE ".$prefix."_advertise_banners SET active='$stat' WHERE bnid='$id'");

updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _STATUS);

include("modules/".$adm_modname."/Banners.php");

?>