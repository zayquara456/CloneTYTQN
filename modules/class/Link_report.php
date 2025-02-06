<?php
if (!defined('CMS_SYSTEM')) die();
global $db, $time, $prefix, $currentlang, $path_upload, $titlelink, $Default_Temp, $urlsite;
$id = $_GET["did"];

if(isset($_POST['subup'])&& $_POST['subup'] == 1) {
	$err = 0;
	$content = $escape_mysql_string(trim($_POST['txtAddedContent']));
	$name = $escape_mysql_string(trim($_POST['txtAddedBy']));
	$email = $escape_mysql_string(trim($_POST['txtAddedByEmail']));
	$docid = intval($_POST['docid']);
	$url= url_sid("index.php?f=document&do=detail&id=$docid");
	$title=$escape_mysql_string(trim($_POST['txtTitle']));
	
	if(empty($content)) {
		$err_title = "<font color=\"red\">Mời bạn nhập nội dung</font><br>";
		$err = 2;
	}
	if(empty($email) || $email=="Email") {
		$err_title = "<font color=\"red\">Mời bạn nhập email</font><br>";
		$err = 3;
	}
	if(empty($name) || $name=="Họ tên") {
		$err_title = "<font color=\"red\">Mời bạn nhập Họ Tên</font><br>";
		$err = 4;
	}

	if(!$err) {
		//upload file attach
		$db->sql_query("INSERT INTO ".$prefix."_link_report (docid, time, name, email, url, title, content, status) VALUES ($docid, '".time()."',  '$name', '$email', '$url','$title','$content',0)");
		//fixweight_cat();
		//updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _CREATE_NEWS_TOPIC);
		//$err_title="<b>Lỗi của bạn đã được gửi. Xin cảm ơn!</b>";
		echo "<script language=\"javascript\" type=\"text/javascript\">";
		echo "alert('Lỗi của bạn đã được gửi. BQT sẽ khắc phục và trả lời bạn trong thời gian sớm nhất. Xin cảm ơn!');";
		echo "window.location.href=\"index.php?f=document&do=link_report&did=$id\"";
		echo "</script>";
	}
}
show_form_comment();
function show_form_comment()
{
	global $err_title, $id, $Default_Temp,$userInfo;
	if($userInfo['id']=="")
	{
	$email="Email";
	$title="Tiêu đề";
	$name="Họ tên";	
	}
	else{
		$email=$userInfo['email'];
		$title="Tiêu đề";
		$name=$userInfo['fullname'];
	}
	
?>
<html><head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8"/>
<script language="javascript" type="text/javascript">
function submitForm(theform){
		document.frmComment.txtAddedBy.value = Trim(document.frmComment.txtAddedBy.value);
		if (document.frmComment.txtAddedBy.value == '' || document.frmComment.txtAddedBy.value == 'Họ tên')
		{
			alert('Xin hay nhap Ho ten!');
			document.frmComment.txtAddedBy.focus();
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
			alert('Xin hay nhap Tieu de!');
			document.frmComment.txtAddedTitle.focus();
			return;
		}
		
		document.frmComment.txtValidCode.value = Trim(document.frmComment.txtValidCode.value);
		if (document.frmComment.txtValidCode.value == '' || document.frmComment.txtValidCode.value == 'Mã xác nhận')
		{
			alert('Xin hay nhap Ma xac nhan!');
			document.frmComment.txtValidCode.focus();
			return;
		}

		document.frmComment.txtAddedContent.value = Trim(document.frmComment.txtAddedContent.value);
		if (document.frmComment.txtAddedContent.value == '')
		{
			alert('Xin hay nhap Noi dung!');
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
	if(field.value=='Họ tên'){ field.value = ''; field.className = 'adword-textbox2'}
}

function NameOnBlur(field)
{
	if(field.value==''){ field.value='Họ tên'; field.className = 'adword-textbox'}
}

function EmailOnFocus(field)
{
	if(field.value=='Email'){ field.value = ''; field.className = 'adword-textbox2'}
}

function EmailOnBlur(field)
{
	if(field.value==''){ field.value='Email'; field.className = 'adword-textbox'}
}

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
<div class="comments">
	<form action="index.php?f=document&do=link_report&did=<?php echo $id ?>" method="POST"  enctype="multipart/form-data"  name="frmComment" id="frmComment" onsubmit="submitForm(this);return false;">
	<div style="padding-top: 0px;" class="adword adword-middle"><div style="font-family: arial; font-weight: bold; font-size: 12px; color: #ff0000"><?php echo $err_title ?></div>
			<div style="padding-top: 10px;" class="adword-nav2 fl">
				<div class="header-comment">Báo lỗi link hỏng:</div>
				<div style="padding-bottom: 5px; overflow: hidden;">
					<div style="margin-bottom: 20px" >
						<input type="radio" class="adword-textbox" id="txtTitle" name="txtTitle" value="Tài liệu lỗi không tải được." gtbfieldid="4" checked=checked>Tài liệu lỗi không tải được.</input><br/>
						<input type="radio" class="adword-textbox" id="txtTitle" name="txtTitle" value="Tài liệu tải về không mở được." gtbfieldid="4">Tài liệu tải về không mở được.</input><br/>
						<input type="radio" class="adword-textbox" id="txtTitle" name="txtTitle" value="Tài liệu tải về không đúng với mô tả." gtbfieldid="4">Tài liệu tải về không đúng với mô tả.</input><br/>
						<input type="radio" class="adword-textbox" id="txtTitle" name="txtTitle" value="Lỗi không tải được bằng IDM (Internet download manager)" gtbfieldid="4">Lỗi không tải được bằng IDM (Internet download manager)</input><br/>
						<input type="radio" class="adword-textbox" id="txtTitle" name="txtTitle" value="Lỗi khác" gtbfieldid="4">Lỗi khác.</input><br/>
					</div>
					<div style="width:280px;margin-bottom: 20px" >
						<input type="text" class="adword-textbox" onkeyup="initTyper(this)" onblur="NameOnBlur(this)" onfocus="NameOnFocus(this)" size="29" style="width: 250px;" id="txtAddedBy" name="txtAddedBy" value="<?php echo $name ?>" gtbfieldid="4">
					</div>
					<div style="width: 280px;margin-bottom: 20px" >
						<input type="text" class="adword-textbox" onblur="EmailOnBlur(this)" onfocus="EmailOnFocus(this)" size="29" style="width: 250px;" id="txtAddedByEmail" name="txtAddedByEmail" value="<?php echo $email ?>" gtbfieldid="5">
					</div>
				</div>
				<div style="padding-top: 0px;margin-bottom: 20px" class="adword-nav2 fl">
				<textarea style="width: 280px;" class="SForm" onkeyup="initTyper(this)" id="txtAddedContent" name="txtAddedContent" rows="5"></textarea>
				<input type="hidden" name="subup" value="1">
				<input type="hidden" value="<?php echo $id ?>" id="docid" name="docid">
			</div>
			</div>

			
			<div><input type="Submit" class="SForm" name="B1" value="Gửi lỗi"></div>
		</div></form><div class="cl"></div>

</div></body></html>
<?php
}
?>