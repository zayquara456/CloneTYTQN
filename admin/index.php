<?php
define('CMS_ADMIN', true);
require_once("../config.php");
require_once("language/$currentlang/main.php");

if (defined('iS_ADMIN')) {
	header("Location: body.php");
} else {
	header("Location: login.php");
}		

?>