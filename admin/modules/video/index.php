<?php

if(!defined('CMS_ADMIN')) die("Illegal File Access");

include_once("page_header.php");
global $urlsite;
$active = 1;
$title = $err_title = $links = $err_links  =$error=$info= "";
////////////////////////////////////////////

$perpage = 15;
$page = intval(isset($_GET['page']) ? $_GET['page'] : (isset($_POST['page']) ? $_POST['page']:1));
$offset = ($page-1) * $perpage;
$total = $db->sql_numrows($db->sql_query("SELECT id FROM {$prefix}_video WHERE active='1'"));
$result = $db->sql_query("SELECT id, title, guid, active, hits FROM {$prefix}_video WHERE alanguage='$currentlang' ORDER BY id desc LIMIT $offset, $perpage");

	ajaxload_content();

	echo "<div id=\"{$adm_modname}_main\"><form action=\"modules.php?f=$adm_modname&page=$page\" name=\"frm\" method=\"POST\" >";
	echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
	echo "<tr><td colspan=\"9\" class=\"header\">"._CURRENT_CATS."</td></tr>";
	echo "<tr>\n";
	echo "<td class=\"row1sd\" width=\"10\"></td>\n";
	echo "<td class=\"row1sd\">"._TITLE."</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"60\">"._STATUS."</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"60\">"._VIEW."</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"30\">"._EDIT."</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"30\">"._DELETE."</td>\n";
	echo "</tr>\n";
	$i = 0;
	if($page > 1) { $a = $perpage * $page - $perpage + 1;}
	while(list($id, $title, $guidlast, $active, $hits) = $db->sql_fetchrow($result)) {
		if (($i % 2) == 1) $css = "row1";
		else $css ="row3";

		if($ajax_active == 1) {
			switch($active) {
				case 1: $active = "<a href=\"?f=".$adm_modname."&do=status&page=$page&id=$id&stat=0\" title=\""._DEACTIVATE."\" onclick=\"ajaxinfoget('modules.php?f=".$adm_modname."&do=status&page=$page&id=$id&stat=0&load_hf=1','ajaxload_container', 'video_main'); return false;\"><img border=\"0\" src=\"images/view.png\"></a>"; break;
				case 0: $active = "<a href=\"?f=".$adm_modname."&do=status&page=$page&id=$id&stat=1\" title=\""._ACTIVE."\" onclick=\"ajaxinfoget('modules.php?f=".$adm_modname."&do=status&page=$page&id=$id&stat=1&load_hf=1','ajaxload_container', 'video_main'); return false;\"><img border=\"0\" src=\"images/viewo.png\"></a>"; break;
			}			
		} else {
			switch($active) {
				case 1: $active = "<a href=\"?f=".$adm_modname."&do=status&page=$page&id=$id&stat=0\" info=\""._DEACTIVATE."\"><img border=\"0\" src=\"images/view.png\"></a>"; break;
				case 0: $active = "<a href=\"?f=".$adm_modname."&do=status&page=$page&id=$id&stat=1\" info=\""._ACTIVE."\"><img border=\"0\" src=\"images/viewo.png\"></a>"; break;
			}
		}
		echo "<tr>\n";
		echo "<td class=\"$css\"><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td>";				
		 $titleLink = "<a href=\"".RPATH.url_sid($guidlast)."\" info=\""._VIEW."\" target=\"_blank\">$title</a> ";		
		echo "<td class=\"$css\"><b>$titleLink</b></td>\n";
		echo "<td align=\"center\" class=\"$css\">$active</td>\n";
		echo "<td align=\"center\" class=\"$css\">$hits</td>\n";
		echo "<td align=\"center\" width=\"30\" class=\"$css\"><a href=\"?f=".$adm_modname."&do=edit&page=$page&id=$id\" info=\""._EDIT."\"><img border=\"0\" src=\"images/edit.png\"></a></td>\n";
		if($ajax_active == 1) {
			echo "<td align=\"center\" width=\"30\" class=\"$css\"><a href=\"?f=".$adm_modname."&do=delete&page=$page&id=$id\" title=\""._DELETE."\" onclick=\"ajaxinfoget('modules.php?f=".$adm_modname."&do=delete&page=$page&id=$id&load_hf=1','ajaxload_container', 'video_main'); return false; aj_base_delete($id,'$adm_modname','"._DELETEASK1."','delete_video','');\"><img border=\"0\" src=\"images/delete.png\"></td>\n";
		} else {
			echo "<td align=\"center\" width=\"30\" class=\"$css\"><a href=\"?f=".$adm_modname."&do=delete&page=$page&id=$id\" info=\""._DELETE."\" onclick=\"return confirm('"._DELETEASK1."');\"><img border=\"0\" src=\"images/delete.png\"></td>\n";
		}
		echo "</tr>\n";
		$i++;
	}
	if($total > $perpage) {
		echo "<tr><td colspan=\"9\">";
		$pageurl = "modules.php?f=".$adm_modname."";
		echo paging($total,$pageurl,$perpage,$page);
		echo "</td></tr>";
	}
	echo "</table><br /></div>";
	
include_once("page_footer.php");
?>