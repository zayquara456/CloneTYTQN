<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

$id = intval($_GET['id']);
$stat = isset($_GET['stat']) ? intval($_GET['stat']) : 2;
$active = isset($_GET['active']) ? intval($_GET['active']) : 2;
$block = isset($_GET['block']) ? intval($_GET['block']) : 2;
$load_hf = isset($_GET['load_hf']) ? 1 : 0;
$result = $db->sql_query("SELECT*FROM ".$prefix."_user WHERE id='$id'");
	if(empty($id) || $db->sql_numrows($result) != 1) {
		header("Location: modules.php?f=".$adm_modname."");
		die();
	}	
if($stat!=2){
	$db->sql_query("UPDATE ".$prefix."_user SET actives='$stat' WHERE id='$id'");
	header("Location: modules.php?f=".$adm_modname."");
}
if($active!=2){	
	if($active==0)
	{
		$activationCode = md5(uniqid(rand(), true));
		$db->sql_query("UPDATE ".$prefix."_user SET activationCode='$activationCode' WHERE id='$id'");
	}
	elseif($active==1)
	{
		$db->sql_query("UPDATE ".$prefix."_user SET activationCode=NULL WHERE id='$id'");

	}

	header("Location: modules.php?f=".$adm_modname."");
}

if($block!=2){
	if($block==0)
	{
		$unblockCode = md5(uniqid(rand(), true));
		$db->sql_query("UPDATE ".$prefix."_user SET loginAttempt=5, unblockCode='$unblockCode' WHERE id='$id'");
	}
	elseif($block==1)
	{
		$db->sql_query("UPDATE ".$prefix."_user SET loginAttempt=0, unblockCode=NULL WHERE id='$id'");
	}
	header("Location: modules.php?f=".$adm_modname."");
}

?>