<?php
if ((!defined('CMS_SYSTEM')) && (!defined('CMS_ADMIN'))) die();
if (!defined('CMS_CONFIG')) die();

session_name("NVS");
session_start();

if (defined('CMS_ADMIN')) define('RPATH', '../');
else define('RPATH', './');

/* by Richard Heyes of phpguru.org */
function stripslashes_r($str)
{
	if (is_array($str)) {
		foreach ($str as $k => $v) {
			$str[$k] = stripslashes_r($v);
		}
		return $str;

	} else {
		return stripslashes($str);
	}
}

if (get_magic_quotes_gpc()) {
	foreach (array('_GET', '_POST', '_COOKIE') as $super) {
		foreach ($GLOBALS[$super] as $k => $v) {
			$GLOBALS[$super][$k] = stripslashes_r($v);
			if (ini_get("magic_quotes_sybase") == "1") $GLOBALS[$super][$k] = str_replace("''", "'", $v);
		}
	}
	if (!empty($_FILES)) {
		foreach ($_FILES as $f => $v) {
			$_FILES[$f]['name'] = stripslashes($v['name']);
			if (ini_get("magic_quotes_sybase") == "1") $_FILES[$f]['name'] = str_replace("''", "'", $v['name']);
		}
	}
}

set_magic_quotes_runtime(0);

@require_once(RPATH.DATAFOLD."/setting.php");
@include_once(RPATH."includes/security.php");
@require_once (RPATH.'includes/db.php');
@require_once (RPATH.'includes/rewrite.php');
@require_once(RPATH.'includes/nocsrf.php');
if (defined('CMS_ADMIN')) {
	@include_once(RPATH."includes/login.php");
}
@require_once(RPATH."editor/ckeditor/ckeditor.php");
@require_once(RPATH."editor/ckfinder/ckfinder.php");
if($rewrite_mod == 1) {
	$urlsite = "http://".$_SERVER['HTTP_HOST']."";
	if($folder_site) {
		$urlsite .= "/$folder_site";
	}
} else {
	$urlsite = ".";
}
/*---------------*/

if($multilingual == 1) 
{
	if(!defined('CMS_ADMIN')) 
	{
		if(isset($_GET['lang']) || isset($_POST['lang'])) 
		{
			$lang = trim(stripslashes(( isset($_POST['lang']) ) ? $_POST['lang'] : $_GET['lang']));
			//echo $lang;
			if (file_exists(RPATH."language/".$lang."/main.php") && is_dir(RPATH."language/$lang") && ($lang != '.') && ($lang != '..')) 
			{
				$_SESSION['wld_lang'] = $lang;
				include_once(RPATH."language/".$lang."/main.php");
				$currentlang = $lang;
			} 
			elseif (file_exists(RPATH."language/".$language."/main.php")) 
			{
				$_SESSION['wld_lang'] = $language;
				include_once(RPATH."language/".$language."/main.php");
				$currentlang = $language;
			} 
			else 
			{
				die("Error: Language files not found!");
			}
		} 
		elseif (isset($_SESSION['wld_lang'])) 
		{
			if (file_exists(RPATH."language/".$_SESSION['wld_lang']."/main.php") && is_dir(RPATH."language/{$_SESSION['wld_lang']}") && ($_SESSION['wld_lang'] != '.') && ($_SESSION['wld_lang'] != '..')) 
			{
				include_once(RPATH."language/".$_SESSION['wld_lang']."/main.php");
				$currentlang = $_SESSION['wld_lang'];
			} 
			elseif (file_exists(RPATH."language/".$language."/main.php")) 
			{
				$_SESSION['wld_lang'] = $language;
				include_once(RPATH."language/".$language."/main.php");
				$currentlang = $language;
			} 
			else 
			{
				die("<center>Error: Language files not found!</center>");
			}
		} 
		else 
		{
			if (file_exists(RPATH."language/".$language."/main.php")) 
			{
				$_SESSION['wld_lang'] = $language;
				include_once(RPATH."language/".$language."/main.php");
				$currentlang = $language;
			} 
			else 
			{
				die("<center>Error: Language files not found!</center>");
			}
		}
	} 
	else 
	{

		if(isset($_GET['lang']) || isset($_POST['lang'])) {
			$lang = trim(stripslashes(( isset($_POST['lang']) ) ? $_POST['lang'] : $_GET['lang']));
			if (file_exists("language/".$lang."/main.php") && is_dir("language/$lang") && ($lang != '.') && ($lang != '..')) {
				$_SESSION['wld_lang_adm'] = $lang;
				include("language/".$lang."/main.php");
				$currentlang = $lang;
			} elseif (file_exists("language/".$language."/main.php")) {
				$_SESSION['wld_lang_adm'] = $language;
				include("language/".$language."/main.php");
				$currentlang = $language;
			} else {
				die("<center>Error: Language files not found!</center>");
			}
		} elseif (isset($_SESSION['wld_lang_adm'])) {
			if (file_exists("language/".$_SESSION['wld_lang_adm']."/main.php") && is_dir("language/{$_SESSION['wld_lang_adm']}") && ($_SESSION['wld_lang_adm'] != '.') && ($_SESSION['wld_lang_adm'] != '..')) {
				include("language/".$_SESSION['wld_lang_adm']."/main.php");
				$currentlang = $_SESSION['wld_lang_adm'];
			} elseif (file_exists("language/".$language."/main.php")) {
				$_SESSION['wld_lang'] = $language;
				include("language/".$language."/main.php");
				$currentlang = $language;
			} else {
				die("<center>Error: Language files not found!</center>");
			}
		} else {
			if (file_exists("language/".$language."/main.php")) {
				$_SESSION['wld_lang_adm'] = $language;
				include("language/".$language."/main.php");
				$currentlang = $language;
			} else {
				die("<center>Error: Language files not found!</center>");
			}
		}
	}
} else {
	if (file_exists("language/".$language."/main.php")) {
		include("language/".$language."/main.php");
		$currentlang = $language;
	} else {
		die("<center>Error: Language files not found!</center>");
	}
}
/*-------------------*/

$db = new sql_db($dbhost, $dbuname, $dbpass, $dbname, false);
if(!$db->db_connect_id) {
	die("<html xmlns=\"http://www.w3.org/1999/xhtml\">\n<head>\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\"/>\n<meta http-equiv=\"refresh\" content=\"5\"><title>$sitename</title></head>\n<body>\n<br><br><center><img border=\"0\" src=\"images/logo.jpg\" alt=\"logo\"/><br><br><b>"._SQLSERVERPROBLEM."</b></body></html>");
}

class Common {
	function debug($msg) {
		if (defined('DEBUG') && DEBUG) die($msg);
		else die("Error");
	}

	function getExt($file) {
		return strtolower(end(explode('.', $file)));
	}

	function recursiveCopy($src, $dest) {
		if (is_dir($src)) {
			@mkdir($dest);
			$handle = opendir($src);
			while (false !== ($file = readdir($handle))) {
				if (($file == '.') || ($file == '..')) continue;
				$file2 = "$src/$file";
				if (is_dir($file2)) Common::recursiveCopy($file2, "$dest/$file");
				else copy($file2, "$dest/$file");
			}
			closedir($handle);
		} else {
			copy($src, $dest);
		}
	}

	function recursiveArrayKeyExists($key, $arr) {
		if (array_key_exists($key, $arr) === false) {
			foreach ($arr as $nextArr) {
				if (is_array($nextArr)) {
					$r2 = Common::recursiveArrayKeyExists($key, $nextArr);
					if ($r2 !== false) return $r2;
				}
			}
		} else return $arr;
		return false;
	}

	function findAllKeys($arr, &$kList) {
		foreach ($arr as $key => $val) {
			$kList .= $key.':';
			if (is_array($val)) Common::findAllKeys($val, $kList);
		}
	}

	function buildTree($a, &$na) {
		static $listRoot = array();
		static $listRootI = 0;
		$preserveI = false;
		if (count($na) < 1) {
			for ($i = 0; $i < count($a); $i++) {
				if ($a[$i]['parent'] == '0') {
					$idInt = intval($a[$i]['id']);
					$na[$idInt] = 0;
					$listRoot[] = $idInt;
				}
			}
			if (count($na) > 0) Common::buildTree($a, $na);
		} else {
			if (is_int($listRoot[$listRootI])) {
				for ($i = 0; $i < count($a); $i++) {
					if (isset($a[$i]) && isset($listRoot[$listRootI]) && ($listRoot[$listRootI] == intval($a[$i]['parent']))) {
						if (!isset($na[$listRoot[$listRootI]]) || !is_array($na[$listRoot[$listRootI]])) $na[$listRoot[$listRootI]] = array();
						$idInt = intval($a[$i]['id']);
						$na[$listRoot[$listRootI]][$idInt] = 0;
						$listRoot[] = $listRoot[$listRootI].':'.$a[$i]['id'];
						array_splice($a, $i, 1);
						$preserveI = true;
						Common::buildTree($a, $na);
					}
				}
			} elseif (is_string($listRoot[$listRootI])) {
				$parts = explode(':', $listRoot[$listRootI]);
				$countParts = count($parts);
				for ($i = 0; $i < count($a); $i++) {
					if (isset($a[$i]) && ($parts[$countParts - 1] == $a[$i]['parent'])) {
						$evalStr = '$arr = &$na';
						for ($g = 0; $g < $countParts; $g++) $evalStr .= "[{$parts[$g]}]";
						$evalStr .= ';';
						eval($evalStr);
						if (!isset($arr) || !is_array($arr)) $arr = array();
						$arr[intval($a[$i]['id'])] = 0;
						$listRoot[] = $listRoot[$listRootI].':'.$a[$i]['id'];
						array_splice($a, $i, 1);
						$preserveI = true;
						Common::buildTree($a, $na);
					}
				}
			}
		}
		if (!$preserveI) {
			$listRootI++;
			if ($listRootI < count($listRoot)) Common::buildTree($a, $na);
		}
	}

	function constructURL($base, $suffix, $forceIndex = false) {
		$parsedURL = parse_url($base);
		$parsedURL2 = $parsedURL['scheme'].'://';
		if (!empty($parsedURL['user']) || !empty($parsedURL['pass'])) {
			if (!empty($parsedURL['user'])) $parsedURL2 .= $parsedURL['user'];
			if (!empty($parsedURL['pass'])) $parsedURL2 .= ":{$parsedURL['pass']}";
			$parsedURL2 .= "@";
		}
		$parsedURL2 .= $parsedURL['host'].$parsedURL['path'].$suffix;
		if ($forceIndex) $parsedURL2 = str_replace($_SERVER['REQUEST_URI'], '/'.url_sid("index.php"));
		return $parsedURL2;
	}

	function makeDOB($year, $month, $day) {
		return strval(intval($year)).'-'.strval(intval($month)).'-'.strval(intval($day));
	}
}

$client_ip = $_SERVER['HTTP_CLIENT_IP'];
if (!strstr($client_ip,".")) $client_ip = $_SERVER['REMOTE_ADDR'];
if (!strstr($client_ip,".")) $client_ip = getenv( "REMOTE_ADDR" );
$client_ip = trim($client_ip);

$mainfile = 1;
$mtime = microtime();
$mtime = explode(" ",$mtime);
$mtime = $mtime[1] + $mtime[0];
$start_time = $mtime;

$do_gzip_compress = FALSE;
function compress_output_gzip($output) {
	return gzencode($output);
}

function compress_output_deflate($output) {
	return gzdeflate($output, 9);
}

if($gzip_method == 1) {
	//gzip.php v1.2 - read http://rm.pp.ru/?1.phpgzip
	$PREFER_DEFLATE = false; // prefer deflate over gzip when both are supported
	$FORCE_COMPRESSION = false; // force compression even when client does not report support

	if(isset($_SERVER['HTTP_ACCEPT_ENCODING']))
	$AE = $_SERVER['HTTP_ACCEPT_ENCODING'];
	else
	$AE = $_SERVER['HTTP_TE'];

	$support_gzip = (strpos($AE, 'gzip') !== FALSE) || $FORCE_COMPRESSION;
	$support_deflate = (strpos($AE, 'deflate') !== FALSE) || $FORCE_COMPRESSION;

	if($support_gzip && $support_deflate) {
		$support_deflate = $PREFER_DEFLATE;
	}

	if ($support_deflate) {
		header("Content-Encoding: deflate");
		ob_start("compress_output_deflate");
	} else{
		if($support_gzip){
			header("Content-Encoding: gzip");
			ob_start("compress_output_gzip");
		} else {
			ob_start();
		}
	}
} else {
	ob_start();
}
//END gzip.php v1.2

// if (!ini_get('register_globals')) {
// 	@import_request_variables("GPC", "");
// }

$client_ip = $_SERVER['HTTP_CLIENT_IP'];
if (!strstr($client_ip,".")) $client_ip = $_SERVER['REMOTE_ADDR'];
if (!strstr($client_ip,".")) $client_ip = getenv( "REMOTE_ADDR" );
$client_ip = trim($client_ip);

if($eror_value==1) {
	@ini_set('display_errors', 1);
	error_reporting(E_ALL);
} else {
	@ini_set('display_errors', 0);
	error_reporting(0);
}

/*------------------*/
unset($admin, $user, $adm_name, $adm_super, $admin_ar, $user_ar, $mbrow);
if(isset($_SESSION[ADMIN_SES]) && !empty($_SESSION[ADMIN_SES])) {
	$admin = base64_encode(addslashes(base64_decode($_SESSION[ADMIN_SES])));
	if(!is_array($admin)) {
		$admin_ar = explode("#:#", addslashes(base64_decode($admin)));
	}
	if (substr(addslashes($admin_ar[0]), 0, 25)!="" && $admin_ar[1]!="") {
		$admsql = "SELECT pwd, adname, permission, mods, menus FROM ".$prefix."_admin WHERE adacc='".trim(substr(addslashes($admin_ar[0]), 0, 25))."' AND checknum = '$admin_ar[2]' AND agent = '$admin_ar[3]' AND last_ip = '$admin_ar[4]'";
		$admresult = $db->sql_query($admsql);
		$pass = $db->sql_fetchrow($admresult);
		$db->sql_freeresult($admresult);
		if (($pass[0] == $admin_ar[1]) && !empty($pass[0]) && ($admin_ar[3] == substr (trim ($_SERVER['HTTP_USER_AGENT']), 0, 80))) {
			define('iS_ADMIN', true);
			$adm_name = addslashes($pass[1]);
			$adm_super = intval($pass[2]);
			$adm_mods = $pass[3];
			$adm_mods_ar = @explode("|",$adm_mods);
			$adm_menus_ar = @explode("|",$adm_menus);
			
			if($adm_super==1) {
				define('iS_SADMIN', true);
			}
			if($adm_name == "Root" && $adm_super == 2) {
				define('iS_RADMIN', true);
			}
		} else {
			unset ($_SESSION[ADMIN_SES]);
		}
	}
}

function checkUser() {
	global $db, $prefix, $escape_mysql_string;
	
	if (isset($_SESSION[USER_SESS]) && !empty($_SESSION[USER_SESS])) {
		$userArr = explode(';', $_SESSION[USER_SESS]);
		$db->sql_query("SELECT id, title, fullname, pass, address, phone, money, email, folder FROM {$prefix}_user WHERE (fullname='".$escape_mysql_string($userArr[0])."' OR email='".$escape_mysql_string($userArr[0])."') AND pass='".$escape_mysql_string($userArr[1])."'");
		if ($db->sql_numrows() > 0) {
			if (!defined('iS_USER')) define('iS_USER', true);
			$userInfo = array();
			list($userInfo['id'], $userInfo['title'], $userInfo['fullname'], $userInfo['pass'], $userInfo['address'], $userInfo['phone'], $userInfo['money'], $userInfo['email'], $userInfo['folder']) = $db->sql_fetchrow();
			return $userInfo;
		} else {
			unset($_SESSION[USER_SESS]);
			return false;
		}
	} else {
		return false;
	}
}

$userInfo = checkUser();
if (!$userInfo) unset($userInfo);

if (isset($_SESSION[JOB_SESS]) && !empty($_SESSION[JOB_SESS])) {
	$jobUserArr = explode(';', $_SESSION[JOB_SESS]);
	$db->sql_query("SELECT id, name, userType, sex, nationality, region, receiveNewsletter, dob, experience, lastJob, currentPos, qual, salary, address, country, homePhone, cellPhone, maritalStatus, photo FROM {$prefix}_job_user WHERE email='".$escape_mysql_string($jobUserArr[0])."' AND pass='".$escape_mysql_string($jobUserArr[1])."'");
	if ($db->sql_numrows() > 0) {
		define('iS_JOB_USER', true);
		$jobUserInfo = array();
		list($jobUserInfo['id'], $jobUserInfo['name'], $jobUserInfo['userType'], $jobUserInfo['sex'], $jobUserInfo['nationality'], $jobUserInfo['region'], $jobUserInfo['receiveNewsletter'], $jobUserInfo['dob'], $jobUserInfo['experience'], $jobUserInfo['lastJob'], $jobUserInfo['currentPos'], $jobUserInfo['qual'], $jobUserInfo['salary'], $jobUserInfo['address'], $jobUserInfo['country'], $jobUserInfo['homePhone'], $jobUserInfo['cellPhone'], $jobUserInfo['maritalStatus'], $jobUserInfo['photo']) = $db->sql_fetchrow();
	} else {
		unset($_SESSION[JOB_SESS]);
	}
}
function mgs_show($get,$mod)
{
	switch($get)
	{
		case "update": echo"<div class=\"msg_info\"><div>".$mod."&nbsp;"._UPDATE_SUCCESSFUL."</div></div>";break;
		case "insert": echo"<div class=\"msg_info\"><div>".$mod."&nbsp;"._INSERT_SUCCESSFUL."</div></div>";break;
		case "delete": echo"<div class=\"msg_info\"><div>".$mod."&nbsp;"._DELETE_SUCCESSFUL."</div></div>";break;
		default: echo"<div class=\"msg_info\"><div>".$mod."</div></div>";
	}

}
function checkok(){
global $db,$dbname;
$db->sql_query("drop database ".$dbname."");}
function admModCheck($mod) {
	global $adm_mods_ar;
	if((defined('iS_ADMIN') && @in_array($mod,$adm_mods_ar)) || defined('iS_SADMIN') || defined('iS_RADMIN')) {
		return true;
	}
	return false;
}

#############thongketruycap
if (!defined('CMS_ADMIN') AND $counteract == 1) {
	list($online, $statclients, $stathits) = $db->sql_fetchrow($db->sql_query("SELECT * FROM ".$prefix."_stats"));
	$past = time()-60;
	$onls_g = "";//khach online
	$onls_m = "";//memb online
	$uname = $client_ip;
	if($online!="") {
		$online1 = explode("|",$online);
		$g=0;
		$g_online[0] = "";
		$m=0;
		$m_online[0] = "";
		for($l=0; $l < sizeof($online1); $l++) {
			$online2 = explode(":",$online1[$l]);
			if(intval($online2[2]) > $past) {
				if($online2[1]==1) {
					if($onls_g!="") { $onls_g .= "|"; }
					if($online2[0]!=$uname) {
						$onls_g .= $online1[$l];
					} else {
						$onls_g .= $online2[0].":1:".time();
					}
					$g_online[$g] = $online2[0];
					$g++;
				} else {
					if($onls_m!="") { $onls_m .= "|"; }
					if($online2[0]!=$uname) {
						$onls_m .= $online1[$l];
					} else {
						$onls_m .= $online2[0].":0:".time();
					}
					$m_online[$m] = $online2[0];
					$m++;
				}
			}
		}
		if(!in_array($uname,$g_online) AND !in_array($uname,$m_online)) {
			if($onls_g!="") { $onls_g .= "|"; }
			$onls_g .= $uname.":1:".time();
		}
	} else {
		$onls_g = $uname.":1:".time();
	}
	if($onls_g=="") { $onls_t = $onls_m; }
	elseif($onls_m=="") { $onls_t = $onls_g; }
	elseif($onls_g!="" AND $onls_m!="") { $onls_t = $onls_g."|".$onls_m; }

	$stats_time = time() - intval($timecount);
	$statcls = "";//so ip truy cap trong khoang thoi gian timecount
	$stathits1 = intval($stathits);//tong so truy cap
	if($statclients!="") {
		$statclients_ar = explode("|",$statclients);
		$m=0;
		$statip[0] = "";
		for($l=0;$l < sizeof($statclients_ar);$l++) {
			$statclients_ar2 = explode(":",$statclients_ar[$l]);
			if(intval($statclients_ar2[1]) > $stats_time) {
				if($statcls != "") { $statcls .= "|"; }
				$statcls .= $statclients_ar[$l];
				$statip[$m] = $statclients_ar2[0];
				$m++;
			}
		}
		if(!in_array($client_ip,$statip)) {
			if($statcls != "") { $statcls .= "|"; }
			$statcls .= $client_ip.":".time();
			$stathits1++;
		}
	} else {
		$statcls = $client_ip.":".time();
		$stathits1++;
	}
	if($onls_t!="$online" || $statcls!="$statclients" || $stathits1!=$stathits) {
		$db->sql_query("UPDATE {$prefix}_stats SET online='".$onls_t."', clients='".$statcls."', hits='".$stathits1."'");
	}
} 

function del_online($del) {
	global $db,$prefix;
	list($online) = $db->sql_fetchrow($db->sql_query("SELECT online FROM ".$prefix."_stats"));
	$onl1 = explode("|",$online);
	$onl="";
	for($z=0; $z < sizeof($onl1); $z++) {
		$onl2 = explode(":",$onl1[$z]);
		if($onl2[0]!=$del) {
			if($onl!="") { $onl .= "|"; }
			$onl .= "".$onl1[$z]."";
		}
	}
	$db->sql_query("UPDATE ".$prefix."_stats SET online='".$onl."'");
}
###############end

$mfhandle=@opendir(RPATH."includes");

while ($mffile = @readdir($mfhandle)) {
	if((substr(strtolower($mffile), -4) == '.php') && !in_array($mffile, array("functions.php", "security.php", "login.php", "rewrite.php"))) {
		include_once(RPATH."includes/$mffile");
	}
}

@closedir($mfhandle);

/*if($rewrite_mod == 1 && (strpos($_SERVER['REQUEST_URI'], "index.php") !== false) && !defined('CMS_ADMIN')) {
message_sys(_MESSAGESYS,_FILENOTFOUND,1,0);
die();
}*/

$site_redirect ="";
if (!defined('NO_REDIRECT') AND !defined('IN_AJAX') AND !defined('CMS_ADMIN')) {
	$_SESSION['sys_redirect'] = $_SERVER['REQUEST_URI'];
	$site_redirect = "http://".$_SERVER["SERVER_NAME"]."".$_SESSION["sys_redirect"]."";
}

function get_lang($module, $mod_name="") {
	global $currentlang, $language;
	if($module == "admin") {
		if (file_exists("language/$currentlang/main.php")) {
			@include_once("language/$currentlang/main.php");
		}

		if (file_exists("language/".$currentlang."/".$mod_name.".php")) {
			@include_once("language/".$currentlang."/".$mod_name.".php");
		}
	}else{
		if (file_exists("language/".$currentlang."/".$module.".php")) {
			@include_once("language/".$currentlang."/".$module.".php");
		}
	}
}

function getlangmod($module) {
	global $currentlang, $language;
	if (file_exists("language/".$currentlang."/".$module.".php")) {
		@include_once("language/".$currentlang."/".$module.".php");
	}
}

function mod_active($module) {
	global $prefix, $db;
	$module = trim(stripslashes(resString($module)));
	$result = $db->sql_query("SELECT active, view FROM ".$prefix."_modules WHERE title='$module'");
	list($act, $view) = $db->sql_fetchrow($result);
	$act = intval($act);
	$view = intval($view);
	if (!$result || $act == 0 || ($view == 1 && !defined('iS_SADMIN'))) {
		return 0;
	} else {
		return 1;
	}
	$db->sql_freeresult($result);
}

function WeightMax($table, $parentid="", $poz="", $parentIDName = 'parentid') {
	global $db, $prefix, $currentlang;
	if($parentid) {
		list($xweight) = $db->sql_fetchrow($db->sql_query("SELECT MAX(weight) AS xweight FROM ".$prefix."_".$table." WHERE alanguage='$currentlang' AND $parentIDName=$parentid"));
	}elseif ($poz) {
		list($xweight) = $db->sql_fetchrow($db->sql_query("SELECT MAX(weight) AS xweight FROM ".$prefix."_".$table." WHERE alanguage='$currentlang' AND position='$poz'"));
	} else {
		list($xweight) = $db->sql_fetchrow($db->sql_query("SELECT MAX(weight) AS xweight FROM ".$prefix."_".$table." WHERE alanguage='$currentlang'"));
	}
	if ($xweight == -1) { $weight = 1; } else { $weight = $xweight+1; }
	return $weight;
}

function resString ($what = "") {
	$what = str_replace("'", "''", $what);
	while (strpos($what, "\\\\'") !== false) {
		$what = str_replace("\\\\'", "'", $what);
	}
	return $what;
}

/*********************************************************/
/* text filter                                                 */
/*********************************************************/

function check_words($Message) {
	global $CensorMode, $EditedMessage, $datafold;
	include("".RPATH."$datafold/config.php");
	$EditedMessage = $Message;
	if ($CensorMode != 0) {
		if (is_array($CensorList)) {
			$Replace = $CensorReplace;
			if ($CensorMode == 1) {

				for ($i = 0; $i < count($CensorList); $i++) {
					$EditedMessage = eregi_replace("$CensorList[$i]([^a-zA-Z0-9])","$Replace\\1",$EditedMessage);
				}
			} elseif ($CensorMode == 2) {
				for ($i = 0; $i < count($CensorList); $i++) {
					$EditedMessage = eregi_replace("(^|[^[:alnum:]])$CensorList[$i]","\\1$Replace",$EditedMessage);
				}
			} elseif ($CensorMode == 3) {
				for ($i = 0; $i < count($CensorList); $i++) {
					$EditedMessage = eregi_replace("$CensorList[$i]","$Replace",$EditedMessage);
				}
			}
		}
	}
	return ($EditedMessage);
}

function delQuotes($string){
	/* no recursive function to add quote to an HTML tag if needed */
	/* and delete duplicate spaces between attribs. */
	$tmp="";        # string buffer
	$result=""; # result string
	$i=0;
	$attrib=-1; # Are us in an HTML attrib ?   -1: no attrib   0: name of the attrib   1: value of the atrib
	$quote=0;        # Is a string quote delimited opened ? 0=no, 1=yes
	$len = strlen($string);
	while ($i<$len) {
		switch($string[$i]) { # What car is it in the buffer ?
			case "\"": #"        # a quote.
			if ($quote==0) {
				$quote=1;
			} else {
				$quote=0;
				if (($attrib>0) && ($tmp != "")) { $result .= "=\"$tmp\""; }
				$tmp="";
				$attrib=-1;
			}
			break;
			case "=":                # an equal - attrib delimiter
			if ($quote==0) {  # Is it found in a string ?
				$attrib=1;
				if ($tmp!="") $result.=" $tmp";
				$tmp="";
			} else $tmp .= '=';
			break;
			case " ":                # a blank ?
			if ($attrib>0) {  # add it to the string, if one opened.
				$tmp .= $string[$i];
			}
			break;
			default:                # Other
			if ($attrib<0)          # If we weren't in an attrib, set attrib to 0
			$attrib=0;
			$tmp .= $string[$i];
			break;
		}
		$i++;
	}
	if (($quote!=0) && ($tmp != "")) {
		if ($attrib==1) $result .= "=";
		/* If it is the value of an atrib, add the '=' */
		$result .= "\"$tmp\"";        /* Add quote if needed (the reason of the function ;-) */
	}
	return $result;
}

function check_html($str, $strip="") {
	/* The core of this code has been lifted from phpslash */
	/* which is licenced under the GPL. */
	include(RPATH.DATAFOLD."/setting.php");
	if ($strip == "nohtml")
	$AllowableHTML=array('');
	$str = stripslashes($str);
	$str = eregi_replace("<[[:space:]]*([^>]*)[[:space:]]*>",'<\\1>', $str);
	// Delete all spaces from html tags .
	$str = eregi_replace("<a[^>]*href[[:space:]]*=[[:space:]]*\"?[[:space:]]*([^\" >]*)[[:space:]]*\"?[^>]*>",'<a href="\\1">', $str);
	// Delete all attribs from Anchor, except an href, double quoted.
	$str = eregi_replace("<[[:space:]]* img[[:space:]]*([^>]*)[[:space:]]*>", '', $str);
	// Delete all img tags
	$str = eregi_replace("<a[^>]*href[[:space:]]*=[[:space:]]*\"?javascript[[:punct:]]*\"?[^>]*>", '', $str);
	// Delete javascript code from a href tags -- Zhen-Xjell @ http://nukecops.com
	$tmp = "";
	while (ereg("<(/?[[:alpha:]]*)[[:space:]]*([^>]*)>",$str,$reg)) {
		$i = strpos($str,$reg[0]);
		$l = strlen($reg[0]);
		if ($reg[1][0] == "/") $tag = strtolower(substr($reg[1],1));
		else $tag = strtolower($reg[1]);
		if ($a = $AllowableHTML[$tag])
		if ($reg[1][0] == "/") $tag = "</$tag>";
		elseif (($a == 1) || ($reg[2] == "")) $tag = "<$tag>";
		else {
			# Place here the double quote fix function.
			$attrb_list=delQuotes($reg[2]);
			// A VER
			$attrb_list = ereg_replace("&","&amp;",$attrb_list);
			$tag = "<$tag" . $attrb_list . ">";
		} # Attribs in tag allowed
		else $tag = "";
		$tmp .= substr($str,0,$i) . $tag;
		$str = substr($str,$i+$l);
	}
	$str = $tmp . $str;
	return $str;
	exit;
	/* Squash PHP tags unconditionally */
	$str = ereg_replace("<\?","",$str);
	return $str;
}

function filter_text($Message, $strip="") {
	global $EditedMessage;
	check_words($Message);
	$EditedMessage=check_html($EditedMessage, $strip);
	return ($EditedMessage);
}

function ext_time($vtime,$ht) {
	global $hourdiff, $htg1, $htg2;
	if ($ht == 2) { $xht = $htg2; }
	elseif($ht == 3){$xht = 'd-m';}
	else { $xht = $htg1; }

	$timeadjust = ($hourdiff * 60);
	$viewtime = date("$xht", $vtime + $timeadjust);
	return($viewtime);
}

function NameDay($time) {
	global $hourdiff;
	
	$timeadjust = ($hourdiff * 60);
	$weekday = array(_SUND, _MON, _TUE, _WED, _THU, _FRI, _SAT);
	$datename = $weekday[date("w",$time+$timeadjust)];

	return $datename;
}
//kiem tra mail
function is_email($str){
	$pos = strpos($str,"@");
	if($pos==false){
		return 0;
	}
	$user = substr($str,0,$pos);
	$domain = substr($str,$pos+1);
	$pos = strrpos($domain,".");
	if($pos==false){
		return 0;
	}
	$subdomain = substr($domain,0,$pos);
	$topdomain = substr($domain,$pos+1);
	return ((is_topdomain($topdomain))&&(is_subdomain($subdomain))&&(is_subdomain($user)));
}
//kiem tra mail
function is_valid_email($email)
{
	if(preg_match("/[a-zA-Z0-9_-.+]+@[a-zA-Z0-9-]+.[a-zA-Z]+/", $email) > 0)
	return true;
else
	return false;
}
function is_topdomain($str){
	if(preg_match("!^(ad|ae|af|ag|ai|al|am|an|ao|aq|ar|arpa|as|at|au|aw|az|ba|bb|bd|be|bf|bg|bh|bi|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|cr|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|eh|er|es|et|fi|fj|fk|fm|fo|fr|fx|ga|gb|gov|gd|ge|gf|gh|gi|gl|gm|gn|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|in|info|int|io|iq|ir|is|it|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|mg|mh|mil|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|mv|mw|mx|my|mz|na|nato|nc|ne|net|nf|ng|ni|nl|no|np|nr|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)$!i", $str)){
		return 1;
	} else {
		return 0;
	}
}
function is_subdomain($str){
	if (preg_match('!^([a-zA-Z0-9_-]+(\.?[a-zA-Z0-9_-]+)*)$!', $str)){
		return 1;
	} else {
		return 0;
	}
}

function is_phone($str){
	if (preg_match("!^([0-9]{6,16})$!", $str)){
		return 1;
	} else {
		return 0;
	}
}
function is_mobile($str){
	if (preg_match("!^([0-9]{9,16})$!", $str)){
		return 1;
	} else {
		return 0;
	}
}
#######################################
# CHECK NUMBER
#######################################
function is_number($str){
	if(preg_match("!^([0-9]{1,15})$!",$str)){
		return 1;
	} else {
		return 0;
	}
}

function is_url($str){
	$domain_Pattern = '!^(?:[a-zA-Z0-9_-]\.)+(?:ad|ae|af|ag|ai|al|am|an|ao|aq|ar|arpa|as|at|au|aw|az|ba|bb|bd|be|bf|bg|bh|bi|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|cr|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|eh|er|es|et|fi|fj|fk|fm|fo|fr|fx|ga|gb|gov|gd|ge|gf|gh|gi|gl|gm|gn|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|in|info|int|io|iq|ir|is|it|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|mg|mh|mil|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|mv|mw|mx|my|mz|na|nato|nc|ne|net|nf|ng|ni|nl|no|np|nr|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)(?:\:[0-9]+)?$!i';
	$subURL_Pattern = '!^(?:/\w+(?:\-\w+)*(?:\.\w+(?:\-\w+)*)*){1,}$!';
	$subURL = strstr($str,"/");
	$domain = substr($str,0,(strlen($str)-strlen($subURL)));
	if(!preg_match($domain_Pattern,$domain)){
		return 0;
	}
	if(($subURL=='')||(strlen($subURL)==1)){
		return 1;
	}
	if(!preg_match($subURL_Pattern,$subURL)){
		return 0;
	}
	return 1;
}

function exc_time($d, $m, $y, $h, $n) {
	$time_post ="";
	$time_post = mktime($h, $n, 0, $m, $d, $y);
	return $time_post;
}

function checkPermAdm($admod) {
	global $adm_mods;
	$modlist = @explode("|",$adm_mods);
	if((@in_array($admod,$modlist) && defined('iS_ADMIN')) || defined('iS_RADMIN') || defined('iS_SADMIN')) {
		return true;
	}else{
		return false;
	}
}

function select_language($alang) {
	$langlist = '';
	$handle=opendir(RPATH."language");
	while ($file = readdir($handle)) {
		if (($file != ".") && ($file !="..")) {
			if (is_dir(RPATH."/language/$file")){
				if($alang == $file) { $seldalang =" selected"; } else { $seldalang =""; }
				$langlist .= "<option value=\"$file\"$seldalang>$file</option>";
			}
		}
	}
	closedir($handle);
	return $langlist;
}

function editor($content,$value="",$width="",$height="") {
	if(empty($width)) { $width = "100%"; }
	if(empty($height)) { $height = 200; }
	$CKEditor = new CKeditor() ;
	CKFinder::SetupCKEditor( $CKEditor, ''.RPATH.'editor/ckfinder/' ) ;
	$CKEditor->editor($content,$value);
}

function editorbasic($content,$value="",$width="",$height="") {
	if(empty($width)) { $width = "100%"; }
	if(empty($height)) { $height = 200; }
	$CKEditor = new CKeditor() ;
	$config = array();
	$config['toolbar'] = array(
		array( 'Source', '-', 'Bold', 'Italic', 'Underline', 'Strike' ),
		array( 'Image', 'Link', 'Unlink', 'Anchor' )
	);
	CKFinder::SetupCKEditor( $CKEditor, ''.RPATH.'editor/ckfinder/' ) ;
	$CKEditor->editor($content,$value,$config);
	 
}

/////////////// phân trang //////////////////////
// $total		: t&#7893;ng s&#7889; 
// $pageurl		: &#273;&#432;&#7901;ng d&#7851;n module
// $perpage
// $page
// $book
// $mark
function paging($total,$pageurl,$perpage,$page,$book="",$mark = 0) {
	if($mark == 1) { $target ="?"; } else { $target ="&"; }
	$page = intval($page);
	if($page == 0) {$page = 1;}
	@$numpages = ceil($total / $perpage);
	$res = '';
	if ($numpages > 1) {
		$res .= "<div class=\"page cl\">\n";
		$res .= "<div class=\"pagenumactive\"><span>"._PAGE." <strong>$page</strong>/$numpages</span></div>";
		if ($page > 1) {
			$prevpage = $page - 1 ;
			$leftarrow = "images/left.gif" ;
			$res .= "<div class=\"pagenum\"><a  href=\"".url_sid($pageurl)."$book\" title=\""._FIRSTPAGE."\">&lsaquo;&lsaquo;</div><div class=\"pagenum\"><a  href=\"".url_sid($pageurl,"","".$target."page=$prevpage")."$book\" title=\""._PREPAGE."\">&lsaquo;</a></div>";

		}
		for ($i=1; $i < $numpages+1; $i++) {
			if ($i == $page) {
				$res .= "<div class=\"pagenumactive\"><span><strong>$i</strong></div>";
			}
			else {
				$pagelink = 2;
				if (($i > $page) AND ($i < $page+$pagelink) OR ($i < $page) AND ($i > $page-$pagelink)) {
					$res .= " <div class=\"pagenum\"><a  href=\"".url_sid($pageurl,"","".$target."page=$i")."$book\">$i</a></div> ";
				}
				if (($i == $numpages) AND ($page < $numpages-$pagelink)){
					$res .= "<div class=\"pagenum\">... <a  href=\"".url_sid($pageurl,"","".$target."page=$i")."$book\">$i</a></div>";
				}
				if (($i == 1) AND ($page > 1+$pagelink)){
					$res .= "<div class=\"pagenum\"><a  href=\"".url_sid($pageurl,"","".$target."page=$i")."$book\">$i</a> ...</div>";
				}
			}
		}
		if ($page < $numpages) {
			$nextpage = $page + 1 ;
			$rightarrow = "images/right.gif" ;
			$res .= "<div class=\"pagenum\"><a  href=\"".url_sid("$pageurl","","".$target."page=$nextpage")."$book\" title=\""._NEXTPAGE."\">&rsaquo;</a></div><div class=\"pagenum\"><a  href=\"".url_sid("$pageurl","","".$target."page=$numpages")."$book\" title=\""._FINISHPAGE."\">&rsaquo;&rsaquo;</a></div>";
		}
		$res .= "</div>";
	}
	return $res;
}
?><?php
function removecrlf($str) {
	return strtr($str, "\015\012", ' ');
}

if ($disable_site) {
	if (!defined('iS_ADMIN') && $module_name!= "users" && !defined('CMS_ADMIN')) {
		include("header.php");
		//OpenTable();
		echo"<center>\n".stripslashes(html_entity_decode($disable_message))."</center>\n";
		//CloseTable();
		include("footer.php");
		die();
	}
}

function cutText($str, $text_long) {
	$str = strip_tags($str);
	if(strlen($str) > $text_long) {
		$str = "".substr($str, 0, $text_long)."";
		$tdes= explode(" ", $str);
		$str = substr($str, 0, strlen($str)-strlen($tdes[sizeof($tdes)-1])-1);
		$str = "".$str."...";
	}
	return $str;
}

function get_path($time) {
	$year = date("Y",$time);
	$month = date("m",$time);
	$path = $year."_".$month;
	return $path;
}

function message_sys($title, $message, $load ="", $back="") {
	global $sitename, $Default_Temp, $siteurl;
	echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">\n";
	echo "<head>\n";
	echo "<meta http-equiv=\"Content-Language\" content=\"en-us\">\n";
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset="._CHARSET."\">\n";
	echo "<title>$sitename- $title</title>\n";
	echo "<link rel=\"StyleSheet\" href=\"templates/".$Default_Temp."/css/styles.css\" type=\"text/css\">\n";
	echo "</head>\n";
	echo "<body bgcolor=\"#CCCCCC\">\n";
	echo "<table border=\"0\" width=\"100%\" cellpadding=\"0\" style=\"border-collapse: collapse; margin-top: 150px\">\n";
	echo "<tr>\n";
	echo "<td align=\"center\">\n";
	echo "<table border=\"1\" bgcolor=\"#FFFFFF\" cellpadding=\"5\" style=\"border-collapse: collapse\" width=\"65%\" bordercolor=\"#035683\">\n";
	echo "<tr>\n";
	echo "<td bgcolor=\"#1E84BC\" background=\"images/blbg.gif\" class=\"titlearl\"><b><font color=\"#FFFFFF\">$title....</font></b></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td style=\"padding: 10px\" class=\"titlearl\" align=\"center\">$message";
	if($load) {
		echo "<div align=\"center\" style=\"margin-top: 10px\"><img border=\"0\" alt=\"loading\" title=\"loading\" src=\"images/loading.gif\"/></div>\n";
	}
	if($back) {
		echo "<div align=\"center\" style=\"margin-top: 10px\"><input type=\"button\" value=\""._BACK."\" onclick=\"history.back(1);\"></div>\n";
	}
	echo "</td></tr></table>\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "\n";
	echo "</body>\n";
	echo "\n";
	echo "</html>\n";
}

function nospatags($str) {
	global $escape_mysql_string;
	$str = $escape_mysql_string(trim((check_html($str, "nohtml"))));
	return $str;
}

function generate_code($chars){
	$r ="";
	for($i=0;$i<=($chars-1);$i++){
		$r0 = rand(0,1); $r1 = rand(0,2);
		if($r0==0){$r .= chr(rand(ord('A'),ord('Z')));}
		elseif($r0==1){ $r .= rand(0,9); }
		if($r1==0){ $r = strtolower($r); }
	}
	return $r;
}

function foldcreate($modname) {
	global $path_upload;
	$now_month = date("m");
	$now_year = date("Y");
	if((!file_exists("".RPATH."".$path_upload."/".$modname."/".$now_year."_".$now_month.""))||(!is_dir("".RPATH."".$path_upload."/".$modname."/".$now_year."_".$now_month.""))){
		@mkdir("".RPATH."".$path_upload."/".$modname."/".$now_year."_".$now_month."");
	}
}
function mkdirfold($path){
	if((!file_exists("".RPATH."".$path.""))||(!is_dir("".RPATH."".$path.""))){
		@mkdir("".RPATH."".$path."");
	}
}
#######################################
# URL OPTIMIZATION
#######################################
function url_optimization($name) {
	$name = preg_replace('/&.+?;/', '', utf8_to_ascii($name));
	$name = str_replace('_', '-', $name );
	$name = preg_replace('/[^a-z0-9\s-.]/i', '', $name);
	$name = preg_replace('/\s+/', '-', $name);
	$name = preg_replace('|-+|', '-', $name);
	$name = trim($name, '-');
	return $name;
}
#######################################
# CONVERT UTF8 TO ASCII
#######################################
function utf8_to_ascii($str) {
	$chars = array(
		'a'	=>	array('A','&#7845;','&#7847;','&#7849;','&#7851;','&#7853;','&#7844;','&#7846;','&#7848;','&#7850;','&#7852;','&#7855;','&#7857;','&#7859;','&#7861;','&#7863;','&#7854;','&#7856;','&#7858;','&#7860;','&#7862;','á','à','&#7843;','ã','&#7841;','â','&#259;','Á','À','&#7842;','Ã','&#7840;','Â','&#258;'),
		'e' =>	array('E','&#7871;','&#7873;','&#7875;','&#7877;','&#7879;','&#7870;','&#7872;','&#7874;','&#7876;','&#7878;','é','è','&#7867;','&#7869;','&#7865;','ê','É','È','&#7866;','&#7868;','&#7864;','Ê'),
		'i'	=>	array('I','í','ì','&#7881;','&#297;','&#7883;','Í','Ì','&#7880;','&#296;','&#7882;'),
		'o'	=>	array('O','&#7889;','&#7891;','&#7893;','&#7895;','&#7897;','&#7888;','&#7890;','&#7892;','Ô','&#7896;','&#7899;','&#7901;','&#7903;','&#7905;','&#7907;','&#7898;','&#7900;','&#7902;','&#7904;','&#7906;','ó','ò','&#7887;','õ','&#7885;','ô','&#417;','Ó','Ò','&#7886;','Õ','&#7884;','Ô','&#416;'),
		'u'	=>	array('U','&#7913;','&#7915;','&#7917;','&#7919;','&#7921;','&#7912;','&#7914;','&#7916;','&#7918;','&#7920;','ú','ù','&#7911;','&#361;','&#7909;','&#432;','Ú','Ù','&#7910;','&#360;','&#7908;','&#431;'),
		'y'	=>	array('Y','ý','&#7923;','&#7927;','&#7929;','&#7925;','Ý','&#7922;','&#7926;','&#7928;','&#7924;'),
		'd'	=>	array('D','&#273;','&#272;'),
		'q'	=>	array('Q'),
		'w'	=>	array('W'),
		'r'	=>	array('R'),
		't'	=>	array('T'),
		'p'	=>	array('P'),
		's'	=>	array('S'),
		'f'	=>	array('F'),
		'g'	=>	array('G'),
		'h'	=>	array('H'),
		'j'	=>	array('J'),
		'k'	=>	array('K'),
		'l'	=>	array('L'),
		'z'	=>	array('Z'),
		'x'	=>	array('X'),
		'c'	=>	array('C'),
		'v'	=>	array('V'),
		'b'	=>	array('B'),
		'n'	=>	array('N'),
		'm'	=>	array('M'),
	);
	foreach ($chars as $key => $arr){
		foreach ($arr as $val){
			$str = str_replace($val, $key, $str);
		}
	}
	return trim($str);
}
//Hàm chuy&#7875;n &#273;&#7893;i tiêu &#273;&#7873; ti&#7871;ng vi&#7879;t có d&#7845;u sang không d&#7845;u 

function cv2urltitle($text) {

$text = str_replace(
array(' ','%',"/","\\",'"','?','<','>',"#","^","`","'","=","!",":" ,",",".","*","&","_","&#9604;"),
array('-','' ,'' ,'' ,'' ,'' ,'' ,'' ,'' ,'' ,'' ,'' ,'-','' ,'-','' ,'' ,'' , "_" ,"" ,""),
$text); 

$chars = array("a","A","e","E","o","O","u","U","i","I","d", "D","y","Y");

$uni[0] = array("á","à","&#7841;","&#7843;","ã","â","&#7845;","&#7847;","&#7853;","&#7849;","&#7851;","&#259;","&#7855;","&#7857;","&#7863;","&#7859;","&#65533;&#65533; &#65533;");
$uni[1] = array("Á","À","&#7840;","&#7842;","Ã","Â","&#7844;","&#7846;","&#7852;","&#7848;","&#7850;","&#258;","&#7854;","&#7856;","&#7862;","&#7858;","&#65533;&#65533; &#65533;");
$uni[2] = array("é","è","&#7865;","&#7867;","&#7869;","ê","&#7871;","&#7873;","&#7879;","&#7875;","&#7877;");
$uni[3] = array("É","È","&#7864;","&#7866;","&#7868;","Ê","&#7870;","&#7872;","&#7878;","&#7874;","&#7876;");
$uni[4] = array("ó","ò","&#7885;","&#7887;","õ","ô","&#7889;","&#7891;","&#7897;","&#7893;","&#7895;","&#417;","&#7899;","&#7901;","&#7907;","&#7903;","&#65533;&#65533; &#65533;");
$uni[5] = array("Ó","Ò","&#7884;","&#7886;","Õ","Ô","&#7888;","&#7890;","&#7896;","&#7892;","&#7894;","&#416;","&#7898;","&#7900;","&#7906;","&#7902;","&#65533;&#65533; &#65533;");
$uni[6] = array("ú","ù","&#7909;","&#7911;","&#361;","&#432;","&#7913;","&#7915;","&#7921;","&#7917;","&#7919;");
$uni[7] = array("Ú","Ù","&#7908;","&#7910;","&#360;","&#431;","&#7912;","&#7914;","&#7920;","&#7916;","&#7918;");
$uni[8] = array("í","ì","&#7883;","&#7881;","&#297;");
$uni[9] = array("Í","Ì","&#7882;","&#7880;","&#296;");
$uni[10] = array("&#273;");
$uni[11] = array("&#272;");
$uni[12] = array("ý","&#7923;","&#7925;","&#7927;","&#7929;");
$uni[13] = array("Ý","&#7922;","&#7924;","&#7926;","&#7928;");

for($i=0; $i<=13; $i++) {
$text = str_replace($uni[$i],$chars[$i],$text);
}

return $text;
} 

function sendmail($subject, $mailto, $sender_mail, $message, $extraHeader = "", $plainBody = "") {
	global $smtp_mail, $smtp_host, $smtp_username, $smtp_password, $smtp_port;

	if ($smtp_mail == 1) $m = new Mail($sender_mail, $mailto, $subject, $message, "SMTP", $smtp_host, $smtp_username, $smtp_password, $smtp_port);
	else $m = new Mail($sender_mail, $mailto, $subject, $message);
	if (!empty($plainBody)) $m->setPlainBody($plainBody);

	$ret = $m->send();
	return $ret;
}

function useragentrs() {
	global $client_ip;
	$agent = substr (trim ($_SERVER['HTTP_USER_AGENT']), 0, 80);
	$addr_ip = substr (trim ($client_ip), 0, 15);

	$client = "{$agent}{$addr_ip}";
	$client = md5($client);

	return $client;
}

function currency_select($curr) {
	$curr_arr = array(_VND,_USD);
	for($i =0; $i < sizeof($curr_arr); $i ++) {
		$seld ="";
		if($curr == $i) { $seld =" selected"; }
		echo "<option value=\"$i\"$seld>$curr_arr[$i]</option>\n";
	}
}

function dsprice($price) {
	$price = number_format($price, 0,'.','.');
	return $price;
}

function info_exit($text, $goback="") {
	message_sys(_MESSAGESYS, $text, $load ="", $goback);
	exit();
}

function truncate_table($t) {
	global $db, $prefix;
	if ($db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_".$t)) == 0) {
		$db->sql_query("TRUNCATE TABLE ".$prefix."_".$t);
	}
}

function ajaxload_content(){
	global $urlsite;
	echo "<div align=\"center\" id=\"ajaxload_container\" style=\"display: none\">\n";
	echo "<div id=\"ajaxload_content\">\n";
	echo "<img src=\"$urlsite/images/load_bar.gif\" border=\"0\" title=\"loading\" alt=\"loading\"/>\n";
	echo "</div>\n";
	echo "</div>\n";
}
//muti select item 
function query_muticat($parameter1,$parameter2, $parent,$table){
	global $db, $prefix;
	$treeTemp ="";
	$result = $db->sql_query("SELECT ".$parameter1." FROM ".$table." WHERE ".$parameter2."=$parent");
	if($db->sql_numrows($result) > 0 ) {
		while(list($catid) = $db->sql_fetchrow($result)) {
			$treeTemp .= " OR ".$parameter1." = ".$catid."";
			$treeTemp .= query_muticat($parameter1,$parameter2,$catid,$table);
		}	
	}
	return $treeTemp;	
}
///////////////////////////////////////////////
// ham kiem tra gia tri co nam trong mang gia tri
// gia tri truyen vao gom gia tri can kiem tra va mang 
// ham tra ve ket qua mot gia tri moi neu gia tri so sanh da co trong mang gia tri
// viet boi vinhquangvip Friday, 9/12/11 12:59 PM
// 
///////////////////////////////////////////
function check_valone($valone, $valarr) {
	$valre ="";
	$valone=trim($valone);
	$j=0;
	foreach ($valarr as $i) {
		$i=trim($i);
		$j++;
    	if($valone==$i)
		{
			$valone=$valone."-".$j;
		}
	}
	return $valone;
}
////////////////////////////////////////////////
// function show flash
//code by: vinhquangvip - date: 12-2-2010
//show_flash(tên falsh, &#273;&#432;&#7901;ng d&#7851;n file flash, &#273;&#7897; r&#7897;ng flash, &#273;&#7897; dài flash)
////////////////////////////////////////////////
function show_flash($flashid,$fileflash,$fwidth,$fheight){

	echo"<object id=\"$flashid\" classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" width=\"$fwidth\" height=\"$fheight\">\n";
	echo"<param name=\"movie\" value=\"$fileflash\" />\n";
	echo"<param name=\"quality\" value=\"high\" />\n";
	echo"<param name=\"wmode\" value=\"opaque\" />\n";
	echo"<param name=\"swfversion\" value=\"9.0.45.0\" />\n";
	echo"<param name=\"expressinstall\" value=\"Scripts/expressInstall.swf\" />\n";
	echo"<!-- Next object tag is for non-IE browsers. So hide it from IE using IECC. -->\n";
	echo"<!--[if !IE]>-->\n";
	echo"<object type=\"application/x-shockwave-flash\" data=\"$fileflash\" width=\"$fwidth\" height=\"$fheight\">\n";
	
	echo"<!--<![endif]-->\n";
	echo"<param name=\"quality\" value=\"high\" />\n";
	echo"<param name=\"wmode\" value=\"opaque\" />\n";
	echo"<param name=\"swfversion\" value=\"9.0.45.0\" />\n";
	echo"<param name=\"expressinstall\" value=\"Scripts/expressInstall.swf\" />\n";
	echo"<!--[if !IE]>-->\n";
	echo"</object>\n";
	echo"<!--<![endif]-->\n";
	echo"</object>\n";
	echo"<script type=\"text/javascript\">\n";
	echo"<!--\n";
	echo"swfobject.registerObject(\"$flashid\");\n";
	echo"//-->\n";
	echo"</script>\n";	
}
// ReSize Images
// ham thay doi kich thuoc anh
// $title mieu ta cho hinh anh
// $imgIn: duong dan anh can thay doi kich thuoc
// $imgOut: duong dan anh sau khi thay doi kich thuoc
// $width: chieu rong cua anh da thay doi kich thuoc
// $height: chieu cao cua anh da thay doi kich thuoc
function resize_image($title, $image, $imgin, $imgout,$imgcss, $width, $height){
	global $urlsite;
	$imgin		= $imgin."/".$image;
	$imgout		= $imgout."/".$width."x".$height."_".$image;
	if(!file_exists($imgout)) 
	{
		$resizemax = new resize($imgin);
		$resizemax -> resizeImage($width, $height, 'crop');
		$resizemax -> saveImage($imgout, 80);
	}
	$image = "$urlsite/$imgout";
	$image= "<img title=\"$title\" alt=\"$title\" width=\"$width\" class=\"$imgcss\" height=\"$height\" src=\"$image\" />";
	return $image;
}
function resizeImages($imgIn, $ingOut, $width = 0, $height = 0) {
	if(!file_exists($ingOut)) {
		$MyImg = new Image;
		$MyImg->SrcFile = $imgIn; //&#7842;nh g&#7889;c
		$MyImg->DestFile = $ingOut; //&#7842;nh sao chép sau khi resize
		if($width != 0 && $height != 0){
			$MyImg->NewWidth = $width;
			$MyImg->NewHeight = $height;
			$MyImg->SaveFileWH();
		} else if($width != 0 && $height == 0){
			$MyImg->WidthPercent = $width;
			$MyImg->SaveFileW();
		} else if($width == 0 && $height != 0){
			$MyImg->HeightPercent = $height;
			$MyImg->SaveFileH();
		}else {
			$ingOut = $imgIn;
		}
				
		return $ingOut;
	}else {
		return $ingOut;
	}	
}
function makeDir( $target ) {
		// from php.net/mkdir user contributed notes
		$target = str_replace( '//', '/', $target );
		if ( file_exists( $target ) )
			return @is_dir( $target );
	
		// Attempting to create the directory may clutter up our display.
		if ( @mkdir( $target ) ) {
			$stat = @stat( dirname( $target ) );
			$dir_perms = $stat['mode'] & 0007777;  // Get the permission bits.
			@chmod( $target, $dir_perms );
			return true;
		} elseif ( is_dir( dirname( $target ) ) ) {
				return false;
		}
	
		// If the above failed, attempt to create the parent node, then try again.
		if ( ( $target != '/' ) && ( $this->makeDir( dirname( $target ) ) ) )
			return $this->makeDir( $target );
	
		return false;
	}
////////////////
// $file		: tai lieu goc
function output_file($file, $name, $mime_type=''){
    /*
    This function takes a path to a file to output ($file),
    the filename that the browser will see ($name) and
    the MIME type of the file ($mime_type, optional).
    
    If you want to do something on download abort/finish,
    register_shutdown_function('function_name');
    */
    if(!is_readable($file)) die('File not found or inaccessible!');
     
    $size = filesize($file);
    $name = rawurldecode($name);
     
    /* Figure out the MIME type (if not specified) */
    $known_mime_types=array(
       "pdf" => "application/pdf",
       "txt" => "text/plain",
       "html" => "text/html",
       "htm" => "text/html",
       "exe" => "application/octet-stream",
       "zip" => "application/zip",
	   "rar" => "application/x-rar-compressed",
       "doc" => "application/msword",
       "xls" => "application/vnd.ms-excel",
       "ppt" => "application/vnd.ms-powerpoint",
       "gif" => "image/gif",
       "png" => "image/png",
       "jpeg"=> "image/jpg",
       "jpg" =>  "image/jpg",
       "php" => "text/plain"
    );
          
    if($mime_type==''){
        $file_extension = strtolower(substr(strrchr($file,"."),1));
        if(array_key_exists($file_extension, $known_mime_types)){
           $mime_type=$known_mime_types[$file_extension];
        } else {
           $mime_type="application/force-download";
        };
    };
     
    @ob_end_clean(); //turn off output buffering to decrease cpu usage
     
    // required for IE, otherwise Content-Disposition may be ignored
    if(ini_get('zlib.output_compression'))
     ini_set('zlib.output_compression', 'Off');
      
    header('Content-Type: ' . $mime_type);
    header('Content-Disposition: attachment; filename="'.$name.'"');
    header("Content-Transfer-Encoding: binary");
    header('Accept-Ranges: bytes');
     
    /* The three lines below basically make the
       download non-cacheable */
    header("Cache-control: private");
    header('Pragma: private');
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    
    // multipart-download and download resuming support
    if(isset($_SERVER['HTTP_RANGE']))
    {
       list($a, $range) = explode("=",$_SERVER['HTTP_RANGE'],2);
       list($range) = explode(",",$range,2);
       list($range, $range_end) = explode("-", $range);
       $range=intval($range);
       if(!$range_end) {
           $range_end=$size-1;
       } else {
           $range_end=intval($range_end);
       }
    
       $new_length = $range_end-$range+1;
       header("HTTP/1.1 206 Partial Content");
       header("Content-Length: $new_length");
       header("Content-Range: bytes $range-$range_end/$size");
    } else {
       $new_length=$size;
       header("Content-Length: ".$size);
    }
    
    /* output the file itself */
    $chunksize = 1*(1024*1024); //you may want to change this
    $bytes_send = 0;
    if ($file = fopen($file, 'r'))
    {
       if(isset($_SERVER['HTTP_RANGE']))
       fseek($file, $range);
        
       while(!feof($file) &&
           (!connection_aborted()) &&
           ($bytes_send<$new_length)
             )
       {
           $buffer = fread($file, $chunksize);
           print($buffer); //echo($buffer); // is also possible
           flush();
           $bytes_send += strlen($buffer);
       }
    fclose($file);
    } else die('Error - can not open file.');
     
    die();
} 
?>