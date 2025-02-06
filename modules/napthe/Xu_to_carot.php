<?php
if (!defined('CMS_SYSTEM')) die();
include("header.php");
OpenTab("Mua thẻ carot");
echo "<div class=\"content\">";

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
		
		updateauserlog($userInfo['id'],"Nạp mã thẻ điện thoại");
		//header("Location: index.php?f=".$module_name."");
		
		echo "<script language=\"javascript\" type=\"text/javascript\">";
		echo "alert('Thẻ của bạn đã nạp thành công!');";
		echo "window.location.href=\"index.php?f=".$module_name."\"";
		echo "</script>";
	}
}


echo "<form action=\"index.php?f=$module_name&do=$do\" method=\"POST\" onsubmit=\"return Check_Valid(this);\">";
//echo "<div class=\"breakcoup\"  style=\"font-size:15px;padding-left:10px;width:auto;float:left;border:0px;\">"._ADDQUESTION."</div>";		
echo "<table class=\"tableborder\" cellpadding=\"5\" cellspacing=\"0\" border=\"0\" width=\"100%\">\n";
echo "<tr>\n";
echo "<td width=\"80px\" class=\"row1\">";
echo '<div id="CPH_ctl01_div_content"><span style="font-weight: bold;">Đại lý nhận đổi xu Avatar lấy thẻ Carot với tỉ lệ như sau:</span><div>&nbsp;2t500k xu đổi thẻ 100k carot .<br></div><div>&nbsp;5t xu đổi thẻ 200k carot . <br></div><div>&nbsp;12t500k xu&nbsp; đổi thẻ 500k carot .<br></div><div>&nbsp;25t xu đổi thẻ 1triệu carot .<br></div><div><span style="font-weight: bold;"><br></span><a href="https://www.baokim.vn/0917372112"><span style="font-weight: bold;"></span></a></div></div>';
echo "</td></tr>";
echo "</table>";
echo"</form>";
echo "</div>";
CloseTab();
include("footer.php");
?>

