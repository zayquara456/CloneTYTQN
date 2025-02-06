<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

include("page_header.php");
global $path_upload, $adm_modname;
$active = 1;
$title =$permalink = $catid = $hometext = $bodytext = $imgtext = $source = $title_seo = $description_seo = $keyword_seo = $tag_seo = $startid = $special = $imgshow = $err_cat = $err_title = $images = $highlight= '';
if( isset($_POST['subup']) && $_POST['subup'] == 1) {
	$err = 0;
	$title = nospatags($_POST['title']);
	$links = nospatags($_POST['links']);
	$link_extend = nospatags($_POST['link_extend']);
	$catid = intval($_POST['catid']);
	$hometext = $escape_mysql_string(trim($_POST['hometext']));
	$bodytext = $escape_mysql_string(trim($_POST['bodytext']));
	$title_seo = nospatags($_POST['title_seo']);
	$description_seo = nospatags($_POST['description_seo']);
	$keyword_seo = nospatags($_POST['keyword_seo']);
	$tag_seo = nospatags($_POST['tag_seo']);
	$startid = isset($_POST['startid']) ? intval($_POST['startid']) : 0;
	$special = isset($_POST['special']) ? intval($_POST['special']) : 0;
	list ($user_id) = $db->sql_fetchrow($db->sql_query("SELECT id FROM ".$prefix."_admin WHERE adacc='$admin_ar[0]'"));
	$result = $db->sql_query("SELECT id FROM ".$prefix."_admin where adacc='$admin_ar[0]'");
	if($db->sql_numrows($result) > 0) {
		list($user_id) = $db->sql_fetchrow($result);
		$user_id= $user_id;
	}
	else{
		$err_name = "<font color=\"red\">Tài khoản không tồn tại</font><br/>";
		$err = 1;
	}
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
		$newnamefile	= substr(str_replace("-","_",$permalink),0,60)."_".generate_code(6);
		$code	= md5(generate_code(6));
		if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {
			$path_upload_img = "$path_upload/$adm_modname";
			
			$upload = new Upload("userfile", $path_upload_img, $maxsize_up,$newnamefile);
			$images = $upload->send();
		}
		if (empty($images)) { $imgtext = ""; $highlight = 0; }
		list ($xid) = $db->sql_fetchrow($db->sql_query("SELECT max(id) AS xid FROM ".$prefix."_video"));
		if ($xid == "-1") { $id = 1; } else { $id = $xid + 1; }
		
		$ckresult = $db->sql_query("SELECT permalink FROM ".$prefix."_video WHERE permalink='$permalink'");
		if($db->sql_numrows($ckresult) > 0) {
			$permalink = $permalink.'-'.$id;
		}
		$query = "INSERT INTO {$prefix}_video (code, catid, title, permalink, alanguage, hometext, bodytext, seo_title, seo_description, seo_keyword, seo_tag, link_extend, links, images, active, hits, nstart, special, time, user_id) VALUES ('$code', $catid, '$title', '$permalink', '$currentlang', '$hometext', '$bodytext', '$title_seo', '$description_seo', '$keyword_seo', '$tag_seo', '$link_extend', '$links', '$images', $active, 0, $startid, $special, ".time().", $user_id)";
		$result = $db->sql_query($query);
		//update guid
		$guid="index.php?f=".$adm_modname."&do=detail&id=$id";
		$query = "UPDATE {$prefix}_video SET guid='$guid' WHERE id='$id'";
		$db->sql_query($query);
		if (($db->sql_affectedrows() > 0) && (!$timed)) {
			fixcount_cat();
			if($startid == 1) {
				$db->sql_query("UPDATE {$prefix}_video SET nstart=0 WHERE id!=$id AND catid=$catid");
				$db->sql_query("UPDATE {$prefix}_video_cat SET startid=$id WHERE catid=$catid");
			}
		}
		//ghi log
		$resultinfo = $db->sql_query("SELECT n.id, c.title, c.catid, n.title FROM {$prefix}_video_cat AS c, {$prefix}_video AS n WHERE c.catid=n.catid AND id='$id'");
		if ($db->sql_numrows($resultinfo) > 0) {
				list($id, $cattitle, $catid, $title) = $db->sql_fetchrow($resultinfo);
		}
		updateadmlog($admin_ar[0], _MODTITLE, 'Đăng video mới', 'Đăng video '.$title.' | ID-'.$id.' | Thuộc chuyên mục '.$cattitle.' | ID-'.$catid);
		echo "<script language=\"javascript\" type=\"text/javascript\">";
			echo "alert('Video đã được gửi lên thành công!');";
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
		<div>"._ADDNEWS."</div>
	</div>
	<div class=\"ctrl-content\">
		<div class=\"ctrl-content-list\">";
echo "<table class=\"tableborder\" cellpadding=\"5\" cellspacing=\"0\" border=\"0\" width=\"100%\">\n";
echo "<tr>";
echo "<td width=\"160px\" align=\"right\" class=\"row1\"><b>"._TITLE."</b></td>\n";
echo "<td class=\"row2\">$err_title<input type=\"text\" id=\"title\" name=\"title\" value=\"$title\" size=\"100\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._INCAT."</b></td>\n";
$result_cat = $db->sql_query("SELECT catid, title FROM ".$prefix."_video_cat WHERE parent='0' and alanguage='$currentlang' ORDER BY weight");
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
		echo "<td align=\"right\" class=\"row1\"><b>"._SPECIAL."</b></td>\n";
		if($special == 1) {
			echo "<td class=\"row2\"><input type=\"radio\" name=\"special\" value=\"1\" checked>"._YES." &nbsp;&nbsp;";
			echo "<input type=\"radio\" name=\"special\" value=\"0\">"._NO."</td>\n";
			echo "</tr>\n";
		} else {
			echo "<td class=\"row2\"><input type=\"radio\" name=\"special\" value=\"1\">"._YES." &nbsp;&nbsp;";
			echo "<input type=\"radio\" name=\"special\" value=\"0\" checked>"._NO."</td>\n";
			echo "</tr>\n";
		}
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
		echo "<td width=\"160px\" align=\"right\" class=\"row1\"><b>"._IMAGE."</b></td>\n";
		echo "<td class=\"row2\"><input type=\"file\" name=\"userfile\" size=\"60\"></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<tr>\n";
		echo "<td width=\"160px\" align=\"right\" class=\"row1\"><b>"._LINKS."</b><br>"._NOTE_VIDEO."</td>\n";
		echo "<td class=\"row2\"><input type=\"text\" name=\"links\" size=\"50\"><br>https://www.youtube.com/watch?v=<b color='red'>003LPzYwSNc</b> người dùng chỉ cần copy đoạn mã đậm";
		echo "</td>\n";
		echo "</tr>\n";
		echo "<tr>";
		echo "<td width=\"160px\" align=\"right\" class=\"row1\"><b>Link mở rộng</b></td>\n";
		echo "<td class=\"row2\"><input type=\"text\" id=\"link_extend\" name=\"link_extend\" value=\"$link_extend\" size=\"100\"></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td align=\"right\" class=\"row1\"  valign=\"top\"><b>"._HOMETEXT."</b></td>\n";
		echo "<td class=\"row2\" colspan=\"2\">";
		echo "<textarea cols=\"80\" rows=\"5\" name=\"hometext\">$hometext</textarea>";
		echo "</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td align=\"right\" class=\"row1\" valign=\"top\"><b>"._BODYTEXT."</b></td>\n";
		echo "<td class=\"row2\" colspan=\"2\">";
		editor("bodytext", $bodytext,"",400);
		echo "</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td width=\"160px\" align=\"right\" class=\"row1\"><b>"._TAG_SEO."</b></td>\n";
		echo "<td class=\"row2\"><input type=\"text\" name=\"tag_seo\" value=\"$tag_seo\" size=\"60\"></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td width=\"160px\" align=\"right\" class=\"row1\"><b>"._TITLE_SEO."</b></td>\n";
		echo "<td class=\"row2\"><input type=\"text\" name=\"title_seo\" value=\"$title_seo\" size=\"60\"></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td width=\"160px\" align=\"right\" class=\"row1\"><b>"._DESCRIPTION_SEO."</b></td>\n";
		echo "<td class=\"row2\"><textarea cols=\"56\" rows=\"3\" name=\"description_seo\">$description_seo</textarea></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td width=\"160px\" align=\"right\" class=\"row1\"><b>"._KEYWORD_SEO."</b></td>\n";
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
