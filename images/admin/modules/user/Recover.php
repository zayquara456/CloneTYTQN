<?php
if (!defined('CMS_SYSTEM')) die();

$page_title = _USER_RECOVER_PASSWORD;

include_once("header.php");

require_once("WebUser.class.php");

$db->sql_query("UPDATE {$prefix}_user SET recoverCode=NULL WHERE UNIX_TIMESTAMP(recoverCodeTime) + ".strval($activationPeriod * 3600).' <= NOW() AND recoverCode IS NOT NULL');

OpenTab(_USER_RECOVER_PASSWORD);

if (isset($_GET['email']) && isset($_GET['code'])) {
	$user = new WebUser(0, $_GET['email']);
	$ret = $user->recover($_GET['code']);
	if ($ret) {
		echo '<div align="center">'._USER_YOUR_NEW_PASSWORD_IS.": $ret</div>";
	} else {
		echo '<div align="center"><font color="red"><b>'._USER_ERROR_RECOVER."</b></font></div>";
	}
} else {
	if (isset($_POST["submit"])) {
		$user = new WebUser(0, $_POST['email']);
		$ret = $user->newRecover($_POST['url']);
		if ($ret) echo '<div align="center">'._USER_CHECK_MAIL_TO_RECOVER."</div>";
		else echo '<div align="center">'._USER_NEW_RECOVER_FAILED."</div>";
	} else {
		echo '<div>'._USER_RECOVER_INSTRUCTIONS."</div><p></p>";
		echo "<div align=\"center\">";
		echo "<form action=\"".url_sid("index.php?f=$module_name&do=$do")."\" method=\"POST\">";
		echo _USER_EMAIL.": <input type=\"text\" name=\"email\" class=\"text\"><br />";
		echo "<script>var currentURL=encodeURI(location.href);";
		echo "document.write('<input type=\"hidden\" name=\"url\" value=\"' + currentURL + '\">')</script>";
		echo "<input type=\"submit\" name=\"submit\" value=\""._SEND."\" class=\"sb_but1\">";
		echo "</form>";
		echo "</div>";
	}
}

CloseTab();
include_once("footer.php");
?>