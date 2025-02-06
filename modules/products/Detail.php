<?php
if (!defined('CMS_SYSTEM')) die();

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$result = $db->sql_query("SELECT p.title, p.text, p.description, p.images, p.price, p.priceold, c.catid, c.title, c.parentid FROM ".$prefix."_products AS p,".$prefix."_products_cat AS c WHERE p.id='$id' AND p.catid=c.catid AND p.alanguage='$currentlang'");
if(empty($id) || $db->sql_numrows($result) != 1) {
	header("Location: ".url_sid("index.php?f=$module_name")."");
	exit();
}

list($title, $text, $desc, $images, $price, $priceold, $catid, $catname, $parentid) = $db->sql_fetchrow($result);

$header_page_keyword = $desc;

$db->sql_query("UPDATE ".$prefix."_products SET hits=hits+1 WHERE id='$id'");

if($parentid != 0) {
	$title_cat = page_tilecat($catid, $parentid, $catname);
	$page_title .= "".$title." - ".$title_cat."";
} else {
	$title_cat = $catname;
	$page_title .= "".$title." - ".$title_cat."";
}

include_once("header.php");
OpenTab("<a href=\"".url_sid("index.php")."\">"._HOMEPAGE."</a> &raquo; ".$title_cat);
if($images !="" && file_exists($path_upload."/".$module_name."/".$images)) {
	//$prd_realsize = getimagesize($path_upload."/".$module_name."/".$images);
	//if($prd_realsize[0] > $prd_sizedetail) {
	//	$prd_pic = "<a href=\"javascript:void(0)\" onClick=\"openNewWindow('".url_sid("viewpic.php?image=$path_upload/$module_name/$images")."',$prd_realsize[1],$prd_realsize[0])\" title=\""._VIEWIMG."\"><img border=\"0\" src=\"".$path_upload."/".$module_name."/".$images."\" width=\"".$prd_sizedetail."\"></a>";
	//} else {
		//$images = "<img border=\"0\" src=\"".$path_upload."/".$module_name."/".$images."\" width=\"".$prd_sizedetail."\">";
		$images = tj_thumbnail("$path_upload/$module_name/$images",$title,$prd_thumbwidth,$prd_thumbheight);
	//}
}
$price = bsVndDot($price);
$priceold = bsVndDot($priceold);
$addToCartLink = "";
//$addToCartLink = "<a href=\"index.php?f=$module_name&do=cart&act=add&id=$id\"><img src=\"templates/{$Default_Temp}/images/add.png\" style=\"vertical-align: middle\" /></a>";
$addToCartLink .= "&nbsp;<a href=\"index.php?f=$module_name&do=cart&act=add&id=$id\" class=\"strong\">"._PRODUCT_ADD_TO_CART."</a>";

temp_products_detail($title, $text, $desc, $images, $price, $priceold, $addToCartLink);
CloseTab();



$result_prd_other = $db->sql_query("SELECT id, title, text, images, description, price, priceold, pnews FROM ".$prefix."_products WHERE active='1' AND id<'".$id."' ORDER BY time DESC LIMIT $prd_nums_other");
if($db->sql_numrows($result_prd_other) > 0) {
	echo OpenTab(_OTHER_PRD, true);
	echo "<div>";
	$countot =0;
	while(list($idot, $titleot, $textot, $imagesot, $descot, $priceot, $priceoldot, $pnewsot) = $db->sql_fetchrow($result_prd_other)) 
	{
		if($imagesot !="" && file_exists("$path_upload/$module_name/$imagesot")) 
		{
			$imagesot = tj_thumbnail("$path_upload/$module_name/$imagesot",$titleot,$prd_thumbwidth,$prd_thumbheight);
			//rewrite
			$rwtitleot = utf8_to_ascii(url_optimization($titleot));
			$url_products_detailot =url_sid("index.php?f=products&do=detail&id=$id&t=$rwtitleot");
			$priceot = bsVndDot($priceot);
			$priceoldot = bsVndDot($priceoldot);
		}
		$countot ++;
		//if($i == 3) { $i =0; echo "</tr>"; }
		temp_products_other($countot, $idot, $titleot, $textot, $descot, $imagesot, $priceot, $priceoldot, $pnewsot,$url_products_detailot);
	}
	echo "</div><div class=\"cl\"></div>";
	echo CloseTab(true);
} else {
	echo "";
}
include_once("footer.php");
?>