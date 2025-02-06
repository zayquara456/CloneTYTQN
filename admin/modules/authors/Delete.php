<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

$acc = trim(stripslashes(resString(isset($_GET['acc']) ? $_GET['acc'] : $_POST['acc'])));
$load_hf = isset($_GET['load_hf']) ? 1 : 0;

$result = $db->sql_query("SELECT adname, permission FROM ".$prefix."_admin WHERE adacc='$acc'");
if(empty($acc) || $db->sql_numrows($result) != 1) { 
	include("modules/".$adm_modname."/index.php");
	exit;
} else {	
	list($adname, $permission) = $db->sql_fetchrow($result);
	if($adname == "Root" && $permission == 0) {
		die("Hi! Chao ban! Chuc mot ngay tot lanh!");
	}
	$db->sql_query("DELETE FROM ".$prefix."_admin WHERE adacc='$acc'");
	updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, ""._DELETE." $acc");
	include("modules/".$adm_modname."/index.php");

}



?>