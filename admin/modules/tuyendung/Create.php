<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

foldcreate("tuyendung");

include("page_header.php");

$active = 1;
$title =$permalink = $catid = $hometext = $bodytext = $imgtext = $source = $title_seo = $description_seo = $keyword_seo = $tag_seo = $startid = $special = $imgshow = $err_cat = $err_title = $images = $highlight = $deadline = $location = $fattach = '';
if( isset($_POST['subup']) && $_POST['subup'] == 1) {
	$err = 0;
	$title = nospatags($_POST['title']);
	//$permalink =nospatags($_POST['permalink']);
	$catid = intval($_POST['catid']);
	$active = intval($_POST['active']);
	$location = $escape_mysql_string(trim($_POST['location']));
	$deadline = $escape_mysql_string(trim($_POST['deadline']));
	//$hometext = $escape_mysql_string(trim($_POST['hometext']));
	$bodytext = $escape_mysql_string(trim($_POST['bodytext']));
	$news_type = "news-type";
	$imgtext = nospatags($_POST['imgtext']);
	$source = nospatags($_POST['source']);
	$title_seo = nospatags($_POST['title_seo']);
	$othershow = isset($_POST['othershow']) ? intval($_POST['othershow']) : 0;
	$description_seo = nospatags($_POST['description_seo']);
	$keyword_seo = nospatags($_POST['keyword_seo']);
	$tag_seo = nospatags($_POST['tag_seo']);
	$startid = isset($_POST['startid']) ? intval($_POST['startid']) : 0;
	$special = isset($_POST['special']) ? intval($_POST['special']) : 0;
	//$imgshow = intval($_POST['imgshow']);
	$highlight = isset($_POST['highlight']) ? intval($_POST['highlight']) : 0;
	$timed = isset($_POST['timed']) ? intval($_POST['timed']) : 0;
	$year = intval($_POST['year']);
	$month = intval($_POST['month']);
	$day = intval($_POST['day']);
	$hour = intval($_POST['hour']);
	$minute = intval($_POST['min']);
	$second = intval($_POST['sec']);

	if (empty($title)) {
		$err_title = "<font color=\"red\">"._ERROR4."</font><br/>";
		$err = 1;
	}
	else
	{
		$permalink=url_optimization(trim($title));
	}
	if (empty($permalink)) {
		$err_title = "<font color=\"red\">"._ERROR4."</font><br/>";
		$err = 1;
	}


	if (!$err) {
		$postTime = mktime($hour, $minute, $second, $month, $day, $year);
		if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {
			$newnamefile=str_replace("-","_",$permalink);
			if ($timed) $get_path = get_path($postTime);
			else $get_path = get_path(time());
			$path_upload_img = "$path_upload/tuyendung/$get_path";
			$upload = new Upload("userfile", $path_upload_img, $maxsize_up,$newnamefile);
			$images = $upload->send();
			//resizeImg($images, $path_upload_img, $sizenews);
		}
		//upload file attach
		if (is_uploaded_file($_FILES['userattach']['tmp_name']))
		{
			$path_upload_attach = "$path_upload/tuyendung/attachs";
			$upload_attach = new Upload("userattach", $path_upload_attach, $maxsize_up);
			$fattach = $upload_attach->send();
		}
		if (empty($images)) { $imgtext = ""; $highlight = 0; }

		list ($xid) = $db->sql_fetchrow($db->sql_query("SELECT max(id) AS xid FROM ".$prefix."_tuyendung"));
		if ($xid == "-1") { $id = 1; } else { $id = $xid + 1; }
		if ($timed) $insertIntoTable = "{$prefix}_tuyendung_temp";
		else $insertIntoTable = "{$prefix}_tuyendung";
		$query = "INSERT INTO $insertIntoTable (id, catid, title, permalink, alanguage, bodytext, seo_title, seo_description, seo_keyword, seo_tag, fattach, news_type, images, imgtext, active, source, othershow, image_highlight, hits, nstart, special, location, deadline";
		if ($timed) $query .= ', timed';
		else $query .= ', time';
		$query .= ") VALUES ($id, $catid, '$title', '$permalink', '$currentlang', '$bodytext', '$title_seo', '$description_seo', '$keyword_seo', '$tag_seo', '$fattach', '$news_type', '$images', '$imgtext', $active, '$source', '$othershow', $highlight, 0, $startid, $special, '$location', '$deadline'";
		if ($timed) $query .= ", FROM_UNIXTIME($postTime)";
		else $query .= ", ".time();
		$query .= ')';
		$result = $db->sql_query($query);
		//update guid
		$guid="index.php?f=tuyendung&do=detail&id=$id";
		$query = "UPDATE $insertIntoTable SET guid='$guid' WHERE id='$id'";
		$db->sql_query($query);
		if (($db->sql_affectedrows() > 0) && (!$timed)) {
			fixcount_cat();
			if($startid == 1) {
				$db->sql_query("UPDATE {$prefix}_tuyendung SET nstart=0 WHERE id!=$id AND catid=$catid");
				$db->sql_query("UPDATE ".$prefix."_tuyendung_cat SET startid=$id WHERE catid=$catid");
			}
		}
		updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _NEWS_CREATE_NEWS);
		header("Location: modules.php?f=".$adm_modname."");
	}
}
	echo '<script type="text/javascript">
function show_abc()
{
var x=document.getElementById("title").value;
document.getElementById("permalink").value='.url_optimization('x').';
}</script>
';
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

echo "<form action=\"modules.php?f=$adm_modname&do=$do\" method=\"POST\" enctype=\"multipart/form-data\" onsubmit=\"return check(this);\">";
echo "
<div id=\"pagecontent\">
	<div class=\"ctrl-header\">
		<div><span id=\"ctl10_lblTitle\">"._ADDNEWS."</span></div>
	</div>
	<div class=\"ctrl-content\">
		<div class=\"ctrl-content-list\">";
echo "<table cellpadding=\"5\" cellspacing=\"0\" border=\"0\" width=\"100%\">\n";
echo "<tr>";
echo "<td width=\"120px\" align=\"right\" class=\"row1\"><b>"._TITLE."</b></td>\n";
echo "<td class=\"row2\">$err_title<input type=\"text\" id=\"title\" name=\"title\" value=\"$title\" size=\"100\"></td>\n";
echo "</tr>\n";
/*echo "<tr>";
echo "<td width=\"120px\" align=\"right\" class=\"row1\"><b>"._PERMALINK."</b></td>\n";
echo "<td class=\"row2\"><span id=\"show_permalink\"><input type=\"text\"  id=\"permalink\"  name=\"permalink\" value=\"$permalink\" size=\"100\"></span></td>\n";
echo "</tr>\n";*/
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._INCAT."</b></td>\n";
$result_cat = $db->sql_query("SELECT catid, title FROM ".$prefix."_tuyendung_cat WHERE parent='0' and alanguage='$currentlang' ORDER BY weight");
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
		echo "<td align=\"right\" class=\"row1\"><b>"._SHOW."</b></td>\n";
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
		/*
		echo "<tr>\n";
		echo "<td align=\"right\" class=\"row1\"><b>"._NEWSTART."</b></td>\n";
		//if($startid == 1) { $check = ' checked="checked"'; } else { $check = ""; }
		//echo "<td class=\"row2\"><input type=\"checkbox\" name=\"startid\" value=\"1\"$check></td>\n";
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
		echo "<td align=\"right\" class=\"row1\"><b>"._SHOWIMGDETAIL."</b></td>\n";
		//echo "<td class=\"row2\"><input type=\"radio\" name=\"imgshow\" value=\"1\">"._YES." &nbsp;&nbsp;";
		//echo "<input type=\"radio\" name=\"imgshow\" value=\"0\" checked=\"checked\">"._NO."</td>\n";
		if($imgshow == 1) {
			echo "<td class=\"row2\"><input type=\"radio\" name=\"imgshow\" value=\"1\" checked>"._YES." &nbsp;&nbsp;";
			echo "<input type=\"radio\" name=\"imgshow\" value=\"0\">"._NO."</td>\n";
			echo "</tr>\n";
		} else {
			echo "<td class=\"row2\"><input type=\"radio\" name=\"imgshow\" value=\"1\">"._YES." &nbsp;&nbsp;";
			echo "<input type=\"radio\" name=\"imgshow\" value=\"0\" checked>"._NO."</td>\n";
			echo "</tr>\n";
		}
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<tr>\n";
		echo "<td width=\"120px\" align=\"right\" class=\"row1\"><b>"._NEWS_IMAGE_HIGHLIGHT."</b></td>\n";
		//echo "<td class=\"row2\"><input type=\"checkbox\" name=\"highlight\" value=\"1\"></td>\n";
		if($highlight == 1) {
			echo "<td class=\"row2\"><input type=\"radio\" name=\"highlight\" value=\"1\" checked>"._YES." &nbsp;&nbsp;";
			echo "<input type=\"radio\" name=\"highlight\" value=\"0\">"._NO."</td>\n";
			echo "</tr>\n";
		} else {
			echo "<td class=\"row2\"><input type=\"radio\" name=\"highlight\" value=\"1\">"._YES." &nbsp;&nbsp;";
			echo "<input type=\"radio\" name=\"highlight\" value=\"0\" checked>"._NO."</td>\n";
			echo "</tr>\n";
		}
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td width=\"120px\" align=\"right\" class=\"row1\"><b>"._NEWS_OTHER_SHOW."</b></td>\n";
		echo "<td class=\"row2\"><input type=\"checkbox\" name=\"othershow\" value=\"1\"> <i>"._NEWS_OTHER_SHOW_DETAIL."</i></td>\n";
		echo "</tr>\n";		
		echo "<tr>\n";
		echo "<td align=\"right\" class=\"row1\"><b>"._HOMETEXT."</b></td>\n";
		echo "<td class=\"row2\" colspan=\"2\">";		
		editorbasic("hometext",$hometext,"",200);
		echo "</td>\n";
		echo "</tr>\n";
		*/
		echo "<td align=\"right\" class=\"row1\"><b>"._LOCATION."</b></td>\n";
		echo "<td class=\"row2\" colspan=\"2\">";		
		echo "<textarea name=\"location\" cols=\"60\" rows=\"5\">$location</textarea> "._NOTE_LOCATION."";
		echo "</td>\n";
		echo "</tr>\n";
		echo "<td align=\"right\" class=\"row1\"><b>"._DEADLINE."</b></td>\n";
		echo "<td class=\"row2\" colspan=\"2\">";		
		echo "<input type=\"text\" name=\"deadline\" value=\"$deadline\" size=\"60\" maxlength=\"253\">";
		echo "</td>\n";
		echo "</tr>\n";
		
		echo "<tr>\n";
		echo "<td align=\"right\" class=\"row1\"><b>"._BODYTEXT."</b></td>\n";
		echo "<td class=\"row2\" colspan=\"2\">";
		editor("bodytext", $bodytext,"",400);
		echo "</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td width=\"120px\" align=\"right\" class=\"row1\"><b>"._NEWS_ATTACH_FILE."</b></td>\n";
		echo "<td class=\"row2\"><input type=\"file\" name=\"userattach\" size=\"60\"></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td width=\"120px\" align=\"right\" class=\"row1\"><b>"._IMAGE."</b></td>\n";
		echo "<td class=\"row2\"><input type=\"file\" name=\"userfile\" size=\"60\"></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td width=\"120px\" align=\"right\" class=\"row1\"><b>"._IMGTEXT."</b></td>\n";
		echo "<td class=\"row2\"><input type=\"text\" name=\"imgtext\" value=\"$imgtext\" size=\"60\" maxlength=\"253\"></td>\n";
echo "</tr>\n";
				echo "<tr>\n";
		echo "<td width=\"120px\" align=\"right\" class=\"row1\"><b>"._SOURCE."</b></td>\n";
		echo "<td class=\"row2\"><input type=\"text\" name=\"source\" value=\"$source\" size=\"60\" maxlength=\"253\"></td>\n";
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
		echo "<tr>\n";
		echo "<td width=\"120px\" align=\"right\" class=\"row1\"><b>"._NEWS_TIMED."</b></td>\n";
		echo "<td class=\"row2\"><input type=\"checkbox\" name=\"timed\" value=\"1\">&nbsp;";
		echo '<select id="day" name="day">'."\n";
		for ($i = 1; $i <= 31; $i++) echo '<option value="'.$i."\">$i</option>\n";
		echo "</select>\n".'<select id="month" name="month">'."\n";
		for ($i = 1; $i <= 12; $i++) echo '<option value="'.$i.'">'._NEWS_MONTH." $i</option>\n";
		echo "</select>\n".'<select id="year" name="year">'."\n";
		$thisYear = localtime(time(), true);
		for ($i = intval($thisYear['tm_year']) + 1900; $i <= (1900 + intval($thisYear['tm_year']) + 100); $i++) echo '<option value="'.$i."\">$i</option>\n";
		echo "</select>\n".'&nbsp;&nbsp;&nbsp;'._NEWS_HOUR.'&nbsp;<select id="hour" name="hour">'."\n";
		for ($i = 0; $i < 24; $i++) echo '<option value="'.$i."\">$i</option>\n";
		echo "</select>\n"._NEWS_MINUTE.'&nbsp;<select id="min" name="min">'."\n";
		for ($i = 0; $i < 60; $i++) echo '<option value="'.$i."\">$i</option>\n";
		echo "</select>\n"._NEWS_SECOND.'&nbsp;<select id="sec" name="sec">'."\n";
		for ($i = 0; $i < 60; $i++) echo '<option value="'.$i."\">$i</option>\n";
		echo "</select>\n";
		echo "</td>\n";
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
