<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

global $load_hf;

if ($load_hf) { $noload_hf = 0; } else { $noload_hf = 1; }
if ($noload_hf) {
	echo "<br/>";
	echo "</div>\n";
	//echo "<div class=\"titlefooter\">"._TITLE_FOOTER."</div>";
	echo "</body>\n";
	echo "</html>\n";
}
$db->sql_close();
?>