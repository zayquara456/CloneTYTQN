<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

include_once("page_header.php");
$path_upload = "$path_upload/$adm_modname";
//luu dia chi truy cap
if(empty($_SESSION['linkpage']))
	$_SESSION['linkpage']="".$_SERVER['QUERY_STRING']."";
	
//updatelink code
$ucode=$_GET['ucode'];
if($ucode=='ok'){
	$result = $db->sql_query("SELECT id FROM {$prefix}_document");
if($db->sql_numrows($result) > 0) 
{
	$i=1;
	while(list($upcodeid) = $db->sql_fetchrow($result)) 
	{
		$code_new = md5(generate_code(6).'-'.$upcodeid);
		$db->sql_query("UPDATE {$prefix}_document SET code='$code_new' WHERE id=$upcodeid");
		$i++;
	}
	echo $i." ban ghi";
}
}
$sort = intval(isset($_GET['sort']) ? $_GET['sort'] : (isset($_POST['sort']) ? $_POST['sort']:0));
switch($sort) {
	case 1: $sortby ="ORDER BY catid ASC"; break;
	case 2: $sortby ="ORDER BY catid DESC"; break;
	case 3: $sortby ="ORDER BY time ASC"; break;
	case 4: $sortby ="ORDER BY time DESC"; break;
	case 5: $sortby ="ORDER BY hits ASC"; break;
	case 6: $sortby ="ORDER BY hits DESC"; break;
	case 7: $sortby ="ORDER BY hits_download ASC"; break;
	case 8: $sortby ="ORDER BY hits_download DESC"; break;
	default: $sortby ="ORDER BY id DESC"; break;
}

$catArr = array();
$cats = $db->sql_query("SELECT catid, title FROM {$prefix}_document_cat");
while (list($cid, $ctitle) = $db->sql_fetchrow()) $catArr[$cid] = $ctitle;

$titleup = isset($_GET["title"]) ? $_GET["title"] : "";
$cat = isset($_GET["cat"]) ? $_GET["cat"] : "";
$user = isset($_GET["user"]) ? $_GET["user"] : "";
$from = isset($_GET["from"]) ? $_GET["from"] : "";
$to = isset($_GET["to"]) ? $_GET["to"] : "";
$s_quantity=isset($_GET["s_quantity"]) ? $_GET["s_quantity"] : 20;
$s_active=isset($_GET["active"]) ? $_GET["active"] : 0;
$where="where alanguage='$currentlang' ";
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
if(!empty($cat))
{
	$where.="AND catid=$cat ";
	$vlink.="&cat=$cat";
}
if(!empty($user))
{
	$user=trim($user);
	$where.="AND user_id IN (SELECT id FROM {$prefix}_user WHERE fullname='$user')";
	$vlink.="&user=$user";
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
if($s_active==0)
{
	$where.="AND active=$s_active ";
	$vlink.="&active=$s_active";
}
elseif($s_active==1)
{
	$where.="AND active=$s_active ";
	$vlink.="&active=$s_active";
}
list ($permission) = $db->sql_fetchrow($db->sql_query("SELECT permission FROM ".$prefix."_admin WHERE adacc='$admin_ar[0]'"));
if($permission!=2){
	$where .= " AND active=0 AND uadmin=(SELECT id FROM ".$prefix."_admin WHERE adacc='$admin_ar[0]')";
}
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
	<input type="hidden" name="f" value="document" />
	<label for="action">Tiêu đề</label>
	<input type="text" id="title" value="" name="title" />
	<label for="cat">Chuyên mục</label>
	<?php
	$resultcat = $db->sql_query("SELECT catid, title FROM {$prefix}_document_cat WHERE catid IN (SELECT document FROM ".$prefix."_document_permission WHERE admingroup=(SELECT permission FROM ".$prefix."_admin WHERE adacc='$admin_ar[0]')) AND parent=0 AND alanguage='$currentlang' ORDER BY weight");
if($db->sql_numrows($resultcat) > 0) 
{
	echo '<select id="cat" name="cat">'."\n";
	echo '<option value="">'._ROOT_CAT."</option>\n";
	$listcat ="";
	while(list($cat_id, $titlecat) = $db->sql_fetchrow($resultcat)) 
	{

		$listcat .= "<option value=\"$cat_id\">--$titlecat</option>";
		$listcat .= subcat($cat_id,"-","", "");
	}
	echo $listcat;
	echo "</select>";
}
	?>
	<label for="action">Người đăng</label>
	<input type="text" id="user" value="" name="user" />
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
	
</form>
</div></div><!-- End demo -->

<?
//echo "<br><br>$admin_ar[0]";
$total = $db->sql_numrows($db->sql_query("SELECT id FROM {$prefix}_document $where"));
$result = $db->sql_query("SELECT id, catid, title, time, active, hits, hits_download, nstart, 'normal', fattach, fattach_intro, price, images, user_id FROM {$prefix}_document $where $sortby LIMIT $offset, $s_quantity");
if($db->sql_numrows($result) > 0) {

 ajaxload_content();
echo "<div id=\"pagecontent\">";
	echo "<div id=\"{$adm_modname}_main\"><form action=\"modules.php?f=$adm_modname&sort=$sort&page=$page\" name=\"frm\" method=\"POST\" onsubmit=\"return checkQuick(this);\">";
	echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
	echo "<tr><td colspan=\"15\" class=\"header\">"._CURRENT_ART."</td></tr>";
	echo "<tr>\n";
	echo "<td class=\"row1sd\" width=\"10\"><input type=\"checkbox\" name=\"checkall\" onclick=\"javascript:check_uncheck();\"></td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"60\">"._NEWSTART."</td>\n";
	echo "<td class=\"row1sd\">"._TITLE."</td>\n";
	echo "<td class=\"row1sd\"  width=\"50\">File</td>\n";
	echo "<td class=\"row1sd\">Người đăng</td>\n";
	echo "<td class=\"row1sd\"  width=\"120\">"._NEWS_CATEGORY." <a href=\"?f=".$adm_modname."&sort=1\" info=\""._SORTUP."\"><img border=\"0\" src=\"images/sup.gif\" align=\"absmiddle\"></a> <a href=\"?f=".$adm_modname."&sort=2\" info=\""._SORTDOWN."\"><img border=\"0\" src=\"images/sdown.gif\" align=\"absmiddle\"></a></td>\n";
	echo "<td class=\"row1sd\">"._PRICE."</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"100\">"._TIMEUP." <a href=\"?f=".$adm_modname."&sort=3\" info=\""._SORTUP."\"><img border=\"0\" src=\"images/sup.gif\" align=\"absmiddle\"></a> <a href=\"?f=".$adm_modname."&sort=4\" info=\""._SORTDOWN."\"><img border=\"0\" src=\"images/sdown.gif\" align=\"absmiddle\"></a></td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"60\">"._STATUS."</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"60\">"._VIEW." <a href=\"?f=".$adm_modname."&active=1&sort=5\" info=\""._SORTUP."\"><img border=\"0\" src=\"images/sup.gif\" align=\"absmiddle\"></a> <a href=\"?f=".$adm_modname."&active=1&sort=6\" info=\""._SORTDOWN."\"><img border=\"0\" src=\"images/sdown.gif\" align=\"absmiddle\"></a></td>\n";
		echo "<td class=\"row1sd\" align=\"center\" width=\"40\"><i class=\"fa fa-download fa-lg\"></i> <a href=\"?f=".$adm_modname."&active=1&sort=7\" info=\""._SORTUP."\"><i class=\"fa fa-sort-desc\"></i></a> <a href=\"?f=".$adm_modname."&active=1&sort=8\" info=\""._SORTDOWN."\"><i class=\"fa fa-sort-asc\"></i></a></td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"30\">"._EDIT."</td>\n";
	echo "<td class=\"row3sd\" align=\"center\" width=\"30\">"._DELETE."</td>\n";
	echo "</tr>\n";
	$i = 0;
	if($page > 1) { $a = $s_quantity * $page - $s_quantity + 1;}
	while(list($id, $catid, $title, $time, $active, $hits, $hits_download, $nstart, $newsType, $fattach, $fattach_intro, $price, $images, $user_id) = $db->sql_fetchrow($result)) {
		$query = "SELECT fullname, folder FROM {$prefix}_user WHERE id=$user_id";
		$result_user = $db->sql_query($query);

//if($db->sql_numrows($result) != 1) header("Location: modules.php?f=$adm_modname");
		list($fullname, $folder) = $db->sql_fetchrow($result_user);
		if($folder==""){$folder='guest';}
		
		//if (($i % 8) == 1) $css = "row1";
		//else $css ="row3";
		$css ="row1";
		$checkfile='';
		if(file_exists(RPATH."$path_upload/$folder/$images")) $checkfile_image="<img border=\"0\" src=\"images/view.png\">";
		else $checkfile_image="<img border=\"0\" src=\"images/viewo.png\">";
		if(file_exists(RPATH."$path_upload/$folder/$fattach")) $checkfile_fattach="<img border=\"0\" src=\"images/view.png\">";
		else $checkfile_fattach="<img border=\"0\" src=\"images/viewo.png\">";
		if(file_exists(RPATH."$path_upload/$folder/$fattach_intro")) $checkfile_fattach_intro="<img border=\"0\" src=\"images/view.png\">";
		else $checkfile_fattach_intro="<img border=\"0\" src=\"images/viewo.png\">";
		$checkfile=$checkfile_image.$checkfile_fattach.$checkfile_fattach_intro;
		if($ajax_active == 1) {
			if($permission==2){
			switch($active) {
				case 1: $active = "<a href=\"?f=".$adm_modname."&do=status&type=$newsType&id=$id&stat=0\" title=\""._DEACTIVATE."\" onclick=\" aj_base_status($id,'0','$adm_modname','status_news',mid); return false;\"><img border=\"0\" src=\"images/view.png\"></a>"; break;
				case 0: $active = "<a href=\"?f=".$adm_modname."&do=status&type=$newsType&id=$id&stat=1\" title=\""._ACTIVE."\" onclick=\" aj_base_status($id,'1','$adm_modname','status_news',mid); return false;\"><img border=\"0\" src=\"images/viewo.png\"></a>"; break;
			}
			}
			else{
				switch($active) {
				case 1: $active = "<img border=\"0\" src=\"images/view.png\">"; break;
				case 0: $active = "<img border=\"0\" src=\"images/viewo.png\">"; break;
			}
			}
			switch($nstart) {
				case 1: $nstart = "<a href=\"?f=".$adm_modname."&do=start&type=$newsType&id=$id&stat=0\" info=\""._NOSTART."\"
				 onclick=\"aj_base_start($id,'0','$adm_modname','start',mid); return false;\"><img border=\"0\" src=\"../images/start.png\"></a>"; break;
				case 0: $nstart = "<a href=\"?f=".$adm_modname."&do=start&type=$newsType&id=$id&stat=1\" info=\""._YESSTART."\" onclick=\"aj_base_start($id,'1','$adm_modname','start',mid); return false;\"><img border=\"0\" src=\"../images/starto.png\"></a>"; break;
			}
		} else {
			if($permission==2){
				switch($active) {
				case 1: $active = "<a href=\"?f=".$adm_modname."&do=status&type=$newsType&id=$id&stat=0\" info=\""._DEACTIVATE."\"><img border=\"0\" src=\"images/view.png\"></a>"; break;
				case 0: $active = "<a href=\"?f=".$adm_modname."&do=status&type=$newsType&id=$id&stat=1\" info=\""._ACTIVE."\"><img border=\"0\" src=\"images/viewo.png\"></a>"; break;
			}
			}
			else{
				switch($active) {
				case 1: $active = "<img border=\"0\" src=\"images/view.png\">"; break;
				case 0: $active = "<img border=\"0\" src=\"images/viewo.png\">"; break;
			}
			}
			
			switch($nstart) {
				case 1: $nstart = "<a href=\"?f=".$adm_modname."&do=start&type=$newsType&id=$id&stat=0\" info=\""._NOSTART."\"><img border=\"0\" src=\"../images/start.png\"></a>"; break;
				case 0: $nstart = "<a href=\"?f=".$adm_modname."&do=start&type=$newsType&id=$id&stat=1\" info=\""._YESSTART."\"><img border=\"0\" src=\"../images/starto.png\"></a>"; break;
			}
		}

		echo "<tr>\n";
		echo "<td class=\"$css\"><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td>";
		echo "<td align=\"center\" class=\"$css\">$nstart</td>\n";
		if ($newsType == 'normal') $titleLink = "<a href=\"".RPATH.url_sid("index.php?f=".$adm_modname."&do=detail&id=$id")."\" info=\""._VIEW."\" target=\"_blank\">$title</a> <a href=\"../".url_sid("index.php?f=".$adm_modname."&do=detail&id=$id")."\" info=\""._GETLINK."\" onclick=\"prompt('"._GETLINK."','".url_sid("index.php?f=$adm_modname&do=detail&id=$id")."'); return false;\"><img border=\"0\" src=\"images/link.png\"></a>";
		else $titleLink = $title;
		echo "<td class=\"$css\"><b>$titleLink</b></td>\n";
		echo "<td class=\"$css\"><b>$checkfile</b></td>\n";
		echo "<td class=\"$css\"><b>$fullname</b></td>\n";
		echo "<td class=\"$css\"><b><a href=\"".RPATH.url_sid("index.php?f=".$adm_modname."&do=categories&id=$catid")."\" info=\""._VIEW."\" target=\"_blank\">{$catArr[$catid]}</a></b></td>\n";
		echo "<td class=\"$css\"><b>$price</b></td>\n";
		echo "<td align=\"center\" class=\"$css\">".ext_time($time, 2)."</td>\n";
		echo "<td align=\"center\" class=\"$css\">$active</td>\n";
		echo "<td align=\"center\" class=\"$css\">$hits</td>\n";
		echo "<td align=\"center\" class=\"$css\">$hits_download</td>\n";
		echo "<td align=\"center\" width=\"30\" class=\"$css\"><a href=\"?f=".$adm_modname."&do=edit&type=$newsType&id=$id\" info=\""._EDIT."\"><img border=\"0\" src=\"images/edit.png\"></a></td>\n";
		if($ajax_active == 1) {
			echo "<td align=\"center\" width=\"30\" class=\"row3\"><a href=\"?f=".$adm_modname."&do=delete&type=$newsType&id=$id\" title=\""._DELETE."\" onclick=\"aj_base_delete($id,'$adm_modname','"._DELETEASK1."','delete','');\"><img border=\"0\" src=\"images/delete.png\"></td>\n";
		} else {
			echo "<td align=\"center\" width=\"30\" class=\"row3\"><a href=\"?f=".$adm_modname."&do=delete&type=$newsType&id=$id\" info=\""._DELETE."\" onclick=\"return confirm('"._DELETEASK1."');\"><img border=\"0\" src=\"images/delete.png\"></td>\n";
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
	echo "</select>&nbsp;<input type=\"submit\" class=\"button2\" name=\"submit\" value=\""._DOACTION."\">";
		$resultcat = $db->sql_query("SELECT catid, title FROM {$prefix}_document_cat WHERE catid IN (SELECT document FROM ".$prefix."_document_permission WHERE admingroup=(SELECT permission FROM ".$prefix."_admin WHERE adacc='$admin_ar[0]')) AND parent=0 AND alanguage='$currentlang' ORDER BY weight");
if($db->sql_numrows($resultcat) > 0) 
{
	echo ' || Chuyển sang chuyên mục: <select id="catchange" name="catchange">'."\n";
	echo '<option value="0">'._ROOT_CAT."</option>\n";
	$listcat ="";
	while(list($cat_id, $titlecat) = $db->sql_fetchrow($resultcat)) 
	{
		$listcat .= "<option value=\"$cat_id\">--$titlecat</option>";
		$listcat .= subcat($cat_id,"-","", "");
	}
	echo $listcat;
	echo "</select>&nbsp;<input type=\"submit\" class=\"button2\" name=\"submit\" value=\""._DOACTION."\">";
}
	echo "</div>";
	echo "<div class=\"fr\">";
	if($total > $s_quantity) {
		$pageurl = "modules.php?f=".$adm_modname."&sort=$sort&title=$titleup&cat=$cat&user=$user&from=$from&to=$to&active=$s_active&s_quantity=$s_quantity&subs=Tìm+kiếm";
		echo paging($total,$pageurl,$s_quantity,$page);
	}
		echo "</div>";
	echo "</td></tr>";
	echo "</table></form></div></div>";
}

include_once("page_footer.php");
?>