<?php
if (!defined('CMS_SYSTEM')) die();

$page_title = _NEWSLETTER_UNREG;

include_once("header.php");

OpenTab(_NEWSLETTER_UNREG);

$db->sql_query("DELETE FROM {$prefix}_newsletter WHERE email='".$escape_mysql_string($_GET['email'])."' AND checkkey='".$escape_mysql_string($_GET['checkkey'])."'");
if ($db->sql_affectedrows() > 0) {
	echo "<div align=\"center\"><b>"._NEWSLETTER_UNREG_SUCCESSFUL."</b></div>";
} else {
	echo "<div align=\"center\"><b>"._NEWSLETTER_UNREG_FAILED."</b></div>";
}
echo "<meta http-equiv=\"refresh\" content=\"5; url= ".url_sid("index.php")."\">";

CloseTab();

include_once("footer.php");
?>