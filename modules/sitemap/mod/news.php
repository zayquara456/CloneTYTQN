<?php
if (!defined('IN_SEARCH')) die();

$num = $db->sql_fetchrow($db->sql_query("SELECT COUNT(*) FROM {$prefix}_news WHERE (title LIKE '%$query%' OR hometext LIKE '%$query%' OR bodytext LIKE '%$query%') AND alanguage='$currentlang'"));
if($num[0] > 0) {
	if($titlemod != "") {
		$sql = "SELECT id, title, hometext, bodytext FROM {$prefix}_news WHERE (title LIKE '%$query%' OR hometext LIKE '%$query%' OR bodytext LIKE '%$query%') AND alanguage='$currentlang' ORDER BY id DESC LIMIT $offset,$perpage";
	} else {
		$sql = "SELECT id, title, hometext, bodytext FROM {$prefix}_news WHERE (title LIKE '%$query%' OR hometext LIKE '%$query%' OR bodytext LIKE '%$query%') AND alanguage='$currentlang' ORDER BY id DESC LIMIT $first_page_res";
	}
	$result = $db->sql_query($sql);
	while ($row = $db->sql_fetchrow($result)) {
		$title[] = $row[1];
		$text[] = "{$row[2]} {$row[3]}";
		$url[] = 'do=detail&id='.$row[0];
	}
}
$num_page = $num[0];
?>