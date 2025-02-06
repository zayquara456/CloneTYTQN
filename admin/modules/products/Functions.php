<?php
if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

function fixweight_cat() {
	global $db, $prefix, $currentlang;
    $result = $db->sql_query("SELECT catid, weight FROM ".$prefix."_products_cat WHERE alanguage='$currentlang' order by weight ASC");
    $weight = 0;
    while($row = $db->sql_fetchrow($result)) {
	    $catid = $row['catid'];
		$weight++;
	    $catid = intval($catid);
		$db->sql_query("UPDATE ".$prefix."_products_cat SET weight='$weight' WHERE catid='$catid'");
    }
}

function fixcount_cat() {
	global $prefix, $db;
	 $result = $db->sql_query("SELECT catid, counts FROM ".$prefix."_products_cat");
	 $i =0;
	 while(list($catid, $counts) = $db->sql_fetchrow($result)) {
	 	$numsnew = $db->sql_numrows($db->sql_query("SELECT*FROM ".$prefix."_products WHERE catid='$catid'"));
	 	if($counts != $numsnew) {
	 		$db->sql_query("UPDATE ".$prefix."_products_cat SET counts='$numsnew' WHERE catid='$catid'");
	 	}
	 	$i ++;
	 }
}

function subcat($catid, $text="", $catcheck="", $catseld="") {
	global $db, $prefix;
	$treeTemp ="";
	$result = $db->sql_query("SELECT catid, title FROM ".$prefix."_products_cat WHERE parentid='$catid' AND catid!='$catseld'");
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

function fixsubcat() {
	global $db, $prefix;
	$result = $db->sql_query("SELECT catid FROM ".$prefix."_products_cat WHERE parentid='0' ORDER BY weight ASC");
	while(list($catid) = $db->sql_fetchrow($result)) {
	 	fixsubcat_rec($catid);
	 }
}	

function fixsubcat_rec($catid) {
	global $db, $prefix;
	$result = $db->sql_query("SELECT catid FROM ".$prefix."_products_cat WHERE parentid='$catid'");
	$sub_id_ar ="";
	if($db->sql_numrows($result) > 0) {
		while(list($catid2) = $db->sql_fetchrow($result)) {
			$sub_id_ar[] = $catid2;
			fixsubcat_rec($catid2);	
		}	
		$sub_id = @implode("|",$sub_id_ar);
		$db->sql_query("UPDATE ".$prefix."_products_cat SET sub_id='$sub_id' WHERE catid='$catid'");
	}else{
		$db->sql_query("UPDATE ".$prefix."_products_cat SET sub_id='' WHERE catid='$catid'");
	}	
}

?>