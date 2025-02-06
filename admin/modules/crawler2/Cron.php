<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

include_once("page_header.php");
//luu dia chi truy cap
if(empty($_SESSION['linkpage']))
	$_SESSION['linkpage']="".$_SERVER['QUERY_STRING']."";
$sort = intval(isset($_GET['sort']) ? $_GET['sort'] : (isset($_POST['sort']) ? $_POST['sort']:0));
switch($sort) {
	case 1: $sortby ="ORDER BY cron_name ASC"; break;
	case 2: $sortby ="ORDER BY cron_name DESC"; break;
	case 3: $sortby ="ORDER BY cdate ASC"; break;
	case 4: $sortby ="ORDER BY cdate DESC"; break;
	case 5: $sortby ="ORDER BY published ASC"; break;
	case 6: $sortby ="ORDER BY published DESC"; break;
	default: $sortby ="ORDER BY cdate DESC"; break;
}
$perpage = 10;
$page = intval(isset($_GET['page']) ? $_GET['page'] : (isset($_POST['page']) ? $_POST['page']:1));
$offset = ($page-1) * $perpage;

$titleup = isset($_GET["title"]) ? $_GET["title"] : "";
$from = isset($_GET["from"]) ? $_GET["from"] : "";
$to = isset($_GET["to"]) ? $_GET["to"] : "";

$where="where alanguage='$currentlang' ";
$vlink="";
if(!empty($titleup))
{
	$titleup2=url_optimization(trim($titleup));
	$where.="AND cron_name LIKE '%$titleup%'";
	$vlink.="&title=$titleup";
}
if(!empty($from))
{
	if(preg_match("/^([0-9]{1,2})\-([0-9]{1,2})\-([0-9]{4})$/",$from,$match)){
		$from=mktime(0,0,0,$match[2],$match[1],$match[3]);
	}
	$where.="AND cdate >= $from ";
	$vlink.="&from=$from";
}
if(!empty($to))
{
	if(preg_match("/^([0-9]{1,2})\-([0-9]{1,2})\-([0-9]{4})$/",$to,$match)){
		$to=mktime(0,0,0,$match[2],$match[1],$match[3]);
	}
	$where.="AND cdate < $to ";
	$vlink.="&to=$to";
}

$total = $db->sql_numrows($db->sql_query("SELECT id FROM {$prefix}_ngrab_filter $where"));
$result = $db->sql_query("SELECT id, cron_name, cron_url, cat_id, cdate, published FROM {$prefix}_ngrab_cron $where $sortby LIMIT $offset, $perpage");
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
	<input type="hidden" name="f" value="news" />
	<label for="action">Tiêu đề</label>
	<input type="text" id="title" value="" name="title" />
	<label for="from">From</label>
	<input type="text" id="from" name="from"/>
	<label for="to">to</label>
	<input type="text" id="to" name="to"/>
	<input type="submit" class="button2" value="<?php echo _SEARCH?>"  name="subs" />&nbsp;&nbsp;
	<input class="button2" type="button" onclick="window.location='modules.php?f=crawler&do=cron_create'" value="<?php echo _CREATE_CRON?>">&nbsp;&nbsp;
    <input class="button2" type="button" onclick="window.location='modules.php?f=crawler&do=cron'" value="<?php echo _LIST_CRON?>">&nbsp;&nbsp;
    <input class="button2" type="button" onclick="window.location='modules.php?f=crawler&do=log'" value="<?php echo _LOG_FILTER?>">&nbsp;&nbsp;
	<input class="button2" type="button" onclick="window.location='modules.php?f=crawler&do=config'" value="<?php echo _CONFIG?>">
    &nbsp;&nbsp;
</form>
</div></div>

<?php ajaxload_content();
$msg = isset($_GET['msg']) ? $_GET['msg'] : "";
	if($msg!="")
	{
		mgs_show($msg,_MODTITLE);
	}
echo "<div id=\"pagecontent\">";
	echo "<div id=\"{$adm_modname}_main\"><form action=\"modules.php?f=$adm_modname&sort=$sort&page=$page\" name=\"frm\" method=\"POST\" onsubmit=\"return checkQuick(this);\">";
	echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
	echo "<tr><td colspan=\"10\" class=\"header\">"._LIST_CRON."</td></tr>";
	echo "<tr>\n";
	echo "<td class=\"row1sd\" width=\"10\"><input type=\"checkbox\" name=\"checkall\" onclick=\"javascript:check_uncheck();\"></td>\n";
	echo "<td class=\"row1sd\">"._TITLE." <a href=\"?f=".$adm_modname."&sort=1\" info=\""._SORTUP."\"><img border=\"0\" src=\"images/sup.gif\" align=\"absmiddle\"></a> <a href=\"?f=".$adm_modname."&sort=2\" info=\""._SORTDOWN."\"><img border=\"0\" src=\"images/sdown.gif\" align=\"absmiddle\"></a></td>\n";
	echo "<td class=\"row1sd\">"._SOURCE." </td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"100\">"._TIMEUP." <a href=\"?f=".$adm_modname."&sort=3\" info=\""._SORTUP."\"><img border=\"0\" src=\"images/sup.gif\" align=\"absmiddle\"></a> <a href=\"?f=".$adm_modname."&sort=4\" info=\""._SORTDOWN."\"><img border=\"0\" src=\"images/sdown.gif\" align=\"absmiddle\"></a></td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"90\">"._STATUS." <a href=\"?f=".$adm_modname."&sort=5\" info=\""._SORTUP."\"><img border=\"0\" src=\"images/sup.gif\" align=\"absmiddle\"></a> <a href=\"?f=".$adm_modname."&sort=6\" info=\""._SORTDOWN."\"><img border=\"0\" src=\"images/sdown.gif\" align=\"absmiddle\"></a></td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"60\">"._LAY_TIN."</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"30\">"._EDIT."</td>\n";
	echo "<td class=\"row3sd\" align=\"center\" width=\"30\">"._DELETE."</td>\n";
	echo "</tr>\n";
	$i = 0;
	if($page > 1) { $a = $perpage * $page - $perpage + 1;}
	while(list($id, $title, $source, $data, $time, $status) = $db->sql_fetchrow($result)) {
		//if (($i % 8) == 1) $css = "row1";
		//else $css ="row3";
		$css ="row1";
		
		if($ajax_active == 1) {
			switch($status) {
				case 1: $status = "<a href=\"?f=".$adm_modname."&do=cron_status&id=$id&stat=0\" title=\""._DEACTIVATE."\" onclick=\" aj_base_status($id,'0','$adm_modname','status_news',mid); return false;\"><img border=\"0\" src=\"images/view.png\"></a>"; break;
				case 0: $status = "<a href=\"?f=".$adm_modname."&do=cron_status&id=$id&stat=1\" title=\""._ACTIVE."\" onclick=\" aj_base_status($id,'1','$adm_modname','status_news',mid); return false;\"><img border=\"0\" src=\"images/viewo.png\"></a>"; break;
			}
		} else {
			switch($status) {
				case 1: $status = "<a href=\"?f=".$adm_modname."&do=cron_status&id=$id&stat=0\" info=\""._DEACTIVATE."\"><img border=\"0\" src=\"images/view.png\"></a>"; break;
				case 0: $status = "<a href=\"?f=".$adm_modname."&do=cron_status&id=$id&stat=1\" info=\""._ACTIVE."\"><img border=\"0\" src=\"images/viewo.png\"></a>"; break;
			}
		}

		echo "<tr>\n";
		echo "<td class=\"$css\"><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td><td class=\"$css\"><b>$title</b></td>\n";
		echo "<td class=\"$css\"><b><a href=\"".$source."\" info=\""._VIEW."\" target=\"_blank\">$source</a></b></td>\n";
		echo "<td align=\"center\" class=\"$css\">".ext_time($time, 2)."</td>\n";
		echo "<td align=\"center\" class=\"$css\">$status</td>\n";
		echo "<td align=\"center\" class=\"$css\"><a class=\"fancybox fancybox.iframe\" href=\"?f=".$adm_modname."&do=cron_play&id=$id\" title=\""._LAY_TIN."\">"._LAY_TIN."</a></td>\n";
		echo "<td align=\"center\" width=\"30\" class=\"$css\"><a href=\"?f=".$adm_modname."&do=cron_edit&id=$id\" info=\""._EDIT."\"><img border=\"0\" src=\"images/edit.png\"></a></td>\n";
		if($ajax_active == 1) {
			echo "<td align=\"center\" width=\"30\" class=\"row3\"><a href=\"?f=".$adm_modname."&do=cron_delete&id=$id\" title=\""._DELETE."\" onclick=\"aj_base_delete($id,'$adm_modname','"._DELETEASK1."','delete_news','');\"><img border=\"0\" src=\"images/delete.png\"></td>\n";
		} else {
			echo "<td align=\"center\" width=\"30\" class=\"row3\"><a href=\"?f=".$adm_modname."&do=delete_news&type=$newsType&id=$id\" info=\""._DELETE."\" onclick=\"return confirm('"._DELETEASK1."');\"><img border=\"0\" src=\"images/delete.png\"></td>\n";
		}
		echo "</tr>\n";
		$i++;
	}
 	
	echo "<input type=\"hidden\" name=\"do\" value=\"quick_do\">";
	echo "<tr><td colspan=\"10\" class=\"row4\"><div class=\"fl\"><select name=\"fc\">";
	echo "<option value=\"\">&raquo; "._QUICKDO."</option>";
	echo "<option value=\"1\">&raquo; "._QUICKDO_1."</option>";
	echo "<option value=\"2\">&raquo; "._QUICKDO_2."</option>";
	echo "<option value=\"3\">&raquo; "._QUICKDO_3."</option>";
	echo "</select>&nbsp;<input type=\"submit\" class=\"button2\" name=\"submit\" value=\""._DOACTION."\"></div>";
	echo "<div class=\"fr\">";
	if($total > $perpage) {
		$pageurl = "modules.php?f=news&sort=$sort";
		echo paging($total,$pageurl,$perpage,$page);
	}
		echo "</div>";
	echo "</td></tr>";
	echo "</table></form></div></div>";
} else {
	 mgs_show("",_NONEWSPOST);
	echo "<META HTTP-EQUIV=\"refresh\" content=\"5;URL=modules.php?f=".$adm_modname."&do=cron_create\">";
}

include_once("page_footer.php");
?>