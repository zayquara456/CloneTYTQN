<?php
if (!defined('CMS_SYSTEM')) die();

$email = $escape_mysql_string($_POST['email']);
$rcode = md5(generate_code(8));

$db->sql_query("DELETE FROM {$prefix}_newsletter WHERE time + ".strval($newsletter_activation_interval * 3600)." <= UNIX_TIMESTAMP(NOW()) AND activateCode IS NOT NULL");
if ($db->sql_numrows($db->sql_query("SELECT id FROM {$prefix}_newsletter WHERE email='$email'")) <= 0) {
	$db->sql_query("INSERT INTO {$prefix}_newsletter (email, status, html, checkkey, time, activateCode) VALUES ('$email', 2, 1, '$rcode', '".TIMENOW."', '$rcode')");
	if ($db->sql_affectedrows() > 0) {
		$db->sql_query("SELECT content FROM {$prefix}_gentext WHERE textname='sign' AND alanguage='$currentlang'");
		list($sign) = $db->sql_fetchrow();
		$parsedURL = Common::constructURL($_POST['url'], "?f={$module_name}&do=activate&email={$_POST['email']}&code=$rcode");
		$msg = _NEWSLETTER_GREETING;
		$msg .= ",<br />"._NEWSLETTER_PLEASE_CLICK.": <a href=\"$parsedURL\" target=\"_blank\">$parsedURL</a><br />";
		$msg .= _NEWSLETTER_THIS_LINK_IS_VALID_FOR." $newsletter_activation_interval "._NEWSLETTER_HOUR."<br />$sign";
		sendmail(_NEWSLETTER_CONFIRM_EMAIL, $email, $adminmail, $msg);
		echo "<font color=\"red\">"._NEWSLETTER_EMAIL_REGISTERED."</font>";
	}
	else echo "<font color=\"red\">"._NEWSLETTER_ERROR_REGISTERING."</font>";
} else {
	echo "<font color=\"red\">"._NEWSLETTER_EMAIL_REGISTERED."</font>";
}

$load_hf = 1;
?>