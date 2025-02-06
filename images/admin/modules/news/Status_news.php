<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

$id = intval($_GET['id']);
$stat = intval($_GET['stat']);
$load_hf = isset($_GET['load_hf']) ? 1 : 0;
$linkpage = $_SESSION['linkpage'];
if ($_GET['type'] == 'normal') $table = "{$prefix}_news";
else $table = "{$prefix}_news_temp";

$db->sql_query("UPDATE $table SET active=$stat WHERE id=$id");
header("Location: modules.php?".$linkpage."");
//include("modules/".$adm_modname."/news_active.php");
?>