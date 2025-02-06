<?php

if(!defined('CMS_ADMIN')) {
	die();
}

function fixweight() {
    global $prefix, $db, $currentlang;
    $old_posit ="";
    $sql = "SELECT * FROM ".$prefix."_blocks WHERE blanguage='$currentlang' ORDER BY bposition, weight";
    $result = $db->sql_query($sql);
    $xweight = 1;
    while ($row = $db->sql_fetchrow($result)) {
	    $posit = $row['bposition'];
	    if($posit != $old_posit) $xweight = 1;
	    $old_posit = $posit;
	    $db->sql_query("UPDATE ".$prefix."_blocks SET weight = '$xweight'  WHERE bid = '$row[bid]'");
	    $xweight++;
    }
}



?>