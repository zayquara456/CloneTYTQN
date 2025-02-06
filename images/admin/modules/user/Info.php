<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");
require_once("language/$currentlang/user.php");
$adm_modname = 'user';

$sort = intval(isset($_GET['sort']) ? $_GET['sort'] : (isset($_POST['sort']) ? $_POST['sort'] : 0));
switch($sort) {
	case 1: $sortby ="ORDER BY id ASC"; break;
	case 2: $sortby ="ORDER BY id DESC"; break;
	case 3: $sortby ="ORDER BY fullname ASC"; break;
	case 4: $sortby ="ORDER BY fullname DESC"; break;
	case 5: $sortby ="ORDER BY email ASC"; break;
	case 6: $sortby ="ORDER BY email DESC"; break;
	default: $sortby ="ORDER BY registrationTime DESC"; break;
}

echo "<div id=\"pagehome\">";
echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
echo "<tr><td colspan=\"15\" class=\"header\">Thành viên mới</td></tr>\n";
echo "<tr>\n<td class=\"row1sd\" width=\"20\" align=\"center\">"._USER_ID."</td>\n";
echo "<td class=\"row1sd\" align=\"center\">"._USER_FULLNAME."</td>\n";
echo "<td class=\"row1sd\" align=\"center\">"._USER_EMAIL."</td>\n";
echo "<td align=\"center\" width=\"120\" class=\"row1sd\"><b>Thời gian</b></td>\n";
echo "<td align=\"center\" class=\"row1sd\"><b>K-H</b></td>\n";
echo "<td align=\"center\" class=\"row1sd\"><b>K</b></td>\n";
echo "<td align=\"center\" class=\"row1sd\"><b></b></td>\n";
echo "</tr>\n";

$result = $db->sql_query("SELECT id, group_id, fullname, money, email, phone, address, activationCode, actives, registrationTime, recoverCode, loginAttempt, unblockCode FROM {$prefix}_user ORDER BY id DESC  LIMIT 9");
if ($db->sql_numrows() > 0) {
	$i = 0;
	while (list($id, $group, $tname, $money, $temail, $phone, $address, $activationCode, $active, $registrationTime, $recoverCode, $loginAttempt, $unblockCode) = $db->sql_fetchrow($result)) {
		if($ajax_active == 1) {	
			switch($active) {
				case 1: $active = "<a href=\"?f=$adm_modname&do=status&id=$id&stat=0\" title=\""._DEACTIVATE."\" onclick=\"return aj_base_status($id,0,'$adm_modname','','');\"><img border=\"0\" src=\"images/view.png\"></a>"; break;
				case 0: $active = "<a href=\"?f=$adm_modname&do=status&id=$id&stat=1\" title=\""._ACTIVE."\" onclick=\"return aj_base_status($id,1,'$adm_modname','','');\"><img border=\"0\" src=\"images/viewo.png\"></a>"; break;
			}
		} else {
			switch($active) {
				case 1: $active = "<a href=\"?f=$adm_modname&do=status&id=$id&stat=0\" info=\""._DEACTIVATE."\"><img border=\"0\" src=\"images/view.png\"></a>"; break;
				case 0: $active = "<a href=\"?f=$adm_modname&do=status&id=$id&stat=1\" info=\""._ACTIVE."\"><img border=\"0\" src=\"images/viewo.png\"></a>"; break;
			}	
		}
		if(is_null($activationCode))
		{
			$activation ="<a href=\"?f=$adm_modname&do=status&id=$id&active=0\" info=\""._DEACTIVATE."\"><img border=\"0\" src=\"images/view.png\"></a>";
		}
		else
		{
			$activation = "<a href=\"?f=$adm_modname&do=status&id=$id&active=1\" info=\""._ACTIVE."\"><img border=\"0\" src=\"images/viewo.png\"></a>";
		}
		if(is_null($unblockCode))
		{
			$unblock ="<a href=\"?f=$adm_modname&do=status&id=$id&block=0\" info=\""._DEACTIVATE."\"><img border=\"0\" src=\"images/view.png\"></a>";
		}
		else
		{
			$unblock = "<a href=\"?f=$adm_modname&do=status&id=$id&block=1\" info=\""._ACTIVE."\"><img border=\"0\" src=\"images/viewo.png\"></a>";
		}
		if (($i % 2) == 1) $css = "row1";
		else $css ="row3";

		if ($ajax_active == 1) {
			$tdId = " id=\"{$adm_modname}_title_edit_$id\"";
			//$fullname = "<a href=\"modules.php?f=$adm_modname&do=edit&id=$id\" onclick=\"return show_edit_title($id,'$tname','$adm_modname',20,'"._SAVECHANGES."','quick_edit_name')\" info=\""._QUICK_EDIT."\">$tname</a>";
			//$tdId2 = " id=\"email_title_edit_$id\"";
			//$email = "<a href=\"modules.php?f=$adm_modname&do=edit&id=$id\" onclick=\"return show_edit_title2($id,'$temail','$adm_modname','email',20,'"._SAVECHANGES."','quick_edit_email','email_title_edit_$id')\" info=\""._QUICK_EDIT."\">$temail</a>";
						
			$delete = "<a href=\"modules.php?f=$adm_modname&do=delete&id=$id\" onclick=\"return aj_base_delete('$id','$adm_modname','"._USER_DELETEASK."','delete','id');\" info=\""._DELETE."\"><img border=\"0\" src=\"images/delete.png\"></a>";
		} else {
			$tdId = $tdId2= '';
			$fullname = $tname;
			$email = $temail;
			$delete = "<a href=\"modules.php?f=$adm_modname&do=delete&id=$id\" onclick=\"return confirm('"._USER_DELETEASK."')\" info=\""._DELETE."\"><img border=\"0\" src=\"images/delete.png\"></a>";
		}

		echo "<tr>\n<td class=\"row1\" align=\"center\">$id</td>\n";
		echo "<td class=\"row1\" align=\"left\">$tname</td>\n";
		echo "<td class=\"row1\" align=\"left\">$temail</td>\n";		
		echo "<td align=\"center\" class=\"row1\">".$registrationTime."</td>\n";
		echo "<td align=\"center\" class=\"row1\">$activation</td>\n";
		echo "<td align=\"center\" class=\"row1\">$unblock</td>\n";
		echo "<td align=\"center\" class=\"row1\">$active</td>\n";
		echo "\n</tr>";
	}
}

if($total > $perpage) {
	echo "<tr><td colspan=\"20\">";
	$pageurl = "modules.php?f=$adm_modname&sort=$sort";
	echo paging($total,$pageurl,$perpage,$page);
	echo "</td></tr>";
}

echo "</table>\n</div>\n";

?>
