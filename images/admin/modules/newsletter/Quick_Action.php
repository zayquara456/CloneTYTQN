<?php
if(!defined('CMS_ADMIN')) {
	die();
}


$id = $_POST['id'];

for($i =0; $i < sizeof($id); $i ++) {
	$result_e = $db->sql_query("SELECT id, newsletterid FROM ".$prefix."_newsletter");
	if($db->sql_numrows($result_e) > 0) {
		while(list($idn, $newsletterid) = $db->sql_fetchrow($result_e)) {
			if($newsletterid !="") {
				$newsletterid_arr = @explode(",",$newsletterid);
				if(@in_array($id[$i], $newsletterid_arr)) {
					$newsletteridx ="";
					for($m =0; $m < sizeof($newsletterid_arr); $m ++) {
						if($id[$i] != $newsletterid_arr[$m]) {
							$newsletteridx[] = $newsletterid_arr[$m];
						}	
					}
					$newsletterid_up = @implode(",",$newsletteridx);
					$db->sql_query("UPDATE ".$prefix."_newsletter SET newsletterid='$newsletterid_up' WHERE id='$idn'");
				}		
			}		
		}	
	}
	$db->sql_query("DELETE FROM ".$prefix."_newsletter_send WHERE id='".intval($id[$i])."'");
}	
	
header("Location: ".$adm_modname.".php");

?>