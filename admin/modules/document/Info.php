<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");
global $url_site;
//luu dia chi truy cap
if(empty($_SESSION['linkpage']))
	$_SESSION['linkpage']="".$_SERVER['QUERY_STRING']."";
include_once("page_header.php");
$sort = intval(isset($_GET['sort']) ? $_GET['sort'] : (isset($_POST['sort']) ? $_POST['sort']:0));
switch($sort) {
	case 1: $sortby ="ORDER BY id ASC"; break;
	case 2: $sortby ="ORDER BY id DESC"; break;
	case 3: $sortby ="ORDER BY time ASC"; break;
	case 4: $sortby ="ORDER BY time DESC"; break;
	case 5: $sortby ="ORDER BY docid ASC"; break;
	case 6: $sortby ="ORDER BY docid DESC"; break;
	default: $sortby ="ORDER BY id DESC"; break;
}

$titleup = isset($_GET["title"]) ? $_GET["title"] : "";
$from = isset($_GET["from"]) ? $_GET["from"] : "";
$to = isset($_GET["to"]) ? $_GET["to"] : "";
$s_quantity=isset($_GET["s_quantity"]) ? $_GET["s_quantity"] : 20;
$status=isset($_GET["status"]) ? $_GET["status"] : 0;
$where="WHERE id>0 ";
$vlink="";
$perpage = 15;
$page = intval(isset($_GET['page']) ? $_GET['page'] : (isset($_POST['page']) ? $_POST['page']:1));
$offset = ($page-1) * $s_quantity;
if(!empty($titleup))
{
	$titleup2=url_optimization(trim($titleup));
	$where.="AND title LIKE '%$titleup%'";
	$vlink.="&title=$titleup";
}
//if(!empty($cat))
//{
//	$where.="AND catid=$cat ";
//	$vlink.="&cat=$cat";
//}
//if(!empty($user))
//{
//	$user=trim($user);
//	$where.="AND user_id IN (SELECT id FROM {$prefix}_user WHERE fullname='$user')";
//	$vlink.="&user=$user";
//}
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

$total = $db->sql_numrows($db->sql_query("SELECT id FROM {$prefix}_link_report $where"));
$result = $db->sql_query("SELECT id, docid, time, name, email, url, url_replace, title, content, status FROM {$prefix}_link_report $where $sortby LIMIT 9");
if($db->sql_numrows($result) > 0) {
?>
<?php ajaxload_content();
echo "<div id=\"pagehome\">";
	echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
	echo "<tr><td colspan=\"15\" class=\"header\">Danh sách tài liệu báo lỗi</td></tr>";
	echo "<tr>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"15\">ID</td>\n";
	echo "<td class=\"row1sd\">"._TITLE."</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"60\">"._STATUS."</td>\n";
	echo "<td class=\"row3sd\" align=\"center\" width=\"30\">"._DELETE."</td>\n";
	echo "</tr>\n";
	$i = 0;
	if($page > 1) { $a = $s_quantity * $page - $s_quantity + 1;}
	while(list($id, $docid, $time, $name, $email, $url, $url_replace, $title, $content, $status) = $db->sql_fetchrow($result)) {
		$css ="row1";
			switch($status) {
				case 0: $status = "<a href=\"modules.php?f=document&do=views_link&id=$id\" info=\"chưa xử lý\"><i class=\"fa fa-minus-circle fa-lg\"></i></a>"; break;
				case 1: $status = "<a href=\"modules.php?f=document&do=views_link&id=$id\" info=\"đang xử lý\"><i class=\"fa fa-ellipsis-h\"></i></a>"; break;
				case 2: $status = "<a href=\"modules.php?f=document&do=views_link&id=$id\" info=\"đã xử lý\"><i class=\"fa fa-check-circle fa-lg fa-green\"></i></a>"; break;
			}
		
		echo "<tr>\n";
		?>
<div id="url_replace<?php echo $id?>" style="width:450px;display: none;">
	<iframe width="100%" src="modules.php?f=document&do=url_replace&id=<?php echo $id?>"></iframe>
</div>
<?php
		echo "<td align=\"center\" class=\"$css\">$id</td>\n";
		echo "<td class=\"$css\"><b>$title</b></td>\n";
		echo "<td align=\"center\" class=\"$css\">$status</td>\n";
		echo "<td align=\"center\" width=\"30\" class=\"row3\"><a href=\"?f=".$adm_modname."&do=delete_link&id=$id\" info=\""._DELETE."\" onclick=\"return confirm('"._DELETEASK1."');\"><i class=\"fa fa-trash-o fa-lg\"></i></td>\n";
		echo "</tr>\n";
		$i++;
		$checkfile="";
	}
	echo "<tr><td colspan=\"15\" class=\"row4\">";
	//echo "<div class=\"fr\">";
	//if($total > $s_quantity) {
	//	$pageurl = "modules.php?f=".$adm_modname."&sort=$sort&title=$titleup&cat=$cat&user=$user&from=$from&to=$to&active=$s_active&s_quantity=$s_quantity&subs=Tìm+kiếm";
	//	echo paging($total,$pageurl,$s_quantity,$page);
	//}
	//	echo "</div>";
	echo "</td></tr>";
	echo "</table></div>";
} else {
	//OpenDiv();
	//echo "<div class=\"info\">"._NONEWSPOST."</div>";
	//echo "<META HTTP-EQUIV=\"refresh\" content=\"5;URL=modules.php?f=".$adm_modname."&do=create\">";
	//CLoseDiv();
}

include_once("page_footer.php");
?>
