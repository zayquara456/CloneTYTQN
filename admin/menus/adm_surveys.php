<?php
if (!defined('CMS_ADMIN')) {
	die("Illegal File Access");
	exit;
}

$menu_main = ""._MENU_SURVEYS."";

$submenu = array(
	"<li><a href=\"modules.php?f=surveys\" target=\"_top\">"._MENU_SURVEYS1."</a></li>",
	"<li><a href=\"modules.php?f=surveys&do=add\" target=\"_top\">"._MENU_SURVEYS2."</a></li>",
	"<li><a href=\"modules.php?f=surveys&do=config\" target=\"_top\">"._CONFIG."</a></li>"
);


?>