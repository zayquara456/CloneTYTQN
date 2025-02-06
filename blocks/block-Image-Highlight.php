<?php
if (!defined('CMS_SYSTEM')) header("Location: index.php");

if(file_exists("data/config_news.php")) require("data/config_news.php");

$content ="";
global $path_upload, $mod_name, $id, $default_temp;
if($mod_name == "news" && isset($id)) {
	$seld = "AND id!=$id";
} else {
	$seld ="";
}

$result_lastnew = $db->sql_query("SELECT id, title, images, time FROM ".$prefix."_news WHERE active=1 AND image_highlight=1 AND alanguage='$currentlang' $seld ORDER BY time DESC LIMIT $perpage");
$numrows = $db->sql_numrows($result_lastnew);
if($numrows > 0) {
	$a=0;
	$content .= "<table border=\"0\" width=\"100%\" cellpadding=\"0\" style=\"border-collapse: collapse\">";
	while(list($idlast, $titlelast, $imageslast, $time) = $db->sql_fetchrow($result_lastnew)) {
		$content .= "<tr>";
		$get_path = get_path($time);
		$path_upload_img = "$path_upload/news/$get_path";
		$a++;
		if(file_exists("$path_upload_img/$imageslast") && $imageslast !="") {
			if (file_exists("$path_upload_img/thumb_".$imageslast)) $imageslast = "thumb_".$imageslast."";
			$content .= "<td width=\"50\" valign=\"top\"><a href=\"".url_sid("index.php?f=news&do=detail&id=$idlast")."\"><img border=\"0\" src=\"$path_upload_img/$imageslast\" width=\"50\"></a></td>";
			$content .= "<td valign=\"top\"><a href=\"".url_sid("index.php?f=news&do=detail&id=$idlast")."\" class=\"newsothers\">$titlelast</a></td>";
		} else {
			$content .= "<td colspan=\"2\"><a href=\"".url_sid("index.php?f=news&do=detail&id=$idlast")."\" class=\"newsothers\">$titlelast</a></td>";
		}
		$content .= "</tr>";
		$content .= "<tr><td colspan=\"2\" height=\"4\"></td></tr>";
		if ($a < $numrows) $content .= "<tr><td colspan=\"3\"><hr size=\"1\" style=\"border: 1px dotted #666\"></td></tr>";
	}
	$content .= "</table>";
}
?>