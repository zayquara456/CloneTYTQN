<?php

if(!defined('CMS_ADMIN')) die("Illegal File Access");

include_once("page_header.php");
$idedit = intval(isset($_GET['id']) ? $_GET['id'] : 0);
global $telecom_arr;
$text = $promotion = $name = $err_title = $err_cat = $ptops = $error = $psale="";
$active = 1;
if( isset($_POST['subup']) && $_POST['subup'] == 1) {
	$name = nospatags($_POST['name']);
	$active = intval($_POST['active']);
	$telecom = intval($_POST['telecom']);
	$promotion = intval($_POST['promotion']);

	if($promotion =="") {
		$error .= "<font color=\"red\">Mời bạn nhập phần trăm (%) khuyến mại</font><br/>";
		$err = 1;
	}
	if(!is_numeric($promotion))
	{
		$error .= "<font color=\"red\">Phần trăm phải là số</font><br/>";
		$err = 1;
	}
	if($name == "") {
		$error .= "<font color=\"red\">Mời bạn nhập tài khoản</font><br/>";
		$err = 1;
	}
	if($name!="*"){
		$result = $db->sql_query("SELECT email FROM ".$prefix."_user WHERE fullname='$name' OR email='$name'");
		if($db->sql_numrows($result) == 0)	
		{
			$error .= "<font color=\"red\">Tài khoản không tồn tại</font><br/>";
			$err = 1;
		}
	}
	elseif($name=="*"){$name=0;}
	$result = $db->sql_query("SELECT username,napthe FROM ".$prefix."_napthe_promotion WHERE username='$name' AND napthe='$telecom'");
	if($db->sql_numrows($result) > 0)	
	{
		$error .= "<font color=\"red\">Tài khỏa và loại thẻ đã tồn tại</font><br/>";
		$err = 1;
	}
	if(!$err) {
		$result = $db->sql_query("INSERT INTO {$prefix}_napthe_promotion (username, napthe, promotion, time, active) VALUES ('$name', $telecom, $promotion, ".time().", $active)");
		updateadmlog($admin_ar[0], $adm_modname, "Quản lý nạp thẻ", "Thêm khuyến mại mới");
		header("Location: modules.php?f=".$adm_modname."&do=$do");
	}
}

//edit the cao
if( isset($_POST['subedit']) && $_POST['subedit'] == 1) {
	$idedit = nospatags($_POST['idedit']);
	$name = nospatags($_POST['name']);
	$active = intval($_POST['active']);
	$telecom = intval($_POST['telecom']);
	$promotion = intval($_POST['promotion']);

	if($promotion =="") {
		$error .= "<font color=\"red\">Mời bạn nhập phần trăm (%) khuyến mại</font><br/>";
		$err = 1;
	}
	if(!is_numeric($promotion))
	{
		$error .= "<font color=\"red\">Phần trăm phải là số</font><br/>";
		$err = 1;
	}
	if($name == "") {
		$error .= "<font color=\"red\">Mời bạn nhập tài khoản</font><br/>";
		$err = 1;
	}
	if($name!="*"){
		$result = $db->sql_query("SELECT email FROM ".$prefix."_user WHERE fullname='$name' OR email='$name' ");
		if($db->sql_numrows($result) == 0)	
		{
			$error .= "<font color=\"red\">Tài khoản không tồn tại</font><br/>";
			$err = 1;
		}
	}
	elseif($name=="*"){$name=0;}
	$result = $db->sql_query("SELECT username,napthe FROM ".$prefix."_napthe_promotion WHERE username='$name' AND napthe='$telecom' AND id<>$idedit");
	if($db->sql_numrows($result) > 0)	
	{
		$error .= "<font color=\"red\">Tài khỏa và loại thẻ đã tồn tại</font><br/>";
		$err = 1;
	}
	if(!$err) {
		$result = $db->sql_query("UPDATE {$prefix}_napthe_promotion SET username='$name', napthe=$telecom, promotion=$promotion, active=$active WHERE id=$idedit");
		updateadmlog($admin_ar[0], $adm_modname, "Quản lý nạp thẻ", "Chỉnh sửa nạp thẻ");
		header("Location: modules.php?f=".$adm_modname."&do=$do&edit=ok");
		//$edit = isset($_GET['edit']) ? $_GET['edit'] : "";
		//if($edit=='ok'){
			echo "<script>window.alert('Chỉnh sửa thành công!');</script>";
		//}
	}
}


// lay du lieu chinh sua

$result = $db->sql_query("SELECT id, username, napthe, promotion, time, active FROM ".$prefix."_napthe_promotion WHERE id=$idedit");
if($db->sql_numrows($result) != 1) {
	//header("Location: ".$adm_modname.".php");
	//die();
}
else{
	list($id, $name, $napthe, $promotion, $time, $active) = $db->sql_fetchrow($result);
	if($name==0){$name="*";}
}
echo "<script language=\"javascript\">\n";
echo "	function check(f) {\n";
echo "		if(f.title.value =='') {\n";
echo "			alert('"._ERROR1_1."');\n";
echo "			f.title.focus();\n";
echo "			return false;\n";
echo "		}	\n";
echo "		\n";
echo "		if(f.catid.value == 0) {\n";
echo "			alert('"._ERROR2."');\n";
echo "			f.catid.focus();\n";
echo "			return false;\n";
echo "		}	\n";
echo "		\n";
echo "		f.submit.disabled = true;\n";
echo "		return true;	\n";
echo "	}	\n";
echo "</script>	\n";
if($error!="")
	echo "<div class=\"info\">$error</div>";
echo "<div id=\"pagecontent\">";
echo "<form action=\"modules.php?f=$adm_modname&do=$do\" method=\"POST\" onsubmit=\"return check(this);\" enctype=\"multipart/form-data\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";

echo "<tr><td colspan=\"2\" class=\"header\">Thêm khuyến mại mới</td></tr>";
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>Tài khoản</b></td>\n";
echo "<td class=\"row2\">Nhập (*) để chọn tất cả<br><input type=\"text\" name=\"name\" value=\"$name\" onblur=\" show_ajaxcontent_byid( this.value, 'user', 'checkuser', 'id', 'checkuser')\" size=\"30\"><span id=\"checkuser\"></span></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>Chọn loại thẻ</b></td>\n";
echo '<td class="row1"><select class="ddl" id="telecom" name="telecom">';
	
	foreach($telecom_arr as $key => $value)
	{
		if($napthe==$value){$selected='selected="selected"';}
		else{$selected='';}
		echo "<option value=\"$value\" $selected>$key</option>";
	}
echo'</select></td>';
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>Kích hoạt</b></td>\n";
if($active == 1) {
	echo "<td class=\"row2\"><input type=\"radio\" name=\"active\" value=\"1\" checked>"._YES." &nbsp;&nbsp;";
	echo "<input type=\"radio\" name=\"active\" value=\"0\">"._NO."</td>\n";
	echo "</tr>\n";
} else {
	echo "<td class=\"row2\"><input type=\"radio\" name=\"active\" value=\"1\">"._YES." &nbsp;&nbsp;";
	echo "<input type=\"radio\" name=\"active\" value=\"0\" checked>"._NO."</td>\n";
	echo "</tr>\n";
}
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>Khuyến mại</b></td>\n";
echo "<td class=\"row2\"><input type=\"text\" name=\"promotion\" value=\"$promotion\" size=\"30\"> Phần trăm(%)</td>\n";
echo "</tr>\n";
if($idedit==""){
	echo "<input type=\"hidden\" name=\"subup\" value=\"1\">";
	echo "<tr><td></td><td><input type=\"submit\" name=\"submit\" value=\""._ADD."\" class=\"button2\"></td></tr>";
}
else{
	echo "<input type=\"hidden\" name=\"subedit\" value=\"1\">";
	echo "<input type=\"hidden\" name=\"idedit\" value=\"$idedit\">";
	echo "<tr><td></td><td><input type=\"submit\" name=\"submit\" value=\"Cập nhật\" class=\"button2\"></td></tr>";
}
echo "</table></form></div>";
echo "<br><div id=\"pagecontent\">";
////////////////////////////////////////////////////
//$sort = intval(isset($_GET['sort']) ? $_GET['sort'] : (isset($_POST['sort']) ? $_POST['sort']:0));
//$perpage = 20;
//$page = intval(isset($_GET['page']) ? $_GET['page'] : (isset($_POST['page']) ? $_POST['page']:1));
//$offset = ($page-1) * $perpage;
//$countf = $db->sql_fetchrow($db->sql_query("SELECT COUNT(*) FROM {$prefix}_thecao_promotion"));
//$total = ($countf[0]) ? $countf[0] : 1;
$result = $db->sql_query("SELECT id, username, napthe, promotion, time, active FROM {$prefix}_napthe_promotion");
if($db->sql_numrows($result) > 0) {
	echo "<script language=\"javascript\" type=\"text/javascript\">\n";
	echo "function check_uncheck(){\n";
	echo "	var f=document.frm;\n";
	echo "	if(f.checkall.checked){\n";
	echo "		CheckAllCheckbox(f,'id[]');\n";
	echo "	}else{\n";
	echo "		UnCheckAllCheckbox(f,'id[]');\n";
	echo "	}			\n";
	echo "}\n";
	echo "	function checkQuick(f) {\n";
	echo "		if(f.fc.value =='') {\n";
	echo "			f.fc.focus();\n";
	echo "			return false;\n";
	echo "		}\n";
	echo "		f.submit.disabled = true; \n";
	echo "		return true;		\n";
	echo "	}	\n";
	echo "	function checkQuickId(f) {\n";
	echo "		if(f.id.value =='') {\n";
	echo "			f.id.focus();\n";
	echo "			return false;\n";
	echo "		}\n";
	echo "		f.submit.disabled = true; \n";
	echo "		return true;		\n";
	echo "	}	\n";
	echo "</script>\n";
	ajaxload_content();

	echo "<div id=\"".$adm_modname."_main\"><form action=\"modules.php?f=$adm_modname&do=$do\" name=\"frm\" method=\"POST\" onsubmit=\"return checkQuick(this);\">";
	echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
	echo "<tr><td colspan=\"10\" class=\"header\">Danh sách thẻ đưa lên</td></tr>";
	echo "<tr>\n";
	echo "<td class=\"row1sd\" width=\"20\" align=\"center\">".sortBy("modules.php?f=$adm_modname",1)."</td>\n";
	echo "<td class=\"row1sd\">Tài khoản</td>\n";
	echo "<td class=\"row1sd\">Loại thẻ</td>\n";
	echo "<td class=\"row1sd\">Khuyến mại (%)</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"100px\">"._TIMEUP."</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"60\">"._STATUS."</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"10px\">"._EDIT."</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"10px\">"._DELETE."</td>\n";
	echo "</tr>\n";
	$i =0;
	$a = 1;
	//if($page > 1) { $a = $perpage*$page - $perpage + 1;}
	
	while(list($id_list, $username_list, $napthe_list, $promotion_list, $time_list, $active_list ) = $db->sql_fetchrow($result)) {
		if($i%2 == 1) {
			$css = "row1";
		}	else {
			$css ="row3";
		}
		switch($active_list) {
			case 1: $active_list = "<img border=\"0\" src=\"images/view.png\">"; break;
			case 0: $active_list = "<img border=\"0\" src=\"images/viewo.png\">"; break;
		}

		echo "<tr>\n";
		echo "<td align=\"center\" class=\"$css\">$a</td>";
		echo "<td class=\"$css\"><b>".show_user($username_list)."</b></td>\n";
		echo "<td class=\"$css\"><b>".telecom_name($napthe_list)."</b></td>\n";
		echo "<td class=\"$css\"><b>$promotion_list</b></td>\n";
		echo "<td align=\"center\" class=\"$css\">".ext_time($time_list, 2)."</td>\n";
		echo "<td align=\"center\" class=\"$css\">$active_list</td>\n";
		echo "<td align=\"center\" width=\"30\" class=\"$css\"><a href=\"?f=".$adm_modname."&do=promotion&id=$id_list\" info=\""._EDIT."\"><img border=\"0\" src=\"../images/edit.gif\"></a></td>\n";
		echo "<td align=\"center\" width=\"30\" class=\"$css\"><a href=\"?f=".$adm_modname."&do=delete_promotion&id=$id_list\" info=\""._DELETE."\" onclick=\"return confirm('"._DELETEASK."');\"><img border=\"0\" src=\"images/delete.png\"></a></td>\n";
		echo "</tr>\n";
		$i ++;
		$a ++;
	}
	//if($total > $perpage) {
	//	echo "<tr><td colspan=\"9\">";
	//	$pageurl = "modules.php?f=".$adm_modname."&sort=$sort";
	//	echo paging($total,$pageurl,$perpage,$page);
	//	echo "</td></tr>";
	//}
	echo "</table></div></div>";

		

}else{
	
}

include_once("page_footer.php");
?>