<?php
if (!defined('CMS_SYSTEM')) exit;

require_once("language/{$currentlang}/newsletter.php");

global $Default_Temp;

$content = '';

$bl_arr = array();
$bl_arr[] = $bl_l;
$bl_arr[] = $bl_r;
$basename = pathinfo(__FILE__, PATHINFO_BASENAME);
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

$content .= "<table style=\"background-color: #c8ffcf\" width=\"100%\">";
$content .= "<tr><td><h4><img src=\"templates/{$Default_Temp}/images/email.png\" style=\"vertical-align: middle\" />&nbsp;{$correctArr[1]}</h4></td></tr>";
$content .= "<tr><td><img src=\"templates/{$Default_Temp}/images/spacer.gif\" height=\"3\" /></td></tr>";
$content .= "<tr><td><div id=\"newsletter_block\"></div></td></tr>";
$content .= "<tr><td>"._NEWSLETTER_REGISTRATION_EXPLAIN."</td></tr>";
$content .= "<tr><td align=\"center\"><input type=\"text\" id=\"newsletterEmail\" class=\"text\" /></td></tr>";
$content .= "<tr><td align=\"right\"><input type=\"button\" value=\""._NEWSLETTER_REGISTER."\" class=\"sb_but1\" onclick=\"registerNewsletter();\" /></td></tr>";
$content .= "<tr><td><img src=\"templates/{$Default_Temp}/images/spacer.gif\" height=\"3\" /></td></tr>";
$content .= "</table>";
$content .= <<<EOT
<script>
	function registerNewsletter() {
		var email = document.getElementById('newsletterEmail').value
		if (!isEmail(email)) {
			alert('
EOT;
$content .= _NEWSLETTER_ERROR_EMAIL."')";
$content .= <<<EOT
			return false
		} else {
			if (location.href.indexOf('index.php') == -1) {
				if (location.href.charAt(location.href.length - 1) != '/')
					var link = location.href + '/index.php'
				else
					var link = location.href + 'index.php'
			} else var link = location.href
			var r = 'email='+encodeURIComponent(email)+'&url='+encodeURIComponent(link)
			ajaxinfopost('index.php?f=newsletter&do=register', r, 'ajaxload_container','newsletter_block')
		}
	}
</script>
EOT;
?>