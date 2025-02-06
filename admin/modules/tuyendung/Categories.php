<?php

if(!defined('CMS_ADMIN')) die("Illegal File Access");

include_once("page_header.php");

$active = 1;
$onhome = 1;
$homelinks = 3;
$title = $err_title = "";
if(isset($_POST['subup'])&& $_POST['subup'] == 1) {
	$err = 0;
	$title = $escape_mysql_string(trim($_POST['title']));
	$active = intval($_POST['active']);
	$onhome = intval($_POST['onhome']);
	$homelinks = intval($_POST['homelinks']);
	$parentid = intval($_POST['parent']);
	$parent = ($parentid >= 0) ? $parentid : 'NULL';
	$cat_type = "tuyendung_cat";

	if(empty($title)) {
		$err_title = "<font color=\"red\">"._ERROR1."</font><br/>";
		$err = 1;
	}
	else
	{
		$permalink=url_optimization(trim($title));
	}
	if(!$err) {
		list ($xcatid) = $db->sql_fetchrow($db->sql_query("SELECT max(catid) AS xid FROM ".$prefix."_tuyendung_cat"));
		if ($xcatid == "-1") { $catid = 1; } else { $catid = $xcatid + 1; }
		$weight = WeightMax("tuyendung_cat", $parentid, '', 'parent');
		$db->sql_query("INSERT INTO ".$prefix."_tuyendung_cat (catid, title, permalink, alanguage, active, weight, onhome, homelinks, parent, counts, startid, cat_type) VALUES (NULL, '$title', '$permalink', '$currentlang', '$active', '$weight', '$onhome', '$homelinks', $parent, 0, 0, '$cat_type')");
		
		fixweight_cat();
		updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _CREATE_NEWS_TOPIC);
		//update guid
		$guid="index.php?f=tuyendung&do=categories&id=$catid";
		$query = "UPDATE ".$prefix."_tuyendung_cat SET guid='$guid' WHERE catid='$catid'";
		$db->sql_query($query);
		header("Location: modules.php?f=".$adm_modname."&do=$do");
	}
} else {
	$err_title = "";
	$title = "";
}

echo "<script language=\"javascript\">\n";
echo "	function check(f) {\n";
echo "		if(f.title.value =='') {\n";
echo "			alert('"._ERROR1."');\n";
echo "			f.title.focus();\n";
echo "			return false;\n";
echo "		}\n";
echo "		f.submit.disabled = true;\n";
echo "		return true;	\n";
echo "	}	\n";
echo "</script>	\n";
ajaxload_content();

echo "<div id=\"{$adm_modname}_main\"><form action=\"modules.php?f=$adm_modname&do=$do\" method=\"POST\" onsubmit=\"return check(this);\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
echo "<tr><td colspan=\"2\" class=\"header\">"._CREATECAT."</td></tr>";
echo "<tr>\n";
echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>"._TITLE."</b></td>\n";
echo "<td class=\"row2\">$err_title<input type=\"text\" id=\"title\" name=\"title\" value=\"$title\" size=\"50\"></td>\n";
echo "</tr>\n";
//hien thi tat ca cac cap danh muc tin tuc

$resultcat = $db->sql_query("SELECT catid, title FROM {$prefix}_tuyendung_cat WHERE parent=0 AND alanguage='$currentlang' ORDER BY weight");
if($db->sql_numrows($resultcat) > 0) 
{
	echo "<tr><td align=\"right\" class=\"row1\"><b>"._IS_SUBCAT_OF."</b></td>\n";
	echo "<td class=\"row2\">";
	echo '<select id="parent" name="parent">'."\n";
	echo '<option value="0">'._ROOT_CAT."</option>\n";
	$listcat ="";
	while(list($cat_id, $titlecat) = $db->sql_fetchrow($resultcat)) 
	{
		if ($cat_id == $parent) 
		{
			$seld =" selected"; 
		}
		else
		{
			$seld ="";
		}
		$listcat .= "<option value=\"$cat_id\" $seld>--$titlecat</option>";
		$listcat .= subcat($cat_id,"-",$catid, "");
	}
	echo $listcat;
	echo "</select></td></tr>";
}


echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._ACTIVE."</b></td>\n";
if($active == 1) {
	echo "<td  class=\"row2\"><input type=\"radio\" name=\"active\" value=\"1\" checked>"._YES." &nbsp;&nbsp;"
	."<input type=\"radio\" name=\"active\" value=\"0\">"._NO."</td>\n";
} else {
	echo "<td  class=\"row2\"><input type=\"radio\" name=\"active\" value=\"1\">"._YES." &nbsp;&nbsp;"
	."<input type=\"radio\" name=\"active\" value=\"0\" checked>"._NO."</td>\n";
}
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._ONHOME."</b></td>\n";
if($onhome == 1) {
	echo "<td  class=\"row2\"><input type=\"radio\" name=\"onhome\" value=\"1\" checked>"._YES." &nbsp;&nbsp;"
	."<input type=\"radio\" name=\"onhome\" value=\"0\">"._NO."</td>\n";
} else {
	echo "<td  class=\"row2\"><input type=\"radio\" name=\"onhome\" value=\"1\">"._YES." &nbsp;&nbsp;"
	."<input type=\"radio\" name=\"onhome\" value=\"0\" checked>"._NO."</td>\n";
}
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>"._HOMELINKS."</b></td>\n";
echo "<td class=\"row2\"><select name=\"homelinks\">\n";
for($i = 0; $i <= 10; $i ++) {
	$seld ="";
	if($i == $homelinks) { $seld =" selected=\"selected\""; }
	echo "<option value=\"$i\"".$seld.">$i</option>\n";
}
echo "</select></td>\n";
echo "</tr>\n";
echo "<input type=\"hidden\" name=\"subup\" value=\"1\">";
echo "<tr><td colspan=\"2\" align=\"center\" class=\"row1\"><input type=\"submit\" id=\"submit\" name=\"submit\" value=\""._ADD."\"></td></tr>";
echo "</table></form>";

function listCat($catArr, $newArr, $tdClass, $pad = '') {
	global $adm_modname, $scolor1, $ajax_active;

	foreach ($newArr as $key => $val) {
		for ($i = 0; $i < count($catArr); $i++) {
			if (intval($catArr[$i]['id']) == $key) $tempArr = $catArr[$i];
		}
		
		switch (intval($tempArr['active'])) {
			case 1: $active = "<a href=\"?f=".$adm_modname."&do=status_cat&id={$tempArr['id']}&stat=0\" info=\""._DEACTIVATE."\"><img border=\"0\" src=\"images/view.png\"></a>"; break;
			case 0: $active = "<a href=\"?f=".$adm_modname."&do=status_cat&id={$tempArr['id']}&stat=1\" info=\""._ACTIVE."\"><img border=\"0\" src=\"images/viewo.png\"></a>"; break;
		}

		switch (intval($tempArr['onhome'])) {
			case 1: $onhome = "<a href=\"?f=".$adm_modname."&do=status_cat_home&id={$tempArr['id']}&stat=0\" info=\""._NOTHOME."\"><img border=\"0\" src=\"../images/home.gif\"></a>"; break;
			case 0: $onhome = "<a href=\"?f=".$adm_modname."&do=status_cat_home&id={$tempArr['id']}&stat=1\" info=\""._ONHOME."\"><img border=\"0\" src=\"../images/nhome.gif\"></a>"; break;
		}

		if(intval($tempArr['counts']) > 0) {
			$counts1 = "<a href=\"".RPATH."index.php?f=".$adm_modname."&do=categories&id={$tempArr['id']}\" target=\"_blank\" info=\""._VIEWCOUNTS."\">{$tempArr['counts']} <img border=\"0\" src=\"images/search.gif\" align=\"absmiddle\"></a>";
			if(intval($tempArr['startid']) == 0) {
				$startids = "<a href=\"".$adm_modname.".php?f=".$adm_modname."&do=cat_newstart&id={$tempArr['id']}\">"._CHOOSE."</a>";
			} else {
				$startids = "<a href=\"".$adm_modname.".php?f=".$adm_modname."&do=cat_newstart&id={$tempArr['id']}\">"._CHANGE."</a>";
			}
		} else {
			$counts1 = 0;
			$startids = "<a href=\"".$adm_modname.".php?f=".$adm_modname."&do=create&id={$tempArr['id']}\">"._NONEWS."</a>";
		}

		if ($ajax_active == 1) {
			$tdId = " id=\"{$adm_modname}_title_edit_{$tempArr['id']}\"";
			$title = "<a href=\"?f=$adm_modname&do=quick_title_cat&id={$tempArr['id']}\" info=\""._QUICK_EDIT."\" onclick=\"return show_edit_title({$tempArr['id']},'{$tempArr['title']}','$adm_modname',20,'"._SAVECHANGES."','quick_title_cat');\">{$tempArr['title']}</a> <a href=\"../".url_sid("index.php?f=".$adm_modname."&do=categories&id={$tempArr['id']}")."\" info=\""._GETLINK."\" onclick=\"prompt('"._GETLINK."','".url_sid("index.php?f=$adm_modname&do=categories&id={$tempArr['id']}")."'); return false;\"><img border=\"0\" src=\"images/link.png\"></a>";
			$icondel = "<a href=\"?f=$adm_modname&do=delete_cat&id={$tempArr['id']}\" onclick=\"return aj_base_delete('{$tempArr['id']}','$adm_modname','"._DELETEASK."','delete_cat','catid');\" info=\""._DELETE."\"><img border=\"0\" src=\"images/delete.png\"></a>";
		} else {
			$tdId = '';
			$title = $tempArr['title'];
			$icondel = "<a href=\"?f=".$adm_modname."&do=delete_cat&id={$tempArr['id']}\" info=\""._DELETE."\" onclick=\"return confirm('"._DELETEASK."');\"><img border=\"0\" src=\"images/delete.png\"></a>";
		}
		
		echo "	<tr>\n";
		echo "<td align=\"center\" class=\"{$tdClass}\"><input type=\"checkbox\" name=\"catid[]\" value=\"{$tempArr['id']}\"></td>\n";
		echo "		<td class=\"{$tdClass}\"$tdId>{$pad}";
		if (!empty($pad)) echo " ";
		if ($tdClass == "row1") {
			$bgcolor = "";
			echo "<b>$title</b>";
		} else {
			$bgcolor = "; background-color: $scolor1";
			echo $title;
		}
		echo "</a></td>\n";
		echo "<td align=\"center\" class=\"{$tdClass}\"><input type=\"text\" name=\"poz[{$tempArr['id']}]\" value=\"{$tempArr['weight']}\" maxlength=\"2\" style=\"text-align: center; width: 30px$bgcolor\"></td>\n";
		echo "<td align=\"center\" class=\"{$tdClass}\">$counts1</td>\n";
		echo "<td align=\"center\" class=\"{$tdClass}\">$startids</td>\n";
		echo "		<td align=\"center\" class=\"{$tdClass}\">{$tempArr['onhome']}</td>\n";
		echo "		<td align=\"center\" class=\"{$tdClass}\">{$tempArr['active']}</td>\n";
		echo "		<td align=\"center\" class=\"{$tdClass}\">{$tempArr['homelinks']}</td>\n";
		echo "<td align=\"center\" class=\"{$tdClass}\"><a href=\"?f=".$adm_modname."&do=edit_cat&id={$tempArr['id']}\" info=\""._EDIT."\"><img border=\"0\" src=\"images/edit.png\"></a></td>\n";
		echo "<td align=\"center\" class=\"{$tdClass}\">$icondel</td></tr>\n";
		
		if (is_array($val)) {
			listCat($catArr, $val, "row3", "$pad---");
		}
	}
}

$resultcat = $db->sql_query("SELECT catid, title, active, weight, counts, startid, onhome, homelinks, parent FROM {$prefix}_tuyendung_cat WHERE alanguage='$currentlang' ORDER BY weight, catid ASC ");
if($db->sql_numrows($resultcat) > 0) {
	echo "<script language=\"javascript\" type=\"text/javascript\">\n";
	echo "function check_uncheck(){\n";
	echo "	var f=fetch_object('frm');\n";
	echo "	if(f.checkall.checked){\n";
	echo "		CheckAllCheckbox(f,'catid[]');\n";
	echo "	}else{\n";
	echo "		UnCheckAllCheckbox(f,'catid[]');\n";
	echo "	}			\n";
	echo "}\n";
	echo "</script>\n";
	echo "<br/><form id=\"frm\" action=\"modules.php?f=$adm_modname&do=quick_do_cat\" method=\"POST\">";
	echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
	echo "<tr><td colspan=\"10\" class=\"header\">";
	echo _CURRENT_CATS;
	echo "</td></tr>";
	echo "	<tr>\n";
	echo "<td width=\"1%\" align=\"center\" class=\"row1sd\"><input type=\"checkbox\" name=\"checkall\" onclick=\"javascript:check_uncheck();\" title=\""._CHECKALL."\"></td>\n";
	echo "		<td class=\"row1sd\">"._TITLE."</td>\n";
	echo "<td align=\"center\" width=\"50\" class=\"row1sd\">"._WEIGHT."</td>\n";
	echo "<td align=\"center\" width=\"60\" class=\"row1sd\">"._COUNTS."</td>\n";
	echo "<td align=\"center\" width=\"60\" class=\"row1sd\">"._NEWSTART."</td>\n";
	echo "		<td align=\"center\" width=\"50\" class=\"row1sd\"><b>"._HOMEP."</b></td>\n";
	echo "		<td align=\"center\" width=\"50\" class=\"row1sd\"><b>"._SHOW."</b></td>\n";
	echo "		<td align=\"center\" width=\"50\" class=\"row1sd\"><b>"._HLINKS."</b></td>\n";
	echo "<td align=\"center\" width=\"30\" class=\"row1sd\">"._EDIT."</td>\n";
	echo "<td align=\"center\" width=\"30\" class=\"row1sd\">"._DELETE."</td>\n";
	echo "	</tr>\n";
	$catArr = array();
	$i = 0;
	while (list($catArr[$i]['id'], $catArr[$i]['title'], $catArr[$i]['active'], $catArr[$i]['weight'], $catArr[$i]['counts'], $catArr[$i]['startid'], $catArr[$i]['onhome'], $catArr[$i]['homelinks'], $catArr[$i]['parent']) = $db->sql_fetchrow($resultcat)) { $i++; }
	$newArr = array();
	Common::buildTree($catArr, $newArr);
	listCat($catArr, $newArr, "row1");
	echo "<tr><td colspan=\"8\"><select name=\"fc\">";
	echo "<option value=\"\">&raquo; "._QUICKDO."</option>";
	echo "<option value=\"2\">&raquo; "._QUICKDO_2."</option>";
	echo "<option value=\"3\">&raquo; "._QUICKDO_3."</option>";
	echo "<option value=\"4\">&raquo; "._QUICKDO_4."</option>";
	echo "</select> <input type=\"submit\" value=\""._DOACTION."\"></td></tr>";
	echo "</table></form>";
	echo "<br />";
	OpenDiv();
	echo "* "._NOTES."";
	CloseDiv();
	echo "</div>";
}

include_once("page_footer.php");
?>