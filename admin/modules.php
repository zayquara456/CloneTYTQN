<?php
define('CMS_ADMIN', true);
require_once("../config.php");
require_once("language/$currentlang/main.php");

if(isset($_GET['f']) || isset($_POST['f'])) {
	$f = trim(isset($_POST['f']) ? $_POST['f'] : $_GET['f']);
	if(isset($_GET['do']) || isset($_POST['do'])) {
		$do = trim(isset($_POST['do']) ? $_POST['do'] : $_GET['do']);
		$do = ucfirst($do);
	} else {
		$do = "index";
	}
	if (preg_match("[^a-zA-Z0-9_]",$do)) { die(info_exit(_FILENOTFOUND." $f/$do")); }	
	
	if(isset($_GET['op']) || isset($_POST['op'])) {
		$op = trim(isset($_POST['op']) ? $_POST['op'] : $_GET['op']);
	} else {
		$op = "";
	}	
	if (preg_match("[^a-zA-Z0-9_]",$op)) { die(info_exit(_FUNCTIONNOTFOUND)); }	
} else {
	$f = "home";
	$do = "index";
	$op = "";
}

if (preg_match("[^a-zA-Z0-9_]",$f)) { die(info_exit(_FUNCTIONNOTFOUND)); }

$module_path = "modules/$f/$do.php";
//the modules do not have access
//$not_accept_mod = array("blocks","modules","configuration","adminlog");
$not_accept_mod = array();
if(file_exists($module_path)) 
{
	$adm_modname = $f;
	if(defined('iS_ADMIN')) 
	{
		//if(defined('iS_RADMIN') || (checkPermAdm($adm_modname) && !in_array($adm_modname,$not_accept_mod))) 
		//{
			get_lang("admin", $adm_modname);
			if(defined('_MODTITLE')) { $adm_pagetitle = _MODTITLE; }
			if(file_exists("../".DATAFOLD."/config_".$adm_modname.".php")) 
				require_once("../".DATAFOLD."/config_".$adm_modname.".php");
			if(file_exists("modules/$f/Functions.php")) 
				require_once("modules/$f/Functions.php");
			include($module_path);	
		//} 
		//else 
		//	die("You don't have permission to access this page.");	
	} 
	else 
	{
		if($_SERVER['QUERY_STRING'] != "") 
		{
			$admurl_redirect = "modules.php?".$_SERVER['QUERY_STRING'];
		} 
		else 
		{
			$admurl_redirect = "modules.php";
		}
		//admin_login($admurl_redirect);
		header("Location: login.php");
	}
} else {
	info_exit(_FILENOTFOUND." $module_path");	
}
?>