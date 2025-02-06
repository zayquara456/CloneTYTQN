<?php
if (!defined('FCKEditor')) {
	define('CMS_CONFIG', true);
	$datafold = "data";
	define("DATAFOLD","data");
	define("BLOCKFOLD","blocks");
	define("TIMENOW",time());
	define("JOB_SESS","anMw_231jhH");
	define("CART_SESS","mawSRio_99s");
	define("USER_SESS","nvu_fCQ82");
	define("CAPTCHA_SESS","asMhWs8");

	$sitekey = "2do82:o;-1wr.uo8l&a00;";
	$numshex_std = 1009002134;
}
session_register('islogin');
define("DEBUG", 1);
$dbhost = "localhost";		// server name
$dbuname = "vq_acud";			// username database
$dbpass = "xCLzcbcDlob";			// password database
$dbname = "vq_acud";		// database name
$prefix = "adoosite";		// prefix table in database
$super_admin = "1";
define("ADMIN_SES","nva_fCQ82");
if (!defined('FCKEditor')) {
	@require_once("includes/functions.php");
}
?>