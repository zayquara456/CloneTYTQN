<?php
if (!defined('CMS_ADMIN')) {
	die("Illegal File Access");
	exit;
}

$menu_main = ""._MENU_NEWS."";

$submenu = array(
	"<li><a href=\"modules.php?f=news\" target=\"_top\">"._MENU_NEWS_1."</a></li>",
	"<li><a href=\"modules.php?f=news&do=categories\" target=\"_top\">"._MENU_NEWS_2."</a></li>",
	"<li><a href=\"modules.php?f=news&do=create\" target=\"_top\">"._MENU_NEWS_4."</a></li>",
	"<li><a href=\"modules.php?f=news&do=config\" target=\"_top\">"._CONFIG."</a></li>"
);


?>