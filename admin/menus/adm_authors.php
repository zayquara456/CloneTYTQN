<?php
if (!defined('CMS_ADMIN')) die("Illegal File Access");

$menu_main = _MENU_AUTHOR;

$submenu = array(
	"<li><a href=\"modules.php?f=authors\" target=\"_top\">"._MENU_AUTHOR1."</a></li>",
	"<li><a href=\"modules.php?f=authors&do=add\" target=\"_top\">"._MENU_AUTHOR2."</li></a>"
);
?>