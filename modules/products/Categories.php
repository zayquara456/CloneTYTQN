<?php
if (!defined('CMS_SYSTEM')) die();

$catid = intval($_GET['id']);
$result = $db->sql_query("SELECT title, sub_id, parentid FROM ".$prefix."_products_cat WHERE catid='$catid' AND alanguage='$currentlang'");
if(empty($catid) || $db->sql_numrows($result) != 1) {
	header("Location: index.php?f=".$module_name);
	exit();
}

list($catname, $sub_id, $parentid) = $db->sql_fetchrow($result);

if ($parentid != 0) {
	$title_cat = page_tilecat($catid, $parentid, $catname);
	$page_title .= " $title_cat";
} else {
	$title_cat = $catname;
	$page_title .= " - $catname";
}

include_once("header.php");

OpenTab(_MODTITLE." &raquo; $title_cat");

if (empty($sub_id)) {
	$perpage = $prd_perpage;
	$page = intval(isset($_GET['page']) ? $_GET['page'] : (isset($_POST['page']) ? $_POST['page']:1));
	$offset = ($page-1) * $perpage;
	$countf = $db->sql_fetchrow($db->sql_query("SELECT COUNT(*) FROM {$prefix}_products WHERE active=1 AND catid='$catid' AND alanguage='$currentlang'"));
	$total = ($countf[0]) ? $countf[0] : 1;
	$pageurl = "index.php?f=".$module_name."&do=categories&id=$catid";
	$result_prd_home = $db->sql_query("SELECT id, title, images FROM {$prefix}_products WHERE catid='$catid' AND active='1' AND alanguage='$currentlang' ORDER BY time DESC LIMIT $offset, $perpage");
	if($db->sql_numrows($result_prd_home) > 0) {
		echo "<table border=\"0\" style=\"margin-left: 10px\">";
		echo "	<tr>";
		$i =0;
		while(list($idprd, $titleprd, $imgprd) = $db->sql_fetchrow($result_prd_home)) {
			if($i < 2) {$xpadding = 25; } else { $xpadding = 0; }
			if($imgprd !="" && file_exists("$path_upload/products/$imgprd")) {
				$i ++;
				if(file_exists("".$path_upload."/products/thumb_".$imgprd)) {
					$imgprd = "thumb_".$imgprd;
				}
				echo "<td style=\"padding-right: ".$xpadding."px; padding-bottom: 15px; vertical-align: middle\">";
				echo "<div style=\"text-align: center; margin-top: 5px; margin-bottom: 5px; margin-left: 3px;\"><a href=\"".url_sid("index.php?f=products&do=detail&id=$idprd" )."\" style=\"color: #FF6500; text-decoration: none; font-weight: bold; text-transform: uppercase;\">$titleprd</a></div>";
				echo "<div align=\"center\"><a href=\"".url_sid("index.php?f=products&do=detail&id=$idprd")."\" title=\""._DETAIL."\"><img border=\"0\" src=\"$path_upload/products/$imgprd\" width=\"$prd_thumbsize\"></a></div>";

				echo "</td>";
			}
			if($i == 4) { $i =0; echo "</tr>"; }
		}
		echo "</tr></table><br/>";
		echo paging($total,$pageurl,$perpage,$page);
	} else {
		echo "<center>"._NODATA."</center>";
	}
	//-----------------------------//
} else {
	$result_subcat_prd = $db->sql_query("SELECT catid, title FROM {$prefix}_products_cat WHERE parentid='$catid' AND active=1 AND alanguage='$currentlang' ORDER BY weight");
	if($db->sql_numrows($result_subcat_prd) > 0) {
		while(list($subcatid, $subcatname) = $db->sql_fetchrow($result_subcat_prd)) {
			$result_prd_home = $db->sql_query("SELECT id, title, images FROM {$prefix}_products WHERE catid='$subcatid' AND active=1 AND alanguage='$currentlang' ORDER BY time DESC LIMIT $prd_perpage_cathome");
			if($db->sql_numrows($result_prd_home) > 0) {
				echo "<div style=\"color: #df4204; margin-left: 10px; margin-bottom: 8px;\"><b>++ <a href=\"".url_sid("index.php?f=".$module_name."&do=categories&id=".$subcatid."")."\" style=\"color: #df4204;\">$subcatname</a> ++</b></div>";
				echo "<table border=\"0\" cellpadding=\"0\" style=\"margin-left: 20px\">";
				echo "	<tr>";
				$i =0;
				while(list($idprd, $titleprd, $imgprd) = $db->sql_fetchrow($result_prd_home)) {
					if($i < 2) {$xpadding = 25; } else { $xpadding = 0; }
					if(!empty($imgprd) && file_exists("$path_upload/products/$imgprd")) {
						$i ++;
						if(file_exists("".$path_upload."/products/thumb_".$imgprd)) {
							$imgprd = "thumb_".$imgprd;
						}
						echo "<td style=\"padding-right: ".$xpadding."px; padding-bottom: 15px\">";
						echo "<div><a href=\"".url_sid("index.php?f=products&do=detail&id=$idprd")."\" title=\""._DETAIL."\"><img border=\"0\" src=\"$path_upload/products/$imgprd\" width=\"$prd_thumbsize\"></a></div>";
						echo "<div style=\"text-align: center; margin-top: 3px\"><a href=\"".url_sid("index.php?f=products&do=detail&id=$idprd")."\"><b>$titleprd</b></a></div>";
						echo "</td>";
					}
					if($i == 4) { $i =0; echo "</tr>"; }
				}
				echo "</tr></table>";
			}
		}
	} else {
		echo "<center>"._NODATA."</center>";
	}
}

CloseTab();

include_once("footer.php");
?>