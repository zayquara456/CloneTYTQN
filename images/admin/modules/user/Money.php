<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}
$adm_pagetitle2 = "Cộng, trừ tiền tài khoản";
$id = intval(isset($_GET['id']) ? $_GET['id'] : 0);
$money= $plus= $moneyplus= $note="";
$result = $db->sql_query("SELECT id, fullname, email, money FROM ".$prefix."_user WHERE id=$id ");
if($db->sql_numrows($result) != 1) {
	//eader("Location: ".$adm_modname.".php");
	//die();
}
else{
	list($id, $fullname, $email, $money) = $db->sql_fetchrow($result);
}
 $error= $error2= $err_name= $err_plus="";

//$stopnick = _ERROR1;

include("page_header.php");

if(isset($_POST['subup']) && $_POST['subup'] == 1) {
	$err=0;
	$moneyplus= intval($_POST['moneyplus']);
	$plus = $_POST['plus'];
	$name = nospatags(trim($_POST['name']));
	$id = intval($_POST['id']);
	$note = nospatags($_POST['note']);
	$result = $db->sql_query("SELECT id, fullname, email, money FROM ".$prefix."_user WHERE fullname='$name' OR email='$name'");
	
	if($db->sql_numrows($result) > 0) {
		list($id, $fullname, $email, $money) = $db->sql_fetchrow($result);
	}
	else{
		$err_name = "<font color=\"red\">Tài khoản không tồn tại</font><br/>";
		$err = 1;
	}
	
	if(empty($plus)) {
		$err_plus = "<font color=\"red\">Mời bạn chọn giao dịch</font><br/>";
		$err = 1;
	}
	if(empty($moneyplus)) {
		$moneyplus = "";
		$error = "<font color=\"red\">Mời bạn nhập số tiền</font><br/>";
		$err = 1;
	}
	if(empty($note)) {
		$note = "";
		$error2 = "<font color=\"red\">Mời bạn nhập ghi chú</font><br/>";
		$err = 1;
	}
	
	if($plus==1){$moneynew = $money+$moneyplus;$txtplus="Cộng tiền";$plusstatus="+";}
	elseif($plus==2){
		if($moneyplus > $money){
			$error = "<font color=\"red\">Số tiền vượt quá giới hạn cho phép</font><br/>";
			$err = 1;
		}
		else{$moneynew = $money-$moneyplus;$txtplus="Trừ tiền";$plusstatus="-";}
	}
		
	if(!$err) {
		$db->sql_query("UPDATE ".$prefix."_user  SET money=$moneynew WHERE id=$id");
		updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, "Cộng trừ tiền cho thành viên | ghi chú: $note");
		updateuserlog($id,"$txtplus", $money, $moneyplus, $moneynew,$plusstatus,"$txtplus tài khoản: $note | Người thực hiện ".$admin_ar[0]."");
		//echo "<script>window.alert('Chỉnh sửa thành công!');</script>";
		echo "<script language=\"javascript\" type=\"text/javascript\">";
		echo "window.alert('$txtplus ".bsVndDot($moneyplus)." thành công!');";
		echo "window.location.href=\"modules.php?f=".$adm_modname."&do=money\"";
		echo "</script>";
		//header("Location: modules.php?f=".$adm_modname."&do");
	}
	else{
		$moneyplus= intval($_POST['moneyplus']);
		$plus = $_POST['plus'];
		$name = nospatags($_POST['name']);
		$id = intval($_POST['id']);
		$note = nospatags($_POST['note']);
	}
}
ajaxload_content();
echo "<div id=\"pagecontent\">";
echo "<form action=\"modules.php?f=$adm_modname&do=$do\" method=\"POST\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" class=\"tableborder\">\n";

echo "<tr><td colspan=\"2\" class=\"header\">Cộng trừ tiền tài khoản</td></tr>";
echo "<tr>\n";
echo "<tr>\n";
echo "<td width=\"150\" align=\"right\" class=\"row1\"><b>Tên người nhận</b></td>\n";
echo "<td class=\"row3\">".$err_name."<input type=\"text\" name=\"name\" value=\"\" onblur=\" show_ajaxcontent_byid( this.value, 'user', 'inforuser', 'id', 'inforuser')\" size=\"20\"><span id=\"inforuser\"></span></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>Giao dịch</b></td>\n";
$plus2=$plus1="";
if($plus==2){$plus2='selected="selected"';}else{$plus2="";}
if($plus==1){$plus1='selected="selected"';}else{$plus1="";}
echo "<td class=\"row2\">".$err_plus."<select name=\"plus\" >
	<option value=\"0\">Chọn giao dịch</option>
	<option $plus1 value=\"1\">Cộng tiền</option>
	<option $plus2 value=\"2\">Trừ tiền</option>
</select></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"150\" align=\"right\" class=\"row1\"><b>Số tiền</b></td>\n";
echo "<td class=\"row3\">".$error."<input type=\"text\" name=\"moneyplus\" value=\"$moneyplus\" size=\"20\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"150\" align=\"right\" class=\"row1\"><b>Ghi chú</b></td>\n";
echo "<td class=\"row3\">".$error2."<input type=\"text\" name=\"note\" value=\"$note\" size=\"50\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<tr><td></td><td align=\"left\" class=\"row4\"><input type=\"hidden\" name=\"id\" value=\"$id\"><input type=\"hidden\" name=\"subup\" value=\"1\"><input class=\"button2\" type=\"submit\" name=\"submit\" value=\"Cập nhật\"> <input class=\"button2\" type=\"button\" value=\""._CANCEL."\" onclick=\"window.location='modules.php?f=$adm_modname'\"></td></tr>";
echo "</table></form></div>\n";

include_once("page_footer.php");
?>