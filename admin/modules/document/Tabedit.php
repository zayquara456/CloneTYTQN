<?php

if(!defined('CMS_ADMIN')) die("Illegal File Access");

include_once("page_header.php");
$menu_type=$where="";
$page = isset($_GET['page']) ? intval($_GET['page']) : (isset($_POST['page']) ? intval($_POST['page']) :1);
$newsid =isset($_GET['newsid'])? intval($_GET['newsid']):0;
$type =isset($_GET['type'])? $_GET['type']:"";

if($newsid!=0)
	$where="newsid='$newsid' AND";
else
	header("Location: modules.php?f=".$adm_modname."");
$resultcat = $db->sql_query("SELECT tabid, title, active, weight, content FROM ".$prefix."_news_tab WHERE $where alanguage='$currentlang' ORDER BY weight,tabid ASC");
if($db->sql_numrows($resultcat) != 0) {
	}
	list($tabid, $title, $active, $weight, $content) = $db->sql_fetchrow($resultcat);


if(isset($_POST['subup']) && $_POST['subup'] == 1) {
	$title = trim(stripslashes(resString($_POST['title'])));
	$content = $escape_mysql_string(trim($_POST['content']));
	//$newsid= $newsid;
	if($title =="") {
		$err_title = "<font color=\"red\">"._ERROR_TAB1."</font><br/>";
		$err = 1;
	}	
	
	//if($content =="") {
	//	$err_content = "<font color=\"red\">"._ERROR_TAB2."</font><br/>";
	//	$err = 1;
	//}	
	
	if(!$err) {
		$guid="index.php?f=news&do=categories&id=$catid";
		$db->sql_query("UPDATE ".$prefix."_news_tab SET title='$title',content='$content' WHERE tabid='$tabid'");
		header("Location: modules.php?f=".$adm_modname."&do=tabnews&newsid=$newsid&newsid=$newsid");
	}	
}
else {
	//$err_title = "";
	//$err_url = "";
	//$title  = "";
	//$url  = "";
}

ajaxload_content();

echo "<script language=\"javascript\">\n";
echo "function check(f) {\n";
echo "if(f.title.value =='') {\n";
echo "alert('"._ERROR_TAB1."');\n";
echo "f.title.focus();\n";
echo "return false;\n";
echo "}\n";
/*echo "if(f.content.value =='') {\n";
echo "alert('"._ERROR_TAB2."');\n";
echo "f.content.focus();\n";
echo "return false;\n";
echo "}\n";*/
echo "f.submit.disabled = true;\n";
echo "return true;	\n";
echo "}	\n";
echo "</script>	\n";


echo "<div id=\"".$adm_modname."_main\"><form action=\"modules.php?f=$adm_modname&do=tabedit&newsid=$newsid\" method=\"POST\" onsubmit=\"return check(this);\" enctype=\"multipart/form-data\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
echo "<tr><td colspan=\"2\" class=\"header\">"._CREATE_TABS."</td></tr>";
echo "<tr>\n";
echo "<td width=\"200px\" align=\"right\" class=\"row1\"><b>"._TITLE."</b></td>\n";
echo "<td class=\"row2\">$err_title<input type=\"text\" name=\"title\" value=\"$title\" size=\"40\"></td>\n";
echo "</tr>\n";
/*echo "<tr>\n";
echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>"._CONTENT_TAB."</b></td>\n";
echo "<td class=\"row2\">$err_url<input type=\"text\" name=\"content\" value=\"$content\" size=\"50\"></td>\n";
echo "</tr>\n";*/
echo "<tr>\n";
		echo "<td align=\"right\" class=\"row1\"><b>"._BODYTEXT."</b></td>\n";
		echo "<td class=\"row2\" colspan=\"2\">$err_content";
		editor("content", $content,"",400);
		echo "</td>\n";
		echo "</tr>\n";
echo "<input type=\"hidden\" name=\"subup\" value=\"1\">";
echo "<tr><td colspan=\"2\" align=\"center\" class=\"row1\"><input type=\"submit\" name=\"submit\" value=\""._ADD."\"></td></tr>";
echo "</table></form>";
include_once("page_footer.php");

?>