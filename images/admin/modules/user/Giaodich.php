<?php
if (!defined('CMS_SYSTEM')) die();

if (!defined('iS_USER') || !isset()) header("Location: index.php?f=user&do=login");

$page_title = _USER_VIEW_ORDER;

include_once('header.php');
require_once('WebUser.class.php');

$sort = intval(isset($_GET['sort']) ? $_GET['sort'] : (isset($_POST['sort']) ? $_POST['sort']:0));
switch($sort) {
	default: $sortby = "ORDER BY orderTime DESC"; break;
	case 1: $sortby = "ORDER BY fullname ASC"; break;
	case 2: $sortby = "ORDER BY fullname DESC"; break;
	case 3: $sortby = "ORDER BY mail ASC"; break;
	case 4: $sortby = "ORDER BY mail DESC"; break;
	case 5: $sortby = "ORDER BY orderTime ASC"; break;
	case 6: $sortby = "ORDER BY orderTime DESC"; break;
}

$perpage = 20;
$page = intval(isset($_GET['page']) ? $_GET['page'] : (isset($_POST['page']) ? $_POST['page']:1));
$offset = ($page - 1) * $perpage;
$countf = $db->sql_fetchrow($db->sql_query("SELECT COUNT(*) FROM {$prefix}_products_order"));
$total = ($countf[0]) ? $countf[0] : 1;
$result = $db->sql_query("SELECT id, userid, fullname, mail, orderList, DATE_FORMAT(orderTime,'%d/%c/%Y %T'), status FROM {$prefix}_products_order WHERE userid=".['id']."  $sortby LIMIT $offset,$perpage");
if($db->sql_numrows($result) > 0) {
	echo "<script language=\"javascript\" type=\"text/javascript\">\n";
	echo "function check_uncheck(){\n";
	echo "	var f= document.frm;\n";
	echo "	if(f.checkall.checked){\n";
	echo "		CheckAllCheckbox(f,'id[]');\n";
	echo "	}else{\n";
	echo "		UnCheckAllCheckbox(f,'id[]');\n";
	echo "	}			\n";
	echo "}\n";
	echo "	function checkQuick(f) {\n";
	echo "		if(f.fc.value =='') {\n";
	echo "			f.fc.focus();\n";
	echo "			return false;\n";
	echo "		}\n";
	echo "		f.submit.disabled = true; \n";
	echo "		return true;		\n";
	echo "	}	\n";
	echo "	function checkQuickId(f) {\n";
	echo "		if(f.id.value =='') {\n";
	echo "			f.id.focus();\n";
	echo "			return false;\n";
	echo "		}\n";
	echo "		f.submit.disabled = true; \n";
	echo "		return true;		\n";
	echo "	}	\n";
	echo "</script>\n";
	ajaxload_content();
	echo "<div id=\"{$module_name}_main\"><form name=\"frm\" action=\"modules.php?f={$module_name}&do=$do\" method=\"POST\" onsubmit=\"return checkQuick(this);\">";
	echo "<table border=\"0\" width=\"100%\" cellspacing=\"1\" style=\"background:#1F98FC\" cellpadding=\"4\" class=\"tableborder\">\n";
	echo "<tr><td colspan=\"9\" style=\"background:#0C67CD; font-weight:bold; color:#FFF\">"._USER_ORDER_LIST."</td></tr>";
	echo "<tr>\n";
	echo "<td style=\"background:#B4D5FA\" width=\"20\" align=\"center\">"._USER_ORDER_ID."</td>\n";
	echo "<td style=\"background:#B4D5FA\" width=\"30\" align=\"center\">UID</td>\n";
	echo "<td style=\"background:#B4D5FA\">"._USER_ORDER_CUSTOMER_NAME."</td>\n";
	echo "<td style=\"background:#B4D5FA\" align=\"center\" width=\"100\">"._USER_ORDER_CUSTOMER_EMAIL."</td>\n";
	echo "<td style=\"background:#B4D5FA\" align=\"center\" width=\"140\">"._USER_ORDER_ORDER_TIME."</td>\n";
	echo "<td style=\"background:#B4D5FA\" align=\"center\" width=\"60\">"._STATUS."</td>\n";
	echo "<td style=\"background:#B4D5FA\" align=\"center\" width=\"30\">"._DELETE."</td>\n";
	echo "</tr>\n";
	$i = 1;
	while(list($orderId, $userid, $fullname, $mail, $orderList, $orderTime, $status) = $db->sql_fetchrow($result)) {
		if($i % 2 == 1) $css = "row3";
		else $css ="row1";
		if ($ajax_active == 1) $delete0 = "<td align=\"center\" width=\"30\"  style=\"background:#E9F4FE\"><a href=\"?f=".$module_name."&do=delete_order&id=$orderId\" title=\""._DELETE."\" onclick=\"return aj_base_delete($orderId,'$module_name','"._DELETEASK2."','delete_order','');\"><img border=\"0\" src=\"../images/trash.gif\"></a></td>\n";
				else $delete0 = "<td align=\"center\" width=\"30\"  style=\"background:#E9F4FE\"><a href=\"?f=".$module_name."&do=delete_order&id=$orderId\" info=\""._DELETE."\" onclick=\"return confirm('"._DELETEASK2."');\"><img border=\"0\" src=\"../images/trash.gif\"></a></td>\n";
		switch (intval($status)) {
			case 2:
				$statusText = _USER_ORDER_PROCESSED;
				$delete="<td  style=\"background:#E9F4FE\"></td>";
				break;
				
			case 1:
				$statusText = _USER_ORDER_WAITPROCESSED;
				$delete="<td  style=\"background:#E9F4FE\"></td>";
				break;
				
			case 0:
				$statusText = "<font color=\"red\">"._USER_ORDER_UNPROCESSED."</font>";
				//$delete="";
				$delete = $delete0;
				break;
		}
		echo "<tr>\n";
		echo "<td  align=\"center\" style=\"background:#E9F4FE\" width=\"20\">#$i</td>";
		echo "<td style=\"background:#E9F4FE\"><b>#".$userid."</b></td>\n";
		echo "<td style=\"background:#E9F4FE\"><b>$fullname</b></td>\n";
		echo "<td align=\"center\"style=\"background:#E9F4FE\" width=\"100\">$mail</td>\n";
		echo "<td align=\"center\" style=\"background:#E9F4FE\" width=\"140\">$orderTime</td>\n";
		echo "<td align=\"center\" style=\"background:#E9F4FE\" width=\"60\">$statusText</td>\n";
		echo $delete;
		echo "</tr>\n";
		echo "<tr><td style=\"background:#ffffff\" colspan=\"8\">";
			echo "<table  cellspacing=\"1\" style=\"background:#F1EE69\" cellpadding=\"4\" width=\"100%\">\n";
			echo "<tr>\n";
			echo "<th style=\"background:#FEFDE7\">"._USER_ORDER_ID."</th>\n";
			echo "<th style=\"background:#FEFDE7\">"._PRODUCT_NAME."</th>\n";
			echo "<th style=\"background:#FEFDE7\">"._PRODUCT_COUNT."</th>\n";
			echo "<th style=\"background:#FEFDE7\">"._PRODUCT_PRICE."</th>\n";
			echo "<th style=\"background:#FEFDE7\">"._PRODUCT_TOTAL."</th>\n";
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
				echo "<td style=\"background:#fff\">$i</td>\n";
				echo "<td style=\"background:#fff\">$prdTitle</td>\n";
				echo "<td style=\"background:#fff\">{$newOrderList[$prdId]}</td>\n";
				echo "<td style=\"background:#fff\">$prdPrice</td>\n";
				$currentTotal = $newOrderList[$prdId] * intval($prdPrice);
				$totalPrice += $currentTotal;
				echo "<td style=\"background:#fff\">".$currentTotal."</td>\n";
				echo "\n</tr>\n";
				$i++;
			}
			echo "<tr><td style=\"background:#fff\" colspan=\"4\" align=\"right\" ><b>"._PRODUCT_TOTAL."</b></td><td style=\"background:#FF9\">$totalPrice</td></tr>\n";
			echo "</table>\n";
		echo "</td></tr>";
		$i++;
	}
	if($total > $perpage) {
		echo "<tr><td colspan=\"9\">";
		$pageurl = "modules.php?f=".$module_name."&sort=$sort";
		echo paging($total,$pageurl,$perpage,$page);
		echo "</td></tr>";
	}
	echo "</table></div>";
} else {
	OpenDiv();
	echo "<center>"._NODATA."</center>";
	CLoseDiv();
}
include_once('footer.php');
?>