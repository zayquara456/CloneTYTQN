<?php
if (!defined('CMS_SYSTEM')) die();
global $db, $time, $prefix, $currentlang, $path_upload, $titlelink, $Default_Temp, $urlsite;
$id = $_GET["id"];
$err_title=$name=$address=$phone=$email=$job=$level=$message="";
$email="Email";
$title="Tiêu đề";
$name="Họ và tên";
$phone="Điện thoại";
$address="Địa chỉ";
if(isset($_POST['subup'])&& $_POST['subup'] == 1) {
	$err = 0;
	$content	= $escape_mysql_string(trim($_POST['txtAddedContent']));
	$name		= $escape_mysql_string(trim($_POST['txtName']));
	$address	= $escape_mysql_string(trim($_POST['txtAddress']));
	$phone		= $escape_mysql_string(trim($_POST['txtPhone']));
	$email		= $escape_mysql_string(trim($_POST['txtEmail']));
	$job		= $escape_mysql_string(trim($_POST['sltJob']));
	$level		= $escape_mysql_string(trim($_POST['sltLevel']));
	$message	= $escape_mysql_string(trim($_POST['message']));
	
	$classid	= intval($_POST['classid']);
	$url		= url_sid("index.php?f=class&do=detail&id=$classid");
	//$title		=$escape_mysql_string(trim($_POST['txtTitle']));
	
	if(empty($name) || $name=="Họ và tên") {
		$err_title .= "<font color=\"red\">Mời bạn nhập Họ Tên</font><br>";
		$err = 1;
	}
	if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$err_title .= "<font color=\"red\">Mời bạn nhập email</font><br>";
		$err = 1;
	}
//	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
//  $emailErr = "Invalid email format";
//}
	if(empty($phone) || $phone=="Điện thoại") {
		$err_title .= "<font color=\"red\">Mời bạn nhập số điện thoại</font><br>";
		$err = 1;
	}
	if(empty($address) || $address=="Địa chỉ") {
		$err_title .= "<font color=\"red\">Mời bạn nhập số địa chỉ</font><br>";
		$err = 1;
	}
	if(empty($job)) {
		$err_title .= "<font color=\"red\">Mời chọn ngành nghề</font><br>";
		$err = 1;
	}
	if(empty($level)) {
		$err_title .= "<font color=\"red\">Mời bạn đối tượng</font><br>";
		$err = 1;
	}
	//if(empty($message)) {
	//	$err_title .= "<font color=\"red\">Mời bạn nhập nội dung</font><br>";
	//	$err = 1;
	//}
	if(!$err) {
		//upload file attach
		//$db->sql_query("INSERT INTO ".$prefix."_link_report (docid, time, name, email, url, title, content, status) VALUES ($docid, '".time()."',  '$name', '$email', '$url','$title','$content',0)");
		$db->sql_query("INSERT INTO ".$prefix."_class_member(id, class_id, full_name, address, email, phone, job, level, add_work, class, content, timed, status) VALUES (NULL, $classid, '$name', '$address','$email', '$phone','$job','$level','$address',0,'$message','".time()."',1)");
		//updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _CREATE_NEWS_TOPIC);
		//$err_title="<b>Lỗi của bạn đã được gửi. Xin cảm ơn!</b>";
		echo "<script language=\"javascript\" type=\"text/javascript\">";
		echo "alert('Bạn đã đăng ký thành công. BQT sẽ liên hệ với bạn trong thời gian sớm nhất. Xin cảm ơn!');";
		echo "window.location.href=\"index.php?f=class&do=reg_class&id=$id\"";
		echo "</script>";
	}
	else
	{

	}
}
?>
<html><head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8"/>
<link rel="StyleSheet" type="text/css" href="http://thuvienxaydung.net/templates/Adoosite/css/styles.css"/>
<script language="javascript" type="text/javascript">
function submitForm(theform){
		document.frmComment.txtName.value = Trim(document.frmComment.txtName.value);
		if (document.frmComment.txtName.value == '' || document.frmComment.txtName.value == 'Họ và tên')
		{
			alert('Moi hay nhap Ho ten!');
			document.frmComment.txtName.focus();
			return;
		}

		if ((SEmail = CheckEmailAddress(document.frmComment.txtAddedByEmail.value))=='')
		{
			alert('Dia chi Email khong hop le!');
			document.frmComment.txtAddedByEmail.focus();
			return;
		}

		document.frmComment.txtAddedByEmail.value = SEmail;

		document.frmComment.txtAddedTitle.value = Trim(document.frmComment.txtAddedTitle.value);
		if (document.frmComment.txtAddedTitle.value == '' || document.frmComment.txtAddedTitle.value == 'Tiêu đề')
		{
			alert('Moi hay nhap Tieu de!');
			document.frmComment.txtAddedTitle.focus();
			return;
		}
		
		document.frmComment.txtValidCode.value = Trim(document.frmComment.txtValidCode.value);
		if (document.frmComment.txtValidCode.value == '' || document.frmComment.txtValidCode.value == 'Mã xác nhận')
		{
			alert('Moi hay nhap Ma xac nhan!');
			document.frmComment.txtValidCode.focus();
			return;
		}

		document.frmComment.txtAddedContent.value = Trim(document.frmComment.txtAddedContent.value);
		if (document.frmComment.txtAddedContent.value == '')
		{
			alert('Moi hay nhap Noi dung!');
			document.frmComment.txtAddedContent.focus();
			return;
		}

		if (!confirm('Gui yeu cau?'))
			return;
			
		var status = AjaxRequest.submit(
			theform
			,{
				'onSuccess':function(req){
					if(req.responseText=='ValidCode'){
						alert('Ma xac nhan khong dung!');
					}
					else{
						alert(req.responseText);
						ResetDefault();
					}
				}
				,'onError':function(req){
					alert(req.responseText);
				}
			}
		);
		return status;
	}
	

function NameOnFocus(field)
{
	if(field.value=='Họ và tên'){ field.value = ''; field.className = 'adword-textbox2'}
}

function NameOnBlur(field)
{
	if(field.value==''){ field.value='Họ và tên'; field.className = 'adword-textbox'}
}

function EmailOnFocus(field)
{
	if(field.value=='Email'){ field.value = ''; field.className = 'adword-textbox2'}
}

function EmailOnBlur(field)
{
	if(field.value==''){ field.value='Email'; field.className = 'adword-textbox'}
}
///////////////////
function PhoneOnFocus(field)
{
	if(field.value=='Điện thoại'){ field.value = ''; field.className = 'adword-textbox2'}
}

function PhoneOnBlur(field)
{
	if(field.value==''){ field.value='Điện thoại'; field.className = 'adword-textbox'}
}
/////////////////////////////
function AddOnFocus(field)
{
	if(field.value=='Địa chỉ'){ field.value = ''; field.className = 'adword-textbox2'}
}

function AddOnBlur(field)
{
	if(field.value==''){ field.value='Địa chỉ'; field.className = 'adword-textbox'}
}
/////////////////////////////
function TitleOnFocus(field)
{
	if(field.value=='Tiêu đề'){ field.value = ''; field.className = 'adword-textbox2'}
}

function TitleOnBlur(field)
{
	if(field.value==''){ field.value='Tiêu đề'; field.className = 'adword-textbox'}
}

function ValidateCodeOnFocus(field)
{
	if(field.value=='Mã xác nhận'){ field.value = ''; field.className = 'adword-textbox2'}
}

function ValidateCodeOnBlur(field)
{
	if(field.value==''){ field.value='Mã xác nhận'; field.className = 'adword-textbox'}
}
</script>
<style type="text/css">
.header-comment{font-family: arial; font-size: 13px; font-weight: bold; margin-bottom: 10px}
</style>
</head><body>
<div class="reg_class" style="padding: 20px;background: #fff">
	<form action="index.php?f=class&do=reg_class&id=<?php echo $id ?>" method="POST"  enctype="multipart/form-data"  name="frmComment" id="frmComment" onsubmit="submitForm(this);return false;">
	<div style="padding-top: 0px;" class="adword adword-middle"><div style="font-family: arial; font-weight: bold; font-size: 12px; color: #ff0000"><?php echo $err_title ?></div>
			<div style="padding-top: 10px;" class="adword-nav2 fl">
				<div style="padding-bottom: 5px; overflow: hidden;">
					<div style="width:280px;margin-bottom: 20px" >
						<input type="text" class="adword-textbox" onkeyup="initTyper(this)" onblur="NameOnBlur(this)" onfocus="NameOnFocus(this)" size="29" style="width: 250px;" id="txtName" name="txtName" value="<?php echo $name ?>" gtbfieldid="4">
					</div>
					<div style="width: 280px;margin-bottom: 20px" >
						<input type="text" class="adword-textbox" onblur="EmailOnBlur(this)" onfocus="EmailOnFocus(this)" size="29" style="width: 250px;" id="txtEmail" name="txtEmail" value="<?php echo $email ?>" gtbfieldid="5">
					</div>
					<div style="width: 280px;margin-bottom: 20px" >
						<input type="text" class="adword-textbox" onblur="AddOnBlur(this)" onfocus="AddOnFocus(this)" size="29" style="width: 250px;" id="txtAddress" name="txtAddress" value="<?php echo $address ?>" gtbfieldid="5">
					</div>
					<div style="width: 280px;margin-bottom: 20px" >
						<input type="text" class="adword-textbox" onblur="PhoneOnBlur(this)" onfocus="PhoneOnFocus(this)" size="29" style="width: 250px;" id="txtPhone" name="txtPhone" value="<?php echo $phone ?>" gtbfieldid="5">
					</div>
					<div style="width: 280px;margin-bottom: 20px" >
						<select name="sltLevel" id="">
							<option value="">Đối tượng</option>
							<option value="Người đi làm">Người đi làm</option>
							<option value="Sinh viên">Sinh viên</option>
							<option value="Khác">khác...</option>
						</select>
					</div>
					<div style="width: 280px;margin-bottom: 20px" >
						<select name="sltJob" id="">
							<option value="">Ngành nghề</option>
							<option value="Kiến trúc sư công trình">Kiến trúc sư công trình</option>
							<option value="Kiến trúc sư quy hoạch">Kiến trúc sư quy hoạch</option>
							<option value="Kỹ sư hạ tầng">Kỹ sư hạ tầng</option>
							<option value="Kỹ sư giao thông">Kỹ sư giao thông</option>
							<option value="Kỹ sư điện">Kỹ sư điện</option>
							<option value="Khác">khác...</option>
						</select>
					</div>
				</div>
				<div style="padding-top: 0px;margin-bottom: 20px" class="adword-nav2">
				<textarea name="message" id="message" style="width: 280px;" class="SForm" onkeyup="initTyper(this)" id="txtAddedContent" name="txtAddedContent" rows="5" onfocus="if(this.value==this.defaultValue)this.value='';" onblur="if(this.value=='')this.value=this.defaultValue;">Yêu cầu khác</textarea>
				<input type="hidden" name="subup" value="1">
				<input type="hidden" value="<?php echo $id ?>" id="classid" name="classid">
			</div>

			<div><input type="Submit" class="sb_but1" name="B1" value="Đăng ký"></div>
		</div></form><div class="cl"></div>
			</div>
</div></body></html>
<?php

?>