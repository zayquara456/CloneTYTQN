<?php
if (!defined('CMS_SYSTEM')) die();
if (!defined('iS_USER') || !isset($userInfo)) header("Location: ".url_sid("index.php?f=user&do=login")."");
$page_title="Rút tiền vào ngân hàng";
global $db, $prefix, $currentlang, $module_name, $userInfo;
include("header.php");
$cryptinstall="captcha/cryptographp.fct.php";
include $cryptinstall; 
OpenTab("Rút tiền vào ngân hàng");
$bankcode = $txt_accountname = $txt_accountnumber = $txt_note = $txt_money = '';
$err_bankcode = $err_accountname = $err_accountnumber = $err_note = $err_money = $error_captcha='';
if( isset($_POST['subup']) && $_POST['subup'] == 1) {
	$err = 0;
	$bankcode			= nospatags(($_POST['bankcode']));
	$txt_accountname	= nospatags(($_POST['txt_accountname']));
	$txt_accountnumber	= nospatags($_POST['txt_accountnumber']);
	$txt_note			= nospatags($_POST['txt_note']);
	$txt_money			= intval($_POST['txt_money']);

	if (empty($bankcode)) {
		$err_bankcode = "<font color=\"red\">Mời bạn chọn ngân hàng!</font>";
		$err = 1;
	}
	if (empty($txt_accountname)) {
		$err_accountname = "<font color=\"red\">Mời bạn nhập chủ tài khoản nhận tiền!</font>";
		$err = 1;
	}
	if (empty($txt_accountnumber)) {
		$err_accountnumber = "<font color=\"red\">Mời bạn nhập số tài khoản nhận tiền!</font>";
		$err = 1;
	}
	if (empty($txt_money) && !is_number($txt_money)) {
		$err_money = "<font  color=\"red\">Số tiền bạn nhập không đúng!</font>";
		$err = 1;
	}
	if ($txt_money < 200000) {
		$err_money = "<font  color=\"red\">Số tiền bạn nhập phải lớn hơn 200.000!</font>";
		$err = 1;
	}
	if ($txt_money > $userInfo['money']) {
		$err_money = "<font  color=\"red\">Số tiền bạn nhập vượt quá giới hạn tài khoản!</font>";
		$err = 1;
	}
	if (empty($txt_note)) {
		$err_note = "<font  color=\"red\">Mời bạn nhập ghi chú!</font>";
		$err = 1;
	}
	if (!chk_crypt($_POST['captcha'])) 
		{
			$err = 1;
			$error_captcha = "<font  color=\"red\">Mã bảo xác nhận không đúng!</font>";
		}
	if (!$err) {
		$query = "INSERT INTO {$prefix}_transfer_bank (userid, bank, account, code, note, money, status, time) VALUES (".$userInfo['id'].",'$bankcode','$txt_accountname','$txt_accountnumber','$txt_note','$txt_money',0,".time().")";
		$db->sql_query($query);
		//updateuserlog($userInfo['id'],"Rút tiền",$txt_money,"-","Rút tiền vào ngân hàng tài khoản ".CutString($txt_accountnumber,8)."(Giao dịch đang chờ xử lý)");
		
		?>
		<script language="javascript" type="text/javascript">
			alert('Giao dịch đang chờ xác nhận! hệ thống tự động xử lý giao dịch trong vòng 24h.');
			window.location.href="index.php?f=<?php echo $module_name ?>&do=abstract";
		</script>
		<?php
	}
}


?>
<div class="content">
<form autocomplete="on" action="index.php?f=<?php echo $module_name ?>&do=<?php echo $do ?>" method="POST">
<table class="tableborder" cellpadding="5" cellspacing="0" border="0" width="100%">
<tr>
<td width="80px" class="row1">Ngân hàng:</td>
<td class="row1">
	<?php
		function ck_bankcode($value,$reg){
			if($value==$reg){echo "selected=\"selected\"";}
			else{echo "";}
		}
	?>
	<select class="ddl" id="bankcode" name="bankcode">
		<option <?php ck_bankcode($bankcode,"");?> value="">Chọn ngân hàng</option>
		<option <?php ck_bankcode($bankcode,"VCB");?> value="VCB">Vietcombank</option>
		<option <?php ck_bankcode($bankcode,"DAB");?> value="DAB">Đông Á Bank</option>
		<option <?php ck_bankcode($bankcode,"MSB");?> value="MSB">MaritimeBank</option>
		<option <?php ck_bankcode($bankcode,"VTB");?> value="VTB">Vietinbank</option>
		<option <?php ck_bankcode($bankcode,"BIDV");?> value="BIDV">BIDV</option>
		<option <?php ck_bankcode($bankcode,"AGR");?> value="AGR">Agribank</option>
		<option <?php ck_bankcode($bankcode,"MILI");?> value="MILI">MilitariBank</option>
	</select><?php echo $err_bankcode?>
</td>
</tr>
<tr>
<td width="80px" class="row1">Tên chủ tài khoản nhận tiền:</td>
<td width="80px" class="row1">
	<input type="text" id="txt_accountname" name="txt_accountname" value="<?php echo $txt_accountname?>" size="40"><?php echo $err_accountname ?>
</td></tr>
<tr>
<td class="row1">Số tài khoản nhận tiền:</td>
<td class="row1">
	<input type="text" id="txt_accountnumber" name="txt_accountnumber"  value="<?php echo $txt_accountnumber?>" size="40"><?php echo $err_accountnumber?></td>
</tr>
<tr>
<td class="row1">Số tiền cần rút:</td>
<td class="row1"><input type="text" id="txt_money" name="txt_money" value="<?php echo $txt_money?>"  size="40"><?php echo $err_money?></td>
</tr>
<tr>
<td class="row1">Ghi chú:</td>
<td class="row1">
	<input type="text" id="txt_note" name="txt_note" value="<?php echo $txt_note?>" size="40"><?echo $err_note?></td>
</tr>
<tr>
<td class="row1">Mã xác nhận:<span class="risk">*</span></td>
<td class="row1"><input type="text" name="captcha"  id="captcha" size="10">
<?php dsp_crypt(0,1); ?><?echo $error_captcha?>
</td>
</tr>
<tr><td>&nbsp;</td>
	<td >
		<input type="hidden" name="subup" value="1">
		<input class="sb_but1" type="submit" name="submit" value="Xác nhận" >
	</td></tr>
</table>
</form>
<?php
$result = $db->sql_query("SELECT content FROM ".$prefix."_gentext WHERE textname='abstract' AND alanguage='$currentlang'");
list($content) = $db->sql_fetchrow($result);
?>
<div><?php echo $content?></div>
</div>
<?php
CloseTab();
include("footer.php");
?>

