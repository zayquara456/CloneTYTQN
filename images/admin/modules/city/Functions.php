<?php
if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}
function fixweight_city() 
{
    global $prefix, $db, $currentlang;
    $sql = "SELECT id FROM ".$prefix."_city WHERE alanguage='$currentlang' ORDER BY weight ASC";
    $result = $db->sql_query($sql);
    $xweight = 0;
    while ($row = $db->sql_fetchrow($result)) {
    $xweight++;
    $id = $row['id'];
    $db->sql_query("UPDATE ".$prefix."_city SET weight='$xweight'  WHERE id='$id'");
    }
}
?>