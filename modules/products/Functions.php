<?php
if (!defined('CMS_SYSTEM')) die();

function page_tilecat($catid, $parentid, $title) {
	global $db, $prefix, $module_name;
	$titlecat ="";
	$result = $db->sql_query("SELECT catid, parentid, title FROM ".$prefix."_products_cat WHERE catid='$parentid'");
	if($db->sql_numrows($result) > 0) {
		list($catid2, $parentid2, $title2) = $db->sql_fetchrow($result);
		$rwtitle= utf8_to_ascii(url_optimization($title2));
		$titlecat .= "<a href=\"".url_sid("index.php?f=".$module_name."&do=categories&id=$catid2&t=$rwtitle")."\" >$title2</a>";
		if($parentid2 != 0) {
			$titlecat = page_tilecat($catid2, $parentid2, $titlecat);
		}
	}
	$rwtitle= utf8_to_ascii(url_optimization($title));
	$titlecat .= " &raquo; <a href=\"".url_sid("index.php?f=".$module_name."&do=categories&id=$catid&t=$rwtitle")."\">$title</a>";

	return $titlecat;
}
?>