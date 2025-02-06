<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

$id = intval($_GET['id']);
$stat = intval($_GET['stat']);
$load_hf = isset($_GET['load_hf']) ? 1 : 0;

if ($_GET['type'] == 'normal') $table = "{$prefix}_picture";
else $table = "{$prefix}_picture_temp";

$db->sql_query("UPDATE $table SET active=$stat WHERE id=$id");
include("modules/".$adm_modname."/index.php");
?>