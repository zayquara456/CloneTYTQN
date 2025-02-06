<?php
if (!defined('CMS_SYSTEM')) die();

include_once("Cart.class.php");

if (!isset($cart)) $cart = new Cart(CART_SESS);

$count = explode(',', $_POST['count']);
$list = explode(',', $_POST['list']);
$name = explode(',', $_POST['name']);
$price = explode(',', $_POST['price']);
//$proid = explode(',', $_POST['proid']);
$newCart = array();
for ($i = 0; $i < count($list); $i++) {
	//if($proid !=$list[$i])
	//{
		if (intval(preg_replace('/[^0-9]/','',$count[$i])) < 1) continue;
		$newCart[intval($list[$i])]['count'] = intval(preg_replace('/[^0-9]/','',$count[$i]));
		$newCart[intval($list[$i])]['name'] = $name[$i];
		$newCart[intval($list[$i])]['price'] = floatval($price[$i]);
	//}
}
$cart->update($newCart);
$load_hf = 1;

include_once("modules/{$module_name}/Cart.php");
?>