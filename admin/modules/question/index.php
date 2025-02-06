<?php

if(!defined('CMS_ADMIN')) die("Illegal File Access");

include_once("page_header.php");
$menu_type=$where="";
$page = isset($_GET['page']) ? intval($_GET['page']) : (isset($_POST['page']) ? intval($_POST['page']) :1);

if(isset($_POST['subup']) && $_POST['subup'] == 1) {
	$question = trim(stripslashes(resString($_POST['question'])));
	$answer = trim(stripslashes(resString($_POST['answer'])));
	$active = 1;
	//$menu_type="main_menu";
	if($question =="") {
		$err_question = "<font color=\"red\">"._ERROR1."</font><br/>";
		$err = 1;
	}	
	
	if(!$err) {
		$weight = WeightMax("question");
		$db->sql_query("INSERT INTO ".$prefix."_question (id, question, answer, weight, active) VALUES (NULL, '$question', '$answer', '$weight','$active')");
		fixweight_mn();
		header("Location: modules.php?f=".$adm_modname."");
	}	
}
else {
	$err_question = "";
	$err_url = "";
	$question  = "";
	$answer  = "";
}

ajaxload_content();

echo "<script language=\"javascript\">\n";
echo "function check(f) {\n";
echo "if(f.title.value =='') {\n";
echo "alert('"._ERROR1."');\n";
echo "f.title.focus();\n";
echo "return false;\n";
echo "}\n";
echo "if(f.url.value =='') {\n";
echo "alert('"._ERROR2."');\n";
echo "f.url.focus();\n";
echo "return false;\n";
echo "}\n";
echo "f.submit.disabled = true;\n";
echo "return true;	\n";
echo "}	\n";
echo "</script>	\n";


echo "<div id=\"".$adm_modname."_main\"><form action=\"modules.php?f=$adm_modname&page=$page\" method=\"POST\" onsubmit=\"return check(this);\" enctype=\"multipart/form-data\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
echo "<tr><td colspan=\"2\" class=\"header\">Tạo câu hỏi ngẫu nhiên</td></tr>";
echo "<tr>\n";
echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>Câu hỏi:</b></td>\n";
echo "<td class=\"row2\">$err_question<input type=\"text\" name=\"question\" value=\"$question\" size=\"40\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>Trả lời:</b></td>\n";
echo "<td class=\"row2\">$err_answer<input type=\"text\" name=\"answer\" value=\"$answer\" size=\"50\"></td>\n";
echo "</tr>\n";
echo "<input type=\"hidden\" name=\"subup\" value=\"1\">";
echo "<tr><td colspan=\"2\" align=\"center\" class=\"row1\"><input type=\"submit\" name=\"submit\" value=\""._ADD."\"></td></tr>";
echo "</table></form>";

//////////////////////////////////DANH SACH MENU//////////////////////////////////////////////////
$perpage = 200;
$offset = ($page-1) * $perpage;
$total = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_question "));
$resultcat = $db->sql_query("SELECT id, question, answer, weight, active  FROM ".$prefix."_question  ORDER BY weight,id ASC LIMIT $offset, $perpage");
if($db->sql_numrows($resultcat) > 0) {
echo "<script language=\"javascript\" type=\"text/javascript\">\n";
echo "function check_uncheck(){\n";
echo "var f= document.frm;\n";
echo "if(f.checkall.checked){\n";
echo "CheckAllCheckbox(f,'mid[]');\n";
echo "}else{\n";
echo "UnCheckAllCheckbox(f,'mid[]');\n";
echo "}			\n";
echo "}\n";
echo "function checkQuick(f) {\n";
echo "if(f.f.value =='') {\n";
echo "f.f.focus();\n";
echo "return false;\n";
echo "}\n";
echo "return true;		\n";
echo "}	\n";
echo "</script>\n";		
echo "<br/><form name=\"frm\" action=\"modules.php\" method=\"POST\" onsubmit=\"return checkQuick(this);\">";
echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
echo "<tr><td colspan=\"9\" class=\"header\">Danh sách câu hỏi</td></tr>";
echo "<tr>\n";
echo "<td width=\"1%\" align=\"center\" class=\"row1sd\"><input type=\"checkbox\" name=\"checkall\" onclick=\"javascript:check_uncheck();\" title=\""._CHECKALL."\"></td>\n";
echo "<td class=\"row1sd\">Câu hỏi</td>\n";
echo "<td class=\"row1sd\">trả lời</td>\n";
echo "<td align=\"center\" width=\"50\" class=\"row1sd\">"._WEIGHT."</td>\n";
echo "<td align=\"center\" width=\"50\" class=\"row1sd\"><b>active</b></td>\n";
echo "<td align=\"center\" width=\"30\" class=\"row1sd\">"._EDIT."</td>\n";
echo "<td align=\"center\" width=\"30\" class=\"row1sd\">"._DELETE."</td>\n";
echo "</tr>\n";
while(list($mid, $question, $answer, $weight, $active) = $db->sql_fetchrow($resultcat)) {
/*if($ajax_active == 1) {	
	switch($active) {
		case 1: $active = "<a href=\"?f=$adm_modname&do=status&menu_type=$menu_type&id=$mid&stat=0\" title=\""._DEACTIVATE."\" onclick=\"return aj_base_status_type($mid,0,'$adm_modname','','','$menu_type');\"><img border=\"0\" src=\"images/view.png\"></a>"; break;
		case 0: $active = "<a href=\"?f=$adm_modname&do=status&menu_type=$menu_type&id=$mid&stat=1\" title=\""._ACTIVE."\" onclick=\"return aj_base_status_type($mid,1,'$adm_modname','','','$menu_type');\"><img border=\"0\" src=\"images/viewo.png\"></a>"; break;
	}	
} else {*/
	switch($active) {
		case 1: $active = "<a href=\"?f=$adm_modname&do=status&&id=$mid&stat=0\" info=\""._DEACTIVATE."\"><img border=\"0\" src=\"images/view.png\"></a>"; break;
		case 0: $active = "<a href=\"?f=$adm_modname&do=status&id=$mid&stat=1\" info=\""._ACTIVE."\"><img border=\"0\" src=\"images/viewo.png\"></a>"; break;
	}	
//}

switch($target) {
	case 1: $target = "<img border=\"0\" src=\"images/ticko.png\">"; break;	
	case 0: $target = "<img border=\"0\" src=\"images/tick.png\">"; break;	
}	
	
echo "<tr>\n";
echo "<td align=\"center\" class=\"row1\"><input type=\"checkbox\" name=\"mid[]\" value=\"$mid\"></td>\n";
//if($ajax_active == 1) {
//	echo "<td class=\"row1\" id=\"".$adm_modname."_title_edit_".$mid."\"><a href=\"modules.php?f=$adm_modname&do=edit&menu_type=$menu_type&mid=$mid\" title=\""._QUICK_EDIT."\" onclick=\"return show_edit_title_type($mid,'$title','$adm_modname',30,'"._SAVECHANGES."','','$menu_type');\"><b>$title</b></a></td>\n";
//} else {
	echo "<td class=\"row1\"><b>$question</b></td>\n";
//}
echo "<td class=\"row1\">$answer</td>\n";
echo "<td align=\"center\" class=\"row1\"><input type=\"text\" name=\"poz[$mid]\" value=\"$weight\" maxlength=\"2\" style=\"text-align: center; width: 30px; font-weight: bold\"></td>\n";
echo "<td align=\"center\" class=\"row1\">$active</td>\n";
echo "<td align=\"center\" class=\"row1\"><a href=\"?f=$adm_modname&do=edit&mid=$mid\" info=\""._EDIT."\"><img border=\"0\" src=\"images/edit.png\"></a></td>\n";
//if($ajax_active == 1) {
//	echo "<td align=\"center\" class=\"row1\"><a href=\"?f=$adm_modname&do=delete&menu_type=$menu_type&mid=$mid\" title=\""._DELETE."\" onclick=\"return aj_base_delete($mid,'$adm_modname','"._DELETEASK."','','mid');\"><img border=\"0\" src=\"images/delete.png\"></td>\n";
//} else {
	echo "<td align=\"center\" class=\"row1\"><a href=\"?f=$adm_modname&do=delete&mid=$mid\" info=\""._DELETE."\" onclick=\"return confirm('"._DELETEASK."');\"><img border=\"0\" src=\"images/delete.png\"></td>\n";
//}
}
echo "<input type=\"hidden\" name=\"f\" value=\"$adm_modname\">";
echo "<input type=\"hidden\" name=\"do\" value=\"quick\">";
if($total > $perpage) {
	echo "<tr><td colspan=\"9\">";	
	$pageurl = "modules.php?f=".$adm_modname."";
	echo paging($total,$pageurl,$perpage,$page);
	echo "</td></tr>";
}	
echo "<tr><td colspan=\"8\"><select name=\"v\">";
echo "<option value=\"\">&raquo; "._QUICKDO."</option>";
echo "<option value=\"1\">&raquo; "._QUICKDO_2."</option>";
echo "<option value=\"2\">&raquo; "._QUICKDO_3."</option>";
echo "<option value=\"3\">&raquo; "._QUICKDO_4."</option>";
echo "<option value=\"4\">&raquo; "._QUICKDO_1."</option>";
echo "</select> <input type=\"submit\" value=\""._DOACTION."\"></td></tr>";
echo "</table></form></div><br/>";
}

include_once("page_footer.php");

?>