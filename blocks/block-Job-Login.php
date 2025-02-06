<?php
if (!defined('CMS_SYSTEM')) die();

require_once("language/$currentlang/job.php");

global $jobUserInfo;

if (!defined('iS_JOB_USER')) {
	$content = '<form method="POST" action="'.url_sid('index.php?f=job&do=login&type=quick').'">';
	$content .= "<script>var currentURL=encodeURI(location.href);";
	$content .= "document.write('<input type=\"hidden\" name=\"url\" value=\"' + currentURL + '\">')</script>";
	$content .= "<table border=\"0\" cellspacing=\"1\" style=\"border-collapse: collapse\" width=\"100%\">";
	if (isset($_GET['err'])) {
		if ($_GET['err'] == '3') $content .= "<tr><td><font color=\"red\">"._LOGIN_FAILED."</font></td></tr>";
		elseif ($_GET['err'] == '4') $content .= "<tr><td><font color=\"red\">"._ACCOUNT_BLOCKED."</font></td></tr>";
	}
	$content .= "<tr><td>"._EMAIL.":<br /><input type=\"text\" name=\"email\" size=\"15\"></td></tr>";
	$content .= "<tr><td>"._PASSWORD.":<br /><input type=\"password\" name=\"password\" size=\"15\"></td></tr>";
	$content .= "<tr><td><input type=\"submit\" name=\"submit\" value=\""._LOGIN."\"></td></tr>";
	$content .= '<tr><td><a href="index.php?f=job&do=register">'._REGISTER."</a></td></tr>";
	$content .= '<tr><td><a href="index.php?f=job&do=recover">'._FORGET_PASSWORD."?</a></td></tr>";
	$content .= "</table></form>";
} else {
	$content = _WELCOME.", {$jobUserInfo['name']}";
	$content .= "<br /><input type=\"button\" onclick=\"window.location.href='index.php?f=job&do=logout'\" value=\""._LOGOUT."\">";
	$content .= "<br /><a href=\"index.php?f=job&do=edit_profile\">"._EDIT_PROFILE."</a>";
}
?>