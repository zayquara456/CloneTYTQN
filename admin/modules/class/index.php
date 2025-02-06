<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

include_once("page_header.php");
$path_upload = "$path_upload/$adm_modname";
//luu dia chi truy cap
if(empty($_SESSION['linkpage']))
	$_SESSION['linkpage']="".$_SERVER['QUERY_STRING']."";
$sort = intval(isset($_GET['sort']) ? $_GET['sort'] : (isset($_POST['sort']) ? $_POST['sort']:0));
switch($sort) {
	case 1: $sortby ="ORDER BY parentid ASC"; break;
	case 2: $sortby ="ORDER BY parentid DESC"; break;
	case 3: $sortby ="ORDER BY time ASC"; break;
	case 4: $sortby ="ORDER BY time DESC"; break;
	default: $sortby ="ORDER BY id DESC"; break;
}

$titleup = isset($_GET["title"]) ? $_GET["title"] : "";
$from = isset($_GET["from"]) ? $_GET["from"] : "";
$to = isset($_GET["to"]) ? $_GET["to"] : "";
$s_quantity=isset($_GET["s_quantity"]) ? $_GET["s_quantity"] : 20;
//$s_active=isset($_GET["active"]) ? $_GET["active"] : 1;
$where="where id>0 ";
$vlink="";
$perpage = 15;
$page = intval(isset($_GET['page']) ? $_GET['page'] : (isset($_POST['page']) ? $_POST['page']:1));
$offset = ($page-1) * $s_quantity;
if(!empty($titleup))
{
	$titleup2=url_optimization(trim($titleup));
	$where.="AND title LIKE '%$titleup%' OR permalink LIKE '%$titleup2%' ";
	$vlink.="&title=$titleup";
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
//if($s_active==0)
//{
//	$where.="AND status=$s_active ";
//	$vlink.="&status=$s_active";
//}
//elseif($s_active==1)
//{	
//	$where.="AND status=$s_active ";
//	$vlink.="&status=$s_active";
//}
list ($permission) = $db->sql_fetchrow($db->sql_query("SELECT permission FROM ".$prefix."_admin WHERE adacc='$admin_ar[0]'"));
if($permission!=2){
	$where .= " AND active=0 AND uadmin=(SELECT id FROM ".$prefix."_admin WHERE adacc='$admin_ar[0]')";
}
//echo "<br><br>$admin_ar[0]";
$total = $db->sql_numrows($db->sql_query("SELECT id FROM {$prefix}_class $where"));
$result = $db->sql_query("SELECT id, parentid, title, time, status, images FROM {$prefix}_class $where $sortby LIMIT $offset, $s_quantity");

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
	$(function() {
		$( "#from" ).datepicker({
			defaultDate: "+1w",
			changeMonth: true,
			numberOfMonths: 1,
			dateFormat: "dd-mm-yy",
			onSelect: function( selectedDate ) {
				$( "#to" ).datepicker( "option", "minDate", selectedDate );
				
			}
		});
		$( "#to" ).datepicker({
			defaultDate: "+1w",
			changeMonth: true,
			numberOfMonths: 1,
			dateFormat: "dd-mm-yy",
			onSelect: function( selectedDate ) {
				$( "#from" ).datepicker( "option", "maxDate", selectedDate );
			}
		});
	});
	</script>
<div class="toolbar"><div>
<form action="" name="frmtool" method="get">
	<input type="hidden" name="f" value="class" />
	<label for="action">Tiêu đề</label>
	<input type="text" id="title" value="" name="title" />
	<label for="from">From</label>
	<input type="text" id="from" name="from"/>
	<label for="to">to</label>
	<input type="text" id="to" name="to"/>
	<?php if($permission==2){?>
	<select id="active" name="active">
	<option value="1">Đã kích hoạt</option>
	<option value="0">Chưa kích hoạt</option>
	</select>
	<?php }?>
	<label for="action">Số lượng</label>
	<input type="text" id="s_quantity" value="20" style="width: 40px" name="s_quantity" />
	<input type="submit" class="button2" value="Tìm kiếm"  name="subs" />
	<a class="button2"  href="modules.php?f=<?php echo $adm_modname?>&do=create">Lớp học mới</a>
	
</form>
</div></div><!-- End demo -->

<?php ajaxload_content();
echo "<div id=\"pagecontent\">";
	echo "<div id=\"{$adm_modname}_main\"><form action=\"modules.php?f=$adm_modname&sort=$sort&page=$page\" name=\"frm\" method=\"POST\" onsubmit=\"return checkQuick(this);\">";
	echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
	echo "<tr><td colspan=\"15\" class=\"header\">Các lớp học đã đưa lên</td></tr>";
	echo "<tr>\n";
	echo "<td class=\"row1sd\" width=\"10\"><input type=\"checkbox\" name=\"checkall\" onclick=\"javascript:check_uncheck();\"></td>\n";
	echo "<td class=\"row1sd\">Tiêu đề</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"100\">"._TIMEUP." <a href=\"?f=".$adm_modname."&sort=3\" info=\""._SORTUP."\"><img border=\"0\" src=\"images/sup.gif\" align=\"absmiddle\"></a> <a href=\"?f=".$adm_modname."&sort=4\" info=\""._SORTDOWN."\"><img border=\"0\" src=\"images/sdown.gif\" align=\"absmiddle\"></a></td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"60\">"._STATUS."</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"30\">"._EDIT."</td>\n";
	echo "<td class=\"row3sd\" align=\"center\" width=\"30\">"._DELETE."</td>\n";
	echo "</tr>\n";
	$i = 0;
	if($page > 1) { $a = $s_quantity * $page - $s_quantity + 1;}
	while(list($id,$parentid, $title, $time, $active) = $db->sql_fetchrow($result)) {
		$css ="row1";
		if($ajax_active == 1) {
			if($permission==2){
			switch($active) {
				case 1: $active = "<a href=\"?f=".$adm_modname."&do=status&id=$id&stat=0\" title=\""._DEACTIVATE."\" onclick=\" aj_base_status($id,'0','$adm_modname','status_news',mid); return false;\"><img border=\"0\" src=\"images/view.png\"></a>"; break;
				case 0: $active = "<a href=\"?f=".$adm_modname."&do=status&id=$id&stat=1\" title=\""._ACTIVE."\" onclick=\" aj_base_status($id,'1','$adm_modname','status_news',mid); return false;\"><img border=\"0\" src=\"images/viewo.png\"></a>"; break;
			}
			}
			else{
				switch($active) {
				case 1: $active = "<img border=\"0\" src=\"images/view.png\">"; break;
				case 0: $active = "<img border=\"0\" src=\"images/viewo.png\">"; break;
			}
			}
			
		} else {
			if($permission==2){
				switch($active) {
				case 1: $active = "<a href=\"?f=".$adm_modname."&do=status&id=$id&stat=0\" info=\""._DEACTIVATE."\"><img border=\"0\" src=\"images/view.png\"></a>"; break;
				case 0: $active = "<a href=\"?f=".$adm_modname."&do=status&id=$id&stat=1\" info=\""._ACTIVE."\"><img border=\"0\" src=\"images/viewo.png\"></a>"; break;
			}
			}
			else{
				switch($active) {
				case 1: $active = "<img border=\"0\" src=\"images/view.png\">"; break;
				case 0: $active = "<img border=\"0\" src=\"images/viewo.png\">"; break;
			}
			}
		}

		echo "<tr>\n";
		echo "<td class=\"$css\"><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td>";
		echo "<td class=\"$css\"><b>$title</b></td>\n";
		echo "<td align=\"center\" class=\"$css\">".ext_time($time, 2)."</td>\n";
		echo "<td align=\"center\" class=\"$css\">$active</td>\n";
		echo "<td align=\"center\" width=\"30\" class=\"$css\"><a href=\"?f=".$adm_modname."&do=edit&id=$id\" info=\""._EDIT."\"><img border=\"0\" src=\"images/edit.png\"></a></td>\n";
		if($ajax_active == 1) {
			echo "<td align=\"center\" width=\"30\" class=\"row3\"><a href=\"?f=".$adm_modname."&do=delete&id=$id\" title=\""._DELETE."\" onclick=\"aj_base_delete($id,'$adm_modname','"._DELETEASK1."','delete','');\"><img border=\"0\" src=\"images/delete.png\"></td>\n";
		} else {
			echo "<td align=\"center\" width=\"30\" class=\"row3\"><a href=\"?f=".$adm_modname."&do=delete&id=$id\" info=\""._DELETE."\" onclick=\"return confirm('"._DELETEASK1."');\"><img border=\"0\" src=\"images/delete.png\"></td>\n";
		}
		echo "</tr>\n";
		$i++;
		$checkfile="";
	}
 	
	echo "<input type=\"hidden\" name=\"do\" value=\"quick_do\">";
	echo "<tr><td colspan=\"15\" class=\"row4\"><div class=\"fl\"><select name=\"fc\">";
	echo "<option value=\"\">&raquo; "._QUICKDO."</option>";
	echo "<option value=\"1\">&raquo; "._QUICKDO_1."</option>";
	echo "<option value=\"2\">&raquo; "._QUICKDO_2."</option>";
	echo "<option value=\"3\">&raquo; "._QUICKDO_3."</option>";
	echo "</div>";
	echo "<div class=\"fr\">";
	if($total > $s_quantity) {
		$pageurl = "modules.php?f=".$adm_modname."&sort=$sort&title=$titleup&from=$from&to=$to&active=$s_active&s_quantity=$s_quantity&subs=Tìm+kiếm";
		echo paging($total,$pageurl,$s_quantity,$page);
	}
		echo "</div>";
	echo "</td></tr>";
	echo "</table></form></div></div>";
} else {
	//OpenDiv();
	echo "<div class=\"info\">Chưa có lớp học nào!</div>";
	echo "<META HTTP-EQUIV=\"refresh\" content=\"5;URL=modules.php?f=".$adm_modname."&do=create\">";
	//CLoseDiv();
}

include_once("page_footer.php");
?>