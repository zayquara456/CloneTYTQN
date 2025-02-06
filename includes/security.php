<?php
if ((!defined('CMS_SYSTEM')) && (!defined('CMS_ADMIN'))) { header("Location: ../../index.php?httperror=404"); exit; }
if(!defined('CMS_CONFIG')) { header("Location: ../../index.php?httperror=404"); exit; }

ini_set("register_globals", "0");
ini_set("session.use_only_cookies", "1");

if (version_compare(PHP_VERSION, '4.1.0', '<')) {
	$_GET = $HTTP_GET_VARS;
	$_POST = $HTTP_POST_VARS;
	$_SERVER = $HTTP_SERVER_VARS;
	$_FILES = $HTTP_POST_FILES;
	$_ENV = $HTTP_ENV_VARS;
	if($_SERVER['REQUEST_METHOD'] == "POST") {
		$_REQUEST = $_POST;
	} elseif($_SERVER['REQUEST_METHOD'] == "GET") {
		$_REQUEST = $_GET;
	}
	if(isset($HTTP_COOKIE_VARS)) {
		$_COOKIE = $HTTP_COOKIE_VARS;
	}
	if(isset($HTTP_SESSION_VARS)) {
		$_SESSION = $HTTP_SESSION_VARS;
	}
} else {
	$HTTP_GET_VARS = $_GET;
	$HTTP_POST_VARS = $_POST;
	$HTTP_SERVER_VARS = $_SERVER;
	$HTTP_POST_FILES = $_FILES;
	$HTTP_ENV_VARS = $_ENV;
	$PHP_SELF = $_SERVER['PHP_SELF'];
	if(isset($_SESSION)) {
		$HTTP_SESSION_VARS = $_SESSION;
	}
	if(isset($_COOKIE)) {
		$HTTP_COOKIE_VARS= $_COOKIE;
	}
}

if (version_compare(PHP_VERSION, '4.3.0', '<')) $escape_mysql_string = 'mysql_escape_string';
else $escape_mysql_string = 'mysql_real_escape_string';

if (stristr($_SERVER['SCRIPT_NAME'], "mainfile.php") || stristr(htmlentities($_SERVER['PHP_SELF']), "mainfile.php")) { die(); }
if (!isset($_SERVER["HTTP_USER_AGENT"]) || $_SERVER['HTTP_USER_AGENT'] == "" || $_SERVER['HTTP_USER_AGENT'] == "-") { exit; }

if (!function_exists("floatval")) {
	function floatval($inputval) {
		return (float)$inputval;
	}
}

unset($loc);
if(isset($_SERVER['QUERY_STRING'])) {
	if (preg_match("/([OdWo5NIbpuU4V2iJT0n]{5}) /", rawurldecode($loc=$_SERVER['QUERY_STRING']), $matches)) {
		Common::debug(error("Illegal Operation"));
	}
}

if(!function_exists('stripos')) {
	function stripos_clone($haystack, $needle, $offset=0) {
		$return = strpos(strtoupper($haystack), strtoupper($needle), $offset);
		if ($return === false) {
			return false;
		} else {
			return true;
		}
	}
} else {
	function stripos_clone($haystack, $needle, $offset=0) {
		$return = stripos($haystack, $needle, $offset=0);
		if ($return === false) {
			return false;
		} else {
			return true;
		}
	}
}

if(isset($_SERVER['QUERY_STRING']) && (!stripos_clone($_SERVER['QUERY_STRING'], "ad_click") || !stripos_clone($_SERVER['QUERY_STRING'], "url"))) {
	$queryString = $_SERVER['QUERY_STRING'];
	if (stripos_clone($queryString,'%20union%20') OR stripos_clone($queryString,'/*') OR stripos_clone($queryString,'*/union/*') OR stripos_clone($queryString,'c2nyaxb0') OR stripos_clone($queryString,'+union+')OR stripos_clone($queryString,'http://') OR (stripos_clone($queryString,'cmd=') AND !stripos_clone($queryString,'&cmd')) OR (stripos_clone($queryString,'exec') AND !stripos_clone($queryString,'execu')) OR stripos_clone($queryString,'concat')) {
		Common::debug('Illegal Operation');
	}
}

$postString = "";
foreach ($_POST as $postkey => $postvalue) {
	if ($postString > "") {
		$postString .= "&".$postkey."=".$postvalue;
	} else {
		$postString .= $postkey."=".$postvalue;
	}
}
str_replace("%09", "%20", $postString);
$postString_64 = base64_decode($postString);
if (stristr($postString,'%20union%20') OR stristr($postString,'*/union/*') OR stristr($postString,' union ') OR stristr($postString_64,'%20union%20') OR stristr($postString_64,'*/union/*') OR stristr($postString_64,' union ')) {
	header("Location: ".url_sid("index.php")."");
}
//if($_SERVER['REQUEST_METHOD'] == 'POST')
//{
    //Here we parse the form
//    if(!isset($_SESSION['csrf']) || $_SESSION['csrf'] !== $_POST['csrf'])
//        throw new RuntimeException('CSRF attack');
 
    //Do the rest of the processing here
//}
 
//Generate a key, print a form:
$key = sha1(microtime());
$_SESSION['csrf'] = $key;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	foreach ($_POST as $FormFieldName => $FormFieldValue) {
		if (gettype($FormFieldValue) == 'array') {
			$iCount = count($_POST[$FormFieldName]);
			for ($i=0;$i < $iCount;$i++) 	{
				$FormFieldValue = $_POST[$FormFieldName][$i];
				$sTemp .= "name=\"" . $FormFieldName . "[$i]\" value=\"$FormFieldValue\"\r\n";
			}
		} else {$sTemp .= "name=\"$FormFieldName\" value=\"$FormFieldValue\"\r\n"; }
		$sTemp = urldecode($sTemp);
	}
}
if($_SERVER["REQUEST_METHOD"] == "POST" && (preg_match("/mod_authors/", $sTemp) || preg_match("/displayadmins/", $sTemp) || preg_match("/updateadmin/", $sTemp) || preg_match("/modifyadmin/", $sTemp) || preg_match("/deladmin/", $sTemp) || preg_match("/deladmin2/", $sTemp))) {
	die(error("Illegal Operation"));
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
	if (isset($_SERVER['HTTP_REFERER'])) {
		if (!stripos_clone($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'])) {
			die(error("Posting from another server not allowed!"));
		}
	} else {
		die(error("<b>Warning:</b> your browser doesn't send the HTTP_REFERER header to the website.<br/>This can be caused due to your browser, using a proxy server or your firewall.<br/>Please change browser or turn off the use of a proxy<br/>or turn off the 'Deny servers to trace web browsing' in your firewall and you shouldn't have problems when sending a POST on this website."));
	}
}

$htmlerr = "<center><img src=\"".RPATH."images/logo.gif\"><br/><br/><b>";
$htmlerr .= "The html tags you attempted to use are not allowed</b><br/><br/>";
$htmlerr .= "[ <a href=\"javascript:history.go(-1)\"><b>Go Back</b></a> ]</center>";
$filerr = "<center><img src=\"".RPATH."images/logo.gif\"><br/><br/><b>";
$filerr .= "The file not allowed</b><br/><br/>";
$filerr .= "[ <a href=\"javascript:history.go(-1)\"><b>Go Back</b></a> ]</center>";

if (!defined('CMS_ADMIN')) {//SEC05 / 18.7.2006
	foreach ($_GET as $var_name => $var_value) {
		if($security_tags!="") {
			if (preg_match("/<[^>]*?\b($security_tags)\b[^<]*?>/i", urldecode($var_value)) || preg_match("/\([^>]*\"?[^)]*\)/", $var_value) || preg_match("/\"/", $var_value)) Common::debug($htmlerr);
		}
		if($security_url_get==1) {
			if (preg_match("!^(http://|ftp://|https://|php://)!i", $var_value)) Common::debug("URL in GET - ".$var_name." = ". $var_value);
		}
	}
	foreach ($_POST as $var_name => $var_value) {
		if(!empty($security_tags)) {
			if (preg_match("/<[^>]*?\b($security_tags)\b[^<]*?>/i", urldecode($var_value), $matches)) die($htmlerr);
		}
		if($security_url_post==1) {
			if (preg_match("!^(http://|ftp://|https://|php://)!i", $var_value)) Common::debug("URL in POST - ".$var_name." = ". $var_value);
		}
	}
}

if($security_cookies==1) {
	foreach ($_COOKIE as $var_name => $var_value) {
		if (!empty($security_tags)) {
			if (preg_match("/<[^>]*?\b($security_tags)\b[^<]*?>/i", $var_value)) Common::debug($htmlerr);
		}
		if (preg_match("!^(http://|ftp://|https://|php://)!i", $var_value)) Common::debug("Hack in COOKIE - ".$var_name." = ". $var_value);
		$security_string = "/UNION|OUTFILE|SELECT|ALTER|INSERT|DROP|FROM|WHERE|UPDATE|".$prefix."_authors|".$prefix."_users|UpdateAuthor|AddAuthor|mod_authors|modifyadmin|deladmin|deladmin2/i";
		$security_decode = base64_decode($var_value);
		if (preg_match($security_string, $security_decode)) Common::debug("Hack base64 in COOKIE - ".$var_name." = ". $var_value."");
		if (preg_match($security_string, $var_value)) Common::debug("Hack in COOKIE - ".$var_name." = ". $var_value."");
		$security_slash = preg_replace("!/\*.*?\*/!", "", $var_value);
		if (preg_match($security_string, $security_slash)) Common::debug("Hack in COOKIE - ".$var_name." = ". $var_value."");
	}
}

if($security_sessions==1) {
	foreach ($_SESSION as $var_name => $var_value) {
		if (!empty($security_tags)) {
			if (preg_match("/<[^>]*?\b($security_tags)\b[^<]*?>/i", $var_value)) Common::debug($htmlerr);
		}
		if (preg_match("!^(http://|ftp://|https://|php://)!i", $var_value)) Common::debug("Hack in SESSION - ".$var_name." = ". $var_value);
		$security_string = "/UNION|OUTFILE|SELECT|ALTER|INSERT|DROP|FROM|WHERE|UPDATE|".$prefix."_authors|".$prefix."_users|UpdateAuthor|AddAuthor|mod_authors|modifyadmin|deladmin|deladmin2/i";
		$security_decode = base64_decode($var_value);
		if (preg_match($security_string, $security_decode)) die ("Hack base64 in SESSION - ".$var_name." = ". $var_value."");
		if (preg_match($security_string, $var_value)) die ("Hack in SESSION - ".$var_name." = ". $var_value."");
		$security_slash = preg_replace("/\/\*.*?\*\//", "", $var_value);
		if (preg_match($security_string, $security_slash)) die ("Hack in SESSION - ".$var_name." = ". $var_value."");
	}
}

if (!empty($security_files)) {
	foreach ($_FILES as $var_name => $var_value) {
		$var_value = end(explode(".", $_FILES['userfile']['name']));
		if (preg_match("/".$security_files."/i", $var_value)) {
			die($filerr);
		}
	}
}
reset($_GET);
reset($_POST);
reset($_COOKIE);
reset($_SESSION);
reset($_FILES);//END

?>