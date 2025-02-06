<?php
if (!defined('IN_SEARCH')) die();

$num = $db->sql_fetchrow($db->sql_query("SELECT COUNT(*) FROM {$prefix}_contact WHERE (title LIKE '%$query%' OR content LIKE '%$query%' OR response LIKE '%$query%') AND (alanguage='$currentlang' AND onHome=1)"));
if($num[0] > 0) {
	if($titlemod != "") {
		$sql = "SELECT id, title, content, response FROM {$prefix}_contact WHERE (title LIKE '%$query%' OR content LIKE '%$query%' OR response LIKE '%$query%') AND (alanguage='$currentlang' AND onHome=1) ORDER BY id DESC LIMIT $offset, $perpage";
	} else {
		$sql = "SELECT id, title, content, response FROM {$prefix}_contact WHERE (title LIKE '%$query%' OR content LIKE '%$query%' OR response LIKE '%$query%') AND (alanguage='$currentlang' AND onHome=1) ORDER BY id DESC LIMIT $first_page_res";
	}
	$result = $db->sql_query($sql);
	while ($row = $db->sql_fetchrow($result)) {
		$title[] = $row[1];
		$text[] = "{$row[2]} {$row[3]}";
		$url[] = '';
	}
}
$num_page = $num[0];
?>