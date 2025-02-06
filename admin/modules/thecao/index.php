<?php

if(!defined('CMS_ADMIN')) die("Illegal File Access");

include_once("page_header.php");
$id = intval(isset($_GET['id']) ? $_GET['id'] : 0);
$text = $menhgia = $serial = $err_serial = $err_cat = $code = $err_code= $error ="";
$active = 1;
$err=0;
if( isset($_POST['subup']) && $_POST['subup'] == 1) {
	$catid = nospatags($_POST['catid']);
	$active = intval($_POST['active']);
	$code = nospatags($_POST['code']);
	$code = str_replace(' ','', $code);
	$code = str_replace('x','', $code);
	//$serial = nospatags($_POST['serial']);
	$menhgia = intval($_POST['menhgia']);
	
	if($catid == 0) {
		$err_cat = "<font color=\"red\">Mời bạn chọn loại thẻ</font><br/>";
		$err = 1;
	}
	if($code == "") {
		$error .= "<font color=\"red\">Mời bạn nhận mã thẻ</font><br/>";
		$err = 1;
	}
	$code = explode('\r\n', $code);
	if(!$err) {
		for($i=0; $i< sizeof($code); $i++)
		{
			if($code[$i]!=""){
				$card = explode(":",$code[$i]);
				if(empty($card[0])){$error .= "Mã code hoặc serial không đúng ''<br>";}
				elseif(empty($card[1])){$error .= "Mã code hoặc serial không đúng ''<br>";}
				//elseif(strlen($card[0])!=16){$error .= "Mã code hoặc serial không đúng ".strlen($card[0])."<br>";}
				//elseif(strlen($card[1])!=9){$error .= "Mã code hoặc serial không đúng ".strlen($card[1])."<br>";}
				//elseif(!is_numeric($card[0])){$error .= "Mã code hoặc serial không đúng (n)<br>";}
				//elseif(!is_numeric($card[1])){$error .= "Mã code hoặc serial không đúng (n)<br>";}
				else{
					//kiem tra code da ton tai
					$result = $db->sql_query("SELECT code FROM {$prefix}_thecao WHERE code='".$card[0]."'");
					if($db->sql_numrows($result) > 0) {
						$error .= "Mã code ".$card[0]." đã tồn tại<br>";
					}
					else
					{
						$result = $db->sql_query("SELECT serial FROM {$prefix}_thecao WHERE serial='".$card[1]."'");
						if($db->sql_numrows($result) > 0) {
							$error .= "Mã serial ".$card[1]." đã tồn tại<br>";
						}
						else{
							$db->sql_query("INSERT INTO {$prefix}_thecao (catid, code, alanguage, time, serial,  active, menhgia, buy) VALUES ($catid, '".$card[0]."', '$currentlang', ".time().", '".$card[1]."', $active, $menhgia, 0)");
							$error .= "Thêm thẻ ".$card[0].":".$card[1]." thành công<br>";
						}
					}
				}
			}
		}
		//fixcount_cat();
		//updateadmlog($admin_ar[0], $adm_modname, "Quản lý thẻ cào", "Thêm thẻ cào mới");
		//header("Location: modules.php?f=".$adm_modname."");
	}
}

//edit the cao
if( isset($_POST['subedit']) && $_POST['subedit'] == 1) {
	$idedit = nospatags($_POST['idedit']);
	$catid = nospatags($_POST['catid']);
	$active = intval($_POST['active']);
	$code = nospatags($_POST['code']);
	$code = str_replace(' ','', $code);
	$code = str_replace('x','', $code);
	//$serial = nospatags($_POST['serial']);
	$menhgia = intval($_POST['menhgia']);
	
	if($code =="") {
		$err_code = "<font color=\"red\">Mời bạn nhập mã thẻ</font><br/>";
		$err = 1;
	}
	if($catid == 0) {
		$err_cat = "<font color=\"red\">Mời bạn chọn loại thẻ</font><br/>";
		$err = 1;
	}

	if(!$err) {
		if(empty($card[0])){$error .= "Mã code hoặc serial không đúng ''<br>";}
		elseif(empty($card[1])){$error .= "Mã code hoặc serial không đúng ''<br>";}
		//elseif(strlen($card[0])!=16){$error .= "Mã code hoặc serial không đúng ".strlen($card[0])."<br>";}
		//elseif(strlen($card[1])!=9){$error .= "Mã code hoặc serial không đúng ".strlen($card[1])."<br>";}
		//elseif(!is_numeric($card[0])){$error .= "Mã code hoặc serial không đúng (n)<br>";}
		//elseif(!is_numeric($card[1])){$error .= "Mã code hoặc serial không đúng (n)<br>";}
		else{
			$card = explode(":",$code);
			$result = $db->sql_query("UPDATE {$prefix}_thecao SET catid=$catid, code='".$card[0]."', serial='".$card[1]."',  active=$active, menhgia=$menhgia WHERE id=$idedit");
			fixcount_cat();
			updateadmlog($admin_ar[0], $adm_modname, "Quản lý thẻ cào", "Chỉnh sửa thẻ cào");
			header("Location: modules.php?f=".$adm_modname."&edit=ok");
		}
	}
}
$getedit= isset($_GET['edit']) ? $_GET['edit'] : "";
if($getedit=='ok'){
	echo "<script>window.alert('Chỉnh sửa thẻ cào thành công!');</script>";
}

// lay du lieu chinh sua

$result = $db->sql_query("SELECT catid, code, alanguage, time, serial,  active, menhgia, buy FROM ".$prefix."_thecao WHERE id=$id ");
if($db->sql_numrows($result) != 1) {
	//eader("Location: ".$adm_modname.".php");
	//die();
}
else{
	list($catid, $code, $alanguage, $time, $serial,  $active, $menhgia, $buy) = $db->sql_fetchrow($result);
	echo $catid;
}

echo "<script language=\"javascript\">\n";
echo "	function check(f) {\n";
echo "		if(f.title.value =='') {\n";
echo "			alert('"._ERROR1_1."');\n";
echo "			f.title.focus();\n";
echo "			return false;\n";
echo "		}	\n";
echo "		\n";
echo "		if(f.catid.value == 0) {\n";
echo "			alert('"._ERROR2."');\n";
echo "			f.catid.focus();\n";
echo "			return false;\n";
echo "		}	\n";
echo "		\n";
echo "		f.submit.disabled = true;\n";
echo "		return true;	\n";
echo "	}	\n";
echo "</script>	\n";
if($error!="")
	echo "<div class=\"info\">$error</div>";
echo "<div id=\"pagecontent\">";
echo "<form action=\"modules.php?f=$adm_modname\" method=\"POST\" onsubmit=\"return check(this);\" enctype=\"multipart/form-data\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
echo "<tr><td colspan=\"2\" class=\"header\">Thêm thẻ cào mới</td></tr>";
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>Chọn loại thẻ</b></td>\n";
echo "<td class=\"row2\">$err_cat<select name=\"catid\" onchange=\" show_ajaxcontent_byid( this.value, 'thecao', 'show_menhgia', 'id', 'menhgia')\">\n";
$result_cat = $db->sql_query("SELECT catid, title FROM ".$prefix."_thecao_cat WHERE parentid='0' AND alanguage='$currentlang' ORDER BY weight");
echo "<option name=\"catid\" value=\"0\">Chọn loại thẻ</option>";
$listcat ="";
while(list($cat_id, $titlecat) = $db->sql_fetchrow($result_cat)) {
	if($cat_id == $catid) {$seld =" selected"; }else{ $seld ="";}
	$listcat .= "<option value=\"$cat_id\"$seld>$titlecat</option>";
}
echo $listcat;
echo "</select></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>Mệnh giá</b></td>\n";
echo "<td class=\"row2\"><span id=\"menhgia\">\n";
echo "<select name=\"menhgia\">";
	echo "<option name=\"menhgia\" value=\"0\">Chọn mệnh giá</option>";
	echo "</select>\n";
echo "</span></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>Kích hoạt</b></td>\n";
if($active == 1) {
	echo "<td class=\"row2\"><input type=\"radio\" name=\"active\" value=\"1\" checked>"._YES." &nbsp;&nbsp;";
	echo "<input type=\"radio\" name=\"active\" value=\"0\">"._NO."</td>\n";
	echo "</tr>\n";
} else {
	echo "<td class=\"row2\"><input type=\"radio\" name=\"active\" value=\"1\">"._YES." &nbsp;&nbsp;";
	echo "<input type=\"radio\" name=\"active\" value=\"0\" checked>"._NO."</td>\n";
	echo "</tr>\n";
}

if(empty($id)){
		echo "<tr>\n";
		echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>Mã thẻ</b></td>\n";
		echo "<td class=\"row2\"><textarea cols=\"35\" rows=\"10\" name=\"code\"></textarea>&nbsp;&nbsp; <br> code:serial (1234567890123456:123456789)</td>\n";
		echo "</tr>\n";
	echo "<input type=\"hidden\" name=\"subup\" value=\"1\">";
	echo "<tr><td></td><td><input type=\"submit\" name=\"submit\" value=\""._ADD."\" class=\"button2\"></td></tr>";
}
else{
	echo "<tr>\n";
	echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>Mã thẻ</b></td>\n";
	echo "<td class=\"row2\">$err_code<input type=\"text\" name=\"code\" value=\"$code:$serial\" size=\"30\"> code:serial (1234567890123456:123456789)</td>\n";
	echo "</tr>\n";
	echo "<input type=\"hidden\" name=\"subedit\" value=\"1\">";
	echo "<input type=\"hidden\" name=\"idedit\" value=\"$id\">";
	echo "<tr><td></td><td><input type=\"submit\" name=\"submit\" value=\"Cập nhật\" class=\"button2\"></td></tr>";
}
echo "</table></form></div>";


/////////////////////////////////////////////////

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
	<input type="hidden" name="f" value="thecao" />
	<label for="action">Mã code</label>
	<input type="text" id="s_code" value="" name="s_code" />
	<label for="action">Mã serial</label>
	<input type="text" id="s_serial" value="" name="s_serial" />
	<label for="cat"></label>
	<?php
	$resultcat = $db->sql_query("SELECT catid, title FROM {$prefix}_thecao_cat WHERE active=1 ORDER BY catid");
if($db->sql_numrows($resultcat) > 0) 
{
	echo '<select id="cat" name="s_cat">'."\n";
	echo '<option value="">Chọn loại thẻ</option>';
	$listcat ="";
	while(list($cat_id, $titlecat) = $db->sql_fetchrow($resultcat)) 
	{

		$listcat .= "<option value=\"$cat_id\">$titlecat</option>";
	}
	echo $listcat;
	echo "</select>";
}

	?>
	<label for="action"></label>
	<?php
	$resultcat = $db->sql_query("SELECT id, menhgia,giaban FROM {$prefix}_thecao_menhgia WHERE active=1 ORDER BY id");
	if($db->sql_numrows($resultcat) > 0) 
	{
		echo '<select id="menhgia" name="s_menhgia">'."\n";
		echo '<option value="">Chọn mệnh giá</option>';
		$listcat ="";
		while(list($id, $menhgia,$giaban) = $db->sql_fetchrow($resultcat)) 
		{
	
			$listcat .= "<option value=\"$id\"> $menhgia </option>";
		}
		echo $listcat;
		echo "</select>";
	}

	?>
	<label for="action"></label>
	<select id="s_status" name="s_status">
	<option value="">Trạng thái</option>
	<option value="1">Đã kích hoạt</option>
	<option value="0">Chưa kich hoạt</option>
	</select>
	<select id="s_buy" name="s_buy">
	<option value="">Bán</option>
	<option value="1">Đã bán</option>
	<option value="0">Chưa bán</option>
	</select>
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
////////////////////////////////////////////////////
//$sort = intval(isset($_GET['sort']) ? $_GET['sort'] : (isset($_POST['sort']) ? $_POST['sort']:0));
//switch($sort) {
//	default: $sortby = "ORDER BY time DESC"; break;
//	case 1: $sortby = "ORDER BY id ASC"; break;
//	case 2: $sortby = "ORDER BY id DESC"; break;
//	case 3: $sortby = "ORDER BY time ASC"; break;
//	case 4: $sortby = "ORDER BY time DESC"; break;
//	case 5: $sortby = "ORDER BY hits ASC"; break;
//	case 6: $sortby = "ORDER BY hits DESC"; break;
//}
//$perpage = 20;
//$page = intval(isset($_GET['page']) ? $_GET['page'] : (isset($_POST['page']) ? $_POST['page']:1));
//$offset = ($page-1) * $perpage;
$s_code = isset($_GET["s_code"]) ? $_GET["s_code"] : '';
$s_cat = isset($_GET["s_cat"]) ? $_GET["s_cat"] : "";
$s_quantity=isset($_GET["s_quantity"]) ? $_GET["s_quantity"] : 20;
$s_status=isset($_GET["s_status"]) ? $_GET["s_status"] : '';
$s_buy=isset($_GET["s_buy"]) ? $_GET["s_buy"] : '';
$s_time=isset($_GET["s_time"]) ? $_GET["s_time"] : '';
$s_serial = isset($_GET["s_serial"]) ? $_GET["s_serial"] : '';
$s_menhgia = isset($_GET["s_menhgia"]) ? $_GET["s_menhgia"] : '';

$where="WHERE id > 0 ";
$vlink="";
if(!empty($s_code))
{
	$s_code=trim($s_code);
	$where.=" AND code LIKE '%$s_code%'";
	$vlink.="&code=$s_code";
}
if(!empty($s_cat))
{
	$where.=" AND catid=$s_cat ";
	$vlink.="&cat=$s_cat";
}
if(!empty($s_serial))
{
	$s_serial=trim($s_serial);
	$where.=" AND serial LIKE '%$s_serial%'";
	$vlink.="&serial=$s_serial";
}
if(!empty($s_menhgia))
{
	$s_menhgia=trim($s_menhgia);
	$where.=" AND menhgia =$s_menhgia";
	$vlink.="&s_menhgia=$s_menhgia";
}
if(!empty($s_status))
{
	$where.=" AND active =$s_status";
	$vlink.="&$s_status=$s_status";
}
if(!empty($s_buy))
{
	$where.=" AND buy = $s_buy";
	$vlink.="&$s_buy=$s_buy";
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

	echo "<div id=\"".$adm_modname."_main\"><form action=\"modules.php?f=$adm_modname\" name=\"frm\" method=\"POST\" onsubmit=\"return checkQuick(this);\">";
	echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
	echo "<tr><td colspan=\"10\" class=\"header\">Danh sách thẻ đưa lên</td></tr>";
	echo "<tr>\n";
	echo "<td class=\"row1sd\" width=\"20\" align=\"center\">".sortBy("modules.php?f=$adm_modname",1)."</td>\n";
	echo "<td class=\"row1sd\" width=\"10\"><input type=\"checkbox\" name=\"checkall\" onclick=\"javascript:check_uncheck();\"></td>\n";
	echo "<td class=\"row1sd\">Mã code</td>\n";
	echo "<td class=\"row1sd\">Mã serial</td>\n";
	echo "<td class=\"row1sd\">Loại thẻ</td>\n";
	echo "<td class=\"row1sd\"  align=\"center\" width=\"150\">Mệnh giá</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"100px\">"._TIMEUP." ".sortBy("modules.php?f=$adm_modname",3)."</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"60\">"._STATUS."</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"60\">Bán</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"10px\">"._EDIT."</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"10px\">"._DELETE."</td>\n";
	echo "</tr>\n";
//$countf = $db->sql_fetchrow($db->sql_query("SELECT COUNT(*) FROM {$prefix}_thecao $where"));
//$total = ($countf[0]) ? $countf[0] : 1;
$result = $db->sql_query("SELECT id, catid, code, time, serial, menhgia, active, buy FROM {$prefix}_thecao $where LIMIT $s_quantity");
//die("SELECT id, catid, code, time, serial, menhgia, active FROM {$prefix}_thecao $where $sortby LIMIT $s_quantity");
//$offset,$perpage
if($db->sql_numrows($result) > 0) {

	$i =0;
	$a = 1;
	//if($page > 1) { $a = $perpage*$page - $perpage + 1;}
	while(list($id, $catid, $code, $time, $serial, $menhgia, $active, $buy ) = $db->sql_fetchrow($result)) {
		if($i <10) {
			$css = "row1";
		}	else {
			$css ="row3";
		}

		if($ajax_active == 1) {
			switch($active) {
				case 1: $active = "<a href=\"?f=".$adm_modname."&do=status&id=$id&stat=0\" title=\""._DEACTIVATE."\" onclick=\"return aj_base_status($id,0,'$adm_modname','','');\"><img border=\"0\" src=\"images/view.png\"></a>"; break;
				case 0: $active = "<a href=\"?f=".$adm_modname."&do=status&id=$id&stat=1\" title=\""._ACTIVE."\" onclick=\"return aj_base_status($id,1,'$adm_modname','','');\"><img border=\"0\" src=\"images/viewo.png\"></a>"; break;
			}
		} else {
			switch($active) {
				case 1: $active = "<a href=\"?f=".$adm_modname."&do=status&id=$id&stat=0\" info=\""._DEACTIVATE."\"><img border=\"0\" src=\"images/view.png\"></a>"; break;
				case 0: $active = "<a href=\"?f=".$adm_modname."&do=status&id=$id&stat=1\" info=\""._ACTIVE."\"><img border=\"0\" src=\"images/viewo.png\"></a>"; break;
			}
		}
		switch($buy) {
				case 0: $buy = "<img border=\"0\" src=\"images/view.png\">"; break;
				case 1: $buy = "<img border=\"0\" src=\"images/viewo.png\">"; break;
			}

		echo "<tr>\n";
		echo "<td align=\"center\" class=\"$css\">$a</td>";
		echo "<td class=\"$css\"><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td>";
		echo "<td class=\"$css\"><b>$code</b></td>\n";
		echo "<td class=\"$css\"><b>$serial</b></td>\n";
		echo "<td class=\"$css\"><b>".catname($catid)."</b></td>\n";
		echo "<td class=\"$css\" align=\"left\"><b>".show_menhgia($menhgia)."</b></td>\n";
		echo "<td align=\"center\" class=\"$css\">".ext_time($time, 2)."</td>\n";
		echo "<td align=\"center\" class=\"$css\">$active</td>\n";
		echo "<td align=\"center\" class=\"$css\">$buy</td>\n";
		echo "<td align=\"center\" width=\"30\" class=\"$css\"><a href=\"?f=".$adm_modname."&id=$id\" info=\""._EDIT."\"><img border=\"0\" src=\"../images/edit.gif\"></a></td>\n";
		
		echo "<td align=\"center\" width=\"30\" class=\"$css\"><a href=\"?f=".$adm_modname."&do=delete&id=$id\" info=\""._DELETE."\" onclick=\"return confirm('"._DELETEASK."');\"><img border=\"0\" src=\"images/delete.png\"></a></td>\n";
		
		echo "</tr>\n";
		$i ++;
		$a ++;
	}
	//if($total > $perpage) {
	//	echo "<tr><td colspan=\"9\">";
	//	$pageurl = "modules.php?f=".$adm_modname."&sort=$sort";
	//	echo paging($total,$pageurl,$perpage,$page);
	//	echo "</td></tr>";
	//}
	//echo "<input type=\"hidden\" name=\"do\" value=\"quick_do\">";
	//echo "<tr><td colspan=\"10\" class=\"row3\"><select name=\"fc\">";
	//echo "<option value=\"\">&raquo; "._QUICKDO."</option>";
	//echo "<option value=\"1\">&raquo; "._QUICKDO_1."</option>";
	//echo "<option value=\"2\">&raquo; "._QUICKDO_2."</option>";
	//echo "<option value=\"3\">&raquo; "._QUICKDO_3."</option>";	
	//echo "</select> <input type=\"submit\" class=\"button2\" name=\"submit\" value=\""._DOACTION."\"></form></td></tr>";
	

		

}else{
	
}
echo "</table></div></div>";
include_once("page_footer.php");
?>