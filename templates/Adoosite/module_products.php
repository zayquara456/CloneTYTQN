<?php
if (!defined('CMS_SYSTEM')) die();
//////////////////////////////////////////////////////////////////////////////////
// Dinh dang chung cho giao dien trang chi tiet san pham
//////////////////////////////////////////////////////////////////////////////////
function temp_products_detail($title, $text, $description, $images,$price, $priceold, $addToCartLink) 
{
echo "
	<div class=\"product-detail-title\"><h3 class=\"posttitle\">$title</h3></div>
	<div class=\"product-detail-image fl\">$images</div>
	<div class=\"product-detail-desc fl\">$text
		<br/><span style=\"text-decoration:line-through\">"._PRODUCT_PRICE_OLD.": $priceold "._VND."</span>
		<br/><span style=\"color:#ff0000\"><b>"._PRICE.": $price "._VND."</b></span>
		</div>
	<div class=\"cl\">
	<div align=\"right\">
		<ul class=\"tbutton\">
			<li>$addToCartLink</li>
			<!--<li><a href=\"\">In sản phẩm này</a></li>
			<li><a href=\"\">Gửi cho bạn bè</a></li>-->
		</ul>
	</div>
	</div>
	<div class=\"cl\"></div>
	<div><ul id=\"countrytabs\" class=\"shadetabs\">
			<li><a href=\"#\" class=\"selected\" rel=\"#default\">"._DESCRIPTION."</a></li>
		</ul></div>
	<div id=\"tabcontent1\">$description</div>
	<div></div>
	
	<div style=\"clear:both\"></div>";
	
}
function temp_products_index($count, $id, $title,$text, $description, $images, $price, $priceold, $pnews,$url_products_detail,$pageing)
{
	echo "<div class=\"product fl\">\n";
	echo "<div class=\"product-tile\"><a href=\"$url_products_detail\">$title</a></div>";
	echo "<div class=\"product-image fl\"><a href=\"$url_products_detail\">$images</a></div>";
	echo "<div class=\"product-desc\">$text
		<br/><span style=\"text-decoration:line-through\">"._PRODUCT_PRICE_OLD.": $priceold "._VND."</span>
		<br/><span style=\"color:#ff0000\"><b>"._PRICE.": $price "._VND."</b></span>
		
			</div>
		<div>
			<span class=\"product-button\"><a href=\"$url_products_detail\">"._DETAIL."</a></span>
			<span class=\"product-button\"><a href=\"index.php?f=products&do=cart&act=add&id=$id\">"._PRODUCT_ADD."</a></span>
		</div>		
		";
			
	echo "</div>\n";
	//echo $pageing;
	if ($count%2 == 0) { echo "<div class=\"cl\" style=\"border-top:1px solid #dfeaf4;\"></div>"; }
}
function temp_products_other($countot, $idot, $titleot, $textot, $descot, $imagesot, $priceot, $priceoldot, $pnewsot,$url_products_detailot)
{
	echo "<div class=\"product fl\">\n";
	echo "<div class=\"product-tile\"><a href=\"$url_products_detailot\">$titleot</a></div>";
	echo "<div class=\"product-image fl\"><a href=\"$url_products_detailot\">$imagesot</a></div>";
	echo "<div class=\"product-desc\">$textot
		<br/><span style=\"text-decoration:line-through\">"._PRODUCT_PRICE_OLD.": $priceoldot "._VND."</span>
		<br/><span style=\"color:#ff0000\"><b>"._PRICE.": $priceot "._VND."</b></span>
		
			</div>
			<div class=\"cl\" style=\"padding:5px; text-align:right\">
			<span class=\"product-button\"><a href=\"$url_products_detailot\">"._DETAIL."</a></span>
			<span class=\"product-button\"><a href=\"index.php?f=products&do=cart&act=add&id=$idot\">"._PRODUCT_ADD."</a></span>
		</div>
			";
	echo "</div>\n";
	//echo $pageing;
	if ($countot%2 == 0) { echo "<div class=\"cl\" style=\"border-top:1px solid #dfeaf4;\"></div>"; }
}
//////////////////////////////////////////////////////////////////////////////////
// Dinh dang chung cho giao dien trang hien thi danh sach san pham
// $title_store		:	Tieu de danh sach san pham
// $content_store	:	Noi dung danh sach san pham
//////////////////////////////////////////////////////////////////////////////////
function temp_stores($title_store, $content_store) 
{
	echo "<div class=\"adoshow\">\n";
	echo "<div class=\"adoshow-t\">$title_store</div>\n";
	echo "<div class=\"adoshow-c\">$content_store<div class=\"cl\"></div></div>\n";
	echo "</div>\n";
}
//////////////////////////////////////////////////////////////////////////////////
// Dinh dang danh sach cac san pham
//////////////////////////////////////////////////////////////////////////////////
function temp_products_list($title,$images,$url_detail,$url_cart,$Link_Images)
{
	global $Default_Temp;
	
	echo "<div class=\"adoshowdetail\" >";
	echo "<div class=\"adoshowdetail-ttt\"><a href=\"$url_detail\" title=\"$title\">$images</a></div>";
	echo "<div class=\"adoshowdetail-t\"><a href=\"$url_detail\">$title</a></div>";
	//<br/><span style=\"color:#ff0000; font-weight:bold\">".bsVndDot($price)."&nbsp;"._VND."</span>
	echo "<div class=\"adoshowdetail-tttt\"><a href=\"$url_detail\"><img src=\"$Link_Images/detail.jpg\"></a></div>";
	echo "</div>";
	if ($i == $col) { echo "<div style=\"clear:both\"></div>"; }
}
?>