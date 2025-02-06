<?php
if (!defined('CMS_SYSTEM')) die();

function page_tilecat($catid, $parent, $catname) {
	global $db, $prefix, $module_name;
	$titlecat ="";
	$result = $db->sql_query("SELECT catid, parent, title FROM ".$prefix."_dichvu_cat WHERE catid='$parent'");
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
function check_module($parent) {
	global $db, $prefix, $module_name;
	$titlecat ="";
	$result = $db->sql_query("SELECT permalink, parent FROM ".$prefix."_dichvu_cat WHERE catid='$parent'");
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