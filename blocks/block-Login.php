<?php
if (!defined('CMS_SYSTEM')) die();

require_once("language/$currentlang/user.php");

global $userInfo, $Default_Temp;

if (!isset($bl_l) && !isset($bl_r) && !isset($bl_c) && !isset($bl_d)) {
	require_once(RPATH.DATAFOLD."/blist.php");
	$quick = true;
} else $quick = false;

$basename = pathinfo(__FILE__, PATHINFO_BASENAME);
$bl_arr = array();
if (isset($bl_l)) $bl_arr[] = $bl_l;
if (isset($bl_r)) $bl_arr[] = $bl_r;
if (isset($bl_c)) $bl_arr[] = $bl_c;
if (isset($bl_d)) $bl_arr[] = $bl_d;
$correctArr = array();
for ($i = 0; $i < count($bl_arr); $i++) {
	for ($h = 0; $h < count($bl_arr[$i]); $h++) {
		$temp = explode("@", $bl_arr[$i][$h]);
		if (($temp[5] == $currentlang) && ($temp[6] == $basename)) {
			$correctArr = $temp;
			break;
		}
	}
}

if (!$quick) $content = "<div id=\"login_block\">";
else $content = '';

if (!defined('iS_USER') || !isset($userInfo)) {
	$content .= "<table style=\"background-color: #fdfdb9\" width=\"100%\">";
	$content .= "<tr><td><h4><img src=\"templates/{$Default_Temp}/images/login.png\" style=\"vertical-align: middle\" />&nbsp;{$correctArr[1]}</h4></td></tr>";
	$content .= "<tr><td><img src=\"templates/{$Default_Temp}/images/spacer.gif\" height=\"3\" /></td></tr>";
	if (isset($loginErr)) {
		if ($loginErr == 1) $content .= "<tr><td><font color=\"red\">"._USER_LOGIN_FAILED."</font></td></tr>";
		elseif ($loginErr == 2) $content .= "<tr><td><font color=\"red\">"._USER_ACCOUNT_BLOCKED."</font></td></tr>";
	}
	$content .= "<tr><td align=\"center\"><input type=\"text\" name=\"email\" size=\"13\" id=\"loginEmail\" class=\"text\" value=\""._USER_EMAIL."\" size=\"15\" onfocus=\"if(this.value=='"._USER_EMAIL."') this.value=''\"></td></tr>";
	$content .= "<tr><td align=\"center\"><input type=\"password\" id=\"loginPassword\" name=\"password\" value=\""._USER_PASSWORD."\" size=\"13\" class=\"text\" size=\"15\" onfocus=\"if(this.value=='"._USER_PASSWORD."') this.value=''\"></td></tr>";
	$content .= "<tr><td align=\"right\"><input type=\"submit\" name=\"submit\" value=\""._USER_LOGIN."\" onclick=\"userLogin();\" class=\"sb_but1\"></td></tr>";
	$content .= "<tr><td><img src=\"templates/{$Default_Temp}/images/spacer.gif\" height=\"3\" /></td></tr>";
	$content .= '<tr><td style="padding-left: 10px"><a href="index.php?f=user&do=register">'._USER_REGISTER."</a></td></tr>";
	$content .= '<tr><td style="padding-left: 10px"><a href="index.php?f=user&do=recover">'._USER_FORGET_PASSWORD."?</a></td></tr>";
	$content .= "<tr><td><img src=\"templates/{$Default_Temp}/images/spacer.gif\" height=\"3\" /></td></tr>";
	$content .= "</table>";
} else {
	$content .= "<table style=\"background-color: #fdfdb9\" width=\"100%\">";
	$content .= "<tr><td><h4><img src=\"templates/{$Default_Temp}/images/login.png\" style=\"vertical-align: middle\" />&nbsp;{$correctArr[1]}</h4></td></tr>";
	$content .= "<tr><td><img src=\"templates/{$Default_Temp}/images/spacer.gif\" height=\"3\" /></td></tr>";
	$content .= "<tr><td>"._USER_WELCOME.", {$userInfo['fullname']}</td></tr>";
	$content .= "<tr><td align=\"center\"><input type=\"button\" onclick=\"userLogout();\" value=\""._USER_LOGOUT."\" class=\"sb_but1\"></td></tr>";
	$content .= "<tr><td><a href=\"index.php?f=user&do=edit_profile\">"._USER_EDIT_PROFILE."</a></td></tr>";
	$content .= "<tr><td><img src=\"templates/{$Default_Temp}/images/spacer.gif\" height=\"3\" /></td></tr>";
	$content .= "</table>";
}

if (!$quick) $content .= '</div>';

$content .= <<<EOT
<script>
	function userLogin() {
		var email = document.getElementById('loginEmail').value
		var pass = document.getElementById('loginPassword').value
		if (location.href.indexOf('index.php') == -1) {
			if (location.href.charAt(location.href.length - 1) != '/')
				var link = location.href + '/index.php'
			else
				var link = location.href + 'index.php'
		} else var link = location.href
		var r = 'email='+encodeURIComponent(email)+'&password='+encodeURIComponent(pass)+'&url='+encodeURIComponent(link)+'&block='+encodeURIComponent('$basename')+'&submit=1'
		ajaxinfopost('index.php?f=user&do=login&type=quick', r, 'ajaxload_container', 'login_block')
	}
	
	function userLogout() {
		if (location.href.indexOf('index.php') == -1) {
			if (location.href.charAt(location.href.length - 1) != '/')
				var link = location.href + '/index.php'
			else
				var link = location.href + 'index.php'
		} else var link = location.href
		ajaxinfoget('index.php?f=user&do=logout&type=quick&url='+encodeURIComponent(link)+'&block='+encodeURIComponent('$basename'), 'ajaxload_container', 'login_block')
	}
</script>
EOT;
?>