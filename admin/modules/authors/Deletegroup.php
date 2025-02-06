<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

$id = trim(stripslashes(resString(isset($_GET['id']) ? $_GET['id'] : $_POST['id'])));
$load_hf = isset($_GET['load_hf']) ? 1 : 0;

$result = $db->sql_query("SELECT id,title FROM ".$prefix."_admingroup WHERE id=$id");
if(empty($id) || $db->sql_numrows($result) != 1) { 
	include("modules/".$adm_modname."/Group.php");
	exit;
} else {	
	list($id,$title) = $db->sql_fetchrow($result);
	if($id == 2) {
		die("Hi! Chao ban! Chuc mot ngay tot lanh!");
	}
	$db->sql_query("DELETE FROM ".$prefix."_admingroup WHERE id=$id");
	updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, ""._DELETE." $title");
	include("modules/".$adm_modname."/Group.php");

}



?>