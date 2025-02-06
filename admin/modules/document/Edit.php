<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

$id = intval(isset($_GET['id']) ? $_GET['id'] : $_POST['id']);

$query = "SELECT d.catid, d.title, d.time, d.hometext, d.bodytext, d.seo_title, d.seo_description, d.seo_keyword, d.seo_tag, d.fattach, d.link_extend, d.fattach_intro, d.price, d.news_type, d.images, d.active, d.alanguage, d.nstart, d.special, d.user_id, u.fullname, u.folder, d.uadmin";
$table = "{$prefix}_document";
$query .= " FROM $table AS d INNER JOIN {$prefix}_user AS u ON d.user_id=u.id WHERE d.id=$id";
$result = $db->sql_query($query);

if($db->sql_numrows($result) != 1) header("Location: modules.php?f=$adm_modname");
list($catid, $title, $time, $hometext, $bodytext, $title_seo, $description_seo, $keyword_seo, $tag_seo, $fattach, $link_extend, $fattach_intro, $price, $news_type, $images, $active, $alang, $startid, $special, $user_id, $fullname, $folder,$uadmin) = $db->sql_fetchrow($result);

if($folder==""){$folder='guest';}
$path_upload = "$path_upload/$adm_modname/$folder";
$err_title = $err_cat = "";
list ($idadmin, $permission) = $db->sql_fetchrow($db->sql_query("SELECT id, permission FROM ".$prefix."_admin WHERE adacc='$admin_ar[0]'"));
if($permission!=2){
	if($idadmin!=$uadmin){
	die("Illegal File Access");
}
}

if (isset($_POST['submit'])) {
	$title				= $escape_mysql_string(trim($_POST['title']));
	$link_extend		= $escape_mysql_string(trim($_POST['link_extend']));
	$catid				= intval($_POST['catid']);
	$account			= nospatags(trim($_POST['account']));
	$price				= intval($_POST['price']);
	$startid			= isset($_POST['startid']) ? intval($_POST['startid']):0;
	$special			= isset($_POST['special']) ? intval($_POST['special']):0;
	$bodytext			= $escape_mysql_string(trim($_POST['bodytext']));
	$news_type			= "document-type";
	$guid				= "index.php?f=".$adm_modname."&do=detail&id=$id";
	$delimg				= isset($_POST['delimg']) ? intval($_POST['delimg']):0;
	$delattach			= isset($_POST['delattach']) ? intval($_POST['delattach']):0;
	$delattach_intro	= isset($_POST['delattach_intro']) ? intval($_POST['delattach_intro']):0;
	$images				= isset($_POST['images']) ? $escape_mysql_string(trim($_POST['images'])) : '';
	$title_seo			= isset($_POST['title_seo']) ? $escape_mysql_string(trim($_POST['title_seo'])) : '';
	$description_seo	= isset($_POST['description_seo']) ? $escape_mysql_string(trim($_POST['description_seo'])) : '';
	$keyword_seo		= isset($_POST['keyword_seo']) ? $escape_mysql_string(trim($_POST['keyword_seo'])) : '';
	$tag_seo			= isset($_POST['tag_seo']) ? $escape_mysql_string(trim($_POST['tag_seo'])) : '';

	$err = 0;
	if($title =="") {
		$err_title = "<font color=\"red\">"._ERROR1."</font><br/>";
		$err = 1;
	}
	else
	{
		$permalink=url_optimization(trim($title));
	}
	if($catid == 0) {
		$err_cat = "<font color=\"red\">"._ERROR1_1."</font><br/>";
		$err = 1;
	}
	// delete file images
	if($delimg == 1) {
		@unlink(RPATH."$path_upload/$images");
		$images = "";
	}
	// delete file attach
	if($delattach_intro == 1) {
		@unlink(RPATH."$path_upload/$fattach_intro");
		$fattach_intro = "";
	}
	// delete file attach
	if($delattach == 1) {
		@unlink(RPATH."$path_upload/$fattach");
		$fattach = "";
	}
	if(!$err) {
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
			//if ((($timed) && (!empty($images))) || ((!$timed) && ($_GET['type'] == 'timed'))) {
			//	if (!is_dir(RPATH."$newUploadPath")) mkdir(RPATH."$newUploadPath");
			//	@copy(RPATH."$path_upload/$images", RPATH."$newUploadPath/$images");
			//	@unlink(RPATH."$path_upload/$images");
			//}
			$images1 = $images;
		}
		//upload file attach
		if (is_uploaded_file($_FILES['userattach_intro']['tmp_name']))
		{
			$upload_attach = new Upload("userattach_intro", $path_upload, $maxsize_up, $newnamefile);
			$fattach_intro1 = $upload_attach->send();
			if(!empty($fattach_intro1) && !empty($fattach_intro))
			{
				@unlink(RPATH."$path_upload/$fattach_intro");
				$fattach_intro="";
			}
		}
		else
		{
			$fattach_intro1=$fattach_intro;
		}
		//upload file attach
		if (is_uploaded_file($_FILES['userattach']['tmp_name']))
		{
			$upload_attach = new Upload("userattach", $path_upload, $maxsize_up, $newnamefile);
			$fattach1 = $upload_attach->sendftp();
			
			if(!empty($fattach1) && !empty($fattach))
			{
				@unlink(RPATH."$path_upload/$fattach");
				$fattach="";
			}
		}
		else
		{
			$fattach1=$fattach;
		}
		if (empty($images1) && empty($images)) { $imgtext = ""; $highlight = 0;}

		if ((($_GET['type'] == 'normal') && (!$timed)) || (($_GET['type'] == 'timed') && ($timed))) {
			$ckresult = $db->sql_query("SELECT permalink FROM ".$prefix."_document WHERE permalink='$permalink' AND  id<>$id");
			if($db->sql_numrows($ckresult) > 0) {
				$permalink=$permalink.'-'.$id;
			}
			$query = "UPDATE $table SET catid=$catid, title='$title', permalink='$permalink',  guid='$guid', bodytext='$bodytext', seo_title='$title_seo', seo_description='$description_seo', seo_keyword='$keyword_seo', seo_tag='$tag_seo', fattach='$fattach1', link_extend='$link_extend', fattach_intro='$fattach_intro1', price = $price, news_type='$news_type', images='$images1', source='$source', nstart=$startid, special=$special";
			$query .= " WHERE id=$id";
			$db->sql_query($query);
		}
		if (($db->sql_affectedrows() > 0) && (!$timed)) {
			fixcount_cat();
			if($startid == 1) {
				if ($_GET['type'] == 'timed') {
					$db->sql_query("SELECT LAST_INSERT_ID()");
					list($lastInsertId) = $db->sql_fetchrow();					
				} else {
					$lastInsertId = $id;
				}
				$db->sql_query("UPDATE {$prefix}_document SET nstart=0 WHERE id!=$lastInsertId AND catid=$catid");
				$db->sql_query("UPDATE {$prefix}_document_cat SET startid=$lastInsertId WHERE catid=$catid");
			}
		}
		//ghi log
		//updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _DOCUMENT_EDIT);
		updateadmlog($admin_ar[0], _MODTITLE, 'Chỉnh sửa tài liệu', 'Chỉnh sửa tài liệu '.$title);
		echo "<script language=\"javascript\" type=\"text/javascript\">";
			echo "alert('Tài liệu đã được chỉnh sửa thành công!');";
			echo " window.location.href=\"modules.php?f=".$adm_modname."&do=edit&id=$id\";";
		echo "</script>";
		//header("Location: modules.php?f=".$adm_modname);
	}
}

$title = str_replace('"',"''",$title);

include_once("page_header.php");

echo "<form action=\"modules.php?f=$adm_modname&do=$do&id=$id&type={$_GET['type']}\" method=\"POST\" enctype=\"multipart/form-data\">";
echo "
<div id=\"pagecontent\">
	<div class=\"ctrl-header\">
		<div><span id=\"ctl10_lblTitle\">"._EDITNEWS."</span></div>
	</div>
	<div class=\"ctrl-content\">
		<div class=\"ctrl-content-list\">";
echo "<table class=\"tableborder\" border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
echo "<tr>\n";
echo "<td width=\"120px\" align=\"right\" class=\"row1\"><b>Người đăng</b></td>\n";
echo "<td class=\"row2\">$fullname</td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"120px\" align=\"right\" class=\"row1\"><b>"._TITLE."</b></td>\n";
echo "<td class=\"row2\">$err_title<input type=\"text\" name=\"title\" value=\"$title\" size=\"100\"></td>\n";
echo "</tr>\n";
echo "<tr>";
echo "<td width=\"120px\" align=\"right\" class=\"row1\"><b>"._PRICE." (xu)</b></td>\n";
echo "<td class=\"row2\">$err_price<input type=\"text\" id=\"price\" name=\"price\" value=\"$price\" size=\"30\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._INCAT."</b></td>\n";
$result_cat = $db->sql_query("SELECT catid, title FROM ".$prefix."_document_cat WHERE catid IN (SELECT document FROM ".$prefix."_document_permission WHERE admingroup=2 OR admingroup=(SELECT permission FROM ".$prefix."_admin WHERE adacc='$admin_ar[0]')) AND parent='0' and alanguage='$currentlang' ORDER BY weight");
echo "		<td class=\"row2\">$err_cat<select name=\"catid\">";
echo "<option name=\"catid\" value=\"0\">"._INCAT0."</option>";
$listcat ="";
while(list($cat_id, $titlecat) = $db->sql_fetchrow($result_cat)) {
	if($cat_id == $catid) {$seld =" selected"; }else{ $seld ="";}
	$listcat .= "<option value=\"$cat_id\"$seld style=\"font-weight: bold\">- $titlecat</option>";
	$listcat .= subcat($cat_id,"-",$catid, "");
}
echo $listcat;
echo "</select></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._NEWSTART."</b></td>\n";
if($startid == 1) {
			echo "<td class=\"row2\"><input type=\"radio\" name=\"startid\" value=\"1\" checked>"._YES." &nbsp;&nbsp;";
			echo "<input type=\"radio\" name=\"startid\" value=\"0\">"._NO."</td>\n";
			echo "</tr>\n";
		} else {
			echo "<td class=\"row2\"><input type=\"radio\" name=\"startid\" value=\"1\">"._YES." &nbsp;&nbsp;";
			echo "<input type=\"radio\" name=\"startid\" value=\"0\" checked>"._NO."</td>\n";
			echo "</tr>\n";
		}
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._SPECIAL."</b></td>\n";
if($special == 1) 
{
	echo "<td class=\"row2\"><input type=\"radio\" name=\"special\" value=\"1\" checked>"._YES." &nbsp;&nbsp;";
	echo "<input type=\"radio\" name=\"special\" value=\"0\">"._NO."</td>\n";
} 
else 
{
	echo "<td class=\"row2\"><input type=\"radio\" name=\"special\" value=\"1\">"._YES." &nbsp;&nbsp;";
	echo "<input type=\"radio\" name=\"special\" value=\"0\"  checked>"._NO."</td>\n";
}
echo "</tr>\n";

if (empty($images)) {
	echo "<tr>\n";
	echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._IMAGE."</b></td>\n";
	echo "<td class=\"row2\"><input type=\"file\" name=\"userfile\" size=\"60\"></td>\n";
	echo "</tr>\n";
	$imgtext = "";
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
echo "</tr>\n";
if($folder_site)  $url = !empty($fattach_intro) ? "$fattach_intro" : '';
else $url = !empty($fattach_intro) ? "$fattach_intro" : '';
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>File xem trước</b></td>\n";
$disabled="";
if (empty($fattach_intro)) 
{
	$disabled="disabled=\"$disabled\"";
}
echo "<td class=\"row2\"><input type=\"checkbox\" name=\"delattach_intro\" value=\"1\" $disabled>&nbsp;<input type=\"text\" value=\"$url\" size=\"60\" readonly=\"readonly\" /><br>Tích chọn để xóa file xem trước</td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._NEWS_ATTACH_FILE."</b></td>\n";
echo "<td class=\"row2\"><input type=\"file\" name=\"userattach_intro\" size=\"60\"></td>\n";
echo "</tr>\n";
if($folder_site)  $url = !empty($fattach) ? "$fattach" : '';
else $url = !empty($fattach) ? "$fattach" : '';
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._NEWS_ATTACH_URL."</b></td>\n";
$disabled="";
if (empty($fattach)) 
{
	$disabled="disabled=\"$disabled\"";
}
echo "<td class=\"row2\"><input type=\"checkbox\" name=\"delattach\" value=\"1\" $disabled>&nbsp;<input type=\"text\" value=\"$url\" size=\"60\" readonly=\"readonly\" /><br>Tích chọn để xóa file đính kèm</td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._NEWS_ATTACH_FILE."</b></td>\n";
echo "<td class=\"row2\"><input type=\"file\" name=\"userattach\" size=\"60\"></td>\n";
echo "</tr>\n";
echo "<tr>";
echo "<td width=\"120px\" align=\"right\" class=\"row1\"><b>Link mở rộng</b></td>\n";
echo "<td class=\"row2\"><input type=\"text\" id=\"link_extend\" name=\"link_extend\" value=\"$link_extend\" size=\"100\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._BODYTEXT."</b></td>\n";
echo "<td class=\"row2\" colspan=\"2\">";
editor("bodytext",$bodytext,"",400);
echo "</td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._TAG_SEO."</b></td>\n";
echo "<td class=\"row2\"><input type=\"text\" name=\"tag_seo\" value=\"$tag_seo\" size=\"60\"></td>\n";
echo "</tr>\n";
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
echo "<tr><td >&nbsp;</td><td ><input type=\"submit\" name=\"submit\" value=\""._SAVECHANGES."\" class=\"button2\"></td></tr>";

echo "</table>";
echo "	</div>
		<div class=\"ctrl-footer\"></div>
</div>
<div class=\"cl\"></div>
</div>
</div>";
echo "</form>";

include_once("page_footer.php");
?>