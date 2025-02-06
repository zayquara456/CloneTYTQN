<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

$id = intval($_GET['id']);
$result = $db->sql_query("SELECT title, bwidth, bheight, abs, type FROM ".$prefix."_advertise_banners WHERE bnid='$id'");
if(empty($id) || $db->sql_numrows($result) != 1) {
	header("Location: modules.php?f=".$adm_modname."&do=banners");
	exit;
}

list($title, $bwidth, $bheight, $absbn, $bntype) = $db->sql_fetchrow($result);

$err_title = $err_size ="";
if(isset($_POST['subup']) && $_POST['subup'] == 1) {
	$title = trim(stripslashes(resString($_POST['title'])));
	$bwidth = intval($_POST['bwidth']);
	$bheight= intval($_POST['bheight']);
	$absbn = intval($_POST['absbn']);
	$bntype = intval($_POST['bntype']);

	if($title =="") {
		$err_title = "<font color=\"red\">"._ERROR1."</font><br/>";
		$err = 1;
	}

	if($db->sql_numrows($db->sql_query("SELECT*FROM ".$prefix."_advertise_banners WHERE title='$title' AND bnid!='$id'")) > 0) {
		$err_title = "<font color=\"red\">"._ERROR6."</font><br/>";
		$err = 1;
	}

	if(empty($bwidth)) {
		$err_size = "<font color=\"red\">"._ERROR7."</font><br/>";
		$err = 1;
	}

	if(empty($bheight) && !$err) {
		$err_size = "<font color=\"red\">"._ERROR8."</font><br/>";
		$err = 1;
	}

	if(!$err) {
		$db->sql_query("UPDATE ".$prefix."_advertise_banners SET title='$title', bwidth='$bwidth', bheight='$bheight', abs='$absbn', type='$bntype' WHERE bnid='$id'");
		header("Location: modules.php?f=".$adm_modname."&do=banners");
		updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _EDIT);
		exit;
	}
}

include("page_header.php");

echo "<script language=\"javascript\">\n";
echo "	function check(f) {\n";
echo "		if(f.title.value =='') {\n";
echo "			alert('"._ERROR5."');\n";
echo "			f.title.focus();\n";
echo "			return false;\n";
echo "		}	\n";
echo "		if(f.bwidth.value =='') {\n";
echo "			alert('"._ERROR7."');\n";
echo "			f.bwidth.focus();\n";
echo "			return false;\n";
echo "		}	\n";
echo "		if(f.bheight.value =='') {\n";
echo "			alert('"._ERROR8."');\n";
echo "			f.bheight.focus();\n";
echo "			return false;\n";
echo "		}	\n";
echo "		f.submit.disabled = true;\n";
echo "		return true;	\n";
echo "	}	\n";
echo "</script>	\n";

echo "<form action=\"modules.php?f=$adm_modname&do=$do&id=$id\" method=\"POST\" enctype=\"multipart/form-data\" onsubmit=\"return check(this);\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
echo "<tr><td class=\"header\" colspan=\"2\">"._ADD_BANNERS."</td></tr>";
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._TITLE."</b></td>\n";
echo "<td class=\"row3\">$err_title<input type=\"text\" name=\"title\" value=\"$title\" size=\"60\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._BNSIZES."</b></td>\n";
echo "<td class=\"row3\">$err_size<input type=\"text\" name=\"bwidth\" value=\"$bwidth\" size=\"6\"> X <input type=\"text\" name=\"bheight\" value=\"$bheight\" size=\"6\"> (width - height)</td>\n";
echo "</tr>\n";
if($absbn == 1) {
	$seld =" checked";
} else {
	$seld ="";
}
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._ABSSIZE."</b></td>\n";
echo "<td class=\"row3\"><input type=\"checkbox\" name=\"absbn\" value=\"1\"$seld></td>\n";
echo "</tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._BNTYPES."</b></td>\n";
echo "<td class=\"row3\"><select name=\"bntype\">";
$bntype_arr = array(_BNTYPES1,_BNTYPES2);
for($i =0; $i < sizeof($bntype_arr); $i ++) {
	$seld1 ="";
	if($i == $bntype) {
		$seld1 =" selected";
	}
	echo "<option value=\"$i\"$seld1>$bntype_arr[$i]</option>";
}
echo "</select></td>\n";
echo "<input type=\"hidden\" name=\"subup\" value=\"1\">";
echo "<tr><td class=\"row3\">&nbsp;</td><td class=\"row3\"><input type=\"submit\" name=\"submit\" value=\""._SAVECHANGES."\" class=\"input1\"></td></tr>";
echo "</table></form><br/>";

include_once("page_footer.php");
?>