<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

if(isset($_POST['submit'])) {
	$bcc_per_mail = intval($_POST['bcc_per_mail']);
	$delay_between_send = intval($_POST['delay_between_send']);
	$newsletter_activation_interval = intval($_POST['activation_interval']);

	@chmod(RPATH.DATAFOLD."/config_".$adm_modname.".php", 0666);
	$file = @fopen(RPATH.DATAFOLD."/config_".$adm_modname.".php", "w");
$content = <<<EOT
<?php
if ((!defined('CMS_SYSTEM')) && (!defined('CMS_ADMIN'))) die('Stop!!!');

\$bcc_per_mail = $bcc_per_mail;
\$delay_between_send = $delay_between_send;
\$newsletter_activation_interval = $newsletter_activation_interval;
?>
EOT;
	@fwrite($file, $content);
	@fclose($file);
	@chmod(RPATH.DATAFOLD."/config_{$adm_modname}.php", 0644);
	updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _NEWSLETTER_CONFIG);
	header("Location: modules.php?f=$adm_modname&do=$do&bf");
}

include_once("page_header.php");

echo "<br/><form action=\"modules.php?f=$adm_modname&do=$do\" method=\"POST\"><table align=\"center\" border=\"0\" width=\"\" cellspacing=\"0\" cellpadding=\"3\" class=\"tableborder\">\n";
echo "<tr><td colspan=\"2\" class=\"header\">"._CONFIG."</td></tr>";
echo "<tr>\n";
echo "<td width=\"50%\" align=\"right\" class=\"row1\"><b>"._NEWSLETTER_CONFIG_BCC_PER_MAIL."</b></td>\n";
echo "<td class=\"row3\"><input type=\"text\" name=\"bcc_per_mail\" value=\"$bcc_per_mail\" size=\"10\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"50%\" align=\"right\" class=\"row1\"><b>"._NEWSLETTER_CONFIG_DELAY_BETWEEN_SEND."</b></td>\n";
echo "<td class=\"row3\"><input type=\"text\" name=\"delay_between_send\" value=\"$delay_between_send\" size=\"10\"></td>\n";
echo "</tr>\n";
echo "<td width=\"50%\" align=\"right\" class=\"row1\"><b>"._NEWSLETTER_CONFIG_ACTIVATION_INTERVAL."</b></td>\n";
echo "<td class=\"row3\"><input type=\"text\" name=\"activation_interval\" value=\"$newsletter_activation_interval\" size=\"10\"></td>\n";
echo "</tr>\n";
echo "<tr><td colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"submit\" value=\""._SAVECHANGES."\" class=\"button2\"></td></tr>";
echo "</table></form>";

include_once("page_footer.php");
?>