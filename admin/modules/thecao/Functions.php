<?php
if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

function fixweight_cat() {
	global $db, $prefix, $currentlang;
    $result = $db->sql_query("SELECT catid, weight FROM ".$prefix."_thecao_cat WHERE alanguage='$currentlang' order by weight ASC");
    $weight = 0;
    while($row = $db->sql_fetchrow($result)) {
	    $catid = $row['catid'];
		$weight++;
	    $catid = intval($catid);
		$db->sql_query("UPDATE ".$prefix."_thecao_cat SET weight='$weight' WHERE catid='$catid'");
    }
}

function fixcount_cat() {
	global $prefix, $db;
	 $result = $db->sql_query("SELECT catid, counts FROM ".$prefix."_thecao_cat");
	 $i =0;
	 while(list($catid, $counts) = $db->sql_fetchrow($result)) {
	 	$numsnew = $db->sql_numrows($db->sql_query("SELECT*FROM ".$prefix."_thecao WHERE catid='$catid'"));
	 	if($counts != $numsnew) {
	 		$db->sql_query("UPDATE ".$prefix."_thecao_cat SET counts='$numsnew' WHERE catid='$catid'");
	 	}
	 	$i ++;
	 }
}
function show_user($id)
{
	global $db, $prefix;
	$name ="";
	$result = $db->sql_query("SELECT fullname FROM ".$prefix."_user WHERE id=$id");
	if($db->sql_numrows($result) > 0 ) {
		list($fullname) = $db->sql_fetchrow($result); 
			$name .= $fullname;
	}
	else{$name="*";}
	return $name;
}


function catname($catid) {
	global $db, $prefix;
	$catname ="";
	$result = $db->sql_query("SELECT title FROM ".$prefix."_thecao_cat WHERE catid=$catid");
	if($db->sql_numrows($result) > 0 ) {
		list($title) = $db->sql_fetchrow($result); 
			$catname .= $title;
	}
	return $catname;
}
function show_menhgia($id)
{
	global $db, $prefix;
	$name ="";
	if($id==0){$name= "*";}
	else{
		$result = $db->sql_query("SELECT menhgia FROM ".$prefix."_thecao_menhgia WHERE id=$id");
		if($db->sql_numrows($result) > 0 ) {
			list($menhgia) = $db->sql_fetchrow($result); 
				$name .= $menhgia;
		}
	}
	return $name;
}
function user_group_name($id) {
	global $db, $prefix;
	$user_group_name ="";
	$result = $db->sql_query("SELECT title FROM ".$prefix."_usergroup WHERE id=$id");
	if($db->sql_numrows($result) > 0 ) {
		list($title) = $db->sql_fetchrow($result); 
			$user_group_name .= $title;
	}
	return $user_group_name;
}
function subcat($catid, $text="", $catcheck="", $catseld="") {
	global $db, $prefix;
	$treeTemp ="";
	$result = $db->sql_query("SELECT catid, title FROM ".$prefix."_thecao_cat WHERE parentid='$catid' AND catid!='$catseld'");
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
	$result = $db->sql_query("SELECT catid FROM ".$prefix."_thecao_cat WHERE parentid='0' ORDER BY weight ASC");
	while(list($catid) = $db->sql_fetchrow($result)) {
	 	fixsubcat_rec($catid);
	 }
}	

function fixsubcat_rec($catid) {
	global $db, $prefix;
	$result = $db->sql_query("SELECT catid FROM ".$prefix."_thecao_cat WHERE parentid='$catid'");
	$sub_id_ar ="";
	if($db->sql_numrows($result) > 0) {
		while(list($catid2) = $db->sql_fetchrow($result)) {
			$sub_id_ar[] = $catid2;
			fixsubcat_rec($catid2);	
		}	
		$sub_id = @implode("|",$sub_id_ar);
		$db->sql_query("UPDATE ".$prefix."_thecao_cat SET sub_id='$sub_id' WHERE catid='$catid'");
	}else{
		$db->sql_query("UPDATE ".$prefix."_thecao_cat SET sub_id='' WHERE catid='$catid'");
	}	
}

?>