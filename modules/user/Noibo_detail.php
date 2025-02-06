<?php
if (!defined('CMS_SYSTEM')) die();

if (!defined('iS_USER') || !isset($userInfo)) header("Location: ".url_sid("index.php?f=user&do=login")."");

$page_title = "Thông tin nội bộ";

$path_upload_attach = "$path_upload/noibo";//path upload file attach
include_once('header.php');
include_once("Menu.php");
function fixweight_cat() {
	global $db, $prefix, $currentlang;
    $result = $db->sql_query("SELECT catid, weight FROM ".$prefix."_noibo_cat WHERE alanguage='$currentlang' order by weight ASC");
    $weight = 0;
    while($row = $db->sql_fetchrow($result)) {
	    $catid = $row['catid'];
		$weight++;
	    $catid = intval($catid);
		$db->sql_query("UPDATE ".$prefix."_noibo_cat SET weight='$weight' WHERE catid='$catid'");
    }
}

function fixcount_cat() {
	global $prefix, $db;
	 $result = $db->sql_query("SELECT catid, counts FROM ".$prefix."_noibo_cat");
	 $i =0;
	 while (list($catid, $counts) = $db->sql_fetchrow($result)) {
	 	$numsnew = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_noibo WHERE catid=$catid"));
	 	if($counts != $numsnew) {
	 		$db->sql_query("UPDATE ".$prefix."_noibo_cat SET counts=$numsnew WHERE catid=$catid");
	 	}
	 	$i++;
	 }
}

function subcat($catid, $text="", $catcheck="", $catseld="") {
	global $db, $prefix;
	$treeTemp ="";
	$result = $db->sql_query("SELECT catid, title FROM ".$prefix."_noibo_cat WHERE parent='$catid' AND catid!='$catseld'");
	if($db->sql_numrows($result) > 0 ) {
		$text = "$text--";
		while(list($cat_id, $title2) = $db->sql_fetchrow($result)) {
			if($catcheck) {
				if($cat_id == $catcheck) {
					$seld = " selected";
				}else{
					$seld ="";
				}	
			}
			$treeTemp .= "<option value=\"$cat_id\"$seld>$text-- $title2</option>";
			$treeTemp .= subcat($cat_id,$text, $catcheck, $catseld);
		}	
	}
	return $treeTemp;	
}
function catname_byparent($catid) {
	global $db, $prefix;
	$catname ="";
	$result = $db->sql_query("SELECT title FROM ".$prefix."_noibo_cat WHERE catid IN (SELECT parent FROM ".$prefix."_noibo_cat WHERE catid='$catid')");
	if($db->sql_numrows($result) > 0 ) {
		list($title) = $db->sql_fetchrow($result); 
			$catname .= $title." > ";
	}
	return $catname;	
}
function catname($catid) {
	global $db, $prefix;
	$catname ="";
	$result = $db->sql_query("SELECT title FROM ".$prefix."_noibo_cat WHERE catid='$catid'");
	if($db->sql_numrows($result) > 0 ) {
		list($title) = $db->sql_fetchrow($result); 
			$catname .= $title;
	}
	return $catname;	
}
function fixweight_newstab() {
	global $db, $prefix, $currentlang;
    $result = $db->sql_query("SELECT tabid, weight FROM ".$prefix."_noibo_tab WHERE alanguage='$currentlang' order by weight ASC");
    $weight = 0;
    while($row = $db->sql_fetchrow($result)) {
	    $tabid = $row['tabid'];
		$weight++;
	    $tabid = intval($tabid);
		$db->sql_query("UPDATE ".$prefix."_noibo_tab SET weight='$weight' WHERE tabid='$tabid'");
    }
}
global $module_name;
OpenTab("Thông tin nội bộ");
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$result = $db->sql_query("SELECT id, catid, title, hometext, time, active, hits, nstart, fattach FROM {$prefix}_noibo where id=$id");
if($db->sql_numrows($result) > 0) {
list($id, $catid, $title, $hometext, $time, $active, $hits, $nstart, $fattach) = $db->sql_fetchrow($result)


	?>
	<style type="text/css">
.row1 {
    border-color: #FFFFFF #E4E8F0 #E4E8F0 #FFFFFF;
    border-style: solid;
    border-width: 1px;
}
.row3 {
    border-color: #FFFFFF #FFFFFF #E4E8F0;
    border-style: solid;
    border-width: 1px;
}
.row1sd {
    background-color: #F2F2F2;
    background-image: -moz-linear-gradient(center top , #F2F2F2, #FAFAFA);
    background-repeat: repeat-x;
    border-color: #FFFFFF #E4E8F0 #E4E8F0 #FFFFFF;
    border-style: solid;
    border-width: 1px;
    color: #004968;
    font-weight: bold;
}
</style>
<?php ajaxload_content();

echo "<div id=\"pagecontent\">";
	echo "<div id=\"{$adm_modname}_main\"><form action=\"modules.php?f=$adm_modname&sort=$sort&page=$page\" name=\"frm\" method=\"POST\" onsubmit=\"return checkQuick(this);\">";
	echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" style=\"border:1px solid #E4E8F0\" >\n";
	echo "<tr  style=\"border:1px solid #CCC\">\n";
	echo "<td class=\"row1sd\" width=\"100\">Thời gian</td>\n";
	echo "<td class=\"row1sd\">".ext_time($time, 1)."</td>\n";
	echo "</tr><tr >\n";
	echo "<td class=\"row1\" width=\"100\">Chủ đề</a></td>\n";
	echo "<td class=\"row1\"><b>".catname($catid)."</b></td>\n";
	echo "</tr><tr >\n";
	echo "<td class=\"row1\">"._TITLE."</td>\n";
	echo "<td class=\"row1\"><b>$title</b></td>\n";
	echo "</tr><tr >\n";
	echo "<td class=\"row1\">"._CONTENT."</td>\n";
	echo "<td class=\"row1\"><b>$hometext</b></td>\n";
	echo "</tr><tr >\n";
	echo "<td class=\"row1\">"._DOWNLOAD."</td>\n";
	echo "<td class=\"row1\"><b><a href=\"$path_upload_attach/$fattach\">"._DOWNLOAD."</a></b></td>\n";
	echo "</tr>\n";
		$css ="row1";
		echo "</tr>\n";
	echo "<div class=\"fr\">";
		echo "</div>";
	echo "</td></tr>";
	echo "</table></form></div></div>";
}
CloseTab();
include_once('footer.php');
?>