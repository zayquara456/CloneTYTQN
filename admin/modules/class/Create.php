<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

include("page_header.php");
global $path_upload, $adm_modname;
$adm_modname = 'class';
//$act=intval(isset($_GET['act']) ? $_GET['act'] : $_POST['act']);
$title =$permalink = $parentid = $description = $content =  $title_seo = $description_seo = $keyword_seo = $tag_seo =  $err_title = $images = '';
list ($permission) = $db->sql_fetchrow($db->sql_query("SELECT permission FROM ".$prefix."_admin WHERE adacc='$admin_ar[0]'"));
if($permission==2){$active = 1;}
else{$active = 0;}
if( isset($_POST['subup']) && $_POST['subup'] == 1) {
	$err = 0;
	$title = nospatags($_POST['title']);
	$link_extend = nospatags($_POST['link_extend']);
	//$permalink =nospatags($_POST['permalink']);
	$parentid = intval($_POST['parentid']);
	$act = intval($_POST['act']);
	$description = $escape_mysql_string(trim($_POST['description']));
	$content = $escape_mysql_string(trim($_POST['content']));
	$title_seo = nospatags($_POST['title_seo']);
	$description_seo = nospatags($_POST['description_seo']);
	$keyword_seo = nospatags($_POST['keyword_seo']);
	$tag_seo = nospatags($_POST['tag_seo']);
	list ($uadmin) = $db->sql_fetchrow($db->sql_query("SELECT id FROM ".$prefix."_admin WHERE adacc='$admin_ar[0]'"));
	if (empty($title)) {
		$error_title = "<font color=\"red\">"._ERROR4."</font><br/>";
		$err = 1;
	}
	else
	{
		$permalink=url_optimization(trim($title));
	}
	if (empty($permalink)) {
		$error_title = "<font color=\"red\">"._ERROR4."</font><br/>";
		$err = 1;
	}

	if (!$err) {
		$newnamefile = substr(str_replace("-","_",$permalink),0,60)."_".generate_code(6);
		
		if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {
			$path_upload_img = "$path_upload/$adm_modname";
			
			$upload = new Upload("userfile", $path_upload_img, $maxsize_up,$newnamefile);
			$images = $upload->send();
		}
		if (empty($images)) { $imgtext = ""; $highlight = 0; }
		list ($xid) = $db->sql_fetchrow($db->sql_query("SELECT max(id) AS xid FROM ".$prefix."_class"));
		if ($xid == "-1") { $id = 1; } else { $id = $xid + 1; }
		
		$ckresult = $db->sql_query("SELECT permalink FROM ".$prefix."_class WHERE permalink='$permalink'");
		if($db->sql_numrows($ckresult) > 0) {
			$permalink = $permalink.'-'.$id;
		}
		$query = "INSERT INTO {$prefix}_class (parentid, title, permalink, description, content, seo_title, seo_description, seo_keyword, seo_tag, images, status, time) VALUES ($parentid, '$title', '$permalink', '$description', '$content', '$title_seo', '$description_seo', '$keyword_seo', '$tag_seo', '$images', $active, ".time().")";
		$result = $db->sql_query($query);
		//update guid
		$guid="index.php?f=".$adm_modname."&do=detail&id=$id";
		$query = "UPDATE {$prefix}_class SET guid='$guid' WHERE id='$id'";
		$db->sql_query($query);
		//ghi log
		updateadmlog($admin_ar[0], _MODTITLE, 'Đăng lớp học mới', 'Đăng lớp học '.$title.' | ID-'.$id);
		echo "<script language=\"javascript\" type=\"text/javascript\">";
			echo "alert('Lớp học được đăng lên thành công!');";
			echo " window.location.href=\"modules.php?f=".$adm_modname."&do=create\";";
		echo "</script>";
		//header("Location: modules.php?f=".$adm_modname."");
	}
}
echo "<script language=\"javascript\">\n";
echo "	function check(f) {\n";
echo "		if(f.title.value =='') {\n";
echo "			alert('"._ERROR4."');\n";
echo "			f.title.focus();\n";
echo "			return false;\n";
echo "		}	\n";
echo "		\n";

echo "		if(f.catid.value == 0) {\n";
echo "			alert('"._ERROR1_1."');\n";
echo "			f.catid.focus();\n";
echo "			return false;\n";
echo "		}\n";
echo "		f.submit.disabled = true;\n";
echo "		return true;	\n";
echo "	}	\n";
echo "</script>	\n";
ajaxload_content();
echo "<form action=\"modules.php?f=$adm_modname&do=$do\" method=\"POST\" enctype=\"multipart/form-data\" onsubmit=\"return check(this);\">";
echo "
<div id=\"pagecontent\">
	<div class=\"ctrl-header\">
		<div>Tạo lớp học mới</div>
	</div>
	<div class=\"ctrl-content\">
		<div class=\"ctrl-content-list\">";
echo "<table class=\"tableborder\" cellpadding=\"5\" cellspacing=\"0\" border=\"0\" width=\"100%\">\n";
echo "<tr>";
echo "<td width=\"120px\" align=\"right\" class=\"row1\"><b>"._TITLE."</b></td>\n";
echo "<td class=\"row2\">$err_title<input type=\"text\" id=\"title\" name=\"title\" value=\"$title\" size=\"100\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"120px\" align=\"right\" class=\"row1\"><b>"._IMAGE."</b></td>\n";
echo "<td class=\"row2\"><input type=\"file\" name=\"userfile\" size=\"60\"></td>\n";
echo "</tr>\n";
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
echo "<td width=\"120px\" align=\"right\" class=\"row1\"><b>"._TAG_SEO."</b></td>\n";
echo "<td class=\"row2\"><input type=\"text\" name=\"tag_seo\" value=\"$tag_seo\" size=\"60\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"120px\" align=\"right\" class=\"row1\"><b>"._TITLE_SEO."</b></td>\n";
echo "<td class=\"row2\"><input type=\"text\" name=\"title_seo\" value=\"$title_seo\" size=\"60\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"120px\" align=\"right\" class=\"row1\"><b>"._DESCRIPTION_SEO."</b></td>\n";
echo "<td class=\"row2\"><textarea cols=\"56\" rows=\"3\" name=\"description_seo\">$description_seo</textarea></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"120px\" align=\"right\" class=\"row1\"><b>"._KEYWORD_SEO."</b></td>\n";
echo "<td class=\"row2\"><input type=\"text\" name=\"keyword_seo\" value=\"$keyword_seo\" size=\"60\"></td>\n";
echo "</tr>\n";
echo "<tr><td></td><td><input type=\"hidden\" name=\"subup\" value=\"1\"><input type=\"submit\" name=\"submit\" value=\""._ADD."\" class=\"button2\"></td></tr>";
echo "</table>";
echo "	</div></div>
		<div class=\"ctrl-footer\"></div>
</div>
<div class=\"cl\"></div>
</div>
</div>";
echo"</form>";

include_once("page_footer.php");
?>
