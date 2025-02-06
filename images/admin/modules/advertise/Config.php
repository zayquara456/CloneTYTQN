<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

if (isset($_POST["submit"])) {
	@chmod(RPATH.DATAFOLD."/config_".$adm_modname.".php", 0666);
	@$file = fopen(RPATH.DATAFOLD."/config_".$adm_modname.".php", "w");
	$newTimeout = intval($_POST["timeout"]);
	$content = <<<EOT
<?php
if ((!defined('CMS_SYSTEM')) AND (!defined('CMS_ADMIN'))) die('Stop!!!');
\$AdTimeout = $newTimeout; //milliseconds
?>
EOT;
	@fwrite($file, $content);
	@fclose($file);
	@chmod(RPATH.DATAFOLD."/config_".$adm_modname.".php", 0644);
	updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _ADV_CONFIG);
	header("Location: modules.php?f=$adm_modname&do=$do&bf");
}

include("page_header.php");

echo "<form method=\"POST\" action=\"modules.php?f=$adm_modname&do=$do\"><table align=\"center\" border=\"0\" width=\"\" cellspacing=\"0\" cellpadding=\"3\" class=\"tableborder\">\n";
echo "<tr><td colspan=\"2\" class=\"header\">"._CONFIG."</td></tr>";
echo "<tr>\n";
echo "<td width=\"50%\" align=\"right\" class=\"row1\"><b>"._ADV_CONFIG_TIMEOUT.": </b></td>\n";
echo "<td width=\"50%\" class=\"row1\"><b><input type=\"text\" name=\"timeout\" value=\"$AdTimeout\"></b></td>\n";
echo "</tr>\n";
echo "<tr><td colspan=\"2\" class=\"row3\" align=\"center\"><input type=\"submit\" value=\""._SAVECHANGES."\" name=\"submit\"></td></tr>\n";
echo "</table></form>";
include_once("page_footer.php");
?>