<?php
$ck_user = $_SESSION[USER_SESS];
if (!defined('CMS_SYSTEM')) die();
if (!defined('iS_USER') || !isset($userInfo) || !isset($ck_user)){
	header("Location: ".url_sid("index.php?f=user&do=login")."");
	die("loi dang nhap");
}
$page_title = "Thêm tài liệu mới";
global $urlsite, $path_upload;
$active = 0;
$error=$info= "";
if(isset($_POST['subup'])&& $_POST['subup'] == 1) {
	$err 		= 0;
	$fileupload	= $_FILES['fileupload'];
	$title		= $escape_mysql_string($_POST['txt_title']);
	$price		= intval($_POST['txt_price']);
	$catid		= intval($_POST['catid']);
	$bodytext	= $escape_mysql_string($_POST['txt_desc']);
	//check title
	if(empty($fileupload)){
		$error .="Mời bạn chọn file tài liệu!<br>";
		$err	= 1;
	}
	if(empty($title)){
		$error .="Mời bạn nhập tiêu đề!<br>";
		$err	= 1;
	}
	if($catid==0){
		$error .="Mời bạn chọn chủ đề!<br>";
		$err	= 1;
	}
	if(empty($bodytext)){
		$error .="Mời nhập miêu tả!<br>";
		$err	= 1;
	}
	if(empty($price)){
		$error .="Mời nhập giá!<br>";
		$err	= 1;
	}
	
	if(!$err) {
		$id			= gen_id('document');
		$guid		= 'index.php?f=document&do=detail&id='.$id;
		$permalink	= gen_permalink(url_optimization(trim($title)),'document');
		$folder		= $userInfo['folder'];
		$userid		= $userInfo['id'];
		if($folder==''){$folder	= 'guest';}
		if($userid==''){$userid	= 0;}
		
		if (is_uploaded_file($_FILES['fileupload']['tmp_name'])) {
			$path_upload_img	= $path_upload.'/document/'.$folder;
			$newnamefile		= substr(str_replace("-","_",$permalink),0,60);
			$upload				= new Upload('fileupload', $path_upload_img, $maxsize_up, $newnamefile);
			if(!file_exists($path_upload_img))
			{
				$upload->makeDir($path_upload_img);
			}
			$filedoc = $upload->send();

			$db->sql_query("INSERT INTO ".$prefix."_document (id, catid, title, permalink, guid, bodytext, fattach, price, alanguage, active, user_id) VALUES ($id, $catid, '$title', '$permalink', '$guid', '$bodytext', '$filedoc', $price, '$currentlang', '$active', $userid)");
			echo "<script language=\"javascript\" type=\"text/javascript\">";
			echo "alert('Tài liệu đã được gửi lên thành công! Hệ thống sẽ xác nhận trọng vòng 2h trước khi đăng tải.');";
			echo " window.location.href=\"".url_sid('index.php?f=user&do=document_add')."\";";
		echo "</script>";
		}
	}
}
else {
	
}

$path_upload_attach = "$path_upload/document";//path upload file attach
include_once('header.php');
global $module_name;

OpenTab("Thêm tài liệu mới");
?>
<script type="text/javascript">
	window.onload = function() {
		document.getElementById("progress").style.visibility = "hidden";
		document.getElementById("prog_text").style.visibility = "hidden";
	}
	
	function dispProgress() {
		document.getElementById("progress").style.visibility = "visible";
		document.getElementById("prog_text").style.visibility = "visible";
	}
</script>

<?php

if ($error!=""){echo "<div class=\"error\">".$error."</div>";}
echo $info;
?>
<form action="index.php?f=user&do=document_add" name="frm" method="POST" enctype="multipart/form-data">
<div class="document-upload">
	<ul>
		<li class="d-lable">Tài liệu:</li>
		<li class="d-input"><input type="file" name="fileupload" class="btn_upload">(.doc,.pdf,.xls,.zip,.rar)</li>
		<li class="d-lable">Tiêu đề:</li>
		<li class="d-input"><input type="text" name="txt_title" class="" style="width:300px"></li>
		<li class="d-lable">Chủ đề:</li>
		<li class="d-input"><?php show_catdocument();?></li>
		<li class="d-lable">Miêu tả:</li>
		<li class="d-input"><textarea name="txt_desc"  style="width:300px"></textarea></li>
		<li class="d-lable">Giá (VNĐ):</li>
		<li class="d-input"><input type="text" name="txt_price" class="" style="width:300px"></li>
		<li class="d-input"><input type="submit" onClick="dispProgress()" class="sb_but1" id="Đăng tài liệu" value="Đăng tài liệu">
		<input type="hidden" name="subup" value="1">
		</li>
	</ul>
</div>
</form>
<?php
CloseTab();
include_once('footer.php');
?>