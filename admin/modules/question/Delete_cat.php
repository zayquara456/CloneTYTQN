<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

$catid = intval($_GET['catid']);
$load_hf = 1;

$result = $db->sql_query("SELECT catid, parent FROM {$prefix}_question_cat WHERE alanguage='$currentlang' ORDER BY weight, catid ASC");
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



$query = "DELETE FROM {$prefix}_question WHERE catid=$catid";
$db->sql_query($query);
$query = "DELETE FROM {$prefix}_question_cat WHERE catid=$catid";
$db->sql_query($query);

truncate_table("question_cat");
fixweight_cat();
updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _DELETE_CAMNANG_CAT);
if ($ajax_active == 0) {
	header("Location: modules.php?f=".$adm_modname."&do=categories");
} else {
	//include_once("modules/$adm_modname/Categories.php");
	header("Location: modules.php?f=".$adm_modname."&do=categories");
}
?>