<?php
if (!defined('CMS_ADMIN')) die("Illegal File Access");

$menu_main = _MENU_NLETTER;

$submenu = array(
	"<li><a href=\"modules.php?f=newsletter\" target=\"_top\">"._MENU_NLETTER1."</a></li>",
	"<li><a href=\"modules.php?f=newsletter&do=create\" target=\"_top\">"._MENU_NLETTER2."</a></li>",
	"<li><a href=\"modules.php?f=newsletter&do=sent\" target=\"_top\">"._MENU_NLETTER3."</a></li>",
	"<li><a href=\"modules.php?f=newsletter&do=config\" target=\"_top\">"._CONFIG."</a></li>"
);
?>