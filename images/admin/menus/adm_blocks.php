<?php
if (!defined('CMS_ADMIN')) {
	die("Illegal File Access");
	exit;
}

$menu_main = ""._MENU_BLOCKS."";

$submenu = array(
	"<li><a href=\"modules.php?f=blocks\" target=\"_top\">"._MANAGERMENT."</a></li>",
	"<li><a href=\"modules.php?f=blocks&do=add\" target=\"_top\">"._MENU_BLOCKS1."</a></li>"
);


?>