<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

$adm_pagetitle2 = _ADDRESSMNG;

$result = $db->sql_query("SELECT address FROM ".$prefix."_contact_add WHERE alanguage='$currentlang'");
list($address) = $db->sql_fetchrow($result);

if(isset($_POST['submit']) && $_POST['submit'] !="") {
	$address = trim(stripslashes(resString($_POST['address'])));
	
	if($db->sql_numrows($result) > 0) {
		$db->sql_query("UPDATE ".$prefix."_contact_add SET address='$address' WHERE alanguage='$currentlang'");
	}else{
		$db->sql_query("INSERT INTO ".$prefix."_contact_add VALUES ('$address', '$currentlang')");
	}	
	updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, ""._SAVECHANGES." Address");
	header("Location: modules.php?f=$adm_modname&do=$do&bf");
	exit();
}	

include("page_header.php");

echo "<form action=\"modules.php?f=$adm_modname&do=$do\" method=\"POST\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" class=\"tableborder\">\n";
echo "<tr><td class=\"header\">"._ADDRESSMNG."</td></tr>";
echo "<tr>\n";
echo "<td>";
editor("address",$address);
echo "</td>\n";
echo "</tr>\n";
echo "<tr><td align=\"center\"><input type=\"submit\" name=\"submit\" value=\""._SAVECHANGES."\" class=\"button2\"></td></tr>";
echo "</table></form>";
include_once("page_footer.php");

?>