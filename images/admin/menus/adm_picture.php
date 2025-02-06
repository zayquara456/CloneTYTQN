<?php
if (!defined('CMS_ADMIN')) {
	die("Illegal File Access");
	exit;
}

$menu_main = ""._MENU_PICTURE."";

$submenu = array(
	"<li><a href=\"modules.php?f=picture\" target=\"_top\">"._MENU_PICTURE_1."</a></li>",
	"<li><a href=\"modules.php?f=picture&do=categories\" target=\"_top\">"._MENU_PICTURE_2."</a></li>",
	"<li><a href=\"modules.php?f=picture&do=create\" target=\"_top\">"._MENU_PICTURE_3."</a></li>",
	"<li><a href=\"modules.php?f=picture&do=config\" target=\"_top\">"._CONFIG."</a></li>"
);


?>