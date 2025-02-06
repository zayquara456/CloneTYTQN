<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");
require_once("language/$currentlang/news.php");
$adm_modname = 'news';
//luu dia chi truy cap
$result = $db->sql_query("(SELECT id, catid, title, time, active, hits, nstart, 'normal' FROM {$prefix}_news $where) UNION (SELECT id, catid, title, UNIX_TIMESTAMP(timed) AS time, active, hits, nstart, 'timed' FROM {$prefix}_news_temp $where) ORDER BY id DESC LIMIT 9");
if($db->sql_numrows($result) > 0) {
	echo "<div id=\"pagehome\"><form action=\"modules.php?f=$adm_modname&sort=$sort&page=$page\" name=\"frm\" method=\"POST\" onsubmit=\"return checkQuick(this);\">";
	echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
	echo "<tr><td colspan=\"10\" class=\"header\">"._CURRENT_ART."</td></tr>";
	echo "<tr>\n";
	echo "<td class=\"row1sd\" width=\"10\">ID</td>\n";
	echo "<td class=\"row1sd\">"._TITLE."</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"100\">"._TIMEUP." <a href=\"modules.php?f=".$adm_modname."&sort=3\" info=\""._SORTUP."\"><img border=\"0\" src=\"images/sup.gif\" align=\"absmiddle\"></a> <a href=\"modules.php?f=".$adm_modname."&sort=4\" info=\""._SORTDOWN."\"><img border=\"0\" src=\"images/sdown.gif\" align=\"absmiddle\"></a></td>\n";
	echo "<td class=\"row1sd\" align=\"center\"></td>\n";
	echo "</tr>\n";
	$i = 0;
	if($page > 1) { $a = $perpage * $page - $perpage + 1;}
	while(list($id, $catid, $title, $time, $active, $hits, $nstart, $newsType) = $db->sql_fetchrow($result)) {
		//if (($i % 8) == 1) $css = "row1";
		//else $css ="row3";
		$css ="row1";
		
		if($ajax_active == 1) {
			switch($active) {
				case 1: $active = "<a href=\"modules.php?f=".$adm_modname."&do=status_news&type=$newsType&id=$id&stat=0\" title=\""._DEACTIVATE."\" onclick=\" aj_base_status($id,'0','$adm_modname','status_news',mid); return false;\"><img border=\"0\" src=\"images/view.png\"></a>"; break;
				case 0: $active = "<a href=\"modules.php?f=".$adm_modname."&do=status_news&type=$newsType&id=$id&stat=1\" title=\""._ACTIVE."\" onclick=\" aj_base_status($id,'1','$adm_modname','status_news',mid); return false;\"><img border=\"0\" src=\"images/viewo.png\"></a>"; break;
			}
			switch($nstart) {
				case 1: $nstart = "<a href=\"modules.php?f=".$adm_modname."&do=news_start&type=$newsType&id=$id&stat=0\" info=\""._NOSTART."\"
				 onclick=\"aj_base_start($id,'0','$adm_modname','news_start',mid); return false;\"><img border=\"0\" src=\"../images/start.png\"></a>"; break;
				case 0: $nstart = "<a href=\"modules.php?f=".$adm_modname."&do=news_start&type=$newsType&id=$id&stat=1\" info=\""._YESSTART."\" onclick=\"aj_base_start($id,'1','$adm_modname','news_start',mid); return false;\"><img border=\"0\" src=\"../images/starto.png\"></a>"; break;
			}
		} else {
			switch($active) {
				case 1: $active = "<a href=\"modules.php?f=".$adm_modname."&do=status_news&type=$newsType&id=$id&stat=0\" info=\""._DEACTIVATE."\"><img border=\"0\" src=\"images/view.png\"></a>"; break;
				case 0: $active = "<a href=\"modules.php?f=".$adm_modname."&do=status_news&type=$newsType&id=$id&stat=1\" info=\""._ACTIVE."\"><img border=\"0\" src=\"images/viewo.png\"></a>"; break;
			}
			switch($nstart) {
				case 1: $nstart = "<a href=\"modules.php?f=".$adm_modname."&do=news_start&type=$newsType&id=$id&stat=0\" info=\""._NOSTART."\"><img border=\"0\" src=\"../images/start.png\"></a>"; break;
				case 0: $nstart = "<a href=\"modules.php?f=".$adm_modname."&do=news_start&type=$newsType&id=$id&stat=1\" info=\""._YESSTART."\"><img border=\"0\" src=\"../images/starto.png\"></a>"; break;
			}
		}

		echo "<tr>\n";
		echo "<td class=\"$css\">$id</td>";
		if ($newsType == 'normal') $titleLink = "<a href=\"".RPATH.url_sid("index.php?f=".$adm_modname."&do=detail&id=$id")."\" info=\""._VIEW."\" target=\"_blank\">$title</a> <a href=\"../".url_sid("index.php?f=".$adm_modname."&do=detail&id=$id")."\" info=\""._GETLINK."\" onclick=\"prompt('"._GETLINK."','".url_sid("index.php?f=$adm_modname&do=detail&id=$id")."'); return false;\"><img border=\"0\" src=\"images/link.png\"></a>";
		else $titleLink = $title;
		echo "<td class=\"$css\"><b>$titleLink</b></td>\n";
		echo "<td align=\"center\" class=\"$css\">".ext_time($time, 2)."</td>\n";
		echo "<td align=\"center\" class=\"$css\">$active</td>\n";
		$i++;
	}
 	
	echo "<input type=\"hidden\" name=\"do\" value=\"quick_do\">";
	echo "<tr><td colspan=\"10\" class=\"row4\">";
	echo "<div class=\"fr\">";
	if($total > $perpage) {
		$pageurl = "modules.php?f=news&sort=$sort";
		echo paging($total,$pageurl,$perpage,$page);
	}
		echo "</div>";
	echo "</td></tr>";
	echo "</table></form></div>";
} else {
	OpenDiv();
	echo "<div class=\"info\">"._NONEWSPOST."</div>";
	echo "<META HTTP-EQUIV=\"refresh\" content=\"5;URL=modules.php?f=".$adm_modname."&do=create\">";
	CLoseDiv();
}
?>