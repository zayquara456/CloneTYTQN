<?php
if (!defined('CMS_SYSTEM')) die();
if (!defined('iS_USER') || !isset($userInfo)) header("Location: ".url_sid("index.php?f=user&do=login")."");
$page_title="Mua thẻ Cà Rốt";
include("header.php");
$cryptinstall="captcha/cryptographp.fct.php";
include $cryptinstall;
session_register("ck_carot");
OpenTab("Mua thẻ carot");
echo "<div class=\"content\">";
$active = 1;
$catid = 1;
$title = $content = $email = $name = $err_thecao = $err_cat = $err_menhgia = $error= $err_email =  $error_captcha = '';
if( isset($_POST['subup']) && $_POST['subup'] == 1) {
	$err = 0;
	$catid = intval($_POST['catid']);
	$menhgiaid = intval($_POST['menhgia']);
	$quantity = intval($_POST['quantity']);
	if ($catid == 0) {
		$err_cat = "<font color=\"red\">Mời bạn chọn loại thẻ</font><br>";
		$err = 1;
		$_SESSION['ck_carot']++;
	}
	if ($menhgiaid == 0) {
		$err_menhgia = "<font color=\"red\">Mời bạn chọn mệnh giá</font><br>";
		$err = 1;
		$_SESSION['ck_carot']++;
	}
	//$result=$db->sql_query("SELECT menhgia, giaban FROM {$prefix}_thecao_menhgia WHERE id=$menhgia");
	//if($db->sql_numrows($result) >0) {
	//list($ck_menhgia, $ck_giaban) = $db->sql_fetchrow($result);
	//	if ($ck_giaban > $userInfo['money']) {
	//		$err_menhgia = "<font  color=\"red\">Số tiền bạn không đủ thực hiện giao dịch!</font>";
	//		$err = 1;
	//	}
	//}
	if($_SESSION['ck_carot'] >= 5) {
		if (!chk_crypt($_POST['captcha'])) 
		{
			$err = 1;
			$error_captcha = "<font  color=\"red\">Mã bảo xác nhận không đúng!</font>";
		}
	}
	//kiem tra so luong the cao co du thuc giao dich
	$result=$db->sql_query("SELECT id, code, serial FROM {$prefix}_thecao WHERE buy=0 AND menhgia=$menhgiaid LIMIT $quantity");
	if($db->sql_numrows($result) < $quantity) {
		$err_thecao = "<font color=\"red\">Số lượng thẻ cào không đủ để thực hiện giao dịch, bạn có thể chọn số lượng ít hơn!</font><br>";
		$err = 1;
	}
	
	if (!$err) {
		//lay phan tram khuyen mai cho tai khoan
		$resultpromotion=$db->sql_query("SELECT id, username, thecao, menhgia, promotion, time, active FROM {$prefix}_thecao_promotion WHERE thecao=$catid AND username =".$userInfo['id']." AND active=1");
		if($db->sql_numrows($resultpromotion) > 1) {
			$resulttal=$db->sql_query("SELECT id, username, thecao, menhgia, promotion, time, active FROM {$prefix}_thecao_promotion WHERE thecao=$catid AND menhgia=$menhgiaid AND username=".$userInfo['id']." AND active=1");
			if($db->sql_numrows($resulttal) >0) {
			list($pmt_id, $pmt_username, $pmt_thecao, $pmt_menhgia, $pmt_promotion, $pmt_time, $pmt_active) = $db->sql_fetchrow($resulttal);
			$promotionkm = $pmt_promotion/100;
			}
			else
			{
				$resulttal=$db->sql_query("SELECT id, username, thecao, menhgia, promotion, time, active FROM {$prefix}_thecao_promotion WHERE thecao=$catid AND menhgia=0 AND username=".$userInfo['id']." AND active=1");
				if($db->sql_numrows($resulttal) >0) {
				list($pmt_id, $pmt_username, $pmt_thecao, $pmt_menhgia, $pmt_promotion, $pmt_time, $pmt_active) = $db->sql_fetchrow($resulttal);
				$promotionkm = $pmt_promotion/100;
				}
			}
		}
		elseif($db->sql_numrows($resultpromotion) == 1) {
			list($pmt_id, $pmt_username, $pmt_thecao, $pmt_menhgia, $pmt_promotion, $pmt_time, $pmt_active) = $db->sql_fetchrow($resultpromotion);
			$promotionkm = $pmt_promotion/100;
		}
		elseif($db->sql_numrows($resultpromotion) == 0) {
			$resultnull=$db->sql_query("SELECT id, username, thecao, menhgia, promotion, time, active FROM {$prefix}_thecao_promotion WHERE thecao=$catid AND menhgia=$menhgiaid AND username=0 AND active=1");
			if($db->sql_numrows($resultnull) >0) {
				list($pmt_id, $pmt_username, $pmt_thecao, $pmt_menhgia, $pmt_promotion, $pmt_time, $pmt_active) = $db->sql_fetchrow($resultnull);
				$promotionkm = $pmt_promotion/100;
			}
			else
			{
				$resultnull=$db->sql_query("SELECT id, username, thecao, menhgia, promotion, time, active FROM {$prefix}_thecao_promotion WHERE thecao=$catid AND menhgia=0 AND username=0 AND active=1");
				if($db->sql_numrows($resultnull) >0) {
					list($pmt_id, $pmt_username, $pmt_thecao, $pmt_menhgia, $pmt_promotion, $pmt_time, $pmt_active) = $db->sql_fetchrow($resultnull);
					$promotionkm = $pmt_promotion/100;
				}
				
			}
		}
		//kiem tra tai khoan du tien thuc hien giao dich
		$result=$db->sql_query("SELECT menhgia, giaban FROM {$prefix}_thecao_menhgia WHERE id=$menhgiaid");
		list($g_menhgia, $g_giaban) = $db->sql_fetchrow($result);
		{
			$totalorder=($g_giaban*$promotionkm)*$quantity;
			if($totalorder > $userInfo['money'])
			{
				$error .= "<font  color=\"red\">Số tiền bạn không đủ thực hiện giao dịch!</font>";
				$err = 1;
			}
			elseif($totalorder <= $userInfo['money'])
			{
				$money_old = $userInfo['money'];
				$moneynew = $money_old - $totalorder;
				//tru tien tai khoan
				$db->sql_query("UPDATE {$prefix}_user SET money=$moneynew WHERE id=".$userInfo['id']."");
				//cap nhat don hang
				$result=$db->sql_query("SELECT id, code, serial FROM {$prefix}_thecao WHERE buy=0 AND menhgia=$menhgiaid LIMIT $quantity");
				if($db->sql_numrows($result) == $quantity) {
					for($i=1; $i<=$quantity; $i++)
					{
						//lay ma the 
						$result=$db->sql_query("SELECT id, code, serial FROM {$prefix}_thecao WHERE buy=0 AND menhgia=$menhgiaid ORDER BY id ASC LIMIT 1");
						if($db->sql_numrows($result) == 1) {
							list($g_id, $g_code, $g_serial) = $db->sql_fetchrow($result);
							//lay gia ban hien tai
							$result=$db->sql_query("SELECT menhgia, giaban FROM {$prefix}_thecao_menhgia WHERE id=$menhgiaid");
							list($g_menhgia, $g_giaban) = $db->sql_fetchrow($result);
							$price=$g_giaban*$promotionkm;
							$code= $g_code;
							$serial=$g_serial;
							$query = "INSERT INTO {$prefix}_thecao_buy (id, code, serial, time, price, userid) VALUES (NULL, '$code', '$serial', ".time().",'$price', ".$userInfo['id'].")";
							$result = $db->sql_query($query);
							
							//cap nhat the da ban
							$db->sql_query("UPDATE {$prefix}_thecao SET buy=1 WHERE id=$g_id");
							$money_new = $money_old - $price;
							updateuserlog($userInfo['id'],"Mua thẻ", $money_old, $price, $money_new,"-","Mua thẻ cà rốt code: $code | serial: $serial");
							$money_old = $money_old - $price;
							echo "<script language=\"javascript\" type=\"text/javascript\">";
							echo "alert('Mua thẻ cào thành công!');";
							echo "window.location.href=\"index.php?f=".$module_name."&do=carot\"";
							echo "</script>";
							unset($_SESSION['ck_carot']);
						}
						
					}
				}
			}
		}
		
	}
}


echo "<form autocomplete=\"off\" action=\"index.php?f=$module_name&do=$do\" method=\"POST\" onsubmit=\"return Check_Valid(this);\">";
//echo "<div class=\"breakcoup\"  style=\"font-size:15px;padding-left:10px;width:auto;float:left;border:0px;\">"._ADDQUESTION."</div>";		
echo "<table class=\"tableborder\" cellpadding=\"5\" cellspacing=\"0\" border=\"0\" width=\"100%\">\n";
if(isset($error)){
echo "<tr>\n";
echo"<tr><td></td><td>$error</td></tr>	";
}
echo "<tr>\n";
echo"<tr><td></td><td>$err_thecao</td></tr>	";
echo "<td width=\"80px\" class=\"row1\">Loại thẻ:</td>\n";
echo "<td class=\"row1\">";
echo "<select name=\"catid\" onchange=\" show_ajaxcontent_byid( this.value, 'napthe', 'show_menhgia', 'id', 'menhgia')\"  style=\"width:250px\">\n";
$result_cat = $db->sql_query("SELECT catid, title FROM ".$prefix."_thecao_cat WHERE parentid='0' AND alanguage='$currentlang' ORDER BY weight");
echo "<option name=\"catid\" value=\"0\">Chọn loại thẻ</option>";
$listcat ="";
while(list($cat_id, $titlecat) = $db->sql_fetchrow($result_cat)) {
	if($cat_id == $catid) {$seld =" selected"; }else{ $seld ="";}
	$listcat .= "<option value=\"$cat_id\" $seld>$titlecat</option>";
}
echo $listcat;
echo "</select>";
echo "$err_cat</td>";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"80px\" class=\"row1\">Mệnh giá:</td>\n";
echo "<td width=\"80px\" class=\"row1\"><span id=\"menhgia\">\n";
echo "<select name=\"menhgia\"  style=\"width:250px\">";
	echo "<option name=\"menhgia\" value=\"0\">Chọn mệnh giá</option>";
$result = $db->sql_query("SELECT id, menhgia, giaban FROM ".$prefix."_thecao_menhgia WHERE catid='$catid' ORDER BY id");
	$select1 = "";
	while(list($id_mg, $menhgia_mg, $giaban_mg) = $db->sql_fetchrow($result_list)) {
		if($menhgiaid == $id_mg){$select1 = "selected";}
		else{$select1 = "";}
		echo "<option value=\"$id_mg\" $select1>$menhgia_mg</option>";
	}
	echo "</select>\n";
echo "</span>$err_menhgia</td>\n";
echo "</td></tr>\n";
echo "<tr>\n";
echo "<td class=\"row1\">Số lượng:</td>\n";
echo "<td class=\"row1\">";
echo '<select class="ddl" id="quantity" name="quantity"  style="width:250px">';
for($i=1;$i<=10;$i++)
{
	if ($quantity==$i){$selected='selected="selected"';}
	else {$selected='';}
	echo "<option value=\"$i\" $selected>$i</option>";
}
echo "</td>\n";
if($_SESSION['ck_carot'] >=5) {
?>
<tr>
<td class="row1">Mã xác nhận:<span class="risk">*</span></td>
<td class="row1"><input type="text" name="captcha"  id="captcha" size="10">
<?php dsp_crypt(0,1); ?><?echo $error_captcha?>
</td>
</tr>
<?php
}
echo "</tr>\n";
echo "<tr><td>&nbsp;</td><td style=\"padding:5px 3px 3px 130px;\"><input type=\"hidden\" name=\"subup\" value=\"1\"><input class=\"sb_but1\" type=\"submit\" name=\"submit\" value=\"Xác nhận\" ></td></tr>";
echo "</table>";

echo"</form>";

$result = $db->sql_query("SELECT content FROM ".$prefix."_gentext WHERE textname='carot' AND alanguage='$currentlang'");
list($content) = $db->sql_fetchrow($result);
?>
<div><?php echo $content?></div>
<?php

echo "</div>";
CloseTab();


OpenTab("Danh sách thẻ đã mua");
echo "<div class=\"content\">";
$perpage = 15;
$page = isset($_GET['page']) ? intval($_GET['page']) : (isset($_POST['page']) ? intval($_POST['page']) :1);
$from = isset($_GET["from"]) ? $_GET["from"] : "";
$to = isset($_GET["to"]) ? $_GET["to"] : "";
$action = isset($_GET["action"]) ? $_GET["action"] : "";
$offset = ($page-1) * $perpage;

$total = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_thecao_buy WHERE userid=".$userInfo['id']." "));
$result = $db->sql_query("SELECT  id, code, serial, time, price  FROM ".$prefix."_thecao_buy WHERE userid=".$userInfo['id']." ORDER BY time DESC LIMIT $offset, $perpage");
?>

<?php
if($db->sql_numrows($result) > 0) {

echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
echo "<tr style=\"background:#f9f9f9\">\n";
echo "<td class=\"row1sd\" width=\"60\">Thời gian giao dịch</td>\n";
echo "<td class=\"row1sd\" width=\"50\">Mã code</td>\n";
echo "<td class=\"row1sd\"  align=\"left\" width=\"10\">Mã serial</td>\n";
echo "<td class=\"row1sd\" align=\"right\" width=\"50\">Số tiền (VNĐ)</td>\n";
echo "</tr>\n";
$cur_ar = array(_VND,_USD);
$i =0;
while(list($id, $code, $serial,$time, $price) = $db->sql_fetchrow($result)) {
if($i%2 == 1) {
	$css = "row1";
	$style_css="style=\"background:#f9f9f9;\"";
	}
else {
	$css ="row3";
	$style_css="style=\"background:#ffffff;\"";
}	
echo "<tr $style_css>\n";
echo "<td class=\"$css\">".ext_time($time, 2)."</td>\n";
echo "<td class=\"$css\">$code</td>\n";
echo "<td class=\"$css\"  align=\"left\">$serial</td>\n";
echo "<td class=\"$css\" align=\"right\"><font color=\"red\">".bsVndDot($price)."</font></td>\n";
echo "</tr>\n";
$i ++;	
}


echo "<tr><td class=\"row4\" colspan=\"9\">";
if($total > $perpage) {
	echo "<div class=\"fr\">";	
	$pageurl = "index.php?f=napthe&do=carot";
	echo paging($total,$pageurl,$perpage,$page);
	echo "</div>";
}	
echo "</td></tr>";
echo "</table></form>";
}else{
	echo "<center>Chưa phát sinh giao dịch.</center>";
}
echo "</div>";

CloseTab();
include("footer.php");
?>

