<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

include_once("page_header.php");

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
$result = $db->sql_query("SELECT id, fullname, mail, DATE_FORMAT(orderTime,'%d/%c/%Y %T'), status FROM {$prefix}_products_order $sortby LIMIT $offset,$perpage");
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

	echo "<div id=\"{$adm_modname}_main\"><form name=\"frm\" action=\"modules.php?f={$adm_modname}&do=quick_do_order\" method=\"POST\" onsubmit=\"return checkQuick(this);\">";
	echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
	echo "<tr><td colspan=\"9\" class=\"header\">"._PRD_ORDER_LIST."</td></tr>";
	echo "<tr>\n";
	echo "<td class=\"row1sd\" width=\"20\" align=\"center\">"._PRD_ORDER_ID."</td>\n";
	echo "<td class=\"row1sd\" width=\"10\"><input type=\"checkbox\" name=\"checkall\" onclick=\"javascript:check_uncheck();\"></td>\n";
	echo "<td class=\"row1sd\">"._PRD_ORDER_CUSTOMER_NAME." ".sortBy("modules.php?f=$adm_modname&do=orders",1)."</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"100\">"._PRD_ORDER_CUSTOMER_EMAIL." ".sortBy("modules.php?f=$adm_modname&do=orders",3)."</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"140\">"._PRD_ORDER_ORDER_TIME." ".sortBy("modules.php?f=$adm_modname&do=orders",5)."</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"60\">"._STATUS."</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"30\">"._VIEW."</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"30\">"._DELETE."</td>\n";
	echo "</tr>\n";
	$i = 1;
	while(list($orderId, $fullname, $mail, $orderTime, $status) = $db->sql_fetchrow($result)) {
		if($i % 2 == 1) $css = "row3";
		else $css ="row1";
		
		switch (intval($status)) {
			case 1:
				$statusText = _PRD_ORDER_PROCESSED;
				break;
			case 0:
				$statusText = "<font color=\"red\">"._PRD_ORDER_UNPROCESSED."</font>";
				break;
		}

		if ($ajax_active == 1) $delete = "<td align=\"center\" width=\"30\" class=\"$css\"><a href=\"?f=".$adm_modname."&do=delete_order&id=$orderId\" title=\""._DELETE."\" onclick=\"return aj_base_delete($orderId,'$adm_modname','"._DELETEASK2."','delete_order','');\"><img border=\"0\" src=\"images/delete.png\"></a></td>\n";
		else $delete = "<td align=\"center\" width=\"30\" class=\"$css\"><a href=\"?f=".$adm_modname."&do=delete_order&id=$orderId\" info=\""._DELETE."\" onclick=\"return confirm('"._DELETEASK2."');\"><img border=\"0\" src=\"images/delete.png\"></a></td>\n";

		echo "<tr>\n";
		echo "<td align=\"center\" class=\"$css\" width=\"20\">$i</td>";
		echo "<td class=\"$css\" width=\"10\"><input type=\"checkbox\" name=\"id[]\" value=\"$orderId\"></td>";
		echo "<td class=\"$css\"><b>$fullname</b></td>\n";
		echo "<td align=\"center\" class=\"$css\" width=\"100\">$mail</td>\n";
		echo "<td align=\"center\" class=\"$css\" width=\"140\">$orderTime</td>\n";
		echo "<td align=\"center\" class=\"$css\" width=\"60\">$statusText</td>\n";
		echo "<td align=\"center\" width=\"30\" class=\"$css\"><a href=\"?f=".$adm_modname."&do=view_order&id=$orderId\" info=\""._VIEW."\"><img border=\"0\" src=\"./images/search.gif\"></a></td>\n";
		echo $delete;
		echo "</tr>\n";
		$i++;
	}
	if($total > $perpage) {
		echo "<tr><td colspan=\"9\">";
		$pageurl = "modules.php?f=".$adm_modname."&sort=$sort";
		echo paging($total,$pageurl,$perpage,$page);
		echo "</td></tr>";
	}
	echo "<tr><td colspan=\"9\" class=\"row3\"><select name=\"fc\">";
	echo "<option value=\"\">&raquo; "._QUICKDO."</option>";
	echo "<option value=\"1\">&raquo; "._QUICKDO_1."</option>";
	echo "</select>&nbsp;<input type=\"submit\" name=\"submit\" value=\""._DOACTION."\"></form></td></tr>";
	echo "</table></div>";
} else {
	OpenDiv();
	echo "<center>"._NODATA."</center>";
	CLoseDiv();
}

include_once("page_footer.php");
?>