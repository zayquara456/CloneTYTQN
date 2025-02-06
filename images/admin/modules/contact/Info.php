<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}
require("language/$currentlang/contact.php");
$adm_modname = 'contact';
	$result = $db->sql_query("SELECT  id, pid, pid_name, title, ctname, time, status  FROM ".$prefix."_contact WHERE alanguage='$currentlang' ORDER BY time DESC LIMIT 9");
	if($db->sql_numrows($result) > 0) {
		echo "<div id=\"pagehome\"><form action=\"modules.php?f=$adm_modname&do=$do&page=$page\" name=\"frm\" method=\"POST\" onsubmit=\"return checkQuick(this);\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" class=\"tableborder\">\n";
		echo "<tr>\n";
		echo "<tr><td colspan=\"7\" class=\"header\">"._MODTITLE."</td></tr>";
		echo "<td class=\"row1sd\" width=\"10\">ID</td>\n";
		echo "<td class=\"row1sd\">Tiêu đề</td>\n";
		echo "<td class=\"row1sd\" align=\"center\" width=\"120\">"._CTPART."</td>\n";
		echo "<td class=\"row1sd\" align=\"center\" width=\"100\">"._DATESENT."</td>\n";
		echo "<td class=\"row2sd\" align=\"center\" ></td>\n";
		echo "</tr>\n";
		$cur_ar = array(_VND,_USD);
		$i =0;
		while(list($id, $pid, $pid_name, $title, $ctname, $time, $status) = $db->sql_fetchrow($result)) {

			switch($status) {
				case 2: $status = "<a href=\"modules.php?f=".$adm_modname."&do=status_news&type=$newsType&id=$id&stat=0\" info=\""._PROCESSED."\"><img border=\"0\" src=\"images/view.png\"></a>"; break;
				case 1: $status = "<a href=\"modules.php?f=".$adm_modname."&do=status_news&type=$newsType&id=$id&stat=0\" info=\""._PROCESSING."\"><img border=\"0\" src=\"images/view.png\"></a>"; break;
				case 0: $status = "<a href=\"modules.php?f=".$adm_modname."&do=status_news&type=$newsType&id=$id&stat=1\" info=\""._NOPROCESS."\"><img border=\"0\" src=\"images/viewo.png\"></a>"; break;
			}
			echo "<tr>\n";
			echo "<td class=\"row1\">$id</td>";
			echo "<td class=\"row1\">\n";
			if($title) {
				echo "<a href=\"modules.php?f=$adm_modname&do=view_ct&id=$id\" info=\""._VIEW."\"><b>$title</b></a>\n";
			} else {
				echo "<a href=\"modules.php?f=$adm_modname&do=view_ct&id=$id\" info=\""._VIEW."\"><b>No name</b></a>\n";
			}
			echo "</td>\n";
			echo "<td align=\"center\" class=\"row1\">$pid_name</td>\n";
			echo "<td align=\"center\" class=\"row1\">".ext_time($time, 2)."</td>\n";
			echo "<td align=\"center\" class=\"row2\"><font color=\"red\">$status</font></td>\n";
			
			echo "</tr>\n";
			$i ++;
		}
		if($total > $perpage) {
			echo "<tr><td colspan=\"6\">";
			$pageurl = "".$adm_modname.".php";
			echo paging($total,$pageurl,$perpage,$page);
			echo "</td></tr>";
		}
		echo "</table></form></div>";

	}else{
		OpenDiv();
		echo "<center>"._NOCONTACT."</center>";
		CLoseDiv();
	}

?>