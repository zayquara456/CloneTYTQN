<?php
define('CMS_ADMIN', true);
require_once("../config.php");
require_once("language/$currentlang/main.php");
	
if(defined('iS_ADMIN')) {	
	header("Location: body.php");
} else {
	$checklogin = 0;
	$substyle ="";
	$messlog ="";
	//session_register("adm_log");

	if(isset($_POST['submit'])) {
		$adname = $escape_mysql_string(trim($_POST['adname']));
		$adpwd = $escape_mysql_string(trim($_POST['adpwd']));

		$adname = substr($adname, 0,25);
		$adpwd = substr($adpwd, 0,40);

		if($adname =="" || $adpwd =="" || (preg_match("![^a-zA-Z0-9_-]!",trim($adname)))) {
			$_SESSION['adm_log']++;
			header("Location: login.php?error=2");
		}

		//if(!empty($adname) && $_SESSION['adName']==$adname) {
		if(!empty($adname)) {
			$adpwd = md5($adpwd);
			list($fbipwd,$active) = $db->sql_fetchrow($db->sql_query("SELECT pwd, active FROM ".$prefix."_admin WHERE adacc='$adname'"));
		if($active==0)
			{
				$messlog="tài khoản đã bị khóa";
			}
			else
			{
				if (($fbipwd == $adpwd)) {
					mt_srand ((double)microtime ()*10000000);
					$maxran = 10000000;
					$checknum = mt_rand (0, $maxran);
					$checknum = md5 ($checknum);
					$agent = substr (trim ($_SERVER['HTTP_USER_AGENT']), 0, 80);
					$addr_ip = substr (trim ($client_ip), 0, 15);
					$db->sql_query("UPDATE {$prefix}_admin SET checknum = '$checknum', last_login = '".time()."', last_ip = '$addr_ip', agent = '$agent' WHERE adacc='$adname'");
					$_SESSION[ADMIN_SES] = base64_encode($adname."#:#".$adpwd."#:#".$checknum."#:#".$agent."#:#".$addr_ip);
					$_SESSION['timeout'] = time();
					$_SESSION['islogin'] = true;
					unset($_SESSION['adm_log']);
					session_write_close();
					updateadmlog($adname,'login','Đăng nhập','Đăng nhập tài khoản quản trị');
					if (isset($_GET['special'])) header("Location: body.php");
					else header("Location: body.php");
				}else{
					$_SESSION['adm_log']++;
					header("Location: login.php?error=1");
				}
			}
		}


	}

	if($_SESSION['adm_log'] >= $blockadm) {
		$substyle = " disabled";
		$messlog = _BEGONELOGIN." {$_SESSION['adm_log']} "._BEGONELOGIN1;
		$origAcc = isset($_GET['acc']) ? $_GET['acc'] : $_POST['acc'];
		$acc = $escape_mysql_string(trim($origAcc));
		$db->sql_query("UPDATE ".$prefix."_admin SET active=0 WHERE adacc='$acc'");
		
	}
?>
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Language" content="en-us">
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo _CHARSET?>">
	<link rel="stylesheet" href="styles/styles.css" />
	<title><?php echo _TITLE_ADMIN ?></title>

<script language="javascript" type="text/javascript">
function setFocus() {
	document.login.adname.select();
	document.login.adname.focus();
}
</script>
</head>
<body onLoad="javascript:setFocus();" class="login">
<div id="login">
<div class="error"><?php echo $messlog?></div>
<form action="login.php" method="POST" name="login">
	<div class="login-form">
	<div class="login-left fl"><img border="0" src="images/login/lion.png" align="baseline"></div>
    <div class="login-right fl">
<div><span class="title"><?php echo _ADMIN_LOGIN ?></span></div>
<div class="login-label fl"><label><?php echo _NICKNAME?></label></div>
<div><input type="text" name="adname" id="adname" maxlength="20" size="20" class="login_input"$substyle></div>
<div class="login-label fl"><label><?php echo _PASSWORD?></label></div>
<div><input type="password" name="adpwd" maxlength="20" size="20" class="login_input"$substyle></div>
<div class="login-label fl"><label><?php echo _LANGUAGE?></label></div>
<?php if($multilingual == 1) { ?>
	<div><select name="lang" class="login_input" <?php echo $substyle?>>
<?php	echo select_language($currentlang);?>
	</select></div>
<?php }?>
<div class="login-button"><input type="submit" name="submit" value="<?php echo _LOGIN?>" maxlength="20" size="20" class="login_button cursor" $substyle></div>
</div>
<div class="cl"></div>
</div></form>
</div>
<div class="titlefooter"><?php echo _TITLE_FOOTER?></div>
</body>
</html>
<?php }
?>