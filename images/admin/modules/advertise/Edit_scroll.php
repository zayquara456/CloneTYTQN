<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

$id = intval($_GET['id']);
$result = $db->sql_query("SELECT title, links, images, imgtext, target, poz FROM ".$prefix."_advertise_scroll WHERE id='$id' AND alanguage='$currentlang'");
if(empty($id) || $db->sql_numrows($result) != 1) {
	header("Location: ".$adm_modname.".php?do=scroll");
	exit;
}

list($title, $links, $images, $imgtext, $target, $poz) = $db->sql_fetchrow($result);
include("page_header.php");

$err_title = $err_links = $err_post = "";
if(isset($_POST['subup']) && $_POST['subup'] == 1) {
	$title = trim(stripslashes(resString($_POST['title'])));
	$links = trim(stripslashes(resString($_POST['links'])));
	$imgtext = trim(stripslashes(resString($_POST['imgtext'])));
	$images = trim(stripslashes(resString($_POST['images'])));
	$target = intval($_POST['target']);
	$poz = intval($_POST['poz']);

	if(empty($title)) {
		$err_title = "<font color=\"red\">"._ERROR1."</font><br/>";
		$err = 1;
	}

	if(empty($links) || !preg_match('!^http(s)?://!i', $links)) {
		$err_links = "<font color=\"red\">"._ERROR2."</font><br/>";
		$err = 1;
	}

	if(!$err) {
		if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {
			@unlink("../$path_upload/adv/$images");
			$upload = new Upload("userfile", "$path_upload/adv", $maxsize_up, "adv");
			$img_up = $upload->send();
		} else {
			$img_up = $images;
		}

		if (empty($img_up)) $imgtext = "";

		$db->sql_query("UPDATE ".$prefix."_advertise_scroll SET title='$title', links='$links', images='$img_up', imgtext='$imgtext', target='$target', poz='$poz' WHERE id='$id'");
		fixweight_scroll();
		header("Location: modules.php?f=".$adm_modname."&do=scroll");
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
echo "		if(f.bnid.value == 0) {\n";
echo "			alert('"._ERROR4."');\n";
echo "			f.bnid.focus();\n";
echo "			return false;\n";
echo "		}	\n";
echo "		f.submit.disabled = true;\n";
echo "		return true;	\n";
echo "	}	\n";
echo "</script>	\n";

echo "<form action=\"modules.php?f=$adm_modname&do=$do&id=$id\" method=\"POST\" enctype=\"multipart/form-data\" onsubmit=\"return check(this);\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" class=\"tableborder\">\n";
echo "<tr><td class=\"header\" colspan=\"2\">"._ADD_ADV."</td></tr>";
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._TITLE."</b></td>\n";
echo "<td class=\"row2\">$err_title<input type=\"text\" name=\"title\" value=\"$title\" size=\"60\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._URL."</b> (http://...)</td>\n";
echo "<td class=\"row2\">$err_links<input type=\"text\" name=\"links\" value=\"$links\" size=\"60\"></td>\n";
echo "</tr>\n";
if($images !="" && file_exists("../$path_upload/adv/$images")) {
	echo "	<tr>\n";
	echo "		<td align=\"right\" class=\"row1\"><b>"._CHANGEIMG."</b></td>\n";
	echo "		<td class=\"row2\"><input type=\"file\" name=\"userfile\" size=\"40\"></td>\n";
	echo "	</tr>\n";
	echo "<input type=\"hidden\" name=\"images\" value=\"$images\">";
} else {
	echo "<tr>\n";
	echo "<td align=\"right\" class=\"row1\"><b>"._IMAGEADV."</b></td>\n";
	echo "<td class=\"row2\"><input type=\"file\" name=\"userfile\" size=\"50\"></td>\n";
	echo "</tr>\n";
}
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
echo "<td class=\"row2\">$err_post<select name=\"poz\">";
$poz_arr = array(_LEFT,_RIGHT);
for($i =0; $i < 2; $i ++) {
	$seld ="";
	if($i == $poz) { $seld ="selected"; }
	echo "<option value=\"$i\"$seld>$poz_arr[$i]</option>";
}
echo "</select></td>\n";
echo "</tr>\n";
echo "<input type=\"hidden\" name=\"subup\" value=\"1\">";
echo "<tr class=\"row4\"><td></td><td><input type=\"submit\" name=\"submit\" value=\""._SAVECHANGES."\" class=\"input1\"></td></tr>";
echo "</table></form><br/>";

include_once("page_footer.php");
?>