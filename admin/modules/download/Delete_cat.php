<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

$catid = intval($_GET['catid']);
$load_hf = 1;

$result = $db->sql_query("SELECT catid, parent FROM {$prefix}_download_cat WHERE alanguage='$currentlang' ORDER BY weight, catid ASC");
if ($db->sql_numrows($result) > 0) {
	$i = 0;
	$tempArr = array();
	while ($rows = $db->sql_fetchrow($result)) {
		list($tempArr[$i]['id'], $tempArr[$i]['parent']) = $rows;
		$i++;
	}
}
$newArr = array();
Common::buildTree($tempArr, $newArr);
$searchArray = Common::recursiveArrayKeyExists($catid, $newArr);
if ($searchArray === false) header("Location: modules.php?f=$adm_modname&do=categories");
$kList = '';
if (is_array($searchArray[$catid])) Common::findAllKeys($searchArray[$catid], $kList);
else $kList = strval($catid);
if (substr($kList, -1) == ':') $kList = substr($kList, 0, strlen($kList) - 1);
$kList = explode(':', $kList);

$condition = "catid=$catid OR ";
for ($i = 0; $i < count($kList); $i++) $condition .= "catid={$kList[$i]} OR ";
$condition = substr($condition, 0, strlen($condition) - 4);

$query = "SELECT time, images FROM ".$prefix."_news WHERE $condition";
$resultimg = $db->sql_query($query);
while(list($time, $images) = $db->sql_fetchrow($resultimg)) {
	//$get_path = get_path($time);
	$path_upload_img = "$path_upload/download";
	@unlink("../$path_upload_img/$images");
}

$query = "DELETE FROM {$prefix}_download WHERE $condition";
$db->sql_query($query);
$query = "DELETE FROM {$prefix}_download_cat WHERE $condition";
$db->sql_query($query);

truncate_table("download_cat");
fixweight_cat();
updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _DELETE_NEWS_CAT);
if ($ajax_active == 0) {
	header("Location: modules.php?f=".$adm_modname."&do=categories");
} else {
	include_once("modules/$adm_modname/Categories.php");
}
?>