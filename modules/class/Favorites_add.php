<?php
if (!defined('CMS_SYSTEM')) die();
global $db, $time, $prefix, $currentlang, $path_upload, $titlelink, $Default_Temp, $urlsite, $userInfo;
$err_title="";
$documentid= isset($_GET['id']) ? intval($_GET['id']) : 0;
if(isset($_POST['subup'])&& $_POST['subup'] == 1) {
	$err = 0;
	$catid = $escape_mysql_string(trim($_POST['catid']));
	$userid = $userInfo['id'];
	$documentid= isset($_POST['documentid']) ? intval($_POST['documentid']) : 0;
	if($catid==0) {
		$err_title .= "<font color=\"red\">Mời bạn chọn danh mục yêu thích.</font><br/>";
		$err = 1;
	}
	$ckresult = $db->sql_query("SELECT documentid FROM ".$prefix."_document_favorites WHERE documentid='$documentid'");
	if($db->sql_numrows($ckresult) > 0) {
		$err_title .= "<font color=\"red\">Tài liệu này đã có trong danh mục yêu thích.</font><br/>";
		$err = 2;
	}
	if(!$err) {
		$db->sql_query("INSERT INTO ".$prefix."_document_favorites (id, catid, userid, documentid) VALUES (NULL, '$catid', '$userid', '$documentid')");
		//header("Location: index.php?f=document&do=favorites_add");
		echo "<script language=\"javascript\" type=\"text/javascript\">";
		echo "alert('Tài liệu đã được thêm vào mục yêu thích');";
		echo "window.location.href=\"index.php?f=document&do=favorites_add&id=$documentid\"";
		echo "</script>";
	}
}

if(isset($_POST['subup2'])&& $_POST['subup2'] == 2) {
	$err = 0;
	$title = $escape_mysql_string(trim($_POST['title']));
	$userid = $userInfo['id'];

	if(empty($title)) {
		$err_title .= "<font color=\"red\">Mời bạn nhập tiêu đề.</font><br/>";
		$err = 1;
	}
	if(!$err) {
		$db->sql_query("INSERT INTO ".$prefix."_document_favorites_cat (catid, title, user_id) VALUES (NULL, '$title', '$userid')");
		//header("Location: index.php?f=document&do=favorites_add");
		echo "<script language=\"javascript\" type=\"text/javascript\">";
		echo "alert('Chuyên mục yêu thích mỡi đã tạo thành công!');";
		echo "window.location.href=\"index.php?f=document&do=favorites_add&id=$documentid\"";
		echo "</script>";
	}
}
if($userInfo['id']=="")
{
	die("Mời bạn đăng nhập để thêm tài liệu vào danh sách yêu thích!");
}
else
{
echo "<form action=\"index.php?f=document&do=favorites_add&id=$documentid\" method=\"POST\" onsubmit=\"return check(this);\">";
$resultcat = $db->sql_query("SELECT catid, title FROM {$prefix}_document_favorites_cat WHERE user_id=".$userInfo['id']." ORDER BY catid");
if($db->sql_numrows($resultcat) > 0) 
{
	echo $err_title;
	echo 'Hãy chọn <select id="catid" name="catid">'."\n";
	echo '<option value="0">Danh mục tài liệu yêu thích</option>\n';
	while(list($cat_id, $titlecat) = $db->sql_fetchrow($resultcat)) 
	{
		$listcat .= "<option value=\"$cat_id\">$titlecat</option>";
	}
	echo $listcat;
	echo "</select>";
	echo "<input type=\"hidden\" name=\"subup\" value=\"1\"> <input type=\"hidden\" name=\"documentid\" value=\"$documentid\"><input type=\"submit\" class=\"sb_but1\" id=\"submit\" name=\"submit\" value=\""._ADD."\">";
}
else
{
	echo "<form action=\"index.php?f=user&do=document_favorite\" method=\"POST\" onsubmit=\"return check(this);\">";
echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
echo "<tr>\n";
echo "<td width=\"30%\" align=\"right\" class=\"row1\"><b>"._TITLE."</b></td>\n";
echo "<td class=\"row2\">$err_title<input type=\"text\" id=\"title\" name=\"title\" value=\"$title\" size=\"50\"> <input type=\"hidden\" name=\"subup2\" value=\"2\"> <input type=\"hidden\" name=\"documentid\" value=\"$documentid\"><input type=\"submit\" class=\"sb_but1\" id=\"submit2\" name=\"submit2\" value=\""._ADD."\"></td></tr>";
echo "</table></form>";
}
echo "</form>";

}
?>