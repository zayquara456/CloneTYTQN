<?php
if (!defined('CMS_SYSTEM')) die();

$page_title = _USER_UNBLOCK;

include_once("header.php");
include_once("User.class.php");

OpenTab(_USER_UNBLOCK);

if (isset($_GET['code']) && isset($_GET['email'])) {
	$user = new WebUser(0, $_GET['email']);
	$ret = $user->unblock($_GET['code']);
	if ($ret) echo "<div align=\"center\">"._USER_UNBLOCK_SUCCEEDED."</font></div>";
	else echo "<div align=\"center\"><font color=\"red\"><b>"._USER_UNBLOCK_FAILED."</b></font></div>";
	echo "<meta http-equiv=\"refresh\" content=\"5;url= ".url_sid("index.php")."\">";
}

CloseTab();
include_once("footer.php");
?>