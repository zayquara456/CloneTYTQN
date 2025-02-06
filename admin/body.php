<?php
define('CMS_ADMIN', true);
require_once("../config.php");
require_once("language/$currentlang/main.php");



if (defined('iS_ADMIN')) {
	include_once("page_header.php");
	ajaxload_content();
	echo "<div id=\"pagehome\">";
	echo "<table border=\"0\" width=\"100%\" align=\"center\" cellspacing=\"0\" cellpadding=\"5\" class=\"tableborder\">\n";
	echo "<tr><td class=\"header\" colspan=\"2\">"._BASEINFO."</td></tr>";
	echo "<tr><td width=\"50%\" class=\"row1\" align=\"right\">"._SITENAME.":</b></td><td class=\"row2\">$sitename</td></tr>\n";
	echo "<tr><td width=\"50%\" class=\"row1\" align=\"right\"><b>"._STARTDATE.":</b></td><td class=\"row2\">$date_start</td></tr>\n";
	if(defined('iS_RADMIN')) {
		$numsadmin = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_admin WHERE permission='0'"));
		$numsadmin1 = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_admin WHERE permission='1'"));
		$numsadmin2 = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_admin WHERE permission='2'"));
		echo "<tr><td width=\"50%\" class=\"row1\" align=\"right\"><b>"._ADMINSITE.":</b></td><td class=\"row2\">$numsadmin</td></tr>\n";
		echo "<tr><td width=\"50%\" class=\"row1\" align=\"right\"><b>"._ADMINSITE1.":</b></td><td class=\"row2\">$numsadmin1</td></tr>\n";
		echo "<tr><td width=\"50%\" class=\"row1\" align=\"right\"><b>"._ADMINSITE2.":</b></td><td class=\"row2\">$numsadmin2</td></tr>\n";
	}
	echo "<tr><td width=\"50%\" class=\"row1\" align=\"right\"><b>"._DEFAULTLANG.":</b></td><td class=\"row2\">$language</td></tr>\n";
	$totalnews = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_news"));
	echo "<tr><td width=\"50%\" class=\"row1\" align=\"right\"><b>"._TOTALNEWS.":</b></td><td class=\"row2\">$totalnews</td></tr>\n";
	$totaladv = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_advertise"));
	echo "<tr><td width=\"50%\" class=\"row1\" align=\"right\"><b>"._TOTALADV.":</b></td><td class=\"row2\">$totaladv</td></tr>\n";
	list($totalhits) = $db->sql_fetchrow($db->sql_query("SELECT hits FROM ".$prefix."_stats"));
	echo "<tr><td width=\"50%\" class=\"row1\" align=\"right\"><b>"._TOTALHITS.":</b></td><td class=\"row2\">$totalhits</td></tr>\n";
	echo "<tr><td colspan=\"2\" class=\"row4\">&nbsp;</td></tr>\n";
	echo "</table>";
	echo "</div>";
	/// thong tin lien he
	$result = $db->sql_query("SELECT title FROM ".$prefix."_modules WHERE active=1");
	if($db->sql_numrows($result) > 0) 
	{
		while(list($title_module) = $db->sql_fetchrow($result)){
			$infoadmin[$title_module] = 'Info.php';
		}
	}

	foreach($infoadmin AS $key => $value){
		if (file_exists("modules/$key/$value")){
			include("modules/$key/$value");
		}
	}
	echo "<div class=\"cl\"></div>";
	include_once("page_footer.php");
}else{
	header("Location: login.php");
}
?>