<?php
if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

function subcat($catid, $text="", $catcheck="", $catseld="") {
	global $db, $prefix;
	$treeTemp ="";
	$result = $db->sql_query("SELECT catid, title FROM ".$prefix."_news_cat WHERE parent='$catid' AND catid!='$catseld'");
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
			$treeTemp .= "<option value=\"$cat_id\" $seld>$text-- $title2</option>";
			$treeTemp .= subcat($cat_id,$text, $catcheck, $catseld);
		}	
	}
	return $treeTemp;	
}
function catname_byparent($catid) {
	global $db, $prefix;
	$catname ="";
	$result = $db->sql_query("SELECT title FROM ".$prefix."_news_cat WHERE catid IN (SELECT parent FROM ".$prefix."_news_cat WHERE catid='$catid')");
	if($db->sql_numrows($result) > 0 ) {
		list($title) = $db->sql_fetchrow($result); 
			$catname .= $title." > ";
	}
	return $catname;	
}
function fixweight_newstab() {
	global $db, $prefix, $currentlang;
    $result = $db->sql_query("SELECT tabid, weight FROM ".$prefix."_news_tab WHERE alanguage='$currentlang' order by weight ASC");
    $weight = 0;
    while($row = $db->sql_fetchrow($result)) {
	    $tabid = $row['tabid'];
		$weight++;
	    $tabid = intval($tabid);
		$db->sql_query("UPDATE ".$prefix."_news_tab SET weight='$weight' WHERE tabid='$tabid'");
    }
}
?>