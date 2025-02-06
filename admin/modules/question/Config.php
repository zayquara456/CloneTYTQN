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

	@chmod(RPATH.DATAFOLD."/config_".$adm_modname.".php", 0666);
	@$file = fopen(RPATH.DATAFOLD."/config_".$adm_modname.".php", "w");
	$content = "<?php\n\n";
	$content .= "if ((!defined('CMS_SYSTEM')) AND (!defined('CMS_ADMIN'))) {\n";
	$content .= "die('Stop!!!');\n";
	$content .= "}\n";
	$content .= "\n";
	$content .= "\$camnang_home_type = $config0;\n";
	$content .= "\$perpage = $config2;\n";
	$content .= "\$camnang_ccd = $config3;\n";
	$content .= "\$pic_align = \"$config4\";\n";
	$content .= "\$pic_align_camnangt = \"$config6\";\n";
	$content .= "\$pic_align_cat = \"$config15\";\n";
	$content .= "\$sizecamnang = $config5;\n";
	@$writefile = fwrite($file, $content);
	@fclose($file);
	@chmod(RPATH.DATAFOLD."/config_".$adm_modname.".php", 0644);
	updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _NCONFIG);
	header("Location: modules.php?f=$adm_modname&do=$do&bf");
}

include_once("page_header.php");

echo "<br><form action=\"modules.php?f=$adm_modname&do=$do\" method=\"POST\"><table align=\"center\" border=\"0\" width=\"\" cellspacing=\"0\" cellpadding=\"3\" class=\"tableborder\">\n";
echo "<tr><td colspan=\"2\" class=\"header\">"._CONFIG."</td></tr>";
echo "<tr>\n";
echo "<td width=\"50%\" align=\"right\" class=\"row1\"><b>"._NCONFIG0."</b></td>\n";
echo "<td class=\"row3\"><select name=\"config0\">";
$nhometype_ar = array(_NHOMETYPE1,_NHOMETYPE2);
for($i = 0; $i < 2; $i ++) {
	$seld ="";
	if($i == $camnang_home_type) { $seld =" selected"; }
	echo "<option value=\"$i\"$seld>$nhometype_ar[$i]</option>";
}
echo "</select></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._NCONFIG2."</b></td>\n";
echo "<td class=\"row3\"><select name=\"config2\">";
for($i = 1; $i <= 30; $i ++) {
	$seld ="";
	if($i == $perpage) { $seld =" selected"; }
	echo "<option value=\"$i\"$seld>$i</option>";
}
echo "</select></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._NCONFIG3."</b></td>\n";
echo "<td class=\"row3\"><select name=\"config3\">";
for($i = 1; $i <= 10; $i ++) {
	$seld ="";
	if($i == $camnang_ccd) { $seld =" selected"; }
	echo "<option value=\"$i\"$seld>$i</option>";
}
echo "</select></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._NCONFIG4."</b></td>\n";
echo "<td class=\"row3\"><select name=\"config4\">";
$pozar = array("left","right");
$poz_nam = array(_LEFT,_RIGHT);
for($i = 0; $i < 2; $i ++) {
	$seld ="";
	if($pozar[$i] == $pic_align) { $seld =" selected"; }
	echo "<option value=\"$pozar[$i]\"$seld>$poz_nam[$i]</option>";
}
echo "</select></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._NCONFIG6."</b></td>\n";
echo "<td class=\"row3\"><select name=\"config6\">";
for($i = 0; $i < 2; $i ++) {
	$seld ="";
	if($pozar[$i] == $pic_align_camnangt) { $seld =" selected"; }
	echo "<option value=\"$pozar[$i]\"$seld>$poz_nam[$i]</option>";
}
echo "</select></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._NCONFIG15."</b></td>\n";
echo "<td class=\"row3\"><select name=\"config15\">";
for($i = 0; $i < 2; $i ++) {
	$seld ="";
	if($pozar[$i] == $pic_align_cat) { $seld =" selected"; }
	echo "<option value=\"$pozar[$i]\"$seld>$poz_nam[$i]</option>";
}
echo "</select></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._NCONFIG5."</b></td>\n";
echo "<td class=\"row3\"><input type=\"text\" name=\"config5\" value=\"$sizecamnang\" size=\"10\"> (pixels)</td>\n";
echo "</tr>\n";
echo "<tr><td colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"submit\" value=\""._SAVECHANGES."\" class=\"button2\"></td></tr>";
echo "</table></form>";

include_once("page_footer.php");
?>