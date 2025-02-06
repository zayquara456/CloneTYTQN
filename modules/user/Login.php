<?php
if (!defined('CMS_SYSTEM')) die();

if (!isset($_GET['type']) || ($_GET['type'] != 'quick')) {
	if (defined('iS_USER') && isset($userInfo)) header("Location: ".url_sid("index.php?f=user&do=login")."");
	$page_title = _USER_LOGIN;
	include_once("header.php");
	OpenTab(_USER_LOGIN);
}

require_once("WebUser.class.php");
if (isset($_POST['submit'])) {
	$user = new WebUser(0, $_POST['email']);
	if ($user) {
		$ret = $user->login($_POST['email'], $_POST['password'], $maxLoginAttempt, $_POST['url']);
		if ($ret == USER_LOGIN_SUCCEEDED) {
			if (!isset($_GET['type']) || ($_GET['type'] != 'quick')) {
				echo "<div class=\"content\" align=\"center\">"._USER_LOGIN_SUCCESSFUL."</div>";
				echo "<meta http-equiv=\"refresh\" content=\"1;url= ".url_sid("index.php")."\">";
				CloseTab();
				include_once("footer.php");
				exit();
			} else {
				if (file_exists("blocks/{$_POST['block']}")) {
					$userInfo = checkUser();
					include_once("blocks/{$_POST['block']}");
					echo $content;
				}
				exit();
			}
		} elseif (isset($_GET['type']) && ($_GET['type'] == 'quick')) {
			if ($ret == USER_LOGIN_FAILED) $loginErr = 1;
			else $loginErr = 2;
			if (file_exists("blocks/{$_POST['block']}")) {
				$userInfo = checkUser();
				include_once("blocks/{$_POST['block']}");
				echo $content;
			}
			exit();
		} elseif (($ret == USER_LOGIN_FAILED) || ($ret == USER_LOGIN_FAILED_BLOCKED)) {
			$err_mess = _USER_LOGIN_FAILED;
			if ($ret == USER_LOGIN_FAILED_BLOCKED) $err_mess .= "<br />"._USER_UNBLOCK_INSTRUCTIONS;
		} elseif ($ret == USER_ACCOUNT_BLOCKED) {
			$err_mess = _USER_ACCOUNT_BLOCKED;
		}
		elseif ($ret == USER_LOGIN_FAILED_ACTIVATION) {
			$err_mess = _USER_LOGIN_ACTIVATION_FAILED;
		}
	} else {
		if (isset($_GET['type']) && ($_GET['type'] == 'quick')) {
			$loginErr = 1;
			if (file_exists("blocks/{$_POST['block']}")) {
				$userInfo = checkUser();
				include_once("blocks/{$_POST['block']}");
				echo $content;
			}
			exit();
		}
	}
}

if (!isset($_GET['type']) || ($_GET['type'] != 'quick')) {
	echo "<div class=\"content\"><form method=\"POST\" action=\"".url_sid("index.php?f=$module_name&do=$do")."\">";
	if (isset($err_mess)) {
		echo "<div align=\"center\"><font color=\"red\"><b>$err_mess</b></font></div>";
	}
	echo "<table border=\"0\" align=\"center\">";
	echo "<tr><td height=\"24\"><font size=\"2\">"._USER_FULLNAME.": </font></td>";
	echo '<td style="padding-left: 10px"><input type="text" id="email" name="email" size="40"></td>'."</tr>";
	echo "<tr><td height=\"24\"><font size=\"2\">"._USER_PASSWORD.": </font></td>";
	echo '<td style="padding-left: 10px"><input type="password" id="password" name="password" size="40"></td>'."</tr>";
	echo "<script>var currentURL=encodeURI(location.href);";
	echo "document.write('<input type=\"hidden\" name=\"url\" value=\"' + currentURL + '\">')</script>";
	echo "<tr><td height=\"24\" colspan=\"2\" align=\"center\"><input class=\"sb_but1\" type=\"submit\" name=\"submit\" value=\""._USER_LOGIN."\"></td></tr>";
	echo "</table></form>";
	echo '<div><strong style="color:#F00">&rsaquo;&nbsp;</strong><a href="'.$urlsite.'/index.php?f=user&do=register">'._USER_REGISTER."</a></div>";
	echo '<div><strong style="color:#F00">&rsaquo;&nbsp;</strong><a href="'.$urlsite.'/index.php?f=user&do=recover">'._USER_FORGET_PASSWORD."?</a></div>";
	echo "</div>";

	CloseTab();
	include_once("footer.php");
}
?>