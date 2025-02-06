<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

$userid = intval($_GET['userid']);

include_once("page_header.php");

$result = $db->sql_query("SELECT userid, sale, orderList, DATE_FORMAT(orderTime,'%d/%c/%Y %T'), status FROM {$prefix}_products_order WHERE userid=$userid ORDER BY orderTime DESC");
if($db->sql_numrows($result) > 0) {
	while(list($userid, $sale, $orderList, $orderTime, $status) = $db->sql_fetchrow($result)){
	echo "<table border=\"1\" width=\"60%\" style=\"border-collapse: collapse;border: 1px solid;margin-left:20%;\">\n";
	echo "<tr>\n";
	echo "<th width=\"100px\">"._PRD_ORDER_ID."</th>\n";
	echo "<th>"._PRODUCT_NAME."</th>\n";
	echo "<th width=\"60px\">"._PRODUCT_COUNT."</th>\n";
	echo "<th  width=\"100px\">"._PRODUCT_PRICE." (VND)</th>\n";
	echo "<th width=\"100px\">"._PRODUCT_TOTAL." (VND)</th>\n";
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
		$j=$i+1;
		echo "<td align=\"center\">$j</td>\n";
		echo "<td>&nbsp;$prdTitle</td>\n";
		echo "<td align=\"center\">{$newOrderList[$prdId]}</td>\n";
		echo "<td align=\"right\">".dsprice($prdPrice)."</td>\n";
		$currentTotal = $newOrderList[$prdId] * intval($prdPrice);
		$totalPrice += $currentTotal;
		echo "<td align=\"right\">".dsprice($currentTotal)."</td>\n";
		echo "\n</tr>\n";
		$i++;
	}	
	
	if($sale==0){
		echo "<tr><td colspan=\"4\" align=\"right\"><b>"._PRODUCT_TOTAL."</b></td><td align=\"right\"><b>".dsprice($totalPrice)."</b></td></tr>\n";
	} else {
		$total_1= $totalPrice - (($totalPrice*3)/100);
		$total_1 = floor($total_1);
		echo "<tr><td colspan=\"4\" align=\"right\"><b>"._PRODUCT_TOTAL."</b><br>"._PRODUCT_SALE_OOF."</td><td align=\"right\"><b><s>".dsprice($totalPrice)."</s></b><br><b style=\"color:red\">".bsVndDot($total_1)."</b></td></tr>\n";
	}	
	
	echo "<tr><td >"._PRD_ORDER_ORDER_TIME."</td><td colspan=\"4\" align=\"left\" align=\"right\"><b>".$orderTime."</b></td></tr>\n";
	echo "<tr><td >"._PRD_ORDER_STATUS."</td><td colspan=\"4\" align=\"left\" align=\"right\">";		
	if ($status == '0') {		
		echo "<font color=\"red\">"._PRD_ORDER_UNPROCESSED."</font>";			
	}	
	else if ($status == '1') {
		echo "<b>"._PRD_ORDER_PROCESSED."</b>";
	}	
	echo "</td></tr>\n";
	
	echo "</table>\n";
	echo "<br />\n";	
}	
} else {
	header("Location: modules.php?f=$adm_modname&do=orders");
}

include_once("page_footer.php");
?>