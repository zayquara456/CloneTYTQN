<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

if(isset($_POST['submit'])) {
	$config1 = intval($_POST['config1']);
	$config2 = intval($_POST['config2']);

	@chmod("".RPATH."".DATAFOLD."/config_".$adm_modname.".php", 0777);
	@$file = fopen("".RPATH."".DATAFOLD."/config_".$adm_modname.".php", "w");
	$content = "<?php\n\n";
	$content .= "if ((!defined('CMS_SYSTEM')) AND (!defined('CMS_ADMIN'))) {\n";
	$content .= "die('Stop!!!');\n";
	$content .= "}\n";
	$content .= "\n";
	$content .= "\$sv_timecorrect = $config1;\n";
	$content .= "\$svblocktype = $config2;\n";

	@$writefile = fwrite($file, $content);
	@fclose($file);
	@chmod("".RPATH."".DATAFOLD."/config_".$adm_modname.".php", 0604);
	header("Location: modules.php?f=".$adm_modname."&do=$do&bf");
}

include("page_header.php");

echo "<form action=\"modules.php?f=$adm_modname&do=$do\" method=\"POST\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" class=\"tableborder\">\n";
echo "<tr><td colspan=\"2\" class=\"header\">"._CONFIGSV."</td></tr>";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\" width=\"40%\"><b>"._SVCONFIG1."</b></td>\n";
echo "<td class=\"row3\"><input type=\"text\" name=\"config1\" value=\"$sv_timecorrect\" size=\"10\" maxlength=\"2\"></td>\n";
echo "</tr>\n";
$sfcf2_arr = array(_SVCONFIG2_1,_SVCONFIG2_2);
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\" width=\"40%\"><b>"._SVCONFIG2."</b></td>\n";
echo "<td class=\"row3\"><select name=\"config2\">\n";
for($i =0; $i < 2; $i ++) {
	$seld ="";
	if($i == $svblocktype) { $seld =" selected"; }
	echo "<option value=\"$i\"$seld>$sfcf2_arr[$i]</option>\n";
}
echo "</select></td>\n";
echo "</tr>\n";
echo "<tr><td></td><td><input type=\"submit\" name=\"submit\" value=\""._SAVECHANGES."\" class=\"button2\"></td></tr>";
echo "</table></form>";
include_once("page_footer.php");
?>