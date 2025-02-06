<?php
if (!defined('CMS_SYSTEM')) die();

if (!defined('iS_USER') || !isset($userInfo)) header("Location: ".url_sid("index.php?f=user&do=login")."");

$page_title = "Danh sách tài liệu đã tải";

$path_upload_attach = "$path_upload/document";//path upload file attach
include_once('header.php');
global $module_name;

OpenTab($page_title);
$sort = intval(isset($_GET['sort']) ? $_GET['sort'] : (isset($_POST['sort']) ? $_POST['sort']:0));
switch($sort) {
	case 1: $sortby ="ORDER BY id ASC"; break;
	case 2: $sortby ="ORDER BY id DESC"; break;
	case 3: $sortby ="ORDER BY dateline ASC"; break;
	case 4: $sortby ="ORDER BY dateline DESC"; break;
	case 5: $sortby ="ORDER BY money ASC"; break;
	case 6: $sortby ="ORDER BY money DESC"; break;
	default: $sortby ="ORDER BY dateline DESC"; break;
}
$perpage = 15;
$page = intval(isset($_GET['page']) ? $_GET['page'] : (isset($_POST['page']) ? $_POST['page']:1));
$offset = ($page-1) * $perpage;

$titleup = isset($_GET["action"]) ? $_GET["action"] : "";
$from = isset($_GET["from"]) ? $_GET["from"] : "";
$to = isset($_GET["to"]) ? $_GET["to"] : "";

$where="WHERE user_id=".$userInfo['id']." ";
$vlink="";
if(!empty($titleup))
{
	$titleup2=url_optimization(trim($titleup));
	$where.="AND action LIKE '%$titleup%' ";
	$vlink.="&action=$titleup";
}

if(!empty($from))
{
	if(preg_match("/^([0-9]{1,2})\-([0-9]{1,2})\-([0-9]{4})$/",$from,$match)){
		$from=mktime(0,0,0,$match[2],$match[1],$match[3]);
	}
	$where.="AND dateline >= $from ";
	$vlink.="&from=$from";
}
if(!empty($to))
{
	if(preg_match("/^([0-9]{1,2})\-([0-9]{1,2})\-([0-9]{4})$/",$to,$match)){
		$to=mktime(0,0,0,$match[2],$match[1],$match[3]);
	}
	$where.="AND dateline < $to ";
	$vlink.="&to=$to";
}
$total = $db->sql_numrows($db->sql_query("SELECT id FROM {$prefix}_user_log $where"));
$result = $db->sql_query("SELECT id, dateline, area, title, action, status, money, ip_add FROM {$prefix}_user_log $where AND id>0 $sortby LIMIT $offset, $perpage");

if($db->sql_numrows($result) > 0) {

	?>
<div class="toolbar"><div style="text-align:right; padding-right:30px">
<form action="" name="frmtool" method="get">
	<input type="hidden" name="f" value="user" />
    	<input type="hidden" name="do" value="document_downloaded" />
	<input type="text" id="action" value="" name="action" />
	<input type="submit" class="sb_but1" value="Tìm kiếm"  name="subs" />
	
</form>

</div></div><!-- End demo -->

<?php ajaxload_content();
echo "<div id=\"pagecontent\">";
	echo "<div id=\"document_main\"><form action=\"modules.php?f=user&sort=$sort&page=$page\" name=\"frm\" method=\"POST\" onsubmit=\"return checkQuick(this);\">";
	echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" style=\"border:1px solid #E4E8F0\" >\n";
	echo "<tr  style=\"border:1px solid #CCC\">\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"100\">Thời gian</td>\n";
	echo "<td class=\"row1sd\">Nội dung</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"100\">ePoint (EP)</a></td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"100\">IP</a></td>\n";
	echo "</tr>\n";
	$i = 0;
	if($page > 1) { $a = $perpage * $page - $perpage + 1;}
	while(list($id, $dateline, $area, $title, $action, $status, $money, $ip_add) = $db->sql_fetchrow($result)) {
		//if (($i % 8) == 1) $css = "row1";
		//else $css ="row3";
		$css ="row1";
		echo "<tr >\n";
		echo "<td align=\"center\" class=\"row1\">".ext_time($dateline, 1)."</td>\n";
		echo "<td class=\"row1\">$action</td>\n";
		echo "<td class=\"row3\" align=\"right\">$money</td>\n";
		echo "<td class=\"row3\" align=\"right\">$ip_add</td>\n";
		echo "</tr>\n";
		$i++;
	}
	echo "<tr><td colspan=\"10\"><div class=\"fr\">";
	if($total > $perpage) {
		$pageurl = "index.php?f=user&do=document_downloaded&sort=$sort";
		echo paging($total,$pageurl,$perpage,$page);
	}
		echo "</div>";
	echo "</td></tr>";
	echo "</table></form></div></div>";
} else {
	echo "<div  class=\"content\" align=\"center\">";
	echo "<center>"._DOCUMENT_NO_POST."</center>";
	//echo "<META HTTP-EQUIV=\"refresh\" content=\"5;URL=index.php?f=user&do=document_list\">";
	echo "</div>";
}
CloseTab();
include_once('footer.php');
?>