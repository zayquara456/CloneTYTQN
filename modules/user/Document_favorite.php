<?php
if (!defined('CMS_SYSTEM')) die();

if (!defined('iS_USER') || !isset($userInfo)) header("Location: ".url_sid("index.php?f=user&do=login")."");

$page_title = "Danh sách tài liệu yêu thích";

$path_upload_attach = "$path_upload/document";//path upload file attach
include_once('header.php');
global $module_name;

OpenTab("Danh sách tài liệu yêu thích");
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
$perpage = 15;
$page = intval(isset($_GET['page']) ? $_GET['page'] : (isset($_POST['page']) ? $_POST['page']:1));
$offset = ($page-1) * $perpage;
$catArr = array();
$cats = $db->sql_query("SELECT catid, title FROM {$prefix}_document_cat");
while (list($cid, $ctitle) = $db->sql_fetchrow()) $catArr[$cid] = $ctitle;

$titleup = isset($_GET["title"]) ? $_GET["title"] : "";
$cat = isset($_GET["cat"]) ? $_GET["cat"] : "";
$from = isset($_GET["from"]) ? $_GET["from"] : "";
$to = isset($_GET["to"]) ? $_GET["to"] : "";

$where="WHERE title !=''";
$vlink="";
if(!empty($titleup))
{
	$titleup2=url_optimization(trim($titleup));
	$where.="AND title LIKE '%$titleup%' OR permalink LIKE '%$titleup2%' ";
	$vlink.="&title=$titleup";
}
if(!empty($cat))
{
	$where.="AND catid=$cat ";
	$vlink.="&cat=$cat";
}
if(!empty($from))
{
	if(preg_match("/^([0-9]{1,2})\-([0-9]{1,2})\-([0-9]{4})$/",$from,$match)){
		$from=mktime(0,0,0,$match[2],$match[1],$match[3]);
	}
	$where.="AND time >= $from ";
	$vlink.="&from=$from";
}
if(!empty($to))
{
	if(preg_match("/^([0-9]{1,2})\-([0-9]{1,2})\-([0-9]{4})$/",$to,$match)){
		$to=mktime(0,0,0,$match[2],$match[1],$match[3]);
	}
	$where.="AND time < $to ";
	$vlink.="&to=$to";
}
$total = $db->sql_numrows($db->sql_query("SELECT id FROM {$prefix}_document $where"));
$result = $db->sql_query("SELECT id, catid, title, time, active, hits, hits_download, nstart, fattach FROM {$prefix}_document $where $sortby LIMIT $offset, $perpage");

if($db->sql_numrows($result) > 0) {

	?>
<div class="toolbar"><div style="text-align:right; padding-right:30px">
<form action="" name="frmtool" method="get">
	<input type="hidden" name="f" value="user" />
    	<input type="hidden" name="do" value="document_list" />
	<input type="text" id="title" value="" name="title" />
	<input type="submit" class="sb_but1" value="Tìm kiếm"  name="subs" />
	
</form>

</div></div><!-- End demo -->

<?php ajaxload_content();
echo "<div id=\"pagecontent\">";
	echo "<div id=\"document_main\"><form action=\"modules.php?f=user&sort=$sort&page=$page\" name=\"frm\" method=\"POST\" onsubmit=\"return checkQuick(this);\">";
	echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" style=\"border:1px solid #E4E8F0\" >\n";
	echo "<tr  style=\"border:1px solid #CCC\">\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"100\">Thời gian</td>\n";
	echo "<td class=\"row1sd\">"._TITLE."</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"50\">Tải</a></td>\n";
	
	echo "</tr>\n";
	$i = 0;
	if($page > 1) { $a = $perpage * $page - $perpage + 1;}
	while(list($id, $catid, $title, $time, $active, $hits, $hits_download, $nstart, $fattach) = $db->sql_fetchrow($result)) {
		//if (($i % 8) == 1) $css = "row1";
		//else $css ="row3";
		$css ="row1";
		echo "<tr >\n";
		echo "<td align=\"center\" class=\"row1\">".ext_time($time, 1)."</td>\n";
		echo "<td class=\"row1\"><b><a href=\"$urlsite/index.php?f=user&do=noibo_detail&id=$id\">$title</a></b><br>Chủ đề: <a href=\"#\">{$catArr[$catid]}</a></td>\n";
		echo "<td class=\"row3\"><b></b></td>\n";
		
		echo "</tr>\n";
		$i++;
	}
 	
	echo "<tr><td colspan=\"10\"><div class=\"fr\">";
	if($total > $perpage) {
		$pageurl = "index.php?f=user&do=document_list&sort=$sort";
		echo paging($total,$pageurl,$perpage,$page);
	}
		echo "</div>";
	echo "</td></tr>";
	echo "</table></form></div></div>";
} else {
	echo "<div  class=\"content\" align=\"center\">";
	echo "<center>"._DOCUMENT_NO_POST."</center>";
	echo "<META HTTP-EQUIV=\"refresh\" content=\"5;URL=index.php?f=user&do=document_list\">";
	echo "</div>";
}
CloseTab();
include_once('footer.php');
?>