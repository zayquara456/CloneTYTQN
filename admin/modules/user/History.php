<?php

if(!defined('CMS_ADMIN')) die("Illegal File Access");

include_once("page_header.php");
$id = intval(isset($_GET['id']) ? $_GET['id'] : 0);
$text = $menhgia = $serial = $err_serial = $err_cat = $s_content = $err_code= $error ="";
$active = 1;
$err=0;
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
	<input type="hidden" name="do" value="history" />
	<label for="action">Tài khoản</label>
	<input type="text" id="s_name" value="" name="s_name"  style="width: 100px"/>
	<label for="action">Nội dung</label>
	<input type="text" id="s_content" value="" name="s_content"  style="width: 120px" />
	<label for="cat"></label>
	<?php
	$resultcat = $db->sql_query("SELECT DISTINCT title FROM {$prefix}_user_log ORDER BY title DESC");
if($db->sql_numrows($resultcat) > 0) 
{
	echo '<select id="title" name="s_title">'."\n";
	echo '<option value="">Chọn hành động</option>';
	while(list($title) = $db->sql_fetchrow($resultcat)) 
	{

		echo "<option value=\"$title\">$title</option>";
	}
	echo "</select>";
}	
	?>	
	<label for="from">From</label>
	<input type="text" id="from" name="from"  style="width: 80px"/>
	<label for="to">to</label>
	<input type="text" id="to" name="to"  style="width: 80px"/>
	<select id="s_time" name="s_time">
	<option value="0">Mới nhất</option>
	<option value="1">Cũ nhất</option>
	</select>
	<label for="action">Số lượng</label>
	<input type="text" id="s_quantity" value="20" style="width: 40px" name="s_quantity" />
	<input type="submit" class="button2" value="Tìm kiếm"  name="subs" />
	
</form>
</div></div><!-- End demo -->
<?php
echo "<div id=\"pagecontent\">";
$s_name = isset($_GET["s_name"]) ? $_GET["s_name"] : '';
$s_content = isset($_GET["s_content"]) ? $_GET["s_content"] : '';
$s_title = isset($_GET["s_title"]) ? $_GET["s_title"] : "";
$s_cat = isset($_GET["s_cat"]) ? $_GET["s_cat"] : "";
$s_quantity=isset($_GET["s_quantity"]) ? $_GET["s_quantity"] : 20;
$s_time=isset($_GET["s_time"]) ? $_GET["s_time"] : '';

$where="WHERE id > 0 ";
$vlink="";
if(!empty($s_name))
{
	$s_name=trim($s_name);
	$where.="AND user_id IN (SELECT id FROM {$prefix}_user WHERE fullname='$s_name')";
	$vlink.="&name=$s_name";
}
if(!empty($s_content))
{
	$s_content=trim($s_content);
	$where.="AND action LIKE '%$s_content%'";
	$vlink.="&content=$s_content";
}
if(!empty($s_title))
{
	$s_title=trim($s_title);
	$where.="AND title LIKE '%$s_title%'";
	$vlink.="&title=$s_title";
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
if($s_time==0)
{
	$where.=" ORDER BY dateline DESC";
	$vlink.="&$s_time=$s_time";
}
elseif($s_time==1)
{	
	$where.=" ORDER BY dateline ASC";
	$vlink.="&$s_time=$s_time";
}

//$countf = $db->sql_fetchrow($db->sql_query("SELECT COUNT(*) FROM {$prefix}_thecao $where"));
//$total = ($countf[0]) ? $countf[0] : 1;
$result = $db->sql_query("SELECT id, user_id, dateline, area, title, action, status, money_old, money, money_new, ip_add FROM {$prefix}_user_log $where LIMIT $s_quantity");
//die("SELECT id, code, serial, time, price, userid FROM {$prefix}_thecao_buy $where LIMIT $s_quantity");
//$offset,$perpage
if($db->sql_numrows($result) > 0) {
	echo "<script language=\"javascript\" type=\"text/javascript\">\n";
	echo "function check_uncheck(){\n";
	echo "	var f=document.frm;\n";
	echo "	if(f.checkall.checked){\n";
	echo "		CheckAllCheckbox(f,'id[]');\n";
	echo "	}else{\n";
	echo "		UnCheckAllCheckbox(f,'id[]');\n";
	echo "	}			\n";
	echo "}\n";
	echo "	function checkQuick(f) {\n";
	echo "		if(f.fc.value =='') {\n";
	echo "			f.fc.focus();\n";
	echo "			return false;\n";
	echo "		}\n";
	echo "		f.submit.disabled = true; \n";
	echo "		return true;		\n";
	echo "	}	\n";
	echo "	function checkQuickId(f) {\n";
	echo "		if(f.id.value =='') {\n";
	echo "			f.id.focus();\n";
	echo "			return false;\n";
	echo "		}\n";
	echo "		f.submit.disabled = true; \n";
	echo "		return true;		\n";
	echo "	}	\n";
	echo "</script>\n";
	ajaxload_content();

	echo "<div id=\"".$adm_modname."_main\"><form action=\"modules.php?f=$adm_modname&do=$do\" name=\"frm\" method=\"POST\" onsubmit=\"return checkQuick(this);\">";
	echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
	echo "<tr><td colspan=\"10\" class=\"header\">Lịch sử giao dịch tài khoản</td></tr>";
	echo "<tr>\n";
	echo "<td class=\"row1sd\" width=\"20\" align=\"center\">STT</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"100px\">Thời gian</td>\n";
	echo "<td class=\"row1sd\" width=\"80\">Tài khoản</td>\n";
	echo "<td class=\"row1sd\" width=\"80\">Hành động</td>\n";
	echo "<td class=\"row1sd\"  align=\"center\" width=\"100\">Tiền cũ</td>\n";
	echo "<td class=\"row1sd\" width=\"60\">Trạng thái</td>\n";
	echo "<td class=\"row1sd\"  align=\"center\" width=\"100\">Số tiền</td>\n";
	echo "<td class=\"row1sd\"  align=\"center\" width=\"100\">Tiền mới</td>\n";
	echo "<td class=\"row1sd\">Nội dung</td>\n";
	
	echo "</tr>\n";
	$i =0;
	$a = 1;
	$total_money=0;
	$total_money_old=0;
	$total_money_new=0;
	while(list($id, $userid, $dateline, $area, $title, $action, $status, $money_old, $money, $money_new, $ip_add) = $db->sql_fetchrow($result)) {
		$css = "row1";
		
		echo "<tr>\n";
		echo "<td align=\"center\" class=\"$css\">$a</td>";
		echo "<td class=\"$css\">".ext_time($dateline, 2)."</td>\n";
		echo "<td class=\"$css\">".show_user($userid)."</td>";
		echo "<td class=\"$css\"><b>$title</b></td>\n";
		echo "<td class=\"$css\" align=\"right\"><b>".bsVndDot($money_old)."</b></td>\n";
		echo "<td class=\"$css\"  align=\"center\"><b>$status</b></td>\n";
		echo "<td class=\"$css\" align=\"right\"><b>".bsVndDot($money)."</b></td>\n";
		echo "<td class=\"$css\" align=\"right\"><b>".bsVndDot($money_new)."</b></td>\n";
		echo "<td class=\"$css\" align=\"left\"><b>$action</b></td>\n";
		echo "</tr>\n";
		if($status=="+"){$total_money=$total_money + $money;}
		elseif($status=="-"){$total_money=$total_money - $money;}
		$total_money_old=$total_money_old+$money_old;
		$total_money_new=$total_money_new+$money_new;
		//
		$i ++;
		$a ++;
	}
	echo "<tr>\n";
		echo "<td align=\"center\" class=\"$css\"></td>";
		echo "<td class=\"$css\"></td>";
		echo "<td class=\"$css\"></td>\n";
		echo "<td class=\"$css\"></td>\n";
		echo "<td class=\"$css\" align=\"right\"><b>Tổng: ".bsVndDot($total_money_old)."</b></td>\n";
		echo "<td class=\"$css\"></td>\n";
		echo "<td class=\"$css\" align=\"right\"><b>Tổng: ".bsVndDot($total_money)."</b></td>\n";
		echo "<td class=\"$css\" align=\"right\"><b>Tổng: ".bsVndDot($total_money_new)."</b></td>\n";
		echo "<td class=\"$css\" align=\"right\"><b></b></td>\n";
		echo "</tr>\n";
	echo "</table></div></div>";

		

}else{
	
}

include_once("page_footer.php");
?>