<?php
if (!defined('CMS_SYSTEM')) die();

include_once("header.php");

$col_prodct = 3;
$row_prodct = 3;
$newspagenum = $row_prodct * $col_prodct;

$perpage = intval($newspagenum);
$page = intval(isset($_GET['page']) ? $_GET['page'] : (isset($_POST['page']) ? $_POST['page']:1));
$offset = ($page-1) * $perpage;
$countf = $db->sql_fetchrow($db->sql_query("SELECT COUNT(*) FROM {$prefix}_products"));
$total = ($countf[0]) ? $countf[0] : 1;
$pageurl = "index.php?f=$module_name";

$result_prds_index = $db->sql_query("SELECT id, title, description, images, pnews FROM ".$prefix."_products WHERE active='1' AND alanguage='$currentlang' ORDER BY time DESC LIMIT $offset,$perpage");
if($db->sql_numrows($result_prds_index) > 0) {

	echo "<table align=\"center\" cellpadding=\"10\" border=\"0\" style=\"text-align: center;\">";
	echo "<tr>";
	$tt = 0;
	while(list($id, $title, $description, $images, $pnews) = $db->sql_fetchrow($result_prds_index)) {
		$tdstyle = '';
		if ((($tt % $col_prodct) == 0)) $tdstyle = '; border-right: 0px dashed';
		elseif (($tt % $col_prodct) == ($col_prodct - 1)) $tdstyle = '; border-left: 0px dashed';
		else $tdstyle = '';
		if ($tt >= $col_prodct) $tdstyle .= '; border-top: 0px dashed';
		$urlid = "$siteurl/".url_sid("index.php?f=$module_name&do=detail&id=$id");
		if($images !="" && file_exists("$path_upload/$module_name/$images")) {
			$images = "<a href=\"".$urlid."\"><img border=\"0\" src=\"$path_upload/$module_name/$images\" width=\"".$prd_thumbsize."\"></a>";
		}
		if($pnews == 1){ $pns = "<img src=\"".RPATH."/images/new.gif\" style=\"margin-top: 5px\">"; } else {$pns = "";}
		echo "<td valign=\"top\" style=\"margin-bottom: 10px$tdstyle;\">";
		echo "<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"center2\">
				<tr>
					<td align=\"left\" valign=\"top\" height=\"200px\" style=\" border:1px solid #cccccc\">
					<div class=\"ptitle\" style=\"padding-bottom: 1px\"><img style=\"border: 0px solid #c9c9c9\" src=\"templates/$Default_Temp/images/bullet.jpg\" align=\"absbottom\" />&nbsp;<a href=\"$urlid\"><b>$title&nbsp;$pns</b></a></div>
					<div style=\"padding-bottom: 5px\"><a href=\"$urlid\">$images</a></div>
					<div style=\"float:left;  padding:0px 5px 0px 5px\"><img style=\"border: 0px solid #c9c9c9\" src=\"templates/$Default_Temp/images/tia.jpg\" align=\"absmiddle\"/></div>
				<div class=\"pdesc\" style=\"padding-bottom: 10px\">$description</div></td>
				</tr>
			</table>";
		echo "</td>";
		$tt++;
		if ($tt%$col_prodct == 0) { echo "</tr><tr>"; }
	}
	$missingTD = $col_prodct - ($tt % $col_prodct);
	for ($i = 0; $i < $missingTD; $i++) {
		$tdstyle = '';
		if ((($tt % $col_prodct) == 0)) $tdstyle = '; border-right: 0px dashed';
		elseif (($tt % $col_prodct) == ($col_prodct - 1)) $tdstyle = '; border-left: 0px dashed';
		if ($tt >= $col_prodct) $tdstyle .= '; border-top: 0px dashed';
		echo "<td valign=\"top\" style=\"margin-bottom: 10px$tdstyle\">";
		echo "<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"center2\">";
		echo "<tr><td>&nbsp;</td></tr>";
		echo "</table>";
		echo "</td>";
		$tt++;
		if ($tt%$col_prodct == 0) { echo "</tr>"; }
	}
	echo "</table>";
	echo paging($total,$pageurl,$perpage,$page);
}

include_once("footer.php");
?>