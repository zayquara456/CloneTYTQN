<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

$id = intval($_GET['id']);

include_once("page_header.php");

if (isset($_POST['submit'])) {
	$status = intval($_POST['status']);
	$db->sql_query("UPDATE {$prefix}_products_order SET status=$status WHERE id=$id");
	updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _SET_ORDER_STATUS);
}

$result = $db->sql_query("SELECT title, fullname, mail, phone, address, info, orderList, DATE_FORMAT(orderTime,'%d/%c/%Y %T'), status FROM {$prefix}_products_order WHERE id=$id");
if($db->sql_numrows($result) > 0) {
	list($title, $fullname, $mail, $phone, $address, $info, $orderList, $orderTime, $status) = $db->sql_fetchrow($result);
	echo "<table border=\"1\" width=\"100%\">\n";
	echo "<tr>\n";
	echo "<th>"._PRD_ORDER_ID."</th>\n";
	echo "<th>"._PRODUCT_NAME."</th>\n";
	echo "<th>"._PRODUCT_COUNT."</th>\n";
	echo "<th>"._PRODUCT_PRICE."</th>\n";
	echo "<th>"._PRODUCT_TOTAL."</th>\n";
	echo "\n</tr>\n";
	$query = "SELECT id, title, price FROM {$prefix}_products WHERE";
	$orderList = explode(',', $orderList);
	$newOrderList = array();
	for ($i = 0; $i < count($orderList); $i++) {
		$orderList[$i] = explode('x', $orderList[$i]);
		$query .= " id={$orderList[$i][1]} OR";
		$newOrderList[$orderList[$i][1]] = intval($orderList[$i][0]);
	}
	$query = substr($query, 0, strlen($query) - 3);
	$db->sql_query($query);
	$totalPrice = 0;
	$i = 0;
	while (list($prdId, $prdTitle, $prdPrice) = $db->sql_fetchrow()) {
		echo "<tr>\n";
		echo "<td>$i</td>\n";
		echo "<td>$prdTitle</td>\n";
		echo "<td>{$newOrderList[$prdId]}</td>\n";
		echo "<td>$prdPrice</td>\n";
		$currentTotal = $newOrderList[$prdId] * intval($prdPrice);
		$totalPrice += $currentTotal;
		echo "<td>".$currentTotal."</td>\n";
		echo "\n</tr>\n";
		$i++;
	}
	echo "<tr><td colspan=\"4\" align=\"right\"><b>"._PRODUCT_TOTAL."</b></td><td>$totalPrice</td></tr>\n";
	echo "</table>\n";
	echo "<br />\n";
	echo "<form action=\"modules.php?f=$adm_modname&do=$do&id=$id\" name=\"frm\" method=\"POST\">\n";
	echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
	echo "<tr><td colspan=\"2\" class=\"header\">"._PRD_VIEW_ORDER."</td></tr>";
	echo "<tr><td class=\"row1\" width=\"15%\">"._PRD_ORDER_CUSTOMER_NAME."</td>\n";
	if ($title == '0') $title = _PRODUCT_MR;
	else $title = _PRODUCT_MRS;
	echo "<td class=\"row2\">$title $fullname</td></tr>\n";
	echo "<tr><td class=\"row1\" width=\"15%\">"._PRD_ORDER_CUSTOMER_EMAIL."</td>\n";
	echo "<td class=\"row2\">$mail</td></tr>\n";
	echo "<tr><td class=\"row1\" width=\"15%\">"._PRD_ORDER_CUSTOMER_PHONE."</td>\n";
	echo "<td class=\"row2\">$phone</td></tr>\n";
	echo "<tr><td class=\"row1\" width=\"15%\">"._PRD_ORDER_CUSTOMER_ADDRESS."</td>\n";
	echo "<td class=\"row2\">$address</td></tr>\n";
	echo "<tr><td class=\"row1\" width=\"15%\">"._PRD_ORDER_INFO."</td>\n";
	echo "<td class=\"row2\">$info</td></tr>\n";
	echo "<tr><td class=\"row1\" width=\"15%\">"._PRD_ORDER_ORDER_TIME."</td>\n";
	echo "<td class=\"row2\">$orderTime</td></tr>\n";
	echo "<tr><td class=\"row1\" width=\"15%\">"._PRD_ORDER_STATUS."</td>\n";
	echo "<td class=\"row2\">";
	echo "<select name=\"status\">";
	$status0 = $status1 = '';
	if ($status == '0') $status0 = ' selected="selected"';
	elseif ($status == '1') $status1 = ' selected="selected"';
	echo "<option value=\"0\"$status0>"._PRD_ORDER_UNPROCESSED."</option>";
	echo "<option value=\"1\"$status1>"._PRD_ORDER_PROCESSED."</option>";
	echo "</select>";
	echo "</td></tr>\n";
	echo "<tr><td colspan=\"2\" class=\"row3\" align=\"center\"><input type=\"submit\" name=\"submit\" value=\""._SAVECHANGES."\"></form></td></tr>";
	echo "</table>";
} else {
	header("Location: modules.php?f=$adm_modname&do=orders");
}

include_once("page_footer.php");
?>