<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

foldcreate("download");

include("page_header.php");
$active = 1;
//$act=intval(isset($_GET['act']) ? $_GET['act'] : $_POST['act']);
$title =$permalink = $catid = $hometext = $bodytext = $imgtext = $source = $title_seo = $description_seo = $keyword_seo = $tag_seo = $startid = $special = $imgshow = $err_cat = $err_title = $images = $highlight= '';
if( isset($_POST['subup']) && $_POST['subup'] == 1) {
	$err = 0;
	$title = nospatags($_POST['title']);
	//$permalink =nospatags($_POST['permalink']);
	$catid = intval($_POST['catid']);
	$hometext = $escape_mysql_string(trim($_POST['hometext']));
	$bodytext = $escape_mysql_string(trim($_POST['bodytext']));
	$download_type = "download-type";
	$imgtext = nospatags($_POST['imgtext']);
	$source = nospatags($_POST['source']);
	$title_seo = nospatags($_POST['title_seo']);
	$othershow = isset($_POST['othershow']) ? intval($_POST['othershow']) : 0;
	$description_seo = nospatags($_POST['description_seo']);
	$keyword_seo = nospatags($_POST['keyword_seo']);
	$tag_seo = nospatags($_POST['tag_seo']);
	$startid = isset($_POST['startid']) ? intval($_POST['startid']) : 0;
	$special = isset($_POST['special']) ? intval($_POST['special']) : 0;
	$imgshow = intval($_POST['imgshow']);
	$highlight = isset($_POST['highlight']) ? intval($_POST['highlight']) : 0;


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
		//upload file attach
		if (is_uploaded_file($_FILES['userattach']['tmp_name']))
		{
			$path_upload_attach = "$path_upload/download/attachs";
			$upload_attach = new Upload("userattach", $path_upload_attach, $maxsize_up);
			$fattach = $upload_attach->send();
		}
		if (empty($images)) { $imgtext = ""; $highlight = 0; }

		list ($xid) = $db->sql_fetchrow($db->sql_query("SELECT max(id) AS xid FROM ".$prefix."_download"));
		if ($xid == "-1") { $id = 1; } else { $id = $xid + 1; }
		$insertIntoTable = "{$prefix}_download";
		$query = "INSERT INTO $insertIntoTable (id, catid, title, permalink, alanguage, hometext, bodytext, seo_title, seo_description, seo_keyword, seo_tag, fattach, download_type, images, imgtext, active, source, imgshow, othershow, image_highlight, hits, nstart, special";
		$query .= ', time';
		$query .= ") VALUES ($id, $catid, '$title', '$permalink', '$currentlang', '$hometext', '$bodytext', '$title_seo', '$description_seo', '$keyword_seo', '$tag_seo', '$fattach', '$download_type', '$images', '$imgtext', $active, '$source', $imgshow, '$othershow', $highlight, 0, $startid, $special";
		$query .= ", ".time();
		$query .= ')';
		$result = $db->sql_query($query);
		//update guid
		$guid="index.php?f=download&do=detail&id=$id";
		$query = "UPDATE $insertIntoTable SET guid='$guid' WHERE id='$id'";
		$db->sql_query($query);
		if (($db->sql_affectedrows() > 0) && (!$timed)) {
			fixcount_cat();
			if($startid == 1) {
				$db->sql_query("UPDATE {$prefix}_download SET nstart=0 WHERE id!=$id AND catid=$catid");
				$db->sql_query("UPDATE ".$prefix."_download_cat SET startid=$id WHERE catid=$catid");
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
		<div>"._ADDNEWS."</div>
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
$result_cat = $db->sql_query("SELECT catid, title FROM ".$prefix."_download_cat WHERE parent='0' and alanguage='$currentlang' ORDER BY weight");
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
