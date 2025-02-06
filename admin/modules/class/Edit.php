<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

$id = intval(isset($_GET['id']) ? $_GET['id'] : $_POST['id']);

$query = "SELECT parentid, title, time, description, content, seo_title, seo_description, seo_keyword, seo_tag, images, status";
$table = "{$prefix}_class";
$query .= " FROM $table WHERE id=$id";
$result = $db->sql_query($query);

if($db->sql_numrows($result) != 1) header("Location: modules.php?f=$adm_modname");
list($parentid, $title, $time, $description, $content, $title_seo, $description_seo, $keyword_seo, $tag_seo, $images, $active) = $db->sql_fetchrow($result);
$path_upload = "$path_upload/$adm_modname";
$err_title = $err_cat = "";
if (isset($_POST['submit'])) {
	$title				= $escape_mysql_string(trim($_POST['title']));
	$parentid			= isset($_POST['parentid']) ? intval($_POST['parentid']):0;
	$description		= $escape_mysql_string(trim($_POST['description']));
	$content			= $escape_mysql_string(trim($_POST['content']));
	$guid				= "index.php?f=".$adm_modname."&do=detail&id=$id";
	$delimg				= isset($_POST['delimg']) ? intval($_POST['delimg']):0;
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
	// delete file images
	if($delimg == 1) {
		@unlink(RPATH."$path_upload/$images");
		$images = "";
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
			$images1 = $images;
		}
		if (empty($images1) && empty($images)) { $imgtext = ""; $highlight = 0;}
			$ckresult = $db->sql_query("SELECT permalink FROM ".$prefix."_class WHERE permalink='$permalink'");
			if($db->sql_numrows($ckresult) > 0) {
				$permalink=$permalink.'-'.$id;
			}
			$query = "UPDATE $table SET parentid=$parentid, title='$title', permalink='$permalink',  guid='$guid', description='$description', content='$content', seo_title='$title_seo', seo_description='$description_seo', seo_keyword='$keyword_seo', seo_tag='$tag_seo', images='$images1'";
			$query .= " WHERE id=$id";
			$db->sql_query($query);
			updateadmlog($admin_ar[0], _MODTITLE, 'Chỉnh sửa lớp học', 'Chỉnh sửa lớp học '.$title);
			echo "<script language=\"javascript\" type=\"text/javascript\">";
				echo "alert('Lớp học đã được chỉnh sửa thành công!');";
				echo " window.location.href=\"modules.php?f=".$adm_modname."\";";
			echo "</script>";
	}
}

$title = str_replace('"',"''",$title);

include_once("page_header.php");

echo "<form action=\"modules.php?f=$adm_modname&do=$do&id=$id\" method=\"POST\" enctype=\"multipart/form-data\">";
echo "
<div id=\"pagecontent\">
	<div class=\"ctrl-header\">
		<div><span id=\"ctl10_lblTitle\">Chỉnh sửa lớp học</span></div>
	</div>
	<div class=\"ctrl-content\">
		<div class=\"ctrl-content-list\">";
echo "<table class=\"tableborder\" border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
echo "<tr>\n";
echo "<td width=\"120px\" align=\"right\" class=\"row1\"><b>"._TITLE."</b></td>\n";
echo "<td class=\"row2\">$err_title<input type=\"text\" name=\"title\" value=\"$title\" size=\"100\"></td>\n";
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
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>Miêu tả</b></td>\n";
echo "<td class=\"row2\" colspan=\"2\">";
echo "<textarea cols=\"80\" rows=\"5\" name=\"description\">$description</textarea>";
//editorbasic("hometext",$hometext,"",200);
echo "</td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>Nội dung</b></td>\n";
echo "<td class=\"row2\" colspan=\"2\">";
editor("content", $content,"",400);
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