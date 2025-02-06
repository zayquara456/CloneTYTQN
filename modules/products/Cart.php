<?php
if (!defined('CMS_SYSTEM')) die();

$snohf = isset($nohf);

if (!$snohf) include_once("header.php");
include_once("Cart.class.php");

echo <<<EOT
<script>
	function prepareToPost(list, name, price) {
		var l = list.split(',');
		var r = 'count=';
		for (i = 0; i < l.length; i++) {
			r += fetch_object('product_' + l[i] + '_count').value + ',';
		}
		r = r.substr(0, r.length - 1);
		r += '&list=' + list;
		r += '&name=' + encodeURIComponent(name);
		r += '&price=' + price;
		ajaxinfopost('index.php?f=products&do=cart_upd', r, 'ajaxload_container', 'products_main');
	}
	function DeletePost(list, name, price, proid) {
		var r = '';
		r += '&proid=' + proid;
		ajaxinfopost('index.php?f=products&do=cart_del', r, 'ajaxload_container', 'products_main');
	}
	
</script>
EOT;

echo "<div id=\"{$module_name}_main\">";
OpenTab(_SHOPPING_CART);

$cart = new Cart(CART_SESS);

if (isset($_GET['act'])) {
	if ($_GET['act'] == 'add') {
		if (isset($_GET['id'])) {
			$id = intval($_GET['id']);
			$db->sql_query("SELECT title, price FROM {$prefix}_products WHERE id=$id");
			if ($db->sql_numrows() > 0) {
				list($title, $price) = $db->sql_fetchrow();
				$newCart = $cart->getData();
				$found = 0;
				foreach ($newCart as $itemId => $itemDetail) {
					if ($itemId == $id) {
						$newCart[$itemId]['count'] += 1;
						$found = 1;
						break;
					}
				}
				if (!$found) $cart->add($id, $title, $price);
				else $cart->update($newCart);
				$newCart = $cart->getData();
			}
		}
	} elseif ($_GET['act'] == 'update') {
		$count = explode(',', $_POST['count']);
		$list = explode(',', $_POST['list']);
		$name = explode(',', $_POST['name']);
		$price = explode(',', $_POST['price']);
		$newCart = array();
		for ($i = 0; $i < count($list); $i++) {
			$newCart[intval($list[$i])]['count'] = intval(preg_replace('/[^0-9]/','',$count[$i]));
			$newCart[intval($list[$i])]['name'] = $name[$i];
			$newCart[intval($list[$i])]['price'] = floatval($price[$i]);
		}
		$cart->update($newCart);
	}
    header("Location: index.php?f=products&do=cart");
}

if (!isset($newCart)) $newCart = $cart->getData();

echo '<form method="POST" action="'.url_sid("index.php?f=$module_name&do=checkout")."\">";
echo "<table border=\"1\" width=\"100%\" style=\"border-collapse: collapse\">";
echo "<tr>";
echo "<th width=\5%\">"._PRODUCT_NO."</th>";
echo "<th width=\"50%\">"._PRODUCT_NAME."</th>";
echo "<th width=\"15%\">"._PRODUCT_COUNT."</th>";
echo "<th width=\"15%\">"._PRODUCT_PRICE."</th>";
echo "<th width=\"15%\">"._PRODUCT_TOTAL."</th>";
echo "<th width=\"15%\">"._PRODUCT_DELETE."</th>";
echo "</tr>";
$productList = array();
$productName = array();
$productPrice = array();
$request = '';
$i = 1;
$total = 0;
foreach ($newCart as $productId => $productDetail) {
	echo "<tr>";
	echo "<td width=\"5%\">$i</td>";
	echo "<td width=\"50%\">{$productDetail['name']}</td>";
	echo "<td width=\"15%\"><input type=\"text\" class=\"text\" size=\"3\" id=\"product_{$productId}_count\" name=\"product_{$productId}_count\" value=\"{$productDetail['count']}\" /></td>";
	echo "<td width=\"15%\">{$productDetail['price']}</td>";
	$currentTotal = $productDetail['price'] * $productDetail['count'];
	echo "<td width=\"15%\">".strval($currentTotal)."</td>";
	echo "<td width=\"15%\"><input type=\"checkbox\" name=\"delete\" class=\"sb_but1\" value=\""._PRODUCT_DELETE."\"></td>";
	echo "</tr>";
	$productList[] = $productId;
	$productName[] = $productDetail['name'];
	$productPrice[] = $productDetail['price'];
	$total += $currentTotal;
	$i++;
}
$productList = implode(',', $productList);
$productName = implode(',', $productName);
$productPrice = implode(',', $productPrice);
echo "<tr><td colspan=\"4\" align=\"right\" style=\"padding-right: 5px\"><b>"._PRODUCT_TOTAL."</b></td><td>$total</td></tr>";
echo "</table>";
echo "<div align=\"left\" style=\"padding-left: 10px; color: red\">* "._PRODUCT_UNIT."</div><br />";
echo "<div align=\"center\"><input type=\"button\" name=\"update\" class=\"sb_but1\" value=\""._PRODUCT_CART_UPDATE."\" onclick=\"prepareToPost('$productList', '$productName', '$productPrice');\">&nbsp;<input type=\"submit\" name=\"submit\" value=\""._PRODUCT_CHECKOUT."\" class=\"sb_but1\"></div></form>";

CloseTab();
echo "</div>";

if (!$snohf) include_once("footer.php");
?>