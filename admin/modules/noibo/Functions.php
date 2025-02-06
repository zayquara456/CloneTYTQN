<?php
if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

function fixweight_cat() {
	global $db, $prefix, $currentlang;
    $result = $db->sql_query("SELECT catid, weight FROM ".$prefix."_noibo_cat WHERE alanguage='$currentlang' order by weight ASC");
    $weight = 0;
    while($row = $db->sql_fetchrow($result)) {
	    $catid = $row['catid'];
		$weight++;
	    $catid = intval($catid);
		$db->sql_query("UPDATE ".$prefix."_noibo_cat SET weight='$weight' WHERE catid='$catid'");
    }
}

function fixcount_cat() {
	global $prefix, $db;
	 $result = $db->sql_query("SELECT catid, counts FROM ".$prefix."_noibo_cat");
	 $i =0;
	 while (list($catid, $counts) = $db->sql_fetchrow($result)) {
	 	$numsnew = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_noibo WHERE catid=$catid"));
	 	if($counts != $numsnew) {
	 		$db->sql_query("UPDATE ".$prefix."_noibo_cat SET counts=$numsnew WHERE catid=$catid");
	 	}
	 	$i++;
	 }
}

function subcat($catid, $text="", $catcheck="", $catseld="") {
	global $db, $prefix;
	$treeTemp ="";
	$result = $db->sql_query("SELECT catid, title FROM ".$prefix."_noibo_cat WHERE parent='$catid' AND catid!='$catseld'");
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
function catname_byparent($catid) {
	global $db, $prefix;
	$catname ="";
	$result = $db->sql_query("SELECT title FROM ".$prefix."_noibo_cat WHERE catid IN (SELECT parent FROM ".$prefix."_noibo_cat WHERE catid='$catid')");
	if($db->sql_numrows($result) > 0 ) {
		list($title) = $db->sql_fetchrow($result); 
			$catname .= $title." > ";
	}
	return $catname;	
}
function fixweight_newstab() {
	global $db, $prefix, $currentlang;
    $result = $db->sql_query("SELECT tabid, weight FROM ".$prefix."_noibo_tab WHERE alanguage='$currentlang' order by weight ASC");
    $weight = 0;
    while($row = $db->sql_fetchrow($result)) {
	    $tabid = $row['tabid'];
		$weight++;
	    $tabid = intval($tabid);
		$db->sql_query("UPDATE ".$prefix."_noibo_tab SET weight='$weight' WHERE tabid='$tabid'");
    }
}
?>