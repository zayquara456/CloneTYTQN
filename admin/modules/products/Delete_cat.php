<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

$catid = intval($_GET['catid']);

$result = $db->sql_query("SELECT sub_id FROM ".$prefix."_products_cat WHERE catid='$catid'");
if(empty($catid) || $db->sql_numrows($result) != 1) {
	header("Location: modules.php?f=".$adm_modname."");
	die();
}

list($sub_id) = $db->sql_fetchrow($result);	

$resultimg = $db->sql_query("SELECT images FROM ".$prefix."_products WHERE catid='$catid'");
while(list($images) = $db->sql_fetchrow($resultimg)) {
	$path_upload_img = "$path_upload/$adm_modname";
	@unlink("../$path_upload_img/$images");
	@unlink("../$path_upload_img/thumb_".$images."");
}	

$db->sql_query("DELETE FROM ".$prefix."_products WHERE catid='$catid'");
$db->sql_query("DELETE FROM ".$prefix."_products_cat WHERE catid='$catid'");
if($sub_id !="") {
	del_sub_cat($sub_id);
}
truncate_table("products_cat");
truncate_table("products");
fixweight_cat();
fixsubcat();
updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _DELETE_CAT);
header("Location: modules.php?f=".$adm_modname."&do=categories");

function del_sub_cat($sub_id) {
	global $db, $prefix, $path_upload;
	$sub_id_ar = @explode("|",$sub_id);
	for($i =0; $i < sizeof($sub_id_ar); $i ++) {
		$resultimg = $db->sql_query("SELECT images FROM ".$prefix."_products WHERE catid='$sub_id_ar[$i]'");
while(list($time, $images) = $db->sql_fetchrow($resultimg)) {
	$path_upload_img = $path_upload."/".$adm_modname;
	@unlink("../$path_upload_img/$images");
	@unlink("../$path_upload_img/thumb_".$images."");
}	
		$db->sql_query("DELETE FROM ".$prefix."_products_cat WHERE catid='$sub_id_ar[$i]'");
		$db->sql_query("DELETE FROM ".$prefix."_products WHERE catid='$sub_id_ar[$i]'");
		$result_sub_cat = $db->sql_query("SELECT sub_id FROM ".$prefix."_products_cat WHERE catid='$sub_id_ar[$i]' AND sub_id!=''");
		if($db->sql_numrows($result_sub_cat) > 0) {
			while(list($sub_id2) = $db->sql_fetchrow($result_sub_cat)) {
				del_sub_cat($sub_id2);
			}
		}	
	}	
}

?>