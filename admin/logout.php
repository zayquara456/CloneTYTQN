<?php
define('CMS_ADMIN', true);
require_once("../config.php");

if(defined('iS_ADMIN')) {
	unset ($admin);
	unset ($_SESSION[ADMIN_SES]);
	$_SESSION['islogin']= false;
	header("Location:".url_sid("index.php",1)."");
}else{
	header("Location: login.php");
}		

?>