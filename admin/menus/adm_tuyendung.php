<?php
if (!defined('CMS_ADMIN')) {
	die("Illegal File Access");
	exit;
}

$menu_main = ""._MENU_TUYENDUNG."";

$submenu = array(
	"<li><a href=\"modules.php?f=tuyendung\" target=\"_top\">"._MENU_TUYENDUNG_1."</a></li>",
	"<li><a href=\"modules.php?f=tuyendung&do=categories\" target=\"_top\">"._MENU_TUYENDUNG_2."</a></li>",
	"<li><a href=\"modules.php?f=tuyendung&do=create\" target=\"_top\">"._MENU_TUYENDUNG_4."</a></li>",
	"<li><a href=\"modules.php?f=tuyendung&do=config\" target=\"_top\">"._CONFIG."</a></li>"
);


?>