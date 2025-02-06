<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

$adm_pagetitle2 = _VIEWCT;

$id = intval($_GET['id']);
$result = $db->sql_query("SELECT id, docid, time, name, email, url, url_replace, title, content, status FROM {$prefix}_link_report WHERE id=$id");
if($db->sql_numrows($result) != 1) header("Location: modules.php?f=$adm_modname");

list($pid, $docid, $time, $name, $email, $url, $url_replace, $title, $content, $status) = $db->sql_fetchrow($result);
$atitle = "Re: Lỗi tài liệu tại website $sitename";
$amess="<p style=\"font-family: Arial; font-size: 12px\">Xin chào, $name:<br/>";
$amess .= "".nl2br("Xin cảm ơn bạn đã gửi thông báo lỗi đến cho chúng tôi.<br/>Thông tin lỗi <strong>$title</strong> đã được chúng tôi khắc phục xong, bạn có thể tại lại tài liệu tại đường dẫn dưới đây<br/> $url <br/>")."<br/>TVXD Team.<br/></p>";
switch($status) {
	case 0: $status_o = "chưa xử lý";  break;
	case 1: $status_o = "đang xử lý"; break;
	case 2: $status_o = "đã xử lý"; break;
}

include_once("page_header.php");

$err_atitle = $err_mess = $err_amess = "";
if(isset($_POST['subup']) && $_POST['subup'] == 1) {
	$atitle = nospatags($_POST['atitle']);
	$amess = trim(stripslashes(resString($_POST['amess'])));
	$show = isset($_POST['show']) ? intval($_POST['show']) : 0;
	if (empty($atitle)) {
		$err_atitle = "<font color=\"red\">"._ERROR3."</font><br/>";
		$err = 1;
	}

	if (empty($amess)) {
		$err_amess = "<font color=\"red\">"._ERROR4."</font><br/>";
		$err = 1;
	}

	if(!$err) {
		$signsite = signsite();
		$message = "<html><body style=\"font-family: Arial; font-size: 12px\">".$amess."</body></html>";
		$subject = $atitle;
		sendmail($subject, $email, $adminmail, $message);
		$db->sql_query("UPDATE {$prefix}_link_report SET url_replace='".$escape_mysql_string($amess)."' WHERE id=$id");
		updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _CONTACT_RESPONSE);
		header("Location: modules.php?f=document&do=link_report");
	}

}

echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"5\" class=\"tableborder\">\n";
echo "<tr><td colspan=\"7\" class=\"header\">Phản hồi báo lỗi tài liệu </td></tr>";
echo "<tr>\n";
echo "<td width=\"150\" align=\"right\" class=\"row1\"><b>Mã báo lỗi</b></td>\n";
echo "<td class=\"row3\"><b>#$pid</b></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"150\" align=\"right\" class=\"row1\"><b>Ngày gửi</b></td>\n";
echo "<td class=\"row3\">".ext_time($time, 2)."</td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"150\" align=\"right\" class=\"row1\"><b>Trạng thái</b></td>\n";
echo "<td class=\"row3\"><font color=\"red\"><b>$status_o</b></font></td>\n";
echo "</tr>\n";
echo "<tr><td colspan=\"2\" bgcolor=\"#FFFFFF\" height=\"4\" style=\"border-bottom: solid 1px #CCC\"></td></tr>\n";
echo "<tr>\n";
echo "<td width=\"150\" align=\"right\" class=\"row1\"><b>Người gửi</b></td>\n";
echo "<td class=\"row3\"><b>$name</b></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"150\" align=\"right\" class=\"row1\"><b>Email</b></td>\n";
echo "<td class=\"row3\"><a href=\"mailto:$email\">$email</a></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"150\" align=\"right\" class=\"row1\"><b>Tiêu đề</b></td>\n";
echo "<td class=\"row3\">$title</td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"150\" align=\"right\" class=\"row1\"><b>Nội dung</b></td>\n";
echo "<td class=\"row3\">".nl2br($content)."</td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"150\" align=\"right\" class=\"row1\"><b>url lỗi</b></td>\n";
echo "<td class=\"row3\"><a href=\"$url\" target=\"_blank\">".$url."</a></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<form action=\"modules.php?f=document&do=status_link&id=$id\" method=\"POST\"><td align=\"right\" class=\"row1\"><select name=\"stat\">";
$stat_ar = array("chưa xử lý","đang xử lý","đã xử lý");
for($i =0; $i < 3; $i ++) {
	$seld ="";
	if($i == $status) { $seld =" selected"; }
	echo "<option value=\"$i\"$seld>$stat_ar[$i]</option>";
}
echo "</select></td>\n";
echo "<input type=\"hidden\" name=\"id\" value=\"$id\">";
echo "<input type=\"hidden\" name=\"do\" value=\"status_link\">";
echo "<td class=\"row3\"><input type=\"submit\" value=\""._SAVECHANGES."\" class=\"button2\"></td></form>";
echo "</tr>\n";
echo "<tr><td colspan=\"2\" class=\"rowst\">Nội dung phản hồi</td></tr>\n";
echo "<tr>\n";
echo "<form action=\"modules.php?f=document&do=views_link&id=$id\" method=\"POST\" onsubmit=\"this.submit.disabled=true;\"><td width=\"150\" align=\"right\" class=\"row1\"><b>Tiêu đề</b></td>\n";
echo "<td class=\"row3\">$err_atitle<input type=\"text\" name=\"atitle\" value=\"$atitle\" size=\"50\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"150\" align=\"right\" class=\"row1\"><b>Nội dung</b></td>\n";
echo "<td class=\"row3\">$err_amess\n";
editor("amess",$amess);
echo "</td>\n";
echo "</tr>\n";
echo "<tr><td class=\"row1\">&nbsp;</td><td class=\"row1\"><input type=\"hidden\" name=\"subup\" value=\"1\"><input type=\"submit\" name=\"submit\" value=\""._SEND."\"></form></td></tr>\n";
echo "</table><br/>";
updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _VIEW);
include_once("page_footer.php");

?>