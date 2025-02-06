<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

include("page_header.php");

$target = 1;
$err_title = $err_links = $title = $links = $err_img = $imgtext = $err_post ="";
if(isset($_POST['subup']) && $_POST['subup'] == 1) {
	$title = trim(stripslashes(resString($_POST['title'])));
	$links = trim(stripslashes(resString($_POST['links'])));
	$target = intval($_POST['target']);
	$imgtext = trim(stripslashes(resString($_POST['imgtext'])));
	$bnid = intval($_POST['bnid']);
	$bmodule = $_POST['bmodule'];

	if(@in_array("all",$bmodule)) {$bmodule_up ="all"; }else{
		$bmodule_up = @implode("|",$bmodule);
	}
	if(empty($bmodule_up)) { $bmodule_up = "all"; }

	if(empty($title)) {
		$err_title = "<font color=\"red\">"._ERROR1."</font><br/>";
		$err = 1;
	}

	if(empty($links) || !preg_match('!^http(s)?://!i', $links)) {
		$err_links = "<font color=\"red\">"._ERROR2."</font><br/>";
		$err = 1;
	}

	if (!is_uploaded_file($_FILES['userfile']['tmp_name'])) {
		$err_img = "<font color=\"red\">"._ERROR3."</font><br/>";
		$err = 1;
	}

	if($bnid == 0) {
		$err_post = "<font color=\"red\">"._ERROR4."</font><br/>";
		$err = 1;
	}

	if(!isset($err)) {
		if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {
			$ext_allow = array("jpg","jpeg","gif","png", "swf");
			$upload = new Upload("userfile", "$path_upload/adv", $maxsize_up, "adv");
			$images = $upload->send();
		}
		$weight = WeightMax("advertise","",$position);
		$db->sql_query("INSERT INTO ".$prefix."_advertise (id, bnid, title, links, time, target, images, imgtext, alanguage, weight, module) VALUES (NULL, '$bnid', '$title', '$links', '".time()."', '$target', '$images', '$imgtext', '$currentlang', '$weight', '$bmodule_up')");
		fixcountbn();
		fixweight();
		updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _ADD);
		header("Location: modules.php?f=$adm_modname&do=viewadv&id=$bnid");
	}
}

echo "<script language=\"javascript\">\n";
echo "	function check(f) {\n";
echo "		if(f.title.value =='') {\n";
echo "			alert('"._ERROR1."');\n";
echo "			f.title.focus();\n";
echo "			return false;\n";
echo "		}	\n";
echo "		if(f.links.value =='') {\n";
echo "			alert('"._ERROR2."');\n";
echo "			f.links.focus();\n";
echo "			return false;\n";
echo "		}	\n";
echo "		if(f.userfile.value =='') {\n";
echo "			alert('"._ERROR3."');\n";
echo "			f.userfile.focus();\n";
echo "			return false;\n";
echo "		}	\n";
echo "		if(f.bnid.value == 0) {\n";
echo "			alert('"._ERROR4."');\n";
echo "			f.bnid.focus();\n";
echo "			return false;\n";
echo "		}	\n";
echo "		f.submit.disabled = true;\n";
echo "		return true;	\n";
echo "	}	\n";
echo "</script>	\n";

echo "<form action=\"modules.php?f=$adm_modname&do=$do\" method=\"POST\" enctype=\"multipart/form-data\" onsubmit=\"return check(this);\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" class=\"tableborder\">\n";
echo "<tr><td class=\"header\" colspan=\"2\">"._ADD_ADV."</td></tr>";
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._TITLE."</b></td>\n";
echo "<td class=\"row2\">$err_title<input type=\"text\" name=\"title\" value=\"$title\" size=\"60\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._URL."</b> (http://...)</td>\n";
echo "<td class=\"row2\">$err_links<input type=\"text\" name=\"links\" value=\"$links\" size=\"60\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._IMAGEADV."</b></td>\n";
echo "<td class=\"row2\">$err_img<input type=\"file\" name=\"userfile\" size=\"50\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._IMGTEXT."</b></td>\n";
echo "<td class=\"row2\"><input type=\"text\" name=\"imgtext\" value=\"$imgtext\" size=\"70\" maxlength=\"253\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._NEWWINDOW."</b></td>\n";
if($target == 0) {
	echo "<td  class=\"row2\"><input type=\"radio\" name=\"target\" value=\"1\">"._YES." &nbsp;&nbsp;"
	."<input type=\"radio\" name=\"target\" value=\"0\" checked>"._NO."</td>\n";
} else {
	echo "<td  class=\"row2\"><input type=\"radio\" name=\"target\" value=\"1\" checked>"._YES." &nbsp;&nbsp;"
	."<input type=\"radio\" name=\"target\" value=\"0\">"._NO."</td>\n";
}
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._POSITION."</b></td>\n";
echo "<td class=\"row2\">$err_post<select name=\"bnid\">";
echo "<option value=\"0\">-----------</option>";
$result_bn = $db->sql_query("SELECT bnid, title FROM ".$prefix."_advertise_banners WHERE active='1' ORDER BY title");
if($db->sql_numrows($result_bn) > 0) {
	while(list($bnid, $titlebn) = $db->sql_fetchrow($result_bn)) {
		echo "<option value=\"$bnid\">$titlebn</option>";
	}
}
echo "</select></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\" valign=\"top\"><b>"._AREA."</b></td>\n";
echo "<td class=\"row2\">\n";
echo "<table border=\"0\" style=\"border-collapse: collapse\" cellpadding=\"4\" cellspacing=\"0\"><tr>\n";
if(@in_array("all",$bmodule)) { $seldall =" checked"; } else { $seldall =""; }
if(@in_array("home",$bmodule) && !@in_array("all",$bmodule)) { $seldhome =" checked"; } else { $seldhome =""; }
echo "<td><input type=\"checkbox\" name=\"bmodule[]\" value=\"all\"$seldall> "._ALL."</td>\n"
."<td><input type=\"checkbox\" name=\"bmodule[]\" value=\"home\"$seldhome> "._HOMEPAGE."</td>\n"
."</tr></table>\n";
echo "<table border=\"0\" style=\"border-collapse: collapse\" cellpadding=\"2\" cellspacing=\"0\"><tr>\n";
$a =0;
for($l=0;$l < sizeof($listmods);$l++) {
	$title = ereg_replace("_", " ", $listmods[$l]);
	$xstitle = strtolower($listmods[$l]);
	$seld ="";
	if(@in_array($listmods[$l],$bmodule) && !in_array("all",$bmodule)) {
		$seld =" checked";
	}

	if(!@in_array($title,$listmods_noaccept)) {
		$a ++;
		echo "<td style=\"padding-right: 30px\"><input type=\"checkbox\" name=\"bmodule[]\" value=\"$listmods[$l]\"$seld> ".$listmods_custom[$l]."</td>";
	}
	if($a == 2) { $a =0; echo "</tr>"; }
}
echo"</table>";
echo "</td>\n";
echo "</tr>\n";
echo "<input type=\"hidden\" name=\"subup\" value=\"1\">";
echo "<tr class=\"row4\"><td></td><td><input type=\"submit\" name=\"submit\" value=\""._ADD."\" class=\"input1\"></td></tr>";
echo "</table></form><br/>";

include_once("page_footer.php");
?>