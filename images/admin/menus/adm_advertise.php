<?php
if (!defined('CMS_ADMIN')) die("Illegal File Access");

$menu_main = _MENU_ADV;

$submenu = array(
	"<li><a href=\"modules.php?f=advertise&do=banners\" target=\"_top\">"._MENU_ADV3."</a></li>",
	"<li><a href=\"modules.php?f=advertise&do=create\" target=\"_top\">"._MENU_ADV2."</a></li>",
	"<li><a href=\"modules.php?f=advertise&do=scroll\" target=\"_top\">"._MENU_ADV4."</a></li>",
	"<li><a href=\"modules.php?f=advertise&do=config\" target=\"_top\">"._CONFIG."</a></li>"
);
?>