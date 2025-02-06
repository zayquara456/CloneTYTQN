<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html dir="ltr" lang="en">
<head>
<title>
<?php
	if($adm_pagetitle) { 
		echo "$adm_pagetitle - ";
	}	
	if($adm_pagetitle2) { 
		echo "$adm_pagetitle2 - ";
	}
	echo "Admin Control Panel";
?>
</title>
<meta http-equiv="Content-Language" content="en-us">
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo _CHARSET; ?>">
<link rel="stylesheet" href="templates/acud/css/styles.css" />
<link rel="stylesheet" href="templates/acud/css/chosen.css" type="text/css" />
<link rel="stylesheet" href="templates/acud/css/template.css" type="text/css" />
</head>
<body onload="set_cp_title();">