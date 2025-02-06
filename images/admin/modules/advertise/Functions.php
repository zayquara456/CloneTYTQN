<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

function fixcountbn() {
    global $prefix, $db, $currentlang;
    $sql = "SELECT bnid FROM ".$prefix."_advertise_banners WHERE alanguage='$currentlang'";
    $result = $db->sql_query($sql);
    while(list($bnid) = $db->sql_fetchrow($result)) {
    	$nums = $db->sql_numrows($db->sql_query("SELECT*FROM ".$prefix."_advertise WHERE bnid='$bnid'"));
    	$db->sql_query("UPDATE ".$prefix."_advertise_banners SET counts ='$nums' WHERE bnid='$bnid'");
    }
}

function fixweight() {
	global $prefix, $db;
	$sql = "SELECT * FROM ".$prefix."_advertise ORDER BY bnid,weight";
    $result = $db->sql_query($sql);
    $xweight = 1;
    while ($row = $db->sql_fetchrow($result)) {
    $bnid = $row['bnid'];
    if($bnid != $old_bnid) $xweight = 1;
    $old_bnid = $bnid;
    $db->sql_query("UPDATE ".$prefix."_advertise SET weight = '$xweight'  WHERE id='$row[id]'");
    $xweight++;
    }	
}

function fixweight_scroll() {
	global $prefix, $db;
	$sql = "SELECT * FROM ".$prefix."_advertise_scroll ORDER BY poz,weight";
    $result = $db->sql_query($sql);
    $xweight = 1;
    while ($row = $db->sql_fetchrow($result)) {
    $poz = $row['poz'];
    if($poz != $old_poz) $xweight = 1;
    $old_poz = $poz;
    $db->sql_query("UPDATE ".$prefix."_advertise_scroll SET weight = '$xweight'  WHERE id='$row[id]'");
    $xweight++;
    }	
}

?>