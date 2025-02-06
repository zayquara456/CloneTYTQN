<?php
if (!defined('CMS_SYSTEM')) die();

$page_title = _NEWSLETTER_ACTIVATE;

include_once('header.php');

OpenTab(_NEWSLETTER_ACTIVATE);

if (isset($_GET['code'])) {
	$db->sql_query("UPDATE {$prefix}_newsletter SET activateCode=NULL WHERE activateCode='".$escape_mysql_string($_GET['code'])."' AND email='".$escape_mysql_string($_GET['email'])."'");
	if ($db->sql_affectedrows() > 0) {
		echo "<div align=\"center\"><b>"._NEWSLETTER_ACTIVATION_SUCCESSFUL."</b></div>";
	} else {
		echo "<div align=\"center\"><b>"._NEWSLETTER_ACTIVATION_FAILED."</b></div>";
	}
	echo "<meta http-equiv=\"refresh\" content=\"5;url= ".url_sid("index.php")."\">";
}

CloseTab();

include_once('footer.php');
?>