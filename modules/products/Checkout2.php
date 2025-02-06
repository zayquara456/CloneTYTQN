<?php
if (!defined('CMS_SYSTEM')) die();

include_once("header.php");
include_once("Cart.class.php");

OpenTab(_PRODUCT_CHECKOUT);

$title = intval($_POST['title']);
$name = $escape_mysql_string($_POST['name']);
$email = $escape_mysql_string($_POST['email']);
$phone = $escape_mysql_string($_POST['phone']);
$address = $escape_mysql_string($_POST['address']);
$message = $escape_mysql_string($_POST['message']);

$cart = new Cart(CART_SESS);
$newCart = $cart->getData();

$orderList = '';
foreach ($newCart as $productId => $productDetail) $orderList .= "{$productDetail['count']}x$productId,";
$orderList = substr($orderList, 0, strlen($orderList) - 1);

$cart->reset();

$db->sql_query("INSERT INTO {$prefix}_products_order (title, fullname, mail, phone, address, info, orderList, orderTime, status) VALUES ('$title', '$name', '$email', '$phone', '$address', '$message', '$orderList', NOW(), 0)");
if ($db->sql_affectedrows() > 0) {
	foreach ($newCart as $productId => $productDetail) {
		$db->sql_query("UPDATE {$prefix}_products SET buyCount=buyCount+{$productDetail['count']} WHERE id=$productId");
	}
	echo '<div align="center">'._PRODUCT_CHECKED_OUT."</div>";
} else {
	echo '<div align="center">'._PRODUCT_ERROR_CHECKING_OUT."</div>";
}
echo "<meta http-equiv=\"refresh\" content=\"5;url=index.php\">";

CloseTab();

include_once("footer.php");
?>