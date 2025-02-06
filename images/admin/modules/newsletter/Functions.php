<?php

if(!defined('CMS_ADMIN')) {
	die();
}

function checkEmail($email,$id="") {
	global $db, $prefix;
	if(!empty($id)) {
		$sqlseld = "AND id!='$id'";
	} else {
		$sqlseld ="";
	}		
	if(!is_email($email) || $email =="") {
		$stop = ""._ERROR1."";
	} else if ($db->sql_numrows($db->sql_query("SELECT*FROM ".$prefix."_newsletter WHERE email='$email' $sqlseld")) > 0) {
		$stop = ""._ERROR2."";	
	}
	return $stop;	
}	

?>