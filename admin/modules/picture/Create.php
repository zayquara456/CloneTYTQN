<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

foldcreate("news");

include("page_header.php");

$active = 1;
$title = $catid = $hometext = $bodytext = $imgtext = $source = $startid = $special = $imgshow = $err_cat = $err_title = $images = '';
if( isset($_POST['subup']) && $_POST['subup'] == 1) {
	$err = 0;
	$title = nospatags($_POST['title']);
	$catid = intval($_POST['catid']);
	$active = intval($_POST['active']);
	$hometext = $escape_mysql_string(trim($_POST['hometext']));
	$bodytext = $escape_mysql_string(trim($_POST['bodytext']));
	$othershow = isset($_POST['othershow']) ? intval($_POST['othershow']) : 0;
	$imgtext = nospatags($_POST['imgtext']);
	$source = nospatags($_POST['source']);
	$startid = isset($_POST['startid']) ? intval($_POST['startid']) : 0;
	$special = isset($_POST['special']) ? intval($_POST['special']) : 0;
	$imgshow = intval($_POST['imgshow']);
	$highlight = isset($_POST['highlight']) ? intval($_POST['highlight']) : 0;
	$timed = isset($_POST['timed']) ? intval($_POST['timed']) : 0;
	$year = intval($_POST['year']);
	$month = intval($_POST['month']);
	$day = intval($_POST['day']);
	$hour = intval($_POST['hour']);
	$minute = intval($_POST['min']);
	$second = intval($_POST['sec']);

	if (empty($title)) {
		$err_title = "<font color=\"red\">"._ERROR4."</font><br>";
		$err = 1;
	}

	if ($catid == 0) {
		$err_cat = "<font color=\"red\">"._ERROR1_1."</font><br>";
		$err = 1;
	}

	if (!$err) {
		$postTime = mktime($hour, $minute, $second, $month, $day, $year);
		if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {
			if ($timed) $get_path = get_path($postTime);
			else $get_path = get_path(time());
			$path_upload_img = "$path_upload/pictures";    //"$path_upload/news/$get_path";
			$upload = new Upload("userfile", $path_upload_img, $maxsize_up);
			$images = $upload->send();
			resizeImg($images, $path_upload_img, $sizenews);
		}
		//upload file attach
		if (is_uploaded_file($_FILES['userattach']['tmp_name']))
		{
			$path_upload_attach = "$path_upload/news/attachs";
			$upload_attach = new Upload("userattach", $path_upload_attach, $maxsize_up);
			$fattach = $upload_attach->send();
		}
		//upload image in body text
		if (empty($images)) { $imgtext = ""; $highlight = 0; }
		list ($xid) = $db->sql_fetchrow($db->sql_query("SELECT max(id) AS xid FROM ".$prefix."_picture"));
		if ($xid == "-1") { $id = 1; } else { $id = $xid + 1; }
		if ($timed) $insertIntoTable = "{$prefix}_picture_temp";
		else $insertIntoTable = "{$prefix}_picture";
		
		$query = "INSERT INTO $insertIntoTable (id, catid, title, alanguage, hometext, bodytext, fattach, othershow, images, imgtext, active, source, imgshow, image_highlight, hits, nstart, special";
		if ($timed) $query .= ', timed';
		else $query .= ', time';
		$query .= ") VALUES ($id, $catid, '$title', '$currentlang', '$hometext', '$bodytext', '$fattach', '$othershow', '$images', '$imgtext', $active, '$source', $imgshow, $highlight, 0, $startid, $special";
		if ($timed) $query .= ", FROM_UNIXTIME($postTime)";
		else $query .= ", ".time();
		$query .= ')';
		$result = $db->sql_query($query);

		if (($db->sql_affectedrows() > 0) && (!$timed)) {
			fixcount_cat();
			if($startid == 1) {
				$db->sql_query("UPDATE {$prefix}_picture SET nstart=0 WHERE id!=$id AND catid=$catid");
				$db->sql_query("UPDATE ".$prefix."_picture_cat SET startid=$id WHERE catid=$catid");
			}
		}
		updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _NEWS_CREATE_NEWS);
		header("Location: modules.php?f=".$adm_modname."");
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

echo "<form action=\"modules.php?f=$adm_modname&do=$do\" method=\"POST\" enctype=\"multipart/form-data\" onsubmit=\"return check(this);\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
echo "<tr><td colspan=\"2\" class=\"header\">"._ADDNEWS."</td></tr>";
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._TITLE."</b></td>\n";
echo "<td class=\"row2\">$err_title<input type=\"text\" name=\"title\" value=\"$title\" size=\"70\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._INCAT."</b></td>\n";
$result_cat = $db->sql_query("SELECT catid, title FROM ".$prefix."_picture_cat WHERE parent='0' and alanguage='$currentlang' ORDER BY weight");
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
/*
echo "<td align=\"right\" class=\"row1\"><b>"._SPECIAL."</b></td>\n";
if($active == 0) {
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
if($startid == 1) { $check = ' checked="checked"'; } else { $check = ""; }
echo "<td class=\"row2\"><input type=\"checkbox\" name=\"startid\" value=\"1\"$check></td>\n";
echo "</tr>\n";
echo "<tr>\n";

echo "<td align=\"right\" class=\"row1\"><b>"._HOMETEXT."</b></td>\n";
echo "<td class=\"row2\">";

editorbasic("hometext",$hometext,"",200);
echo "</td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._BODYTEXT."</b></td>\n";
echo "<td class=\"row2\">";
editor("bodytext", $bodytext,"",400);
echo "</td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._NEWS_ATTACH_FILE."</b></td>\n";
echo "<td class=\"row2\"><input type=\"file\" name=\"userattach\" size=\"50\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
*/
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._IMAGE."</b></td>\n";
echo "<td class=\"row2\"><input type=\"file\" name=\"userfile\" size=\"50\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._IMGTEXT."</b></td>\n";
echo "<td class=\"row2\"><input type=\"text\" name=\"imgtext\" value=\"$imgtext\" size=\"70\" maxlength=\"253\"></td>\n";
echo "</tr>\n";
/*
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._SHOWIMGDETAIL."</b></td>\n";
echo "<td class=\"row2\"><input type=\"radio\" name=\"imgshow\" value=\"1\">"._YES." &nbsp;&nbsp;";
echo "<input type=\"radio\" name=\"imgshow\" value=\"0\" checked=\"checked\">"._NO."</td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._NEWS_IMAGE_HIGHLIGHT."</b></td>\n";
echo "<td class=\"row2\"><input type=\"checkbox\" name=\"highlight\" value=\"1\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._NEWS_OTHER_SHOW."</b><br><i>"._NEWS_OTHER_SHOW_DETAIL."</i></td>\n";
echo "<td class=\"row2\"><input type=\"checkbox\" name=\"othershow\" value=\"1\"></td>\n";
echo "</tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._SOURCE."</b></td>\n";
echo "<td class=\"row2\"><input type=\"text\" name=\"source\" value=\"$source\" size=\"50\" maxlength=\"253\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._NEWS_TIMED."</b></td>\n";
echo "<td class=\"row2\"><input type=\"checkbox\" name=\"timed\" value=\"1\"><br />";
echo '<select id="day" name="day">'."\n";
for ($i = 1; $i <= 31; $i++) echo '<option value="'.$i."\">$i</option>\n";
echo "</select>\n".'<select id="month" name="month">'."\n";
for ($i = 1; $i <= 12; $i++) echo '<option value="'.$i.'">'._NEWS_MONTH." $i</option>\n";
echo "</select>\n".'<select id="year" name="year">'."\n";
$thisYear = localtime(time(), true);
for ($i = intval($thisYear['tm_year']) + 1900; $i <= (1900 + intval($thisYear['tm_year']) + 100); $i++) echo '<option value="'.$i."\">$i</option>\n";
echo "</select>\n".'&nbsp;&nbsp;'._NEWS_HOUR.'&nbsp;<select id="hour" name="hour">'."\n";
for ($i = 0; $i < 24; $i++) echo '<option value="'.$i."\">$i</option>\n";
echo "</select>\n"._NEWS_MINUTE.'&nbsp;<select id="min" name="min">'."\n";
for ($i = 0; $i < 60; $i++) echo '<option value="'.$i."\">$i</option>\n";
echo "</select>\n"._NEWS_SECOND.'&nbsp;<select id="sec" name="sec">'."\n";
for ($i = 0; $i < 60; $i++) echo '<option value="'.$i."\">$i</option>\n";
echo "</select>\n";
echo "</td>\n";
echo "</tr>\n";
*/
echo "<input type=\"hidden\" name=\"subup\" value=\"1\">";
echo "<tr><td></td><td><input type=\"submit\" name=\"submit\" value=\""._ADD."\" class=\"button2\"></td></tr>";
echo "</table></form>";

include_once("page_footer.php");
?>