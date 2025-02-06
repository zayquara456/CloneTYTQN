<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

$id = isset($_GET['id']) ? intval($_GET['id']) : intval($_POST['id']);
$load_hf = isset($_GET['load_hf']) ? 1 : 0;

$result = $db->sql_query("SELECT * FROM ".$prefix."_newsletter_send WHERE id=$id");
if ($db->sql_numrows($result) != 1) {
	include("modules/".$adm_modname."/Sent.php");
	die();
} else {
	$result_e = $db->sql_query("SELECT id, newsletterid FROM ".$prefix."_newsletter");
	if($db->sql_numrows($result_e) > 0) {
		while(list($idn, $newsletterid) = $db->sql_fetchrow($result_e)) {
			if($newsletterid != '0') {
				$newsletterid_arr = @explode(",",$newsletterid);
				if(@in_array($id, $newsletterid_arr)) {
					$newsletteridx = array();
					for($i = 0; $i < sizeof($newsletterid_arr); $i++) {
						if($id != $newsletterid_arr[$i]) {
							$newsletteridx[] = $newsletterid_arr[$i];
						}	
					}
					$newsletterid_up = @implode(",",$newsletteridx);
					$db->sql_query("UPDATE ".$prefix."_newsletter SET newsletterid='$newsletterid_up' WHERE id=$idn");
				}
			}
		}
	}
	
	$db->sql_query("DELETE FROM ".$prefix."_newsletter_send WHERE id=$id");
	
	include("modules/".$adm_modname."/Sent.php");
}
?>