 <?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

include_once("page_header.php");
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
	<input type="hidden" name="do" value="abstract" />
	<label for="action">Tài khoản</label>
	<input type="text" id="s_name" value="" name="s_name"  style="width: 100px"/>
	<label for="action">Chủ tài khoản</label>
	<input type="text" id="s_account" value="" name="s_account"  style="width: 120px" />
	<label for="action">Số tài khoản</label>
	<input type="text" id="s_codebank" value="" name="s_codebank"  style="width: 120px" />
	<label for="from">From</label>
	<input type="text" id="from" name="from"  style="width: 80px"/>
	<label for="to">to</label>
	<input type="text" id="to" name="to"  style="width: 80px"/>
	<label for="to"></label>
	<select id="s_status" name="s_status">
	<option value="">Trạng thái</option>
	<option value="0">Chưa xử lý</option>
	<option value="1">Chờ xử lý</option>
	<option value="2">Đã xử lý</option>
	<option value="3">Hủy giao dịch</option>
	</select>
	<select id="s_time" name="s_time">
	<option value="0">Mới nhất</option>
	<option value="1">Cũ nhất</option>
	</select>
	<input type="submit" class="button2" value="Tìm kiếm"  name="subs" />
	
</form>
</div></div><!-- End demo -->
<?php

$s_name = isset($_GET["s_name"]) ? $_GET["s_name"] : '';
$s_account = isset($_GET["s_account"]) ? $_GET["s_account"] : '';
$s_codebank = isset($_GET["s_codebank"]) ? $_GET["s_codebank"] : "";
$s_status=isset($_GET["s_status"]) ? $_GET["s_status"] : '';
$s_time=isset($_GET["s_time"]) ? $_GET["s_time"] : '';

$where="WHERE id > 0 ";
$vlink="";
if(!empty($s_name))
{
	$s_name=trim($s_name);
	$where.="AND userid IN (SELECT id FROM {$prefix}_user WHERE fullname='$s_name')";
	$vlink.="&name=$s_name";
}
if(!empty($s_account))
{
	$s_account=trim($s_account);
	$where.="AND account LIKE '%$s_account%'";
	$vlink.="&account=$s_account";
}
if(!empty($s_codebank))
{
	$s_codebank=trim($s_codebank);
	$where.="AND code LIKE '%$s_codebank%'";
	$vlink.="&code=$s_codebank";
}
if($s_status!="")
{
	$where.="AND status=$s_status";
	$vlink.="&status=$s_status";
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
if($s_time==0)
{
	$where.=" ORDER BY time DESC";
	$vlink.="&$s_time=$s_time";
}
elseif($s_time==1)
{	
	$where.=" ORDER BY time ASC";
	$vlink.="&$s_time=$s_time";
}
$db->sql_query("SELECT COUNT(id) FROM {$prefix}_transfer_bank");
list($total) = $db->sql_fetchrow();
$perpage = 20;
$page = intval(isset($_GET['page']) ? $_GET['page'] : (isset($_POST['page']) ? $_POST['page'] : 1));
$offset = ($page - 1) * $perpage;

ajaxload_content();
echo "<div id=\"pagecontent\">";
echo "<div id=\"{$adm_modname}_main\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
echo "<tr><td colspan=\"10\" class=\"header\">Quản lý rút tiền vào ngân hàng</td></tr>\n";
echo "<tr>\n<td class=\"row1sd\" width=\"10\" align=\"center\">Mã</td>\n";
echo "<td class=\"row1sd\" width=\"80\" align=\"center\">Thời gian</td>\n";
echo "<td class=\"row1sd\" width=\"100\" align=\"center\">Tài khoản rút</td>\n";
echo "<td class=\"row1sd\"   width=\"100\" align=\"center\">Chủ tài khoản</td>\n";
echo "<td class=\"row1sd\"  width=\"100\" align=\"center\">Ngân hàng</td>\n";
echo "<td align=\"center\" width=\"100\" class=\"row1sd\">Số tài khoản</td>\n";
echo "<td align=\"center\" width=\"50\" class=\"row1sd\"><b>Tiền (VNĐ)</b></td>\n";
echo "<td align=\"center\" width=\"200\" class=\"row1sd\"><b>Ghi chú</b></td>\n";
echo "<td class=\"row1sd\" width=\"80\" align=\"center\">Trạng thái</td>\n";
echo "<td class=\"row1sd\"   width=\"30\" align=\"center\"></td>\n";

echo "</tr>\n";

$result = $db->sql_query("SELECT id, userid, bank, account, code, note, money, status, time FROM {$prefix}_transfer_bank $where LIMIT $offset, $perpage");

if ($db->sql_numrows() > 0) {
	$i = 0;
	while (list($id, $userid, $bank, $account, $code, $note, $money, $status, $time) = $db->sql_fetchrow($result)) {
		
		if (($i % 2) == 1) $css = "row1";
		else $css ="row3";
		echo "<tr>\n<td class=\"row1\" width=\"5%\" align=\"center\">$id</td>\n";
		echo "<td class=\"row1\" align=\"center\">".ext_time($time, 2)."</td>";
		echo "<td class=\"row1\" align=\"left\">".show_user($userid)."</td>\n";
		echo "<td class=\"row1\" align=\"left\">$account</td>\n";		
		echo "<td align=\"center\" class=\"row1\">".show_bank($bank)."</td>\n";
		echo "<td align=\"center\" class=\"row1\">$code</td>\n";
		echo "<td align=\"center\" class=\"row1\">".bsVndDot($money)."</td>\n";
		echo "<td align=\"center\" class=\"row1\">$note</td>\n";
		echo "<td align=\"center\" class=\"row1\" >".show_status($status)." </td>\n";
		echo "<td align=\"center\" class=\"row1\" >".show_action_status($id,$status)."</td>\n";
		echo "\n</tr>";
	}
}

if($total > $perpage) {
	echo "<tr><td colspan=\"5\">";
	$pageurl = "modules.php?f=$adm_modname&do=abstract";
	echo paging($total,$pageurl,$perpage,$page);
	echo "</td></tr>";
}

echo "</table>\n</div></div>\n";

include_once("page_footer.php");
?>
