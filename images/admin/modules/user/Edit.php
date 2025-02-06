<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

include_once("page_header.php");

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$email = $fullname = $address = $phone = $err_name = $err_email = $vip =$error ='';
$group = $title = $err = 0;

if (!isset($_POST['subup'])) {
	$db->sql_query("SELECT  group_id, email, title, fullname, address, phone FROM {$prefix}_user WHERE id=$id");
	list($group, $email, $title, $fullname, $address, $phone) = $db->sql_fetchrow();
}
else {
	$group = intval($_POST['group']);
	$title = intval($_POST['title']);
	$fullname = htmlspecialchars($_POST['name'], ENT_QUOTES);
	$email = htmlspecialchars($_POST['email'], ENT_QUOTES);
	$address = htmlspecialchars($_POST['address'], ENT_QUOTES);
	$phone = htmlspecialchars($_POST['phone'], ENT_QUOTES);

	if (empty($fullname)) {
		$err = 1;
		$error .= "<font color=\"red\">"._USER_ERROR_NAME."</font>";
	}
	elseif(strlen($name)<6 )
	{
		$err = 1;
		$error .=  "<font  color=\"red\">Tài khoản phải lớn hơn 6 ký tự!</font><br>";
	}
	
	if (empty($email)) {
		$err = 1;
		$error .=  "<font color=\"red\">"._USER_ERROR_EMAIL."</font><br>";
	}
	if ($id != 0) {
		$result = $db->sql_query("SELECT email FROM ".$prefix."_user WHERE email='$email'  AND id<>$id");
		if($db->sql_numrows($result) > 0) {
			$err = 1;
			$error .=  "<font  color=\"red\">Email đã tồn tại!</font><br>";
		}
		$result = $db->sql_query("SELECT fullname FROM ".$prefix."_user WHERE fullname='$name' AND id<>$id");
		if($db->sql_numrows($result) > 0) {
			$err = 1;
			$error .=  "<font  color=\"red\">Tài khoản đã tồn tại!</font><br>";
		}
	}
	else
	{
		$result = $db->sql_query("SELECT email FROM ".$prefix."_user WHERE email='$email'");
		if($db->sql_numrows($result) > 0) {
			$err = 1;
			$error .=  "<font  color=\"red\">Email đã tồn tại!</font><br>";
		}
		$result = $db->sql_query("SELECT fullname FROM ".$prefix."_user WHERE fullname='$name'");
		if($db->sql_numrows($result) > 0) {
			$err = 1;
			$error .=  "<font  color=\"red\">Tài khoản đã tồn tại!</font><br>";
		}
	}
	
	if (!$err) {
		$fullname = nospatags($fullname);
		$group = nospatags($group);
		$email = nospatags($email);
		$address = nospatags($address);
		$phone = nospatags($phone);
		if ($id != 0) {
			$query = "UPDATE {$prefix}_user SET group_id=$group, title=$title, fullname='$fullname', email='$email', address='$address', phone='$phone'";
			if (!empty($_POST['password'])) $query .= ", pass='".md5($_POST['password'])."'";
			$query .= " WHERE id=$id";
			$db->sql_query($query);
			//die($query);
		}
		else {
			$db->sql_query("INSERT INTO {$prefix}_user (id, group_id, email, title, fullname, pass, address, phone, actives, registrationTime, loginAttempt)  VALUES (null, $group, '$email', $title, '$fullname', '".md5($_POST['password'])."', '$address', '$phone', 1, NOW(), 0)");
		}
		header("Location: modules.php?f=$adm_modname");
	}
}
if($error!="")
echo "<div class=\"info\">$error</div>";
echo "<form method=\"POST\" action=\"modules.php?f=$adm_modname&do=$do&id=$id\">\n";
echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
echo "<tr><td colspan=\"2\" class=\"header\">"._USER_EDIT_ADD."</td></tr>\n";
echo "<tr><td class=\"row1\" width=\"20%\">&nbsp;"._USER_TITLE.":</td>\n";
echo "<td class=\"row2\"><select name=\"title\" id=\"title\">\n";
$mrSelected = $mrsSelected = '';
if ($title == 0) $mrSelected = ' selected="selected"';
else $mrsSelected = ' selected="selected"';
echo "<option value=\"0\"$mrSelected>"._USER_MR."</option>\n";
echo "<option value=\"1\"$mrsSelected>"._USER_MRS."</option>\n";
echo "</select></td></tr>\n";
$result = $db->sql_query("SELECT id, title FROM ".$prefix."_usergroup");
if($db->sql_numrows($result) > 0) {
echo "<tr>\n";
echo "<td width=\"20%\" align=\"left\" class=\"row1\">Nhóm quản trị</td>\n";
echo "<td class=\"row2\"><select name=\"group\">";
	$listcat ="";
			if($group==0) {$seld =" selected"; }else{ $seld ="";}
			$listcat .= "<option value=\"0\"$seld>Chọn nhóm thành viên</option>";
	while(list($m_id, $titlecat) = $db->sql_fetchrow($result)) {
			if($m_id == $group) {$seld =" selected"; }else{ $seld ="";}
			$listcat .= "<option value=\"$m_id\"$seld>$titlecat</option>";
		}
		echo $listcat;
echo "</select></td>\n";
echo "</tr>\n";
}
echo "<tr><td class=\"row1\" width=\"20%\">&nbsp;"._USER_FULLNAME.":</td>\n";
echo "<td class=\"row2\"><input type=\"text\" name=\"name\" id=\"name\" value=\"$fullname\" size=\"50\" /><br />$err_name</td></tr>\n";
echo "<tr><td class=\"row1\" width=\"20%\">&nbsp;"._USER_EMAIL.":</td>\n";
echo "<td class=\"row2\"><input type=\"text\" name=\"email\" id=\"email\" value=\"$email\" size=\"50\" /><br />$err_email</td></tr>\n";
echo "<tr><td class=\"row1\" width=\"20%\">&nbsp;"._USER_PASSWORD.":</td>\n";
echo "<td class=\"row2\"><input type=\"password\" name=\"password\" id=\"password\" size=\"50\" /><br />";
if ($id != 0) echo _USER_LEAVE_BLANK_TO_KEEP_AS_IS;
echo "</td></tr>\n";
echo "<tr><td class=\"row1\" width=\"20%\">"._USER_ADDRESS.":</td>\n";
echo "<td class=\"row2\"><input type=\"text\" name=\"address\" id=\"address\" value=\"$address\" size=\"50\" /></td></tr>\n";
echo "<tr><td class=\"row1\" width=\"20%\">"._USER_PHONE.":</td>\n";
echo "<td class=\"row2\"><input type=\"text\" name=\"phone\" id=\"phone\" value=\"$phone\" size=\"50\" /></td></tr>\n";
echo "<input type=\"hidden\" value=\"1\" name=\"subup\" />";
$btnName = ($id == 0) ? _ADD : _SAVECHANGES;
echo "<tr><td></td><td class=\"row2\"  ><input class=\"button2\" type=\"submit\" value=\"$btnName\" /> <input class=\"button2\" type=\"button\" value=\""._CANCEL."\" onclick=\"window.location='modules.php?f=$adm_modname'\"></td></tr>\n";
echo "</table></form>";

include_once("page_footer.php");
?>
