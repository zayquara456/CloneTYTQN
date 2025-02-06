<?php
if (!defined('CMS_SYSTEM')) die();

include_once("header.php");

$col_prodct = 2;
$row_prodct = 4;
$newspagenum = $row_prodct * $col_prodct;

$perpage = intval($newspagenum);
$page = intval(isset($_GET['page']) ? $_GET['page'] : (isset($_POST['page']) ? $_POST['page']:1));
$offset = ($page-1) * $perpage;
$countf = $db->sql_fetchrow($db->sql_query("SELECT COUNT(*) FROM {$prefix}_products"));
$total = ($countf[0]) ? $countf[0] : 1;
$pageurl = "index.php?f=$module_name";
// tieu de
$page_title .= ""._PRODUCTS."";
OpenTab(""._PRODUCTS."");
$result_prds_index = $db->sql_query("SELECT id, title,text, description, images, price, priceold, pnews FROM ".$prefix."_products WHERE active='1' AND alanguage='$currentlang' ORDER BY time DESC LIMIT $offset,$perpage");
if($db->sql_numrows($result_prds_index) > 0) {
	$count = 0;
	while(list($id, $title,$text, $description, $images, $price, $priceold, $pnews) = $db->sql_fetchrow($result_prds_index)) {
		//rewrite
		$rwtitle = utf8_to_ascii(url_optimization($title));
		$url_products_detail =url_sid("index.php?f=products&do=detail&id=$id&t=$rwtitle");
		if($images !="" && file_exists("$path_upload/$module_name/$images")) {
			$images = tj_thumbnail("$path_upload/$module_name/$images",$title,$prd_thumbwidth,$prd_thumbheight);
		}
		$price = bsVndDot($price);
		$priceold = bsVndDot($priceold);
		$pageing = paging($total,$pageurl,$perpage,$page);
		$count++;
		//if ($tt%$col_prodct == 0) { echo "<div style=\"clear:both\"></div>"; }
		temp_products_index($count, $id, $title,$text, $description, $images, $price, $priceold, $pnews,$url_products_detail,$pageing);
	}
}
CloseTab();
include_once("footer.php");
?>
