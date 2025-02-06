<?php
if (!defined('CMS_SYSTEM')) die();

$page_title = _USER_ACTIVATE;

include_once('header.php');
require_once('WebUser.class.php');

OpenTab(_USER_ACTIVATE);

if (isset($_GET['code'])) {
	$user = new WebUser();
	$user->setEmail($_GET['email']);
	$ret = $user->activate($_GET['code']);
	if ($ret) {
		echo "<div align=\"center\"><b>"._USER_ACTIVATION_SUCCESSFUL."</b></div>";
	} else {
		echo "<div align=\"center\"><b>"._USER_ACTIVATION_FAILED."</b></div>";
	}
	echo "<meta http-equiv=\"refresh\" content=\"5;url= ".url_sid("index.php")."\">";
}

CloseTab();

include_once('footer.php');
?>