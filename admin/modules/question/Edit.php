<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

$mid2 = intval(isset($_GET['mid']) ? $_GET['mid'] : $_POST['mid']);

$result = $db->sql_query("SELECT id, question, answer, active FROM ".$prefix."_question WHERE $where id='$mid2'");
if(empty($mid2) || $db->sql_numrows($result) != 1) {
	header("Location: $adm_modname.php");
	exit;
}

list($midedit,$questionedit, $answeredit, $acitveedit) = $db->sql_fetchrow($result);

$err_question = $err_answer = "";
if(isset($_POST['subup']) && $_POST['subup'] == 1) {
	$question = trim(stripslashes(resString($_POST['question'])));
	$answer = trim(stripslashes(resString($_POST['answer'])));
	//$menu_type="main_menu";
	
	$err = 0;
	if($question =="") {
		$err_question = "<font color=\"red\">"._ERROR1."</font><br/>";
		$err = 1;
	}

	if($answer =="") {
		$err_answer = "<font color=\"red\">"._ERROR2."</font><br/>";
		$err = 1;
	}

	if(!$err) {
		$db->sql_query("UPDATE ".$prefix."_question SET question='$question', answer='$answer' WHERE id='$mid2'");
		fixweight_mn();
		Header("Location: modules.php?f=".$adm_modname."");
		exit();
	}
}

include("page_header.php");
echo "<script language=\"javascript\">\n";
echo "	function check(f) {\n";
echo "		if(f.title.value =='') {\n";
echo "			alert('"._ERROR1."');\n";
echo "			f.title.focus();\n";
echo "			return false;\n";
echo "		}\n";
echo "		if(f.url.value =='') {\n";
echo "			alert('"._ERROR2."');\n";
echo "			f.url.focus();\n";
echo "			return false;\n";
echo "		}\n";
echo "		f.submit.disabled = true;\n";
echo "		return true;	\n";
echo "	}	\n";
echo "</script>	\n";

echo "<form action=\"modules.php?f=$adm_modname&do=$do&mid=$midedit\" method=\"POST\" onsubmit=\"return check(this);\" enctype=\"multipart/form-data\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
echo "<tr><td colspan=\"2\" class=\"header\">Sửa câu hỏi</td></tr>";
echo "<tr>\n";
echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>Câu hỏi</b></td>\n";
echo "<td class=\"row2\">$err_question<input type=\"text\" name=\"question\" value=\"$questionedit\" size=\"40\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>Trả lời</b></td>\n";
echo "<td class=\"row2\">$err_answer<input type=\"text\" name=\"answer\" value=\"$answeredit\" size=\"50\"></td>\n";
echo "</tr>\n";
echo "<input type=\"hidden\" name=\"subup\" value=\"1\">";
echo "<tr><td colspan=\"2\" align=\"center\" class=\"row1\"><input type=\"submit\" name=\"submit\" value=\""._SAVECHANGES."\"></td></tr>";
echo "</table></form>";

include_once("page_footer.php");

?>