<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

function AccCheck($nick,$acc="") {
	global $db, $prefix;
	if ((!$nick) || ($nick=="") || (preg_match("![^a-zA-Z0-9_-]!",$nick))) { $stop = ""._ERROR1.""; }
	elseif (strlen($nick) > 10) { $stop = ""._ERROR1.""; }
	elseif (strlen($nick) < 3) { $stop = ""._ERROR1.""; }
	elseif (strrpos($nick,' ') > 0) { $stop = ""._ERROR1.""; }
	elseif ($db->sql_numrows($db->sql_query("SELECT adacc FROM ".$prefix."_admin WHERE adacc='$nick' AND adacc!='$acc'")) > 0) { $stop = ""._ERROR1_1.""; }
	else { $stop = ""; }
    return($stop);
}
function show_groupauthor($id)
{
	global $db, $prefix;

	$result = $db->sql_query("SELECT id,title FROM ".$prefix."_admingroup WHERE id='$id'");
	if($db->sql_numrows($result) > 0 ) 
	{
		list($id, $title) = $db->sql_fetchrow($result);
		return $title;
	}		
}	
function subcat($mid, $text="", $mcheck="", $mseld="", $css) {
	
	global $db, $prefix;
	$treeTemp ="";
	$result = $db->sql_query("SELECT mid, title FROM ".$prefix."_adminmenus WHERE parentid='$mid' AND mid!='$mseld'");
	if($db->sql_numrows($result) > 0 ) {
		$text = "$text--";
		$i=0;
		while(list($mid2, $title) = $db->sql_fetchrow($result)) {
			//if($catcheck) {
			//if($mid2) {
				if(@in_array($mid2,$mcheck)) {
					$seld = " checked ";
				}else{
					$seld ="";

				}	
			//}
			$treeTemp .= "<td><input type=\"checkbox\" name=\"auth_menus[]\" value=\"$mid2\"$seld $css> ".$title."</td>";
			if($i==3){$treeTemp .= "</td></tr><tr><td></td>";}
			$treeTemp .= subcat($mid2,$text, $mcheck, $mseld, $css);
			$i++;
		}	
	}
	return $treeTemp;	
}


?>