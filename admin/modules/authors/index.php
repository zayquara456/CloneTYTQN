<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

include("page_header.php");
echo "<div id=\"pagecontent\">";
$sql="";
if(defined('iS_SADMIN'))
{
	$sql="SELECT adacc, adname, email, permission, mods, last_login FROM ".$prefix."_admin WHERE permission<>0  ORDER BY permission DESC";
}
else
{
	$sql="SELECT adacc, adname, email, permission, mods, last_login FROM ".$prefix."_admin ORDER BY permission ASC";
}
	$result = $db->sql_query($sql);
ajaxload_content();

echo "<div id=\"".$adm_modname."_main\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
echo "<tr><td colspan=\"6\" class=\"header\">"._MODTITLE."</td></tr>";
echo "<tr>\n";
echo "<td class=\"row1sd\">"._ATACC."</td>\n";
echo "<td align=\"center\" class=\"row1sd\">"._ATNAME."</td>\n";
echo "<td align=\"center\" class=\"row1sd\">"._ATPERMISSION."</td>\n";
echo "<td align=\"center\" class=\"row1sd\">"._LASTLOGIN."</td>\n";
echo "<td align=\"center\" width=\"30\" class=\"row1sd\">"._EDIT."</td>\n";
echo "<td align=\"center\" width=\"30\" class=\"row3sd\">"._DELETE."</td>\n";
echo "</tr>\n";
$i =0;
while(list($adacc, $adname, $email, $permission, $mods, $last_login) = $db->sql_fetchrow($result)) {
	if($adname == "Root" && $permission == 0) 
	{
		$class = "bred";
		$spacepermiss = _ROOTADMIN;
		$icondel = "<img border=\"0\" src=\"../images/lock.gif\">";
	}
	elseif ($adname != "Root" && $permission == 1)
	{
		$class = "bblue";
		$spacepermiss = _SPADMIN;
		if($ajax_active == 1) 
		{
			$icondel = "<a href=\"?f=$adm_modname&do=delete&acc=$adacc\" onclick=\"return aj_base_delete('$adacc','$adm_modname','"._DELETEASK."','','acc');\" info=\""._DELETE."\"><img border=\"0\" src=\"images/delete.png\"></a>";
		} else {
			$icondel = "<a href=\"?f=$adm_modname&do=delete&acc=$adacc\" onclick=\"return confirm('"._DELETEASK."');\" info=\""._DELETE."\"><img border=\"0\" src=\"images/delete.png\"></a>";
		}
	}
	else{
		$class = "";
		/*if($mods !="") {
			$admods_arr = @explode("|",$mods);

			$spacepermiss = "<select style=\"width: 150px\">";
			for($i =0; $i < sizeof($admods_arr); $i ++) {
				$spacepermiss .="<option>".$listmods_name[$admods_arr[$i]]."</option>";
			}
			$spacepermiss .="</select>";
		} else {
			$spacepermiss = "Co loi voi viec xet quyen";
		}*/
		if($permission!=0)
			$spacepermiss=show_groupauthor($permission);
		
		if($ajax_active == 1) {
			$icondel = "<a href=\"?f=$adm_modname&do=delete&acc=$adacc\" onclick=\"return aj_base_delete('$adacc','$adm_modname','"._DELETEASK."','','acc');\" info=\""._DELETE."\"><img border=\"0\" src=\"images/delete.png\"></a>";
		} else {
			$icondel = "<a href=\"?f=$adm_modname&do=delete&acc=$adacc\" onclick=\"return confirm('"._DELETEASK."');\" info=\""._DELETE."\"><img border=\"0\" src=\"images/delete.png\"></a>";
		}
	}
	echo "<tr>\n";
	echo "<td class=\"row1\"><span class=\"$class\">$adacc</span></td>\n";
	echo "<td align=\"center\" class=\"row1\"><span class=\"$class\">$adname</span></td>\n";
	echo "<td align=\"center\" class=\"row1\"><span class=\"$class\">$spacepermiss</span></td>\n";
	if(!empty($last_login)) {
		echo "<td align=\"center\" class=\"row1\">".ext_time($last_login,2)."</td>\n";
	} else {
		echo "<td align=\"center\" class=\"row1\">------</td>\n";
	}
	echo "<td align=\"center\" class=\"row1\"><a href=\"?f=$adm_modname&do=edit&acc=$adacc\" info=\""._EDIT."\"><img border=\"0\" src=\"images/edit.png\"></a></td>\n";
	echo "<td align=\"center\" class=\"row3\">$icondel</td>\n";
	
	echo "</tr>\n";
	$i ++;
}
echo "<td align=\"center\" class=\"row4\">&nbsp;</td>\n";
echo "</table></div>\n";
echo "</div>\n";
include_once("page_footer.php");
?>