<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

$id = intval(isset($_GET['id']) ? $_GET['id'] : $_POST['id']);
$result = $db->sql_query("SELECT email, html FROM {$prefix}_newsletter WHERE id=$id");
if(empty($id) || $db->sql_numrows($result) != 1) die();

list($email, $ntype) = $db->sql_fetchrow($result);

$err_mail ="";
if(isset($_POST['subup']) && $_POST['subup'] == 1) {
	$email = nospatags($_POST['email']);
	$ntype = intval($_POST['ntype']);

	$stopmail = checkEmail($email,$id);
	if($stopmail) {
		$err_mail = "<font color=\"red\">$stopmail</font><br/>";
	} else {
		$db->sql_query("UPDATE ".$prefix."_newsletter SET email='$email', html='$ntype' WHERE id='$id'");
		header("Location: modules.php?f=".$adm_modname."");
		exit;
	}
}

include("page_header.php");

echo "<script language=\"javascript\">\n";
echo "	function check(f) {\n";
echo "		if(!isEmail(f.email.value)) {\n";
echo "			alert('"._ERROR1."');\n";
echo "			f.email.focus();\n";
echo "			return false;\n";
echo "		}\n";
echo "		fetch_object('ajaxload_container').style.display ='block';\n";
echo "		f.submit.disabled = true;\n";
echo "		return true;	\n";
echo "	}	\n";
echo "</script>	\n";
ajaxload_content();

echo "<br/><form action=\"modules.php?f=$adm_modname&do=$do&id=$id\" method=\"POST\" onsubmit=\"return check(this)\"><table align=\"center\" border=\"0\" width=\"90%\" cellspacing=\"0\" cellpadding=\"3\" class=\"tableborder\">\n";
echo "<tr><td class=\"header\" colspan=\"2\">"._MODTITLE." &raquo; "._EDITEMAIL."</td></tr>";
echo "<tr>\n";
echo "<td width=\"30%\" align=\"right\" class=\"row1\"><b>Email</b></td>\n";
echo "<td class=\"row2\">$err_mail<input type=\"text\" size=\"40\" name=\"email\" value=\"$email\"></td></tr>\n";
echo "<tr>\n";
$ntype_arr = array("Plaintext","HTML");
echo "<td width=\"30%\" align=\"right\" class=\"row1\"><b>"._NTYPE."</b></td>\n";
echo "<td class=\"row2\"><select name=\"ntype\">\n";
for($i =0; $i < 2; $i ++) {
	$seld ="";
	if($i == $ntype) { $seld =" selected"; }
	echo "<option value=\"$i\"$seld>$ntype_arr[$i]</option>\n";
}
echo "</select></td></tr>\n";
echo "<tr><td class=\"row3\">&nbsp;</td><td class=\"row3\"><input type=\"hidden\" name=\"subup\" value=\"1\"><input type=\"submit\" name=\"submit\" value=\""._SAVECHANGES."\" class=\"button2\"></td></tr>";
echo "</table></form>";

include_once("page_footer.php");
?>