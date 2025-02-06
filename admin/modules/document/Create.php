<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

include("page_header.php");
global $path_upload, $adm_modname;
$adm_modname = 'document';
//$act=intval(isset($_GET['act']) ? $_GET['act'] : $_POST['act']);

$title =$permalink = $catid = $hometext = $bodytext = $imgtext = $source = $title_seo = $description_seo = $keyword_seo = $tag_seo = $startid = $special = $imgshow = $err_cat = $err_title = $images = $highlight= $err_name = $account = $err_price = $price = $link_extend = '';
list ($permission) = $db->sql_fetchrow($db->sql_query("SELECT permission FROM ".$prefix."_admin WHERE adacc='$admin_ar[0]'"));
if($permission==2){$active = 1;}
else{$active = 0;}
if( isset($_POST['subup']) && $_POST['subup'] == 1) {
	$err = 0;
	$title = nospatags($_POST['title']);
	$link_extend = nospatags($_POST['link_extend']);
	//$permalink =nospatags($_POST['permalink']);
	$catid = intval($_POST['catid']);
	//$act = intval($_POST['act']);
	$account = nospatags(trim($_POST['account']));
	$bodytext = $escape_mysql_string(trim($_POST['bodytext']));
	$news_type = "document-type";
	$price	= intval($_POST['price']);
	$title_seo = nospatags($_POST['title_seo']);
	$description_seo = nospatags($_POST['description_seo']);
	$keyword_seo = nospatags($_POST['keyword_seo']);
	$tag_seo = nospatags($_POST['tag_seo']);
	$startid = isset($_POST['startid']) ? intval($_POST['startid']) : 0;
	$special = isset($_POST['special']) ? intval($_POST['special']) : 0;
	list ($uadmin) = $db->sql_fetchrow($db->sql_query("SELECT id FROM ".$prefix."_admin WHERE adacc='$admin_ar[0]'"));
	$result = $db->sql_query("SELECT id, fullname, email, money, folder FROM ".$prefix."_user WHERE fullname='$account' OR email='$account'");
	if($db->sql_numrows($result) > 0) {
		list($user_id, $fullname, $email, $money, $folder) = $db->sql_fetchrow($result);
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
		$newnamefile = substr(str_replace("-","_",$permalink),0,60)."_".generate_code(6);
		
		if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {
			$path_upload_img = "$path_upload/$adm_modname/$folder";
			
			$upload = new Upload("userfile", $path_upload_img, $maxsize_up,$newnamefile);
			$images = $upload->send();
		}
		//upload file attachintro
		if (is_uploaded_file($_FILES['attachintro']['tmp_name']))
		{
			$path_upload_attach = "$path_upload/$adm_modname/$folder";
			$upload_attach = new Upload("attachintro", $path_upload_attach, $maxsize_up, $newnamefile);
			$fattach_intro = $upload_attach->send();
		}
		//upload file attach
		if (is_uploaded_file($_FILES['userattach']['tmp_name']))
		{
			$path_upload_attach = "$path_upload/$adm_modname/$folder";
			$upload_attach = new Upload("userattach", $path_upload_attach, $maxsize_up, $newnamefile);
			$fattach = $upload_attach->sendftp();
		}
		if (empty($images)) { $imgtext = ""; $highlight = 0; }
		list ($xid) = $db->sql_fetchrow($db->sql_query("SELECT max(id) AS xid FROM ".$prefix."_document"));
		if ($xid == "-1") { $id = 1; } else { $id = $xid + 1; }
		
		$ckresult = $db->sql_query("SELECT permalink FROM ".$prefix."_document WHERE permalink='$permalink'");
		if($db->sql_numrows($ckresult) > 0) {
			$permalink = $permalink.'-'.$id;
		}
		$code	= md5(generate_code(6).'-'.$id);
		$query = "INSERT INTO {$prefix}_document (code, catid, title, permalink, alanguage, bodytext, price, seo_title, seo_description, seo_keyword, seo_tag, fattach, link_extend, fattach_intro, news_type, images, active, hits, nstart, special, time, user_id, uadmin) VALUES ('$code', $catid, '$title', '$permalink', '$currentlang', '$bodytext', $price, '$title_seo', '$description_seo', '$keyword_seo', '$tag_seo', '$fattach', '$link_extend', '$fattach_intro', '$news_type', '$images', $active, 0, $startid, $special, ".time().", $user_id, $uadmin)";
		$result = $db->sql_query($query);
		//update guid
		$guid="index.php?f=".$adm_modname."&do=detail&id=$id";
		$query = "UPDATE {$prefix}_document SET guid='$guid' WHERE id='$id'";
		$db->sql_query($query);
		if (($db->sql_affectedrows() > 0) && (!$timed)) {
			fixcount_cat();
			if($startid == 1) {
				$db->sql_query("UPDATE {$prefix}_document SET nstart=0 WHERE id!=$id AND catid=$catid");
				$db->sql_query("UPDATE {$prefix}_document_cat SET startid=$id WHERE catid=$catid");
			}
		}
		//ghi log
		$resultinfo = $db->sql_query("SELECT n.id, c.title, c.catid, n.title FROM {$prefix}_document_cat AS c, {$prefix}_document AS n WHERE c.catid=n.catid AND id='$id'");
		if ($db->sql_numrows($resultinfo) > 0) {
				list($id, $cattitle, $catid, $title) = $db->sql_fetchrow($resultinfo);
		}
		updateadmlog($admin_ar[0], _MODTITLE, 'Đăng tài liệu mới', 'Đăng tài liệu '.$title.' | ID-'.$id.' | Thuộc chuyên mục '.$cattitle.' | ID-'.$catid);
		echo "<script language=\"javascript\" type=\"text/javascript\">";
			echo "alert('Tài liệu đã được gửi lên thành công!');";
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
echo "		fetch_object('ajaxload_container').style.display ='block';\n";
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
echo "<td width=\"120px\" align=\"right\" class=\"row1\"><b>"._TITLE."</b></td>\n";
echo "<td class=\"row2\">$err_title<input type=\"text\" id=\"title\" name=\"title\" value=\"$title\" size=\"100\"></td>\n";
echo "</tr>\n";
echo "<tr>";
echo "<td width=\"120px\" align=\"right\" class=\"row1\"><b>"._ACCOUNT."</b></td>\n";
echo "<td class=\"row2\">$err_name<input type=\"text\" onblur=\" show_ajaxcontent_byid(this.value, 'user', 'checkuser', 'id', 'checkuser')\" id=\"account\" name=\"account\" value=\"$account\" size=\"30\"><span id=\"checkuser\"></span></td>\n";
echo "</tr>\n";
echo "<tr>";
echo "<td width=\"120px\" align=\"right\" class=\"row1\"><b>"._PRICE." (xu)</b></td>\n";
echo "<td class=\"row2\">$err_price<input type=\"text\" id=\"price\" name=\"price\" value=\"$price\" size=\"30\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._INCAT."</b></td>\n";

echo "		<td class=\"row2\">$err_cat";
$result_cat = $db->sql_query("SELECT catid, title FROM ".$prefix."_document_cat WHERE catid IN (SELECT document FROM ".$prefix."_document_permission WHERE admingroup=(SELECT permission FROM ".$prefix."_admin WHERE adacc='$admin_ar[0]')) AND parent='0' AND alanguage='$currentlang' ORDER BY weight");
echo "<select name=\"catid\">";
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
echo "<td width=\"120px\" align=\"right\" class=\"row1\"><b>"._IMAGE."</b></td>\n";
echo "<td class=\"row2\"><input type=\"file\" name=\"userfile\" size=\"60\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"120px\" align=\"right\" class=\"row1\"><b>"._ATTACH_INTRO."</b></td>\n";
echo "<td class=\"row2\"><input type=\"file\" name=\"attachintro\" size=\"60\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"120px\" align=\"right\" class=\"row1\"><b>"._NEWS_ATTACH_FILE."</b></td>\n";
echo "<td class=\"row2\"><input type=\"file\" name=\"userattach\" size=\"60\"></td>\n";
echo "</tr>\n";
echo "<tr>";
echo "<td width=\"120px\" align=\"right\" class=\"row1\"><b>Link mở rộng</b></td>\n";
echo "<td class=\"row2\"><input type=\"text\" id=\"link_extend\" name=\"link_extend\" value=\"$link_extend\" size=\"100\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._BODYTEXT."</b></td>\n";
echo "<td class=\"row2\" colspan=\"2\">";
editor("bodytext", $bodytext,"",400);
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
