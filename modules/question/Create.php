<?php
if (!defined('CMS_SYSTEM')) die();
include("header.php");
OpenTab("");
echo "<div class=\"content\">";
echo "<h1 class=\"posttitle\">"._HOI_DAP."</h1>";
echo "<div class=\"div-home\">";

$active = 1;
$title = $catid = $content = $email = $name = $err_title = $err_cat = $err_name =  $err_email =  $err_content = '';
if( isset($_POST['subup']) && $_POST['subup'] == 1) {
	$err = 0;
	$title = ($_POST['title']);
	$name = ($_POST['name']);
	$catid = intval($_POST['catid']);
	$active = 0;
	$content = $escape_mysql_string(trim($_POST['content']));
	$email = ($_POST['email']);

	if (empty($title)) {
		$err_title = "<font color=\"red\">"._ERROR_TITLE."</font>";
		$err = 1;
	}
	else
	{
		$permalink=url_optimization(trim($title));
	}
	if (empty($name)) {
		$err_name = "<font color=\"red\">"._ERROR_NAME."</font>";
		$err = 1;
	}	
	if (empty($content)) {
		$err_content = "<font color=\"red\">"._ERROR_CONTENT."</font>";
		$err = 1;
	}
	if ($catid == 0) {
		$err_cat = "<font color=\"red\">"._ERROR_CAT."</font><br>";
		$err = 1;
	}
	if (!$err) {
		//update catid
		list ($xcatid) = $db->sql_fetchrow($db->sql_query("SELECT max(id) AS xid FROM ".$prefix."_question"));
		if ($xcatid == "-1") { $catid = 1; } else { $catid = $xcatid + 1; }
		$guid="index.php?f=question&do=detail&id=$catid";		
		$insertIntoTable = "{$prefix}_question";
		$query = "INSERT INTO $insertIntoTable (id, catid, title, permalink, guid, name, alanguage, content, email, active,time, hits,weight) VALUES (NULL, $catid, '$title', '$permalink','$guid', '$name', '$currentlang', '$content', '$email', $active,".time().", 0, 0)";
		$result = $db->sql_query($query);
		
		//updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _CAMNANG_CREATE_CAMNANG);
		//header("Location: index.php?f=".$module_name."");
		
		echo "<script language=\"javascript\" type=\"text/javascript\">";
		echo "alert('"._THANKS_QUESTION."');";
		echo "window.location.href=\"index.php?f=".$module_name."\"";
		echo "</script>";
	}
}

echo "<script language=\"javascript\" type=\"text/javascript\">";
echo "function Check_Valid(f) {";
//echo "var Email = document.getElementById('email');";
echo "var Content = document.getElementById('content');";
echo "var Name = document.getElementById('name');";
echo "var Title = document.getElementById('title');";
echo "var Cat = document.getElementById('catid');";
echo "var err = 0;";
echo "if (isEmpty(Title.value)) {";
echo "alert('"._ERROR_TITLE."');";
echo "Title.focus();";
echo "return false;";
echo "err = 1;";
echo "}	";
echo "if (Cat.value == 0) {";
echo "alert('"._ERROR_CAT."');";
echo "Cat.focus();";
echo "return false;";
echo "err = 1;";
echo "}	";
echo "if (isEmpty(Name.value)) {";
echo "alert('"._ERROR_NAME."');";
echo "Name.focus();";
echo "return false;";
echo "err = 1;";
echo "}	";
/*
echo "if (!isEmail(Email.value)) {";
echo "alert('"._ERROR_EMAIL."');";
echo "Email.focus();";
echo "return false;";
echo "err = 1;";
echo "}	";
*/
echo "if (isEmpty(Content.value)) {";
echo "alert('"._ERROR_CONTENT."');";
echo "Content.focus();";
echo "return false;";
echo "err = 1;";
echo "}	";
echo "if(!err) {";
echo "if(f.submit) f.submit.disabled = true; }";
echo "return true; ";
echo "}";
echo "</script>";


echo "<form action=\"index.php?f=$module_name&do=$do\" method=\"POST\" onsubmit=\"return Check_Valid(this);\">";
echo "<div class=\"question1\">";
//echo "<div class=\"breakcoup\"  style=\"font-size:15px;padding-left:10px;width:auto;float:left;border:0px;\">"._ADDQUESTION."</div>";	
echo "<br />";		
echo "<table class=\"tableborder\" cellpadding=\"5\" cellspacing=\"0\" border=\"0\" width=\"100%\">\n";
echo "<tr>\n";
echo "<td width=\"80px\" class=\"row1\">"._TITLE_QUESTION."&nbsp;$err_title</td>\n";
echo "<td class=\"row1\"><input type=\"text\" name=\"title\" value=\"$title\" id=\"title\" size=\"75\" style=\"height:auto;\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"80px\" class=\"row1\">"._THUOC_CHUYEN_MUC."&nbsp;</td>\n";
echo "<td width=\"80px\" class=\"row1\">";
$result_cat = $db->sql_query("SELECT catid, title FROM ".$prefix."_question_cat WHERE parent=0 and alanguage='$currentlang' ORDER BY weight,catid");
echo " &nbsp;<select name=\"catid\" id=\"catid\">";
echo "<option name=\"catid\" value=\"0\">"._CHON_CHUYEN_MUC."</option>";
$listcat ="";
while(list($cat_id, $titlecat) = $db->sql_fetchrow($result_cat)) {
	if($cat_id == $catid) {$seld =" selected"; }else{ $seld ="";}
	$listcat .= "<option value=\"$cat_id\" style=\"font-weight: bold\">- $titlecat</option>";
	$listcat .= subcat($cat_id,"-",$catid, "");
}
echo $listcat;
echo "</select>$err_cat</td>\n";
echo "</td></tr>\n";
echo "<tr>\n";
echo "<td class=\"row1\">"._NAME_CONTACT."&nbsp;$err_name</td>\n";
echo "<td class=\"row1\"><input type=\"text\" id=\"name\" name=\"name\"  size=\"30\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td class=\"row1\">"._EMAIL_CONTACT."&nbsp;$err_email</td>\n";
echo "<td class=\"row1\"><input type=\"text\" name=\"email\"  id=\"email\"  size=\"30\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td colspan=\"2\" class=\"row1\">"._CONTENT_QUESTION."&nbsp;$err_content<br><textarea cols=\"77\" rows=\"8\" name=\"content\" id=\"content\" style=\"margin-top:3px;\">$content</textarea></td>\n";
//echo "<td colspan=\"2\" class=\"row1\">"._CONTENT_QUESTION."&nbsp;$err_content<br>";
//editor("content",$content,"",200);
//editorbasic("content","","",100);
//editbasic("content",$content,"",200);
//echo "<textarea cols=\"123\" rows=\"8\" name=\"content\" id=\"content\" ></textarea>";
echo "</td>\n";
echo "</tr>\n";
echo "<tr><td>&nbsp;</td><td style=\"padding:5px 3px 3px 130px;\"><input type=\"hidden\" name=\"subup\" value=\"1\"><input class=\"sb_but1\" type=\"submit\" name=\"submit\" value=\""._ADD."\" class=\"button2\"></td></tr>";
echo "</table>";

echo "</div>";
echo"</form>";

echo "</div>";

echo "</div>";
CloseTab();
include("footer.php");
?>
