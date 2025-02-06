<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

$id = isset($_GET['id']) ? $_GET['id'] : $_POST['id'];
$load_hf = isset($_GET['load_hf']) ? 1 : 0;

$db->sql_query("DELETE FROM {$prefix}_newsletter WHERE id='$id'");

include_once("modules/$adm_modname/index.php");
?>