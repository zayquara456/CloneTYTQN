<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

if(isset($_POST['submit'])) {
	$config1 = intval($_POST['config1']);
	$config2 = intval($_POST['config2']);
	$config3 = intval($_POST['config3']);
	$config4 = intval($_POST['config4']);
	$config5 = intval($_POST['config5']);
	$config6 = intval($_POST['config6']);
	$config7 = intval($_POST['config7']);
	$config8 = intval($_POST['config8']);
	$config9 = intval($_POST['config9']);
	$config10 = intval($_POST['config10']);

	@chmod(RPATH.DATAFOLD."/config_{$adm_modname}.php", 0777);
	@$file = fopen(RPATH.DATAFOLD."/config_{$adm_modname}.php", "w");
	$content = "<?php\n\n";
	$content .= "if ((!defined('CMS_SYSTEM')) AND (!defined('CMS_ADMIN'))) {\n";
	$content .= "die('Stop!!!');\n";
	$content .= "}\n";
	$content .= "\n";
	$content .= "\$prd_thumbwidth = $config1;\n";
	$content .= "\$prd_thumbheight = $config2;\n";
	$content .= "\$prd_sizedetail = $config3;\n";
	$content .= "\$prd_perpage = $config4;\n";
	$content .= "\$prd_nums_other = $config5;\n";
	$content .= "\$prd_nums_home = $config6;\n";
	$content .= "\$prd_nums_news_bl = $config7;\n";
	$content .= "\$prd_nums_tops_bl = $config8;\n";
	$content .= "\$prd_perpage_cathome = $config9;\n";
	$content .= "\$prd_desc_max = $config10;\n";
	//$content .= "\$prd_view_type = \"$config10;\"\n";
	@$writefile = fwrite($file, $content);
	@fclose($file);
	@chmod(RPATH.DATAFOLD."/config_{$adm_modname}.php", 0604);
	updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _PRD_CONFIG);
	header("Location: modules.php?f=".$adm_modname."&do=$do&bf");
}

include_once("page_header.php");

echo "<br/><form action=\"modules.php?f=$adm_modname&do=$do\" method=\"POST\"><table align=\"center\" border=\"0\" width=\"\" cellspacing=\"0\" cellpadding=\"3\" class=\"tableborder\">\n";
echo "<tr><td colspan=\"2\" class=\"header\">"._MODTITLE." &raquo; "._CONFIG."</td></tr>";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._CONFIG1."</b></td>\n";
echo "<td class=\"row3\"><input type=\"text\" name=\"config1\" value=\"$prd_thumbwidth\" size=\"5\"> (pixels)</td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._CONFIG2."</b></td>\n";
echo "<td class=\"row3\"><input type=\"text\" name=\"config2\" value=\"$prd_thumbheight\" size=\"5\"> (pixels)</td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._CONFIG3."</b></td>\n";
echo "<td class=\"row3\"><input type=\"text\" name=\"config3\" value=\"$prd_sizedetail\" size=\"5\"> (pixels)</td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._CONFIG4."</b></td>\n";
echo "<td class=\"row3\"><input type=\"text\" name=\"config4\" value=\"$prd_perpage\" size=\"5\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._CONFIG5."</b></td>\n";
echo "<td class=\"row3\"><input type=\"text\" name=\"config5\" value=\"$prd_nums_other\" size=\"5\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._CONFIG6."</b></td>\n";
echo "<td class=\"row3\"><input type=\"text\" name=\"config6\" value=\"$prd_nums_home\" size=\"5\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._CONFIG7."</b></td>\n";
echo "<td class=\"row3\"><input type=\"text\" name=\"config7\" value=\"$prd_nums_news_bl\" size=\"5\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._CONFIG8."</b></td>\n";
echo "<td class=\"row3\"><input type=\"text\" name=\"config8\" value=\"$prd_nums_tops_bl\" size=\"5\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._CONFIG9."</b></td>\n";
echo "<td class=\"row3\"><input type=\"text\" name=\"config9\" value=\"$prd_perpage_cathome\" size=\"5\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._CONFIG10."</b></td>\n";
echo "<td class=\"row3\"><input type=\"text\" name=\"config10\" value=\"$prd_desc_max\" size=\"5\"></td>\n";
echo "</tr>\n";
//echo "<tr>\n";
//echo "<td align=\"right\" class=\"row1\"><b>"._CONFIG10."</b></td>\n";
//echo "<td class=\"row3\"><input type=\"text\" name=\"config10\" value=\"$prd_view_type\" size=\"5\"></td>\n";
//echo "</tr>\n";
echo "<tr><td colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"submit\" value=\""._SAVECHANGES."\" class=\"button2\"></td></tr>";
echo "</table></form>";

include_once("page_footer.php");
?>
