<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

if(isset($_POST['submit'])) {
	$config0 = intval($_POST['config0']);
	$config2 = intval($_POST['config2']);
	$config3 = intval($_POST['config3']);
	$config4 = trim(stripslashes(resString($_POST['config4'])));
	$config5 = intval($_POST['config5']);
	$config6 = trim(stripslashes(resString($_POST['config6'])));
	$config15 = trim(stripslashes(resString($_POST['config15'])));

	@chmod(RPATH.DATAFOLD."/config_".$adm_modname.".php", 0777);
	@$file = fopen(RPATH.DATAFOLD."/config_".$adm_modname.".php", "w");
	$content = "<?php\n\n";
	$content .= "if ((!defined('CMS_SYSTEM')) AND (!defined('CMS_ADMIN'))) {\n";
	$content .= "die('Stop!!!');\n";
	$content .= "}\n";
	$content .= "\n";
	$content .= "\$activationPeriod = $config0;\n";
	$content .= "\$recoverPeriod = $config1;\n";
	$content .= "\$maxLoginAttempt = $config2;\n";
	$content .= "\$promotion_mobile = \"$config3\";\n";
	$content .= "\$promotion_carot = \"$config4\";\n";
	@$writefile = fwrite($file, $content);
	@fclose($file);
	@chmod(RPATH.DATAFOLD."/config_".$adm_modname.".php", 0644);
	updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _NCONFIG);
	header("Location: modules.php?f=$adm_modname&do=$do&bf");
}

include_once("page_header.php");

echo "<br/><form action=\"modules.php?f=$adm_modname&do=$do\" method=\"POST\">";
echo "<div id=\"pagecontent\">";
echo "<table align=\"center\" width=\"100%\" border=\"0\" width=\"\" cellspacing=\"0\" cellpadding=\"3\" class=\"tableborder\">\n";
echo "<tr><td colspan=\"2\" class=\"header\">"._CONFIG."</td></tr>";
echo "<tr>\n";
echo "<td width=\"50%\" align=\"right\" class=\"row1\"><b>"._ACTIVATION_PERIOD."</b></td>\n";
echo "<td class=\"row3\"><input type=\"text\" name=\"config0\" value=\"$activationPeriod\" size=\"10\"> (h)</td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._RECOVER_PERIOD."</b></td>\n";
echo "<td class=\"row3\"><input type=\"text\" name=\"config1\" value=\"$recoverPeriod\" size=\"10\"> (h)</td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._MAX_LOGIN_ATTEMPT."</b></td>\n";
echo "<td class=\"row3\"><input type=\"text\" name=\"config2\" value=\"$maxLoginAttempt\" size=\"10\"> (lần)</td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._PROMOTION_MOBILE."</b></td>\n";
echo "<td class=\"row3\"><input type=\"text\" name=\"config3\" value=\"$promotion_mobile\" size=\"10\"> (%)</td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._PROMOTION_CAROT."</b></td>\n";
echo "<td class=\"row3\"><input type=\"text\" name=\"config4\" value=\"$promotion_carot\" size=\"10\"> (%)</td>\n";
echo "</tr>\n";
echo "<tr><td colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"submit\" value=\""._SAVECHANGES."\" class=\"button2\"></td></tr>";
echo "</table></div></form>";

include_once("page_footer.php");
?>