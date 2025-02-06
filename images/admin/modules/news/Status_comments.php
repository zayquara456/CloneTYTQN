<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

$id = intval($_GET['id']);
$stat = intval($_GET['stat']);
$load_hf = isset($_GET['load_hf']) ? 1 : 0;

$db->sql_query("UPDATE {$prefix}_comments SET status=$stat WHERE id=$id");
//include("modules/".$adm_modname."/index.php");
header("Location: modules.php?f=".$adm_modname."&do=comments");
?>