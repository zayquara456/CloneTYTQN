<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

$id = intval(isset($_GET['id']) ? $_GET['id'] : $_POST['id']);

//$id = $_GET['id'];
$query = "SELECT catid, title, time, hometext, bodytext, seo_title, seo_description, seo_keyword, seo_tag, fattach, news_type, images, imgtext, active, alanguage, source, imgshow,othershow, nstart, special, image_highlight";
$table = "{$prefix}_noibo";
$query .= ", time";
$query .= " FROM $table WHERE id=$id";
$result = $db->sql_query($query);
if($db->sql_numrows($result) != 1) header("Location: modules.php?f=$adm_modname");
list($catid, $title, $time, $hometext, $bodytext, $title_seo, $description_seo, $keyword_seo, $tag_seo, $fattach, $news_type, $images, $imgtext, $active, $alang, $source, $imgshow, $othershow, $startid, $special, $time, $highlight) = $db->sql_fetchrow($result);

if ($_GET['type'] == 'timed') {
	list($sec, $min, $hour, $day, $mon, $yr) = localtime($time);
	$mon++;
	$yr += 1900;
}

$get_path = get_path($time);
$path_upload_img = "$path_upload/$adm_modname/$get_path";
$path_upload_attach = "$path_upload/$adm_modname";//path upload file attach
$err_title = $err_cat = "";
if (isset($_POST['submit'])) {
	$title = $escape_mysql_string(trim($_POST['title']));
	$catid = intval($_POST['catid']);
	//$active = intval($_POST['active']);
	$startid = isset($_POST['startid']) ? intval($_POST['startid']):0;
	$special = isset($_POST['special']) ? intval($_POST['special']):0;
	$hometext = $escape_mysql_string(trim($_POST['hometext']));
	$bodytext = $escape_mysql_string(trim($_POST['bodytext']));
	$news_type = "noibo-type";
	$guid="index.php?f=$adm_modname&do=detail&id=$id";
	$imgtext = $escape_mysql_string(trim($_POST['imgtext']));
	$delimg = isset($_POST['delimg']) ? intval($_POST['delimg']):0;
	$delattach = isset($_POST['delattach']) ? intval($_POST['delattach']):0;
	$othershow = isset($_POST['othershow']) ? intval($_POST['othershow']) : 0;
	$images = isset($_POST['images']) ? $escape_mysql_string(trim($_POST['images'])) : '';
	$source = isset($_POST['source']) ? $escape_mysql_string(trim($_POST['source'])) : '';
	$title_seo = isset($_POST['title_seo']) ? $escape_mysql_string(trim($_POST['title_seo'])) : '';
	$description_seo = isset($_POST['description_seo']) ? $escape_mysql_string(trim($_POST['description_seo'])) : '';
	$keyword_seo = isset($_POST['keyword_seo']) ? $escape_mysql_string(trim($_POST['keyword_seo'])) : '';
	$tag_seo = isset($_POST['tag_seo']) ? $escape_mysql_string(trim($_POST['tag_seo'])) : '';
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
		@unlink(RPATH."$path_upload_img/$images");
		//@unlink(RPATH."$path_upload_img/thumb_".$images."");
		$images = "";
	}
	// delete file attach
	if($delattach == 1) {
		@unlink(RPATH."$path_upload_attach/$fattach");
		$fattach = "";
	}
	if(!$err) {
		
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

			$query = "UPDATE $table SET catid=$catid, title='$title', permalink='$permalink',  guid='$guid', hometext='$hometext', fattach='$fattach1', nstart=$startid, special=$special";
			$query .= " WHERE id=$id";
			$db->sql_query($query);
		if (($db->sql_affectedrows() > 0) && (!$timed)) {
			fixcount_cat();
			if($startid == 1) {
				if ($_GET['type'] == 'timed') {
					$db->sql_query("SELECT LAST_INSERT_ID()");
					list($lastInsertId) = $db->sql_fetchrow();					
				} else {
					$lastInsertId = $id;
				}
				$db->sql_query("UPDATE {$prefix}_noibo SET nstart=0 WHERE id!=$lastInsertId AND catid=$catid");
				$db->sql_query("UPDATE {$prefix}_noibo_cat SET startid=$lastInsertId WHERE catid=$catid");
			}
		}
		updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _NEWS_EDIT_NEWS);
		header("Location: modules.php?f=".$adm_modname);
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
echo "<td width=\"120px\" align=\"right\" class=\"row1\"><b>"._TITLE."</b></td>\n";
echo "<td class=\"row2\">$err_title<input type=\"text\" name=\"title\" value=\"$title\" size=\"100\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._INCAT."</b></td>\n";
$result_cat = $db->sql_query("SELECT catid, title FROM ".$prefix."_noibo_cat WHERE parent='0' and alanguage='$currentlang' ORDER BY weight");
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
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._HOMETEXT."</b></td>\n";
echo "<td class=\"row2\" colspan=\"2\">";
echo "<textarea cols=\"80\" rows=\"5\" name=\"hometext\">$hometext</textarea>";
//editorbasic("hometext",$hometext,"",200);
echo "</td>\n";
echo "</tr>\n";

if($folder_site)  $url = !empty($fattach) ? "$path_upload_attach/$fattach" : '';
else $url = !empty($fattach) ? "$fattach" : '';
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._NEWS_ATTACH_URL."</b></td>\n";
$disabled="";
if (empty($fattach)) 
{
	$disabled="disabled=\"$disabled\"";
}
echo "<td class=\"row2\"><input type=\"checkbox\" name=\"delattach\" value=\"1\" $disabled>&nbsp;"._DELETE_FILE_ATTACH."<br/><input type=\"text\" value=\"$url\" size=\"100\" readonly=\"readonly\" /></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._NEWS_ATTACH_FILE."</b></td>\n";
echo "<td class=\"row2\"><input type=\"file\" name=\"userattach\" size=\"60\"></td>\n";
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