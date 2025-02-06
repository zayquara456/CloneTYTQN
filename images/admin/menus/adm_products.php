<?php
if (!defined('CMS_ADMIN')) die("Illegal File Access");

$menu_main = _MENU_PRODUCT;

$submenu = array(
	"<li><a href=\"modules.php?f=products\" target=\"_top\">"._MENU_PRODUCT1."</a></li>",
	"<li><a href=\"modules.php?f=products&do=categories\" target=\"_top\">"._MENU_PRODUCT2."</a></li>",
	"<li><a href=\"modules.php?f=products&do=create\" target=\"_top\">"._MENU_PRODUCT3."</a></li>",
	"<li><a href=\"modules.php?f=products&do=pnews\" target=\"_top\">"._MENU_PRODUCT4."</a></li>",
	"<li><a href=\"modules.php?f=products&do=ptops\" target=\"_top\">"._MENU_PRODUCT5."</a></li>",
	"<li><a href=\"modules.php?f=products&do=orders\" target=\"_top\">"._MENU_VIEW_ORDERS."</a></li>",
	"<li><a href=\"modules.php?f=products&do=config\" target=\"_top\">"._CONFIG."</a></li>"
);
?>