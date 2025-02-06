<?php
if (!defined('CMS_SYSTEM')) die();

function page_tilecat($catid, $parent, $catname) {
	global $db, $prefix, $module_name;
	$titlecat ="";
	$result = $db->sql_query("SELECT catid, parent, title FROM ".$prefix."_document_cat WHERE catid='$parent'");
	if($db->sql_numrows($result) > 0) {
		list($catid2, $parent2, $title2) = $db->sql_fetchrow($result);
		$titlecat .= "<a href=\"".url_sid("index.php?f=".$module_name."&do=categories&id=$catid2")."\" >$title2</a>";
		if($parent2 != 0) {
			$titlecat = page_tilecat($catid2, $parent2, $titlecat);
		}
	}
	$titlecat .= " &rsaquo; <a href=\"".url_sid("index.php?f=".$module_name."&do=categories&id=$catid")."\">$catname</a>";

	return $titlecat;
}

function show_catdocument($catid)
{
	global $db, $prefix, $module_name, $currentlang;
		$result = $db->sql_query("SELECT catid, title FROM ".$prefix."_document_cat WHERE parent='0' and alanguage='$currentlang' ORDER BY weight");
		echo "<select name=\"catid\"  style=\"width:300px\">";
		echo "<option name=\"catid\" value=\"0\">Chọn chủ đề</option>";
		$listcat ="";
		while(list($cat_id, $titlecat) = $db->sql_fetchrow($result)) {
			if($cat_id == $catid) {$seld =" selected"; }else{ $seld ="";}
			$listcat .= "<option value=\"$cat_id\"$seld style=\"font-weight: bold\" disabled>- $titlecat</option>";
			$listcat .= subcat($cat_id,"-",$catid, "");
		}
		echo $listcat;
		echo "</select>\n";
	
}
function subcat($catid, $text="", $catcheck="", $catseld="") {
	global $db, $prefix;
	$treeTemp ="";
	$result = $db->sql_query("SELECT catid, title FROM ".$prefix."_document_cat WHERE parent='$catid' AND catid!='$catseld'");
	if($db->sql_numrows($result) > 0 ) {
		$text = "$text--";
		while(list($cat_id, $title2) = $db->sql_fetchrow($result)) {
			if($catcheck) {
				if($cat_id == $catcheck) {
					$seld = " selected";
				}else{
					$seld ="";
				}	
			}
			$treeTemp .= "<option value=\"$cat_id\"$seld>$text-- $title2</option>";
			$treeTemp .= subcat($cat_id,$text, $catcheck, $catseld);
		}	
	}
	return $treeTemp;	
}
function check_module($parent) {
	global $db, $prefix, $module_name;
	$titlecat ="";
	$result = $db->sql_query("SELECT permalink, parent FROM ".$prefix."_document_cat WHERE catid='$parent'");
	if($db->sql_numrows($result) > 0) {
		list($permalink2,$parent2) = $db->sql_fetchrow($result);
		$titlecat=$permalink2;
		if($parent2 != 0) {
			$titlecat = page_tilecat($parent2);
		}
	}
	return $titlecat;
}
?>