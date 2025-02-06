<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

$id = intval(isset($_GET['id']) ? $_GET['id'] : $_POST['id']);
$contentanswer="";
$query = "SELECT id, catid, title, content, time, name, email,hits";
$table = "{$prefix}_question";
$query .= ", time";
$query .= " FROM $table WHERE id=$id";
$result = $db->sql_query($query);
//if($db->sql_numrows($result) != 1) header("Location: modules.php?f=$adm_modname");
list($id, $catid, $title, $content, $time, $name, $email,$hits) = $db->sql_fetchrow($result);

$err_title = $err_cat = "";

$title = str_replace('"',"''",$title);

include_once("page_header.php");
echo "<form action=\"modules.php?f=$adm_modname&do=$do&id=$id\" method=\"POST\" enctype=\"multipart/form-data\" onsubmit=\"return Check_Valid(this);\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" class=\"tableborder\">\n";
echo "<tr><td class=\"header\" colspan=\"2\">"._EDITCAMNANG."</td></tr>";
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._TITLE."</b></td>\n";
echo "<td class=\"row3\"><strong>$title</strong></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._INCAT."</b></td>\n";
$result_cat = $db->sql_query("SELECT catid, title FROM ".$prefix."_question_cat WHERE catid=$catid and alanguage='$currentlang'");
echo "		<td class=\"row3\">";
list($cat_id, $titlecat) = $db->sql_fetchrow($result_cat);
echo "<strong>$titlecat</strong></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._BODYTEXT."</b></td>\n";
echo "<td class=\"row3\"><strong>$content</strong></td>\n";
echo "</tr>\n";
echo "<tr>";

echo "<script language=\"javascript\" type=\"text/javascript\">";
echo "function Check_Valid(f) {";

echo "var Content = document.getElementById('contentanswer');";
echo "var Name = document.getElementById('nameanswer');";
echo "var err = 0;";
echo "if (isEmpty(Name.value)) {";
echo "alert('Mời bạn nhập tên');";
echo "Name.focus();";
echo "return false;";
echo "err = 1;";
echo "}	";

echo "if (isEmpty(Content.value)) {";
echo "alert('Mời bạn nhập nội dung');";
echo "Content.focus();";
echo "return false;";
echo "err = 1;";
echo "}	";
echo "if(!err) {";
echo "if(f.submit) f.submit.disabled = true; }";
echo "return true; ";
echo "}";
echo "</script>";			
		echo "<td align=\"right\" class=\"row1\">Người trả lời:</td><td  class=\"row3\">$admin_ar[0]</td></tr>\n";
		$result_admin = $db->sql_query("SELECT adacc,email FROM ".$prefix."_admin WHERE adacc='".$admin_ar[0]."'");
		list($adacc,$emailadmin) = $db->sql_fetchrow($result_admin);
		echo "<tr><td  align=\"right\" class=\"row1\">Email:</td><td  class=\"row3\">$emailadmin</td>\n";
		echo "</tr>\n";
		
		$resul_answer = $db->sql_query("SELECT id, content, time, name, email FROM ".$prefix."_answer WHERE active=1 AND qid=$id  ORDER BY time DESC ");
if($db->sql_numrows($resul_answer) > 0) {
list($idanswer,$contentanswer, $timeanswer, $nameanswer, $emailanswer) = $db->sql_fetchrow($resul_answer);
		echo "<tr>\n";
		echo "<td align=\"right\" class=\"row1\"><b>Nội dung trả lời:";
		if($ajax_active != 1) {
			echo "<a href=\"?f=".$adm_modname."&do=delete_answer2&id=$idanswer&catid=$id\" title=\""._DELETE."\" onclick=\"ajaxinfoget('modules.php?f=".$adm_modname."&do=delete_answer2&id=$idanswer&catid=$id&load_hf=1','ajaxload_container', 'answer_main'); return false; aj_base_delete($id,'$adm_modname','"._DELETEASK1."','delete_answer','');\"><img border=\"0\" src=\"images/delete.png\">\n";
		} else {
			echo "<a href=\"?f=".$adm_modname."&do=delete_answer2&id=$idanswer&catid=$id\" info=\""._DELETE."\" onclick=\"return confirm('"._DELETEASK1."');\"><img border=\"0\" src=\"images/delete.png\">\n";
		}
		echo"</b></td>\n";
		echo "<td class=\"row3\"><strong>$contentanswer</strong></td>\n";
		echo "</tr>\n";
}
		echo "<tr>\n";
		echo "<td  align=\"right\" class=\"row1\">Nội dung trả lời:</td><td  class=\"row3\">";
		editor("contentanswer", $contentanswer,"",400);
		echo "</td>\n";
		echo "</tr>\n";
		echo "<tr><td></td><td style=\"padding:3px;\"><input type=\"hidden\" name=\"subup\" value=\"1\"><input class=\"sb_but1\" type=\"submit\" name=\"submit\" value=\"Trả lời\" class=\"button2\"></td></tr>";
echo "</td></tr>";
echo "</table></form><br>";

$active = 1;
$contentanswer = $emailanswer = $nameanswer = $err_name =  $err_email =  $err_content = '';		
if( isset($_POST['subup']) && $_POST['subup'] == 1) {
	$err = 0;
	$nameanswer = $adacc;
	$active = 1;
	$contentanswer = $escape_mysql_string(trim($_POST['contentanswer']));
	$emailanswer = $emailadmin;

	
	if (empty($nameanswer)) {
		$err_name = "<font color=\"red\">"._ERROR_NAME."</font>";
		$err = 1;
	}	
	if (empty($nameanswer)) {
		$err_content = "<font color=\"red\">"._ERROR_CONTENT."</font>";
		$err = 1;
	}
	
	if (!$err) {		
		$insertIntoTable = "{$prefix}_answer";
		$query = "INSERT INTO $insertIntoTable (id, qid, name, alanguage, content, email, active,time) VALUES (NULL, $id, '$nameanswer', '$currentlang', '$contentanswer', '$emailanswer', $active,".time().")";
		$result = $db->sql_query($query);
		
		//updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _CAMNANG_CREATE_CAMNANG);
		
		echo "<script language=\"javascript\" type=\"text/javascript\">";
		echo "alert('"._THANKS_ANSWER."');";
		echo "window.location.href=\"index.php?f=".$module_name."&do=detail&id=$qid\"";
		echo "</script>";
		header("Location: modules.php?f=$adm_modname&do=$do&id=$id");
		
	}
}	
include_once("page_footer.php");
?>