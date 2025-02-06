<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

foldcreate("noibo");

include("page_header.php");
$active = 1;
//$act=intval(isset($_GET['act']) ? $_GET['act'] : $_POST['act']);
$title =$permalink = $catid = $hometext = $bodytext = $imgtext = $source = $title_seo = $description_seo = $keyword_seo = $tag_seo = $startid = $special = $imgshow = $err_cat = $err_title = $images = $highlight= '';
if( isset($_POST['subup']) && $_POST['subup'] == 1) {
	$err = 0;
	$title = nospatags($_POST['title']);
	//$permalink =nospatags($_POST['permalink']);
	$catid = intval($_POST['catid']);
	$act = intval($_POST['act']);
	//if($act==1)
	//	$active = 1;
	//else
	//	$active = 0;
	$hometext = $escape_mysql_string(trim($_POST['hometext']));
	$bodytext = "";
	$news_type = "noibo-type";
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
		//upload file attach
		if (is_uploaded_file($_FILES['userattach']['tmp_name']))
		{
			$path_upload_attach = "$path_upload/$adm_modname";
			$upload_attach = new Upload("userattach", $path_upload_attach, $maxsize_up);
			$fattach = $upload_attach->send();
		}

		list ($xid) = $db->sql_fetchrow($db->sql_query("SELECT max(id) AS xid FROM ".$prefix."_noibo"));
		if ($xid == "-1") { $id = 1; } else { $id = $xid + 1; }
		$insertIntoTable = "{$prefix}_noibo";
		$query = "INSERT INTO $insertIntoTable (id, catid, title, permalink, hometext, fattach, active, hits, nstart, special";
		$query .= ', time';
		$query .= ") VALUES ($id, $catid, '$title', '$permalink', '$hometext', '$fattach', $active,  0, $startid, $special";
		$query .= ", ".time();
		$query .= ')';
		$result = $db->sql_query($query);
		//update guid
		$guid="index.php?f=$adm_modname&do=detail&id=$id";
		$query = "UPDATE $insertIntoTable SET guid='$guid' WHERE id='$id'";
		$db->sql_query($query);
		if (($db->sql_affectedrows() > 0) && (!$timed)) {
			fixcount_cat();
			if($startid == 1) {
				$db->sql_query("UPDATE {$prefix}_noibo SET nstart=0 WHERE id!=$id AND catid=$catid");
				$db->sql_query("UPDATE ".$prefix."_noibo_cat SET startid=$id WHERE catid=$catid");
			}
		}
		updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _NEWS_CREATE_NEWS);
		if($act==1)
			header("Location: modules.php?f=".$adm_modname."&do=news_active");
		else
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
echo "<table class=\"tableborder\" cellpadding=\"5\" cellspacing=\"0\" border=\"0\" width=\"100%\">\n";
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
		echo "<tr>\n";
		echo "<td align=\"right\" class=\"row1\"><b>"._HOMETEXT."</b></td>\n";
		echo "<td class=\"row2\" colspan=\"2\">";
		echo "<textarea cols=\"80\" rows=\"5\" name=\"hometext\">$hometext</textarea>";
		//editorbasic("hometext",$hometext,"",200);
		echo "</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td width=\"120px\" align=\"right\" class=\"row1\"><b>"._NEWS_ATTACH_FILE."</b></td>\n";
		echo "<td class=\"row2\"><input type=\"file\" name=\"userattach\" size=\"60\"></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
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
