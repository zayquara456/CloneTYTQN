
<?php
if (!defined('CMS_SYSTEM')) die();

$id = intval($_GET['id']);

$db->sql_query("UPDATE ".$prefix."_video SET hits=hits+1 WHERE id='$id'");

$result = $db->sql_query("SELECT title, links, hits FROM {$prefix}_video WHERE id='$id'");
list($title, $links, $hits) = $db->sql_fetchrow($result);

	$str1 = substr($links, 0, 15);
	$str2 = substr($links, 15, 3);
	$str2 = 550;
	$str3 = substr($links, 18, 10);
	$str4 = substr($links, 28, 3);
	$str4 = 380;
	$str5 = substr($links, 31, 200);
	$str = $str1.$str2.$str3.$str4.$str5;

$page_title = $title;
$description_page = $title;
$title_page = $title;
include_once("header.php");

echo "<div class=\"title_home\">".$title."</div>";
echo "<div class=\"div-home\" style=\"padding-bottom:23px;\" >";
echo "<div class=\"play-video\">";
echo '<iframe width="658" height="404" src="//www.youtube.com/embed/'.$links.'?rel=0" frameborder="0" allowfullscreen></iframe>';
echo "</div>";
echo "<div class=\"play-hits\">"._HITS_VIDEO." : ".$hits."</div>";

echo "</div>";

include_once("footer.php");
?>