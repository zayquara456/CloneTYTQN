<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

$id = intval($_GET['id']);
$load_hf = 1;

$result = $db->sql_query("SELECT images, links FROM ".$prefix."_video WHERE id='$id'");
if(empty($id) || $db->sql_numrows($result) != 1) {
	include("modules/".$adm_modname."/index.php");
	die();
}	
list($images,$links) = $db->sql_fetchrow($result);

$db->sql_query("DELETE FROM ".$prefix."_video WHERE id='$id'");
$path_upload_img = "$path_upload/video";
@unlink(RPATH."$path_upload_img/$images");
$destination_file = $_SERVER['DOCUMENT_ROOT']."files/video/".$links; 

// set up basic connection
$conn_id = ftp_connect($ftp_host);

// login with username and password
$login_result = ftp_login($conn_id, $ftp_username, $ftp_password);

// try to delete $file
if (ftp_delete($conn_id, $destination_file)) {
 echo "$file deleted successful\n";
} else {
 echo "could not delete $file\n";
}

// close the connection
ftp_close($conn_id);
truncate_table("video");
//include("modules/".$adm_modname."/index.php");
header("Location: modules.php?f=$adm_modname");
?>