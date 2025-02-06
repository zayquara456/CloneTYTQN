<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

$id = intval($_GET['id']);
$stat = intval($_GET['stat']);
$load_hf = isset($_GET['load_hf']) ? 1 : 0;
$linkpage = $_SESSION['linkpage'];
$table = $prefix.'_class';

$db->sql_query("UPDATE $table SET status=$stat WHERE id=$id");
header("Location: modules.php?f=".$adm_modname."");
//include("modules/".$adm_modname."/news_active.php");
?>