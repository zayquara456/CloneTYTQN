<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

$catid = intval($_GET['id']);
$path_upload = $path_upload."/".$adm_modname."_cat";
$err_title = "";
if (isset($_POST['subup']) && $_POST['subup'] == 1) {
	$title = $escape_mysql_string(trim($_POST['title']));
	$active = intval($_POST['active']);
	$onhome = intval($_POST['onhome']);
	$homelinks = intval($_POST['homelinks']);
	$parent = intval($_POST['parent']);
	$cat_type="document_cat";
	$title_seo			= isset($_POST['title_seo']) ? $escape_mysql_string(trim($_POST['title_seo'])) : '';
	$description_seo		= isset($_POST['description_seo']) ? $escape_mysql_string(trim($_POST['description_seo'])) : '';
	$keyword_seo			= isset($_POST['keyword_seo']) ? $escape_mysql_string(trim($_POST['keyword_seo'])) : '';
	
	if (empty($title)) {
		$err_title = "<font color=\"red\">"._ERROR1."</font><br/>";
		$err = 1;
	}
	else
	{
		$permalink=url_optimization(trim($title));
	}
	// delete file images
	if($delimg == 1) {
		@unlink(RPATH."$path_upload/$images");
		$images = "";
	}
	if (!$err) {
		$newnamefile	= substr(str_replace("-","_",$permalink),0,60)."_".generate_code(6);
		if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {
			$upload = new Upload("userfile", $path_upload, $maxsize_up,$newnamefile);
			$images1 = $upload->send();
			if(!empty($images1) && !empty($images)) {
				@unlink(RPATH."$path_upload/$images");
				$images = "";
			}
		}
		else 
		{
			$images1 = $images;
		}
		$guid="index.php?f=".$adm_modname."&do=categories&id=$catid";
		$ckresult = $db->sql_query("SELECT permalink FROM ".$prefix."_document_cat WHERE permalink='$permalink' AND  catid<>'$catid'");
		if($db->sql_numrows($ckresult) > 0) {
			$permalink=$permalink.'-'.$catid;
		}
		$db->sql_query("UPDATE ".$prefix."_document_cat SET title='$title',permalink='$permalink',guid='$guid', seo_title='$title_seo', seo_description='$description_seo', seo_keyword='$keyword_seo', images='$images1', active='$active', onhome='$onhome', homelinks='$homelinks', parent=$parent, cat_type='$cat_type' WHERE catid='$catid'");
		fixweight_cat();
		//updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _EDIT_DOCUMENT_CAT);
		updateadmlog($admin_ar[0], _MODTITLE, 'Chỉnh sửa chuyên mục', 'Chỉnh sửa chuyên mục '.$title.' | ID-'.$catid);
		header("Location: modules.php?f=".$adm_modname."&do=categories");
	}
}

$result = $db->sql_query("SELECT title, seo_title, seo_description, seo_keyword, images, active, onhome, homelinks, parent FROM ".$prefix."_document_cat WHERE catid=$catid AND alanguage='$currentlang'");

if($db->sql_numrows($result) != 1) {
	header("Location: ".$adm_modname.".php");
	die();
}
list($title, $title_seo, $description_seo, $keyword_seo, $images, $active, $onhome, $homelinks, $parent) = $db->sql_fetchrow($result);

include_once("page_header.php");

echo "<script language=\"javascript\">\n";
echo "	function check(f) {\n";
echo "		if(f.title.value =='') {\n";
echo "			alert('"._ERROR1."');\n";
echo "			f.title.focus();\n";
echo "			return false;\n";
echo "		}\n";
echo "		f.submit.disabled = true;\n";
echo "		return true;	\n";
echo "	}	\n";
echo "</script>	\n";
echo "
<div id=\"pagecontent\">";
echo "<form method=\"POST\" action=\"modules.php?f=".$adm_modname."&do=edit_cat&id=$catid\" onsubmit=\"return check(this);\"  enctype=\"multipart/form-data\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
echo "<tr><td colspan=\"2\" class=\"header\">"._MODTITLE." &rsaquo; "._EDITCAT."</td></tr>";
echo "<tr>\n";
echo "<td width=\"30%\" align=\"right\" class=\"row1\"><b>"._TITLE."</b></td>\n";
echo "<td class=\"row2\">$err_title<input type=\"text\" name=\"title\" value=\"$title\" size=\"50\"></td>\n";
echo "</tr>\n";

//hien thi tat ca cac cap danh muc tin tuc
	$resultcat = $db->sql_query("SELECT catid, title FROM ".$prefix."_document_cat WHERE parent='0' AND catid!='$catid' AND alanguage='$currentlang' ORDER BY weight");
	if($db->sql_numrows($resultcat) > 0) {
		echo "<tr bgcolor=\"$scolor1\">\n";
		echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>"._IS_SUBCAT_OF."</b></td>\n";
		echo "<td class=\"row2\"><select name=\"parent\">";
		echo "<option name=\"catid\" value=\"0\">"._ROOT_CAT."</option>";
		$listcat ="";
		while(list($cat_id, $titlecat) = $db->sql_fetchrow($resultcat)) {
			if($cat_id == $parent) {$seld =" selected"; }else{ $seld ="";}
			$listcat .= "<option value=\"$cat_id\"$seld>--$titlecat</option>";
			$listcat .= subcat($cat_id,"-",$catid, $catid);
		}
		echo $listcat;
		echo "</select></td>\n";
		echo "</tr>\n";
	}

echo "<tr bgcolor=\"#F7F7F7\">\n";
echo "<td align=\"right\" class=\"row1\"><b>"._SHOW."</b></td>\n";
if ($active == 1) {
	echo "<td class=\"row2\"><input type=\"radio\" name=\"active\" value=\"1\" checked>"._YES." &nbsp;&nbsp;"
	."<input type=\"radio\" name=\"active\" value=\"0\">"._NO."</td>\n";
	echo "</tr>\n";
} else {
	echo "<td class=\"row2\"><input type=\"radio\" name=\"active\" value=\"1\">"._YES." &nbsp;&nbsp;"
	."<input type=\"radio\" name=\"active\" value=\"0\" checked>"._NO."</td>\n";
	echo "</tr>\n";
}
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._ONHOME."</b></td>\n";
if ($onhome == 1) {
	echo "<td  class=\"row2\"><input type=\"radio\" name=\"onhome\" value=\"1\" checked>"._YES." &nbsp;&nbsp;"
	."<input type=\"radio\" name=\"onhome\" value=\"0\">"._NO."</td>\n";
} else {
	echo "<td  class=\"row2\"><input type=\"radio\" name=\"onhome\" value=\"1\">"._YES." &nbsp;&nbsp;"
	."<input type=\"radio\" name=\"onhome\" value=\"0\" checked>"._NO."</td>\n";
}
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>"._HOMELINKS."</b></td>\n";
echo "<td class=\"row2\"><select name=\"homelinks\">\n";
for($i = 0; $i <= 10; $i ++) {
	$seld ="";
	if($i == $homelinks) { $seld =" selected"; }
	echo "<option value=\"$i\"".$seld.">$i</option>\n";
}
echo "</select></td>\n";
echo "</tr>\n";
if (empty($images)) {
	echo "<tr>\n";
	echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._IMAGE."</b></td>\n";
	echo "<td class=\"row2\"><input type=\"file\" name=\"userfile\" size=\"60\"></td>\n";
	echo "</tr>\n";
} else {
	echo "	<tr>\n";
	echo "		<td align=\"right\" class=\"row1\"><b>"._DELIMAGE."</b></td>\n";
	echo "		<td class=\"row2\"><input type=\"checkbox\" name=\"delimg\" value=\"1\">&nbsp;<a href=\"".RPATH."$path_upload/$images\" target=\"_blank\" info=\""._VIEWIMAGE."\"><img border=\"0\" src=\"../images/img.gif\" align=\"absmiddle\"></a></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" class=\"row1\"><b>"._CHANGE_IMAGE."</b></td>\n";
	echo "		<td class=\"row2\"><input type=\"file\" name=\"userfile\" size=\"60\"></td>\n";
	echo "	</tr>\n";
	echo "<input type=\"hidden\" name=\"images\" value=\"$images\">";
}
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._TITLE_SEO."</b></td>\n";
echo "<td class=\"row2\"><input type=\"text\" name=\"title_seo\" value=\"$title_seo\" size=\"60\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._DESCRIPTION_SEO."</b></td>\n";
echo "<td class=\"row2\"><textarea cols=\"58\" rows=\"3\" name=\"description_seo\">$description_seo</textarea></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._KEYWORD_SEO."</b></td>\n";
echo "<td class=\"row2\"><input type=\"text\" name=\"keyword_seo\" value=\"$keyword_seo\" size=\"40\"></td>\n";
echo "</tr>\n";
echo "<input type=\"hidden\" name=\"subup\" value=\"1\">";
echo "<tr><td colspan=\"2\" align=\"center\" class=\"row4\"><input type=\"submit\"  class=\"button2\" name=\"submit\" value=\""._SAVECHANGES."\"> <input type=\"button\"  class=\"button2\" value=\""._CANCEL."\" onclick=\"window.location='".$adm_modname.".php?f=".$adm_modname."&do=categories'\"></td></tr>";
echo "</table></form></div>";

include_once("page_footer.php");
?>