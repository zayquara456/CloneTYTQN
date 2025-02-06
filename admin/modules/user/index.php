 <?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

include_once("page_header.php");

$sort = intval(isset($_GET['sort']) ? $_GET['sort'] : (isset($_POST['sort']) ? $_POST['sort'] : 0));
switch($sort) {
	case 1: $sortby ="ORDER BY id ASC"; break;
	case 2: $sortby ="ORDER BY id DESC"; break;
	case 3: $sortby ="ORDER BY fullname ASC"; break;
	case 4: $sortby ="ORDER BY fullname DESC"; break;
	case 5: $sortby ="ORDER BY email ASC"; break;
	case 6: $sortby ="ORDER BY email DESC"; break;
	default: $sortby ="ORDER BY registrationTime DESC"; break;
}
$s_name = isset($_GET["s_name"]) ? $_GET["s_name"] : '';
$s_email = isset($_GET["s_email"]) ? $_GET["s_email"] : '';
$s_time = isset($_GET["s_time"]) ? $_GET["s_time"] : "";
$s_title = isset($_GET["s_title"]) ? $_GET["s_title"] : "";
$s_quantity=isset($_GET["s_quantity"]) ? $_GET["s_quantity"] : 20;
$s_giaodich=isset($_GET["s_giaodich"]) ? $_GET["s_giaodich"] : 0;
$s_money=isset($_GET["s_money"]) ? $_GET["s_money"] : 0;
$s_active=isset($_GET["active"]) ? $_GET["active"] : 1;
$s_block=isset($_GET["block"]) ? $_GET["block"] : 1;
$where="WHERE id > 0 ";
$vlink="";
if(!empty($s_name))
{
	$s_name=trim($s_name);
	$where.="AND fullname LIKE '%$s_name%'";
	$vlink.="&name=$s_name";
}
if(!empty($s_email))
{
	$s_email=trim($s_email);
	$where.="AND email LIKE '%$s_email%'";
	$vlink.="&email=$s_email";
}
//if(is_number($s_giaodich) && $s_giaodich!=0)
//{
//	$s_giaodich=trim($s_giaodich);
//	$where.="AND $s_giaodich=(SELECT  COUNT(id) FROM ".$prefix."_user_log WHERE user_id=id)";
//	$vlink.="&s_giaodich=$s_giaodich";
//}
//else
//{
//	$s_money=trim($s_money);
//	$where.="AND money $s_money";
//	$vlink.="&s_money=$s_money";
//}
//if(is_number($s_money) && $s_money!=0)
//{
//	$s_money=trim($s_money);
//	$where.="AND money=$s_money";
//	$vlink.="&s_money=$s_money";
//}
//else
//{
//	$s_money=trim($s_money);
//	$where.="AND money $s_money";
//	$vlink.="&s_money=$s_money";
//}
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
	$where.="AND registrationTime < $to ";
	$vlink.="&to=$to";
}
if($s_time==0)
{
	$where.=" ORDER BY registrationTime DESC";
	$vlink.="&$s_time=$s_time";
}
elseif($s_time==1)
{	
	$where.=" ORDER BY registrationTime ASC";
	$vlink.="&$s_time=$s_time";
}
$db->sql_query("SELECT COUNT(id) FROM {$prefix}_user $where");
list($total) = $db->sql_fetchrow();
$perpage = 20;
$page = intval(isset($_GET['page']) ? $_GET['page'] : (isset($_POST['page']) ? $_POST['page'] : 1));
$offset = ($page - 1) * $s_quantity;

ajaxload_content();
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
	<input type="hidden" name="f" value="user" />
	<label for="action">Tài khoản</label>
	<input type="text" id="s_name" value="" name="s_name"  style="width: 100px"/>
	<label for="action">Email</label>
	<input type="text" id="s_email" value="" name="s_email"  style="width: 120px" />
	<label for="from">From</label>
	<input type="text" id="from" name="from"  style="width: 80px"/>
	<label for="to">to</label>
	<input type="text" id="to" name="to"  style="width: 80px"/>
	<select id="s_time" name="s_time">
	<option value="0">Mới nhất</option>
	<option value="1">Cũ nhất</option>
	</select>
	<select id="active" name="active">
	<option value="1">Đã kích hoạt</option>
	<option value="0">Chưa kích hoạt</option>
	</select>
	<select id="active" name="block">
	<option value="1">Đang khóa</option>
	<option value="0">Chưa khóa</option>
	</select>
	<label for="action">Tiền</label>
	<input type="text" id="s_quantity" value="<?php echo $s_money?>" style="width: 40px" name="s_money" />
	<label for="action">Giao dịch</label>
	<input type="text" id="s_quantity" value="<?php echo $s_giaodich?>" style="width: 40px" name="s_giaodich" />
	<label for="action">Số lượng</label>
	<input type="text" id="s_quantity" value="<?php echo $s_quantity?>" style="width: 40px" name="s_quantity" />
	<input type="submit" class="button2" value="Tìm kiếm"  name="subs" />
	
</form>
</div></div><!-- End demo -->
<?php
echo "<div id=\"pagecontent\">";
echo "<div id=\"{$adm_modname}_main\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
echo "<tr><td colspan=\"15\" class=\"header\">"._MODTITLE."</td></tr>\n";
echo "<tr>\n<td class=\"row1sd\" width=\"20\" align=\"center\">"._USER_ID."</td>\n";
echo "<td class=\"row1sd\" align=\"center\">"._USER_FULLNAME."</td>\n";
echo "<td class=\"row1sd\" align=\"center\">"._USER_EMAIL."</td>\n";
echo "<td class=\"row1sd\" align=\"center\">Thời gian</td>\n";
echo "<td align=\"center\" width=\"100\" class=\"row1sd\"><b>Điện thoại</b></td>\n";
echo "<td align=\"center\" width=\"150\" class=\"row1sd\"><b>Điạ chỉ</b></td>\n";
echo "<td align=\"center\" width=\"100\" class=\"row1sd\"><b>Tiền (VNĐ)</b></td>\n";
echo "<td align=\"center\" width=\"80\" class=\"row1sd\"><b>Giao dịch</b></td>\n";
echo "<td align=\"center\" width=\"80\" class=\"row1sd\"><b>Kích hoạt</b></td>\n";
echo "<td align=\"center\" width=\"50\" class=\"row1sd\"><b>Khóa</b></td>\n";
echo "<td align=\"center\" width=\"50\" class=\"row1sd\"><b>"._SHOW."</b></td>\n";
echo "<td class=\"row1sd\" width=\"10\" align=\"center\">"._EDIT."</td>\n";
echo "<td class=\"row1sd\" width=\"10\" align=\"center\">"._DELETE."</td>\n";
echo "</tr>\n";

$result = $db->sql_query("SELECT id, group_id, fullname, money, email, phone, address, activationCode, actives, registrationTime, recoverCode, loginAttempt, unblockCode FROM {$prefix}_user $where LIMIT $offset, $s_quantity");
//die("SELECT id, group_id, fullname, money, email, phone, address, activationCode, actives, registrationTime, recoverCode, loginAttempt, unblockCode FROM {$prefix}_user $where LIMIT $offset, $s_quantity");
if ($db->sql_numrows() > 0) {
	$i = 0;
	while (list($id, $group, $tname, $money, $temail, $phone, $address, $activationCode, $active, $registrationTime, $recoverCode, $loginAttempt, $unblockCode) = $db->sql_fetchrow($result)) {
		if($ajax_active == 1) {	
			switch($active) {
				case 1: $active = "<a href=\"?f=$adm_modname&do=status&id=$id&stat=0\" title=\""._DEACTIVATE."\" onclick=\"return aj_base_status($id,0,'$adm_modname','','');\"><img border=\"0\" src=\"images/view.png\"></a>"; break;
				case 0: $active = "<a href=\"?f=$adm_modname&do=status&id=$id&stat=1\" title=\""._ACTIVE."\" onclick=\"return aj_base_status($id,1,'$adm_modname','','');\"><img border=\"0\" src=\"images/viewo.png\"></a>"; break;
			}
		} else {
			switch($active) {
				case 1: $active = "<a href=\"?f=$adm_modname&do=status&id=$id&stat=0\" info=\""._DEACTIVATE."\"><img border=\"0\" src=\"images/view.png\"></a>"; break;
				case 0: $active = "<a href=\"?f=$adm_modname&do=status&id=$id&stat=1\" info=\""._ACTIVE."\"><img border=\"0\" src=\"images/viewo.png\"></a>"; break;
			}	
		}
		if(is_null($activationCode))
		{
			$activation ="<a href=\"?f=$adm_modname&do=status&id=$id&active=0\" info=\""._DEACTIVATE."\"><img border=\"0\" src=\"images/view.png\"></a>";
		}
		else
		{
			$activation = "<a href=\"?f=$adm_modname&do=status&id=$id&active=1\" info=\""._ACTIVE."\"><img border=\"0\" src=\"images/viewo.png\"></a>";
		}
		if(is_null($unblockCode))
		{
			$unblock ="<a href=\"?f=$adm_modname&do=status&id=$id&block=0\" info=\""._DEACTIVATE."\"><img border=\"0\" src=\"images/view.png\"></a>";
		}
		else
		{
			$unblock = "<a href=\"?f=$adm_modname&do=status&id=$id&block=1\" info=\""._ACTIVE."\"><img border=\"0\" src=\"images/viewo.png\"></a>";
		}
		if (($i % 2) == 1) $css = "row1";
		else $css ="row3";

		if ($ajax_active == 1) {
			$tdId = " id=\"{$adm_modname}_title_edit_$id\"";
			//$fullname = "<a href=\"modules.php?f=$adm_modname&do=edit&id=$id\" onclick=\"return show_edit_title($id,'$tname','$adm_modname',20,'"._SAVECHANGES."','quick_edit_name')\" info=\""._QUICK_EDIT."\">$tname</a>";
			//$tdId2 = " id=\"email_title_edit_$id\"";
			//$email = "<a href=\"modules.php?f=$adm_modname&do=edit&id=$id\" onclick=\"return show_edit_title2($id,'$temail','$adm_modname','email',20,'"._SAVECHANGES."','quick_edit_email','email_title_edit_$id')\" info=\""._QUICK_EDIT."\">$temail</a>";
						
			$delete = "<a href=\"modules.php?f=$adm_modname&do=delete&id=$id\" onclick=\"return aj_base_delete('$id','$adm_modname','"._USER_DELETEASK."','delete','id');\" info=\""._DELETE."\"><img border=\"0\" src=\"images/delete.png\"></a>";
		} else {
			$tdId = $tdId2= '';
			$fullname = $tname;
			$email = $temail;
			$delete = "<a href=\"modules.php?f=$adm_modname&do=delete&id=$id\" onclick=\"return confirm('"._USER_DELETEASK."')\" info=\""._DELETE."\"><img border=\"0\" src=\"images/delete.png\"></a>";
		}

		echo "<tr>\n<td class=\"row1\" align=\"center\">$id</td>\n";
		echo "<td class=\"row1\" align=\"left\">$tname</td>\n";
		echo "<td class=\"row1\" align=\"left\">$temail</td>\n";
		echo "<td class=\"row1\" align=\"left\">$registrationTime</td>\n";
		echo "<td align=\"right\" class=\"row1\">".$phone."</td>\n";
		echo "<td align=\"left\" class=\"row1\">".$address."</td>\n";
		echo "<td align=\"right\" class=\"row1\">".bsVndDot($money)." <a href=\"modules.php?f=$adm_modname&do=addmoney&id=$id\"><img border=\"0\" src=\"images/money.png\"  info=\"Cộng trừ tiền tài khoản\"></a></td>\n";
		echo "<td align=\"right\" class=\"row1\">".show_giaodich($id,$tname)."</td>\n";
		echo "<td align=\"center\" class=\"row1\">$activation</td>\n";
		echo "<td align=\"center\" class=\"row1\">$unblock</td>\n";
		echo "<td align=\"center\" class=\"row1\">$active</td>\n";
		echo "<td class=\"row1\" align=\"center\"><a href=\"modules.php?f=$adm_modname&do=edit&id=$id\"><img border=\"0\" src=\"../images/edit.gif\"></a></td>\n";
		echo "<td class=\"$css\" align=\"center\">$delete</td>\n</tr>";
	}
}

if($total > $s_quantity) {
	echo "<tr><td colspan=\"20\">";
	//$pageurl = "modules.php?f=$adm_modname&sort=$sort";
	$pageurl = "modules.php?f=".$adm_modname."&sort=$sort&title=$titleup&cat=$cat&user=$user&from=$from&to=$to&active=$s_active&s_quantity=$s_quantity&subs=Tìm+kiếm";
	echo paging($total,$pageurl,$s_quantity,$page);
	echo "</td></tr>";
}

echo "</table>\n</div></div>\n";

include_once("page_footer.php");
?>
