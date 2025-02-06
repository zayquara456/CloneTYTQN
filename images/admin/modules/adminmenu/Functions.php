<?php
if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}
function subcat($mid, $text="", $mcheck="", $mseld="") {
	global $db, $prefix;
	$treeTemp ="";
	$result = $db->sql_query("SELECT mid, title FROM ".$prefix."_adminmenus WHERE parentid='$mid' AND mid!='$mseld'");
	if($db->sql_numrows($result) > 0 ) {
		$text = "$text--";
		while(list($mid2, $title) = $db->sql_fetchrow($result)) {
			//if($catcheck) {
			if($mid2) {
				if($mid2 == $mcheck) {
					$seld = " selected";
				}else{
					$seld ="";
				}	
			}
			$treeTemp .= "<option value=\"$mid2\"$seld>$text-- $title</option>";
			$treeTemp .= subcat($mid2,$text, $mcheck, $mseld);
		}	
	}
	return $treeTemp;	
}
function fixweight_mn() {
	global $db, $prefix, $currentlang;
    $result = $db->sql_query("SELECT mid, weight FROM ".$prefix."_adminmenus WHERE alanguage='$currentlang' order by weight ASC");
    $weight = 0;
    while($row = $db->sql_fetchrow($result)) {
	    $mid = $row['mid'];
		$weight++;
	    $mid = intval($mid);
		$db->sql_query("UPDATE ".$prefix."_adminmenus SET weight='$weight' WHERE mid='$mid'");
    }
}

?>