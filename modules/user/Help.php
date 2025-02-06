<?php
$ck_user = $_SESSION[USER_SESS];
if (!defined('CMS_SYSTEM')) die();
if (!defined('iS_USER') || !isset($userInfo) || !isset($ck_user)){
	header("Location: ".url_sid("index.php?f=user&do=login")."");
	exit();
}
$page_title="Hỗ trợ trực tuyến";
include("header.php");
$cryptinstall="captcha/cryptographp.fct.php";
include $cryptinstall;
//if($_SESSION['ck_nap']==null){$_SESSION['ck_nap']=0;}
session_register("ck_nap");
//if($_SESSION['ck_nap']==0){$_SESSION['ck_nap']=="";}
$code = $serial = $content = $email = $name = $err_code = $err_serial = $error_captcha = '';

OpenTab("Hỗ trợ trực tuyến");
echo "<div class=\"content\">";
echo "<div class=\"div-home\">";

$result = $db->sql_query("SELECT content FROM ".$prefix."_gentext WHERE textname='help' AND alanguage='$currentlang'");
list($content) = $db->sql_fetchrow($result);
?>
<div><?php echo $content?></div>
<?php
echo "</div>";
echo "</div>";
CloseTab();
include("footer.php");
?>