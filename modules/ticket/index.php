<?php
if (!defined('CMS_SYSTEM')) die();

include_once("header.php");

$db->sql_query("SELECT COUNT(*) FROM {$prefix}_contact WHERE onHome=1");
list($total) = $db->sql_fetchrow();
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $ticket_perpage;

$db->sql_query("SELECT content, title, response FROM {$prefix}_contact WHERE onHome=1 LIMIT $offset, $ticket_perpage");
if ($db->sql_numrows() > 0) {
	list($content, $title, $response) = $db->sql_fetchrow();
	echo "<table><tr><td colspan=\"2\"><div style=\"margin-bottom: 3px\" class=\"content\"><h4>$title</h4></div></td></tr>";
	echo "<tr><td><strong>"._TICKET_ASK."</strong></td><td>$content</td></tr>";
	echo "<tr><td><strong>"._TICKET_ANSWER."</strong></td><td>$response</td></tr></table>";
	if($total > $ticket_perpage) {
		echo "<div>";
		$pageurl = "index.php?f=$module_name";
		echo paging($total,$pageurl,$ticket_perpage,$page);
		echo "</div>";
	}
} else {
	OpenTable();
	echo "<center>"._NODATA."</center>";
	CloseTable();
}

include_once("footer.php");
?>