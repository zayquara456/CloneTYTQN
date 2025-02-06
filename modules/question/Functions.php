<?php

function fixweight_cat() {
	global $db, $prefix, $currentlang;
    $result = $db->sql_query("SELECT catid, weight FROM ".$prefix."_question_cat WHERE alanguage='$currentlang' order by weight ASC");
    $weight = 0;
    while($row = $db->sql_fetchrow($result)) {
	    $catid = $row['catid'];
		$weight++;
	    $catid = intval($catid);
		$db->sql_query("UPDATE ".$prefix."_question_cat SET weight='$weight' WHERE catid='$catid'");
    }
}

function fixcount_cat() {
	global $prefix, $db;
	 $result = $db->sql_query("SELECT catid, counts FROM ".$prefix."_camnang_cat");
	 $i =0;
	 while (list($catid, $counts) = $db->sql_fetchrow($result)) {
	 	$numsnew = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_camnang WHERE catid=$catid"));
	 	if($counts != $numsnew) {
	 		$db->sql_query("UPDATE ".$prefix."_camnang_cat SET counts=$numsnew WHERE catid=$catid");
	 	}
	 	$i++;
	 }
}
function page_tilecat($catid, $parent, $catname) {
	global $db, $prefix, $module_name;
	$titlecat ="";
	$result = $db->sql_query("SELECT catid, parent, title FROM ".$prefix."_question_cat WHERE catid='$parent'");
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

function subcat($catid, $text="", $catcheck="", $catseld="") {
	global $db, $prefix;
	$treeTemp ="";
	$result = $db->sql_query("SELECT catid, title FROM ".$prefix."_question_cat WHERE parent='$catid' AND catid!='$catseld'");
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
?>