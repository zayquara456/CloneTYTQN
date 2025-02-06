<?php
if (!defined('IN_SEARCH')) die();
$query2=url_optimization(trim($query));
$num = $db->sql_fetchrow($db->sql_query("SELECT COUNT(*) FROM {$prefix}_document WHERE (title LIKE '%$query%' OR permalink LIKE '%$query2%' OR hometext LIKE '%$query%' OR bodytext LIKE '%$query%' OR seo_title LIKE '%$query%' OR seo_description LIKE '%$query%' OR seo_keyword LIKE '%$query%' OR seo_tag LIKE '%$query%') AND active=1 AND alanguage='$currentlang'"));
if($num[0] > 0) {
	if($titlemod != "") {
		$sql = "SELECT id, title, hometext, bodytext, seo_title, seo_description, seo_keyword, seo_tag FROM {$prefix}_document WHERE (title LIKE '%$query%' OR permalink LIKE '%$query2%' OR hometext LIKE '%$query%' OR bodytext LIKE '%$query%' OR seo_title LIKE '%$query%' OR seo_description LIKE '%$query%' OR seo_keyword LIKE '%$query%' OR seo_tag LIKE '%$query%') AND active=1 AND alanguage='$currentlang' ORDER BY id DESC LIMIT $offset,$perpage";
	} else {
		$sql = "SELECT id, title, hometext, bodytext, seo_title, seo_description, seo_keyword, seo_tag FROM {$prefix}_document WHERE (title LIKE '%$query%' OR permalink LIKE '%$query2%' OR hometext LIKE '%$query%' OR bodytext LIKE '%$query%' OR seo_title LIKE '%$query%' OR seo_description LIKE '%$query%' OR seo_keyword LIKE '%$query%' OR seo_tag LIKE '%$query%') AND active=1 AND alanguage='$currentlang' ORDER BY id DESC LIMIT $first_page_res";
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