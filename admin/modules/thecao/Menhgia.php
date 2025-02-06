<?php

if(!defined('CMS_ADMIN')) die("Illegal File Access");

include_once("page_header.php");
$up = isset($_GET['up']) ? $_GET['up'] : "no";
$catid = isset($_GET['catid']) ? intval($_GET['catid']) : 0;
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$menhgia=$giaban= $menhgia_edit= $giaban_edit= "";
$err=0;
$result = $db->sql_query("SELECT id, catid, menhgia, giaban, active FROM ".$prefix."_thecao_menhgia WHERE catid=$catid AND id=$id ORDER BY id DESC");
if($db->sql_numrows($result) > 0) {
  list($id_edit, $catid_edit, $menhgia_edit, $giaban_edit, $active_edit) = $db->sql_fetchrow($result);
}
if(isset($_POST['subup']) && $_POST['subup'] == 1) {
	$menhgia = nospatags($_POST['menhgia']);
	$giaban = floatval(str_replace(',', '.', $_POST['giaban']));
    $catid = intval($_GET['catid']);
	//$menu_type="main_menu";
	/*if($title =="") {
		$err_title = "<font color=\"red\">"._ERROR1."</font><br/>";
		$err = 1;
	}	
	
	if($url =="") {
		$err_url = "<font color=\"red\">"._ERROR2."</font><br/>";
		$err = 1;
	}	*/
	
	if(!$err) {
		$db->sql_query("INSERT INTO ".$prefix."_thecao_menhgia (id, catid, menhgia, giaban, active) VALUES (NULL, $catid, '$menhgia',$giaban, 1)");
		//fixweight_ngoaite();
		header("Location: modules.php?f=".$adm_modname."&do=menhgia&catid=$catid");
	}	
}
if(isset($_POST['subedit']) && $_POST['subedit'] == 1) {
	$menhgia = nospatags($_POST['menhgia']);
	$giaban = floatval(str_replace(',', '.', $_POST['giaban']));
    $catid = intval($_GET['catid']);
    $id = intval($_POST['id']);
	//$menu_type="main_menu";
	/*if($title =="") {
		$err_title = "<font color=\"red\">"._ERROR1."</font><br/>";
		$err = 1;
	}	
	
	if($url =="") {
		$err_url = "<font color=\"red\">"._ERROR2."</font><br/>";
		$err = 1;
	}	*/
	
	if(!$err) {
		$db->sql_query("UPDATE ".$prefix."_thecao_menhgia SET menhgia='$menhgia', giaban=$giaban WHERE id=$id");
		//fixweight_ngoaite();
		header("Location: modules.php?f=".$adm_modname."&do=menhgia&catid=$catid");
	}	
}
/*else {
	$err_title = "";
	$err_url = "";
	$title  = "";
	$url  = "";
}*/

ajaxload_content();

echo "<div id=\"".$adm_modname."_main\"><form action=\"modules.php?f=$adm_modname&do=menhgia&catid=$catid\" method=\"POST\" onsubmit=\"return check(this);\" enctype=\"multipart/form-data\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
echo "<tr><td colspan=\"2\" class=\"header\">Tạo mệnh giá</td></tr>";
echo "<tr>\n";
echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>Mệnh giá</b></td>\n";
echo "<td class=\"row2\"><input type=\"text\" name=\"menhgia\" value=\"$menhgia_edit\" size=\"30\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>Giá bán</b></td>\n";
echo "<td class=\"row2\"><input type=\"text\" name=\"giaban\" value=\"$giaban_edit\" size=\"30\"></td>\n";
echo "<tr>\n";

if(empty($id)){
echo "<input type=\"hidden\" name=\"subup\" value=\"1\">";
echo "<tr><td colspan=\"2\" align=\"center\" class=\"row1\"><input type=\"submit\" class=\"button2\" name=\"submit\" value=\""._ADD."\"></td></tr>";
}
else{
  echo "<input type=\"hidden\" name=\"id\" value=\"$id_edit\">";
  echo "<input type=\"hidden\" name=\"subedit\" value=\"1\">";
  echo "<tr><td colspan=\"2\" align=\"center\" class=\"row1\"><input type=\"submit\" class=\"button2\" name=\"submit\" value=\"Cập nhật\"></td></tr>";
}
echo "</table></form>";

//////////////////////////////////DANH SACH MENU//////////////////////////////////////////////////
$resultcat = $db->sql_query("SELECT id, catid, menhgia, giaban, active FROM ".$prefix."_thecao_menhgia WHERE catid=$catid ORDER BY id DESC");
if($db->sql_numrows($resultcat) > 0) {
echo "<script language=\"javascript\" type=\"text/javascript\">\n";
echo "function check_uncheck(){\n";
echo "var f= document.frm;\n";
echo "if(f.checkall.checked){\n";
echo "CheckAllCheckbox(f,'id[]');\n";
echo "}else{\n";
echo "UnCheckAllCheckbox(f,'id[]');\n";
echo "}			\n";
echo "}\n";
echo "function checkQuick(f) {\n";
echo "if(f.f.value =='') {\n";
echo "f.f.focus();\n";
echo "return false;\n";
echo "}\n";
echo "return true;		\n";
echo "}	\n";
echo "</script>\n";		
echo "<br/><form name=\"frm\" action=\"modules.php\" method=\"POST\" onsubmit=\"return checkQuick(this);\">";
echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
echo "<tr><td colspan=\"9\" class=\"header\">Danh sách mệnh giá";
if($up=="ok"){echo "<em> - Cập nhật thành công!</em>";}
echo "</td></tr>";

echo "<tr>\n";
echo "<td class=\"row1sd\" width=\"10\">id</td>\n";
echo "<td  align=\"center\" class=\"row1sd\" >Mệnh giá</td>\n";
echo "<td  align=\"center\" class=\"row1sd\" >Giá bán</td>\n";
echo "<td align=\"center\" width=\"10\" class=\"row1sd\">Sửa</td>\n"; 
echo "<td align=\"center\" width=\"10\" class=\"row1sd\">"._DELETE."</td>\n"; 
echo "</tr>\n";
$i=0;
while(list($id, $catid, $menhgia, $giaban, $active) = $db->sql_fetchrow($resultcat)) {
$i++;
echo "<tr>\n";
echo "<td class=\"row1\"><b><input type=\"hidden\" name=\"id2[$i]\" value=\"$id\">$id</b></td>\n";
echo "<td class=\"row1\">$menhgia</td>\n";
echo "<td class=\"row1\">$giaban</td>\n";
echo "<td align=\"center\" class=\"row1\"><a href=\"?f=$adm_modname&do=menhgia&catid=$catid&id=$id\" info=\"Sửa\"><img border=\"0\" src=\"images/edit.png\"></td>\n";
echo "<td align=\"center\" class=\"row1\"><a href=\"?f=$adm_modname&do=delete_menhgia&id=$id\" info=\""._DELETE."\" onclick=\"return confirm('"._DELETEASK."');\"><img border=\"0\" src=\"images/delete.png\"></td>\n";
}
echo "</table></form></div><br/>";
}
include_once("page_footer.php");

?>