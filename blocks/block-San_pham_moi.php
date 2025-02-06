<?php
if (!defined('CMS_SYSTEM')) exit;

if(file_exists(DATAFOLD."/config_products.php")) {
	require(DATAFOLD."/config_products.php");
}

global $db, $prefix, $currentlang, $module_name, $path_upload, $id;

if(isset($id) && !empty($id) && ($module_name == "products")) $sql_seld_prdbl = " AND id!='$id'";
else $sql_seld_prdbl = "";

$content = "";
$result_prd_blnews = $db->sql_query("SELECT id, title, images FROM {$prefix}_products WHERE active=1 AND pnews=1 AND alanguage='$currentlang' AND images!='' $sql_seld_prdbl ORDER BY time DESC LIMIT $prd_nums_news_bl");
if ($db->sql_numrows($result_prd_blnews) > 0) {
	while (list($idprdbln, $titleprdbln, $imgprdbln) = $db->sql_fetchrow($result_prd_blnews)) {
		if (!empty($imgprdbln) && file_exists(RPATH."$path_upload/products/$imgprdbln")) {
			if (file_exists("$path_upload/products/thumb_$imgprdbln")) $imgprdbln = "thumb_$imgprdbln";
			$content .= "<div style=\"margin-bottom: 2px\"><a href=\"".url_sid("index.php?f=products&do=detail&id=$idprdbln")."\" title=\"$titleprdbln\"><img border=\"0\" src=\"$path_upload/products/$imgprdbln\" width=\"140\"></a></div>";
		}
	}
}

?>