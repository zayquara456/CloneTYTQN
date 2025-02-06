<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

include_once("page_header.php");

$sort = intval(isset($_GET['sort']) ? $_GET['sort'] : (isset($_POST['sort']) ? $_POST['sort']:0));
switch($sort) {
	case 1: $sortby ="ORDER BY catid ASC"; break;
	case 2: $sortby ="ORDER BY catid DESC"; break;
	case 3: $sortby ="ORDER BY time ASC"; break;
	case 4: $sortby ="ORDER BY time DESC"; break;
	case 5: $sortby ="ORDER BY hits ASC"; break;
	case 6: $sortby ="ORDER BY hits DESC"; break;
	default: $sortby ="ORDER BY time DESC"; break;
}
$perpage = 20;
$page = intval(isset($_GET['page']) ? $_GET['page'] : (isset($_POST['page']) ? $_POST['page']:1));
$offset = ($page-1) * $perpage;
$catArr = array();
$cats = $db->sql_query("SELECT catid, title FROM {$prefix}_tuyendung_cat");
while (list($cid, $ctitle) = $db->sql_fetchrow()) $catArr[$cid] = $ctitle;
$total = $db->sql_numrows($db->sql_query("(SELECT id FROM {$prefix}_tuyendung WHERE alanguage='$currentlang') UNION (SELECT id FROM {$prefix}_tuyendung_temp WHERE alanguage='$currentlang')"));
$result = $db->sql_query("(SELECT id, catid, title, time, active, hits, nstart, 'normal' FROM {$prefix}_tuyendung WHERE alanguage='$currentlang') UNION (SELECT id, catid, title, UNIX_TIMESTAMP(timed) AS time, active, hits, nstart, 'timed' FROM {$prefix}_tuyendung_temp WHERE alanguage='$currentlang') $sortby LIMIT $offset, $perpage");
if($db->sql_numrows($result) > 0) {
	?>
	<script language="javascript" type="text/javascript">
	function check_uncheck(){
		var f= document.frm;
		if(f.checkall.checked){
			CheckAllCheckbox(f,'id[]');
		}else{
			UnCheckAllCheckbox(f,'id[]');
		}			
	}
		function checkQuick(f) {
			if(f.f.value =='') {
				f.f.focus();
				return false;
			}
			f.submit.disabled = true; 
			return true;		
		}	
		function checkQuickId(f) {
			if(f.id.value =='') {
				f.id.focus();
				return false;
			}
			f.submit.disabled = true; 
			return true;		
		}	
	</script>
	
<?php
	ajaxload_content();
	echo "<div id=\"{$adm_modname}_main\"><form action=\"modules.php?f=$adm_modname&sort=$sort&page=$page\" name=\"frm\" method=\"POST\" onsubmit=\"return checkQuick(this);\">";
	echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
	echo "<tr><td colspan=\"10\" class=\"header\">"._CURRENT_ART."</td></tr>";
	echo "<tr>\n";
	echo "<td class=\"row1sd\" width=\"10\"><input type=\"checkbox\" name=\"checkall\" onclick=\"javascript:check_uncheck();\"></td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"60\">"._NEWSTART."</td>\n";
	echo "<td class=\"row1sd\">"._TITLE."</td>\n";
	echo "<td class=\"row1sd\">"._NEWS_CATEGORY." <a href=\"?f=".$adm_modname."&sort=1\" info=\""._SORTUP."\"><img border=\"0\" src=\"images/sup.gif\" align=\"absmiddle\"></a> <a href=\"?f=".$adm_modname."&sort=2\" info=\""._SORTDOWN."\"><img border=\"0\" src=\"images/sdown.gif\" align=\"absmiddle\"></a></td>\n";
	echo "<td class=\"row1sd\">"._CREATE_TAB."</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"100\">"._TIMEUP." <a href=\"?f=".$adm_modname."&sort=3\" info=\""._SORTUP."\"><img border=\"0\" src=\"images/sup.gif\" align=\"absmiddle\"></a> <a href=\"?f=".$adm_modname."&sort=4\" info=\""._SORTDOWN."\"><img border=\"0\" src=\"images/sdown.gif\" align=\"absmiddle\"></a></td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"60\">"._STATUS."</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"60\">"._VIEW." <a href=\"?f=".$adm_modname."&sort=5\" info=\""._SORTUP."\"><img border=\"0\" src=\"images/sup.gif\" align=\"absmiddle\"></a> <a href=\"?f=".$adm_modname."&sort=6\" info=\""._SORTDOWN."\"><img border=\"0\" src=\"images/sdown.gif\" align=\"absmiddle\"></a></td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"30\">"._EDIT."</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"30\">"._DELETE."</td>\n";
	echo "</tr>\n";
	$i = 0;
	if($page > 1) { $a = $perpage * $page - $perpage + 1;}
	while(list($id, $catid, $title, $time, $active, $hits, $nstart, $newsType) = $db->sql_fetchrow($result)) {
		if (($i % 2) == 1) $css = "row1";
		else $css ="row3";

		if($ajax_active == 1) {
			switch($active) {
				case 1: $active = "<a href=\"?f=".$adm_modname."&do=status_news&type=$newsType&id=$id&stat=0\" title=\""._DEACTIVATE."\" onclick=\"ajaxinfoget('modules.php?f=".$adm_modname."&do=status_news&type=$newsType&id=$id&stat=0&load_hf=1','ajaxload_container', '{$adm_modname}_main'); return false;\"><img border=\"0\" src=\"images/view.png\"></a>"; break;
				case 0: $active = "<a href=\"?f=".$adm_modname."&do=status_news&type=$newsType&id=$id&stat=1\" title=\""._ACTIVE."\" onclick=\"ajaxinfoget('modules.php?f=news&do=status_news&type=$newsType&id=$id&stat=1&load_hf=1','ajaxload_container', '{$adm_modname}_main'); return false;\"><img border=\"0\" src=\"images/viewo.png\"></a>"; break;
			}
			switch($nstart) {
				case 1: $nstart = "<a href=\"?f=".$adm_modname."&do=news_start&type=$newsType&id=$id&stat=0\" info=\""._NOSTART."\" onclick=\"ajaxinfoget('modules.php?f={$adm_modname}&do=news_start&type=$newsType&id=$id&stat=0','ajaxload_container','{$adm_modname}_main'); return false;\"><img border=\"0\" src=\"../images/start.png\"></a>"; break;
				case 0: $nstart = "<a href=\"?f=".$adm_modname."&do=news_start&type=$newsType&id=$id&stat=1\" info=\""._YESSTART."\" onclick=\"ajaxinfoget('modules.php?f={$adm_modname}&do=news_start&type=$newsType&id=$id&stat=1','ajaxload_container','{$adm_modname}_main'); return false;\"><img border=\"0\" src=\"../images/starto.png\"></a>"; break;
			}
		} else {
			switch($active) {
				case 1: $active = "<a href=\"?f=".$adm_modname."&do=status_news&type=$newsType&id=$id&stat=0\" info=\""._DEACTIVATE."\"><img border=\"0\" src=\"images/view.png\"></a>"; break;
				case 0: $active = "<a href=\"?f=".$adm_modname."&do=status_news&type=$newsType&id=$id&stat=1\" info=\""._ACTIVE."\"><img border=\"0\" src=\"images/viewo.png\"></a>"; break;
			}
			switch($nstart) {
				case 1: $nstart = "<a href=\"?f=".$adm_modname."&do=news_start&type=$newsType&id=$id&stat=0\" info=\""._NOSTART."\"><img border=\"0\" src=\"../images/start.png\"></a>"; break;
				case 0: $nstart = "<a href=\"?f=".$adm_modname."&do=news_start&type=$newsType&id=$id&stat=1\" info=\""._YESSTART."\"><img border=\"0\" src=\"../images/starto.png\"></a>"; break;
			}
		}

		echo "<tr>\n";
		echo "<td class=\"$css\"><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td>";
		echo "<td align=\"center\" class=\"$css\">$nstart</td>\n";
		if ($newsType == 'normal') $titleLink = "<a href=\"".RPATH.url_sid("index.php?f=".$adm_modname."&do=detail&id=$id")."\" info=\""._VIEW."\" target=\"_blank\">$title</a> <a href=\"../".url_sid("index.php?f=".$adm_modname."&do=detail&id=$id")."\" info=\""._GETLINK."\" onclick=\"prompt('"._GETLINK."','".url_sid("index.php?f=$adm_modname&do=detail&id=$id")."'); return false;\"><img border=\"0\" src=\"images/link.png\"></a>";
		else $titleLink = $title;
		echo "<td class=\"$css\"><b>$titleLink</b></td>\n";
		echo "<td class=\"$css\"><b>".catname_byparent($catid)."<a href=\"".RPATH.url_sid("index.php?f=".$adm_modname."&do=categories&catid=$catid")."\" info=\""._VIEW."\" target=\"_blank\">{$catArr[$catid]}</a></b></td>\n";
		echo "<td class=\"$css\"><b><a href=\"modules.php?f=".$adm_modname."&do=tabnews&newsid=$id\" info=\""._VIEW."\" target=\"_blank\">"._CREATE_TAB."</a></b></td>\n";
		echo "<td align=\"center\" class=\"$css\">".ext_time($time, 2)."</td>\n";
		echo "<td align=\"center\" class=\"$css\">$active</td>\n";
		echo "<td align=\"center\" class=\"$css\">$hits</td>\n";
		echo "<td align=\"center\" width=\"30\" class=\"$css\"><a href=\"?f=".$adm_modname."&do=edit_news&type=$newsType&id=$id\" info=\""._EDIT."\"><img border=\"0\" src=\"images/edit.png\"></a></td>\n";
		if($ajax_active == 1) {
			echo "<td align=\"center\" width=\"30\" class=\"$css\"><a href=\"?f=".$adm_modname."&do=delete_news&type=$newsType&id=$id\" title=\""._DELETE."\" onclick=\"ajaxinfoget('modules.php?f={$adm_modname}&do=delete_news&type=$newsType&id=$id&load_hf=1','ajaxload_container', '{$adm_modname}_main'); return false; aj_base_delete($id,'$adm_modname','"._DELETEASK1."','delete_news','');\"><img border=\"0\" src=\"images/delete.png\"></td>\n";
		} else {
			echo "<td align=\"center\" width=\"30\" class=\"$css\"><a href=\"?f=".$adm_modname."&do=delete_news&type=$newsType&id=$id\" info=\""._DELETE."\" onclick=\"return confirm('"._DELETEASK1."');\"><img border=\"0\" src=\"images/delete.png\"></td>\n";
		}
		echo "</tr>\n";
		$i++;
	}
	if($total > $perpage) {
		echo "<tr><td colspan=\"10\">";
		$pageurl = "modules.php?f=".$adm_modname."&sort=$sort";
		echo paging($total,$pageurl,$perpage,$page);
		echo "</td></tr>";
	}
	echo "<input type=\"hidden\" name=\"do\" value=\"quick_do\">";
	echo "<tr><td colspan=\"10\" class=\"row3\"><select name=\"fc\">";
	echo "<option value=\"\">&raquo; "._QUICKDO."</option>";
	echo "<option value=\"1\">&raquo; "._QUICKDO_1."</option>";
	echo "<option value=\"2\">&raquo; "._QUICKDO_2."</option>";
	echo "<option value=\"3\">&raquo; "._QUICKDO_3."</option>";
	echo "</select>&nbsp;<input type=\"submit\" name=\"submit\" value=\""._DOACTION."\"></form></td></tr>";
	echo "</table><br /></div>";
} else {
	OpenDiv();
	echo "<center>"._NONEWSPOST."</center>";
	echo "<META HTTP-EQUIV=\"refresh\" content=\"5;URL=modules.php?f=".$adm_modname."&do=create\">";
	CLoseDiv();
}

include_once("page_footer.php");
?>