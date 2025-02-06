<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

$id = isset($_GET['id']) ? $_GET['id'] : $_POST['id'];
$result = $db->sql_query("SELECT subject, text, html FROM ".$prefix."_newsletter_send WHERE id='$id'");
if(empty($id) || $db->sql_numrows($result) != 1) {
	die();
}

list($subject, $text, $html) = $db->sql_fetchrow($result);
$text = nl2br($text);
include("page_header.php");

echo "<br/><table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\" class=\"tableborder\">\n";
echo "<tr><td class=\"header\" colspan=\"2\">"._MODTITLE." &raquo; "._VIEWEMAIL."</td></tr>";
echo "<tr>\n";
echo "<td class=\"row1\" width=\"100\"><b>"._TITLE."</b></td>\n";
echo "<td class=\"row2\">$subject</td></tr>\n";
echo "<tr>\n";
echo "<td class=\"row1\"><b>"._NTYPETEXT."</b></td>\n";
echo "<td class=\"row2\">$text</td></tr>\n";
echo "<tr>\n";
echo "<td class=\"row1\"><b>"._NTYPEHTML."</b></td>\n";
echo "<td class=\"row2\">$html</td></tr>\n";
echo "<tr><td class=\"row3\" colspan=\"2\" align=\"center\"><input type=\"button\" name=\"submit\" value=\""._DELETE."\" onclick=\"window.location='?f=$adm_modname&do=delete_sent&id=$id'\"></td></tr>";
echo "</table>";

include_once("page_footer.php");

?>