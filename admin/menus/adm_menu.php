<?php
if (!defined('CMS_ADMIN')) {
	die("Illegal File Access");
	exit;
}

$menu_main = ""._MENU_MENUS."";

$submenu = array(
	"<li><a href=\"modules.php?f=mainmenus&menu_type=main_menu\" target=\"_top\">"._MAINMENUS_USER."</a></li>",
	"<li><a href=\"modules.php?f=mainmenus&menu_type=top_menu\" target=\"_top\">"._TOP_MENUS."</a></li>",
	"<li><a href=\"modules.php?f=mainmenus&menu_type=home_menu\" target=\"_top\">"._HOME_MENUS."</a></li>",
	"<li><a href=\"modules.php?f=mainmenus&menu_type=left_menu\" target=\"_top\">"._MAINMENUS_LEFT."</a></li>",
	"<li><a href=\"modules.php?f=mainmenus&menu_type=right_menu\" target=\"_top\">"._RIGHT_MENUS."</a></li>",
	
	"<li><a href=\"modules.php?f=mainmenus&menu_type=footer_menu\" target=\"_top\">"._MAINMENUS_FOOTER."</a></li>"
);


?>