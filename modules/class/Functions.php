<?php
if (!defined('CMS_SYSTEM')) die();

function page_tilecat($catid, $parent, $title) {
	global $db, $prefix, $module_name;
	$title ="";
	$titleh = $db->sql_query("SELECT id, parentid, title FROM ".$prefix."_class WHERE catid='$parentid'");
	if($db->sql_numrows($result) > 0) {
		list($id2, $parentid2, $title2) = $db->sql_fetchrow($result);
		$titleh .= "<a href=\"".url_sid("index.php?f=".$module_name."&do=categories&id=$id2")."\" >$title2</a>";
		if($parentid2 != 0) {
			$titleh = page_tilecat($id2, $parentid2, $title);
		}
	}
	$titleh .= " &rsaquo; <a href=\"".url_sid("index.php?f=".$module_name."&do=categories&id=$id")."\">$title</a>";

	return $titleh;
}

function check_module($parentid) {
	global $db, $prefix, $module_name;
	$titlecat ="";
	$result = $db->sql_query("SELECT permalink, parentid FROM ".$prefix."_class WHERE id='$parentid'");
	if($db->sql_numrows($result) > 0) {
		list($permalink2,$parentid2) = $db->sql_fetchrow($result);
		$titlecat=$permalink2;
		if($parentid2 != 0) {
			$titlecat = page_tilecat($parent2);
		}
	}
	return $titlecat;
}
?>