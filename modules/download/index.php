<?php
if (!defined('CMS_SYSTEM')) die();
include_once("header.php");
if($download_home_type == 0) 
{
	OpenTab("");
	$result_catindex = $db->sql_query("SELECT catid, title, homelinks FROM {$prefix}_download_cat WHERE active=1 AND onhome=1 AND alanguage='$currentlang' ORDER BY weight");
	if($db->sql_numrows($result_catindex) > 0) 
	{
		while(list($catid, $titlecat, $homelinks) = $db->sql_fetchrow($result_catindex)) 
		{
			
				$query = "SELECT id, title, hometext, time, fattach FROM ".$prefix."_download WHERE alanguage='$currentlang' AND catid=$catid ORDER BY time DESC LIMIT 5";
				$resultn = $db->sql_query($query);
				if($db->sql_numrows($resultn) > 0) {
					$titlecat= "<a style=\" color:#fff;\"  href=\"".url_sid("index.php?f=download&do=categories&id=$catid")."\">$titlecat</a>";
					OpenContent($titlecat);
					while(list($id, $title, $hometext, $time, $fattach) = $db->sql_fetchrow($resultn)) {
						$url_download_detail =url_sid("index.php?f=download&do=detail&id=$id");
						$hometext = strip_tags($hometext, '<a><b><u><i><strong><span>');
						$fattach = "$urlsite/$path_upload/download/attachs/$fattach";
						temp_download_index($id, $title, $hometext, $url_download_detail, $time);
					}
					CloseContent();
					echo "<br/>";
				}
			
		}
	} 
	else 
	{
		OpenTable();
		echo "<center>"._NODATA."</center>";
		CloseTable();
	}
	CloseTab();
} 
else 
{
	OpenTab(_download);
	$result_download_index = $db->sql_query("SELECT id, title, hometext, images, time FROM ".$prefix."_download WHERE active=1 AND alanguage='$currentlang' ORDER BY time DESC LIMIT $perpage");
	if($db->sql_numrows($result_download_index) > 0) 
	{
		while(list($id, $title, $hometext, $images, $time) = $db->sql_fetchrow($result_download_index)) 
		{
			$get_path_newindex = get_path($time);
			$path_upload_imgnewind = "$path_upload/download/$get_path_newindex";
			if($images !="" && file_exists("$path_upload_imgnewind/$images")) 
			{
				if(file_exists("$path_upload_imgnewind/thumb_".$images."")) 
				{
					$images = "thumb_".$images."";
				}
				$downloadpic = "<div style=\"float: ".$pic_align_cat."; margin-right: 5px; border: 1px solid #CCC; padding: 1px\"><a href=\"".url_sid("index.php?f=download&do=detail&id=$id")."\"><img border=\"0\" src=\"$path_upload_imgnewind/$images\"></a></div>";
			} 
			else 
			{
				$downloadpic = "";
			}
			temp_download_index($id, $title, $hometext, $downloadpic);
		}
	}
	CloseTab();
}
include_once("footer.php");
?>