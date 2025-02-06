<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

$id = intval(isset($_GET['id']) ? $_GET['id'] : $_POST['id']);

$query = "SELECT catid, title, time, hometext, bodytext, fattach, othershow, images, imgtext, active, alanguage, source, imgshow, nstart, special, image_highlight";
if ($_GET['type'] == 'normal') {
	$table = "{$prefix}_picture";
	$query .= ", time";
} else {
	$table = "{$prefix}_picture_temp";
	$query .= ", UNIX_TIMESTAMP(timed)";
}
$query .= " FROM $table WHERE id=$id";
$result = $db->sql_query($query);
if($db->sql_numrows($result) != 1) header("Location: modules.php?f=$adm_modname");
list($catid, $title, $time, $hometext, $bodytext, $fattach, $othershow, $images, $imgtext, $active, $alang, $source, $imgshow, $startid, $special, $time, $highlight) = $db->sql_fetchrow($result);

if ($_GET['type'] == 'timed') {
	list($sec, $min, $hour, $day, $mon, $yr) = localtime($time);
	$mon++;
	$yr += 1900;
}

$get_path = get_path($time);
$path_upload_img = "$path_upload/pictures";
$siteurl = "http://".$_SERVER['HTTP_HOST']."/Adoosite";
$path_upload_attach = "$path_upload/news/attachs";//path upload file attach
$err_title = $err_cat = "";
if (isset($_POST['submit'])) {
	$title = $escape_mysql_string(trim($_POST['title']));
	$catid = intval($_POST['catid']);
	$active = intval($_POST['active']);
	$startid = isset($_POST['startid']) ? intval($_POST['startid']):0;
	$special = isset($_POST['special']) ? intval($_POST['special']):0;
	$hometext = $escape_mysql_string(trim($_POST['hometext']));
	$bodytext = $escape_mysql_string(trim($_POST['bodytext']));
	$othershow = isset($_POST['othershow']) ? intval($_POST['othershow']) : 0;
	$imgtext = $escape_mysql_string(trim($_POST['imgtext']));
	$delimg = isset($_POST['delimg']) ? intval($_POST['delimg']):0;
	$delattach = isset($_POST['delattach']) ? intval($_POST['delattach']):0;
	$images = isset($_POST['images']) ? $escape_mysql_string(trim($_POST['images'])) : '';
	$source = isset($_POST['source']) ? $escape_mysql_string(trim($_POST['source'])) : '';
	$imgshow = intval($_POST['imgshow']);
	$highlight = isset($_POST['highlight']) ? intval($_POST['highlight']) : 0;
	$timed = isset($_POST['timed']) ? intval($_POST['timed']) : 0;
	$year = intval($_POST['year']);
	$month = intval($_POST['month']);
	$day = intval($_POST['day']);
	$hour = intval($_POST['hour']);
	$min = intval($_POST['min']);
	$sec = intval($_POST['sec']);

	$err = 0;
	if($title =="") {
		$err_title = "<font color=\"red\">"._ERROR1."</font><br>";
		$err = 1;
	}

	if($catid == 0) {
		$err_cat = "<font color=\"red\">"._ERROR1_1."</font><br>";
		$err = 1;
	}
	// delete file images
	if($delimg == 1) {
		@unlink(RPATH."$path_upload_img/$images");
		@unlink(RPATH."$path_upload_img/thumb_".$images."");
		$images = "";
	}
	// delete file attach
	if($delattach == 1) {
		@unlink(RPATH."$path_upload_attach/$fattach");
		$fattach = "";
	}
	if(!$err) {
		$postTime = mktime($hour, $min, $sec, $month, $day, $year);
		if ($timed) $newUploadPath = "$path_upload/pictures";
		else $newUploadPath = $path_upload_img;
		if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {
			$upload = new Upload("userfile", $newUploadPath, $maxsize_up);
			$images1 = $upload->send();
			resizeImg($images1, $newUploadPath, $sizenews);
			if(!empty($images1) && !empty($images)) {
				@unlink(RPATH."$path_upload_img/$images");
				@unlink(RPATH."$path_upload_img/thumb_".$images);
				$images = "";
			}
		}
		else 
		{
			if ((($timed) && (!empty($images))) || ((!$timed) && ($_GET['type'] == 'timed'))) {
				if (!is_dir(RPATH."$newUploadPath")) mkdir(RPATH."$newUploadPath");
				@copy(RPATH."$path_upload_img/$images", RPATH."$newUploadPath/$images");
				@copy(RPATH."$path_upload_img/thumb_$images", RPATH."$newUploadPath/$images");
				@unlink(RPATH."$path_upload_img/$images");
				@unlink(RPATH."$path_upload_img/thumb_".$images);
			}
			$images1 = $images;
		}
		//upload file attach
		if (is_uploaded_file($_FILES['userattach']['tmp_name']))
		{
			$upload_attach = new Upload("userattach", $path_upload_attach, $maxsize_up);
			$fattach1 = $upload_attach->send();
			if(!empty($fattach1) && !empty($fattach))
			{
				@unlink(RPATH."$path_upload_attach/$fattach");
				$fattach="";
			}
		}
		else
		{
			$fattach1=$fattach;
		}
		if (empty($images1) && empty($images)) { $imgtext = ""; $highlight = 0;}

		if ((($_GET['type'] == 'normal') && (!$timed)) || (($_GET['type'] == 'timed') && ($timed))) {
			$query = "UPDATE $table SET catid=$catid, title='$title', hometext='$hometext', bodytext='$bodytext', fattach='$fattach1', othershow='$othershow', images='$images1', imgtext='$imgtext', source='$source', imgshow=$imgshow, image_highlight=$highlight, nstart=$startid, special=$special";
			if ($timed) $query .= ", timed=FROM_UNIXTIME($postTime)";
			$query .= " WHERE id=$id";
			$db->sql_query($query);
		} elseif (($_GET['type'] == 'normal') && ($timed)) {
			$db->sql_query("DELETE FROM $table WHERE id=$id");
			$db->sql_query("INSERT INTO {$prefix}_picture_temp (catid, title, alanguage, hometext, bodytext, fattach, othershow, images, imgtext, active, source, imgshow, image_highlight, hits, nstart, special, timed) VALUES ($catid, '$title', '$currentlang', '$hometext', '$bodytext', '$fattach', '$othershow', '$images', '$imgtext', $active, '$source', $imgshow, $highlight, 0, $startid, $special, FROM_UNIXTIME($postTime))");
		} elseif (($_GET['type'] == 'timed') && (!$timed)) {
			$db->sql_query("DELETE FROM $table WHERE id=$id");
			$db->sql_query("INSERT INTO {$prefix}_picture (catid, title, alanguage, time, hometext, bodytext, fattach, othershow, images, imgtext, active, source, imgshow, image_highlight, hits, nstart, special) VALUES ($catid, '$title', '$currentlang', ".time().", '$hometext', '$bodytext', '$fattach', '$othershow', '$images', '$imgtext', $active, '$source', $imgshow, $highlight, 0, $startid, $special)");
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
				$db->sql_query("UPDATE {$prefix}_picture SET nstart=0 WHERE id!=$lastInsertId AND catid=$catid");
				$db->sql_query("UPDATE {$prefix}_picture_cat SET startid=$lastInsertId WHERE catid=$catid");
			}
		}
		updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _NEWS_EDIT_NEWS);
		header("Location: modules.php?f=".$adm_modname);
	}
}

$title = str_replace('"',"''",$title);

include_once("page_header.php");

echo "<form action=\"modules.php?f=$adm_modname&do=$do&id=$id&type={$_GET['type']}\" method=\"POST\" enctype=\"multipart/form-data\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" class=\"tableborder\">\n";
echo "<tr><td class=\"header\" colspan=\"2\">"._EDITNEWS."</td></tr>";
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._TITLE."</b></td>\n";
echo "<td class=\"row3\">$err_title<input type=\"text\" name=\"title\" value=\"$title\" size=\"70\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._INCAT."</b></td>\n";
$result_cat = $db->sql_query("SELECT catid, title FROM ".$prefix."_picture_cat WHERE parent='0' and alanguage='$currentlang' ORDER BY weight");
echo "		<td class=\"row3\">$err_cat<select name=\"catid\">";
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
if($active == 1) 
{
	echo "<td class=\"row3\"><input type=\"radio\" name=\"active\" value=\"1\" checked>"._YES." &nbsp;&nbsp;";
	echo "<input type=\"radio\" name=\"active\" value=\"0\">"._NO."</td>\n";
} 
else 
{
	echo "<td class=\"row3\"><input type=\"radio\" name=\"active\" value=\"1\">"._YES." &nbsp;&nbsp;";
	echo "<input type=\"radio\" name=\"active\" value=\"0\"  checked>"._NO."</td>\n";
}
echo "</tr>\n";
echo "<tr>\n";

if (empty($images)) {
	echo "<tr>\n";
	echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._IMAGE."</b></td>\n";
	echo "<td class=\"row3\"><input type=\"file\" name=\"userfile\" size=\"50\"></td>\n";
	echo "</tr>\n";
	$imgtext = "";
} else {
	echo "	<tr>\n";
	echo "		<td align=\"right\" class=\"row1\"><b>"._DELIMAGE."</b></td>\n";
	echo "		<td class=\"row3\"><input type=\"checkbox\" name=\"delimg\" value=\"1\">&nbsp;<a href=\"".RPATH."$path_upload_img/$images\" target=\"_blank\" info=\""._VIEWIMAGE."\"><img border=\"0\" src=\"../images/img.gif\" align=\"absmiddle\"></a></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" class=\"row1\"><b>"._CHANGE_IMAGE."</b></td>\n";
	echo "		<td class=\"row3\"><input type=\"file\" name=\"userfile\" size=\"40\"></td>\n";
	echo "	</tr>\n";
	echo "<input type=\"hidden\" name=\"images\" value=\"$images\">";
}
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._IMGTEXT."</b></td>\n";
echo "<td class=\"row3\"><input type=\"text\" name=\"imgtext\" value=\"$imgtext\" size=\"70\" maxlength=\"253\"></td>\n";
echo "</tr>\n";

echo "<tr><td class=\"row3\">&nbsp;</td><td class=\"row3\"><input type=\"submit\" name=\"submit\" value=\""._SAVECHANGES."\" class=\"button2\"></td></tr>";
echo "</table></form><br>";

include_once("page_footer.php");
?>