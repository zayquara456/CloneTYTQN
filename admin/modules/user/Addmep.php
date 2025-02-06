<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}
$adm_pagetitle2 = "Cộng, trừ tiền tài khoản";
$id = intval(isset($_GET['id']) ? $_GET['id'] : 0);
$money="";
$result = $db->sql_query("SELECT id, fullname, email, mep FROM ".$prefix."_user WHERE id=$id ");
if($db->sql_numrows($result) != 1) {
	//eader("Location: ".$adm_modname.".php");
	//die();
}
else{
	list($id, $fullname, $email, $money) = $db->sql_fetchrow($result);
}
 $error= $error2= "";

//$stopnick = _ERROR1;

if(isset($_POST['subup']) && $_POST['subup'] == 1) {
	$moneyplus= intval($_POST['moneyplus']);
	$actvie= intval($_POST['active']);
	$id = intval($_POST['id']);
	$note = nospatags($_POST['note']);
	$result = $db->sql_query("SELECT id, fullname, email, mep FROM ".$prefix."_user WHERE id=$id ");
	if($db->sql_numrows($result) > 0) {
		list($id, $fullname, $email, $money) = $db->sql_fetchrow($result);
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
	
	if($actvie==1){$moneynew = $money+$moneyplus;$plus="Cộng tiền";$plusstatus="+";}
	else{
		if($moneyplus > $money){
			$error = "<font color=\"red\">Số tiền vượt quá giới hạn cho phép</font><br/>";
			$err = 1;
		}
		else{$moneynew = $money-$moneyplus;$plus="Trừ tiền";$plusstatus="-";}
	}
		
	if(!$err) {
		$db->sql_query("UPDATE ".$prefix."_user  SET mep=$moneynew WHERE id=$id");
		updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, "Cộng trừ tiền cho thành viên | ghi chú: $note");
		updateuserlog($id,"$plus", $money ,$moneyplus, $moneynew, $plusstatus,"$plus tài khoản: $note | Người thực hiện ".$admin_ar[0]."");
		echo "<script language=\"javascript\" type=\"text/javascript\">";
		echo "window.alert('$plus ".bsVndDot($moneyplus)." thành công!');";
		echo "window.location.href=\"modules.php?f=".$adm_modname."\"";
		echo "</script>";
		//header("Location: modules.php?f=".$adm_modname."");
	}
}
ajaxload_content();
echo "<link rel=\"stylesheet\" href=\"styles/styles.css\" />\n";
echo "<div id=\"pagecontent\">";
echo "<form action=\"modules.php?f=$adm_modname&do=$do&id=$id\" method=\"POST\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" class=\"tableborder\">\n";

echo "<tr><td colspan=\"2\" class=\"header\">Cộng trừ VP cho tài khoản $fullname ($email)</td></tr>";
echo "<tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>Giao dịch</b></td>\n";
echo "<td class=\"row2\"><input type=\"radio\" name=\"active\" value=\"1\" checked>Cộng tiền &nbsp;&nbsp;";
echo "<input type=\"radio\" name=\"active\" value=\"0\">Trừ tiền</td>\n";
echo "</tr>\n";
echo "<td width=\"150\" align=\"right\" class=\"row1\"><b>Số tiền hiện tại</b></td>\n";
echo "<td class=\"row3\">".bsVndDot($money)."</td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"150\" align=\"right\" class=\"row1\"><b>Số tiền</b></td>\n";
echo "<td class=\"row3\">".$error."<input type=\"text\" name=\"moneyplus\" value=\"\" size=\"20\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"150\" align=\"right\" class=\"row1\"><b>Ghi chú</b></td>\n";
echo "<td class=\"row3\">".$error2."<input type=\"text\" name=\"note\" value=\"\" size=\"50\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<tr><td></td><td align=\"left\" class=\"row4\"><input type=\"hidden\" name=\"id\" value=\"$id\"><input type=\"hidden\" name=\"subup\" value=\"1\"><input class=\"button2\" type=\"submit\" name=\"submit\" value=\"Cập nhật\"> <input class=\"button2\" type=\"button\" value=\""._CANCEL."\" onclick=\"window.location='modules.php?f=$adm_modname'\"></td></tr>";
echo "</table></form></div>\n";
?>