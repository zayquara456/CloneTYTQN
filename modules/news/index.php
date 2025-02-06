<?php
if (!defined('CMS_SYSTEM')) die();
//title
$resultmodule = $db->sql_query("SELECT mid, custom_title, seo_title, seo_description, seo_keyword FROM {$prefix}_modules WHERE active=1 AND alanguage='$currentlang' AND title='$module_name'");
if($db->sql_numrows($resultmodule) > 0) 
{
	list($mmid, $mcustom_title, $mseo_title, $mseo_description, $mseo_keyword) = $db->sql_fetchrow($result);
	if($mseo_title!="")
		$page_title = "$mseo_title";
	else
		$page_title .= "$mcustom_title";
	if($mseo_keyword!="")
	$keywords_site =$mseo_keyword;
	//description
	if($mseo_description!="") 
		$description_site =$mseo_description;
}
include_once("header.php");
if($news_home_type == 0) 
{
	$result_catindex = $db->sql_query("SELECT catid, title, homelinks FROM {$prefix}_news_cat WHERE active=1 AND onhome=1 AND alanguage='$currentlang' ORDER BY weight");
if($db->sql_numrows($result_catindex) > 0) 
{
	while(list($catid, $titlecat, $homelinks) = $db->sql_fetchrow($result_catindex)) 
	{
		$url_news_cat =url_sid("index.php?f=news&do=categories&id=$catid");
		$result_newsindex = $db->sql_query("SELECT id, title, hometext, images, time FROM {$prefix}_news WHERE active=1 AND catid=$catid ORDER BY time DESC LIMIT 1");
		if($db->sql_numrows($result_newsindex) > 0) 
		{
			list($idnewind, $titlenewind, $hometextind, $imagesind, $timenewind) = $db->sql_fetchrow($result_newsindex);
			$url_news_detail =url_sid("index.php?f=news&do=detail&id=$idnewind");
			$hometextind = strip_tags($hometextind,"<a><u><i><b><strong><em>");
			$get_path_newindex = get_path($timenewind);
			$path_upload_imgnewind = "$path_upload/news/$get_path_newindex";

			if($imagesind !="" && file_exists("$path_upload_imgnewind/$imagesind")) 
			{
				$news_pic_index = resizeImages("$path_upload_imgnewind/$imagesind", "$path_upload_imgnewind/120x90_$imagesind" ,120,90);				$news_pic_index = $urlsite."/".$news_pic_index;

			}
			else
			{
				$news_pic_index = "";
			}
			$othersnewindex="";
			$result_newsindex_others = $db->sql_query("SELECT id, title FROM {$prefix}_news WHERE active=1 AND id!=$idnewind AND catid=$catid ORDER BY time DESC LIMIT $homelinks");
			if($db->sql_numrows($result_newsindex_others) > 0) 
			{
				while(list($idotherindex, $titleotherindex) = $db->sql_fetchrow($result_newsindex_others)) 
				{
					$url_news_other =url_sid("index.php?f=news&do=detail&id=$idotherindex");
					$othersnewindex.= temp_news_other_index($idotherindex,$url_news_other,$titleotherindex);
				}
			} 
			else 
			{
				$othersnewindex ="";
			}
			temp_news_loop_cat_index($catid, $titlecat, $idnewind, $titlenewind, $hometextind, $news_pic_index, $othersnewindex, $url_news_detail, $url_news_cat);
		}
	}
} 
else 
{
	OpenTable();
	echo "<center>"._NODATA."</center>";
	CloseTable();
}

} 
else 
{
	OpenTab(_NEWS);
	$result_news_index = $db->sql_query("SELECT id, title, hometext, images, time FROM ".$prefix."_news WHERE active=1 AND alanguage='$currentlang' ORDER BY time DESC LIMIT $perpage");
	if($db->sql_numrows($result_news_index) > 0) 
	{
		while(list($id, $title, $hometext, $images, $time) = $db->sql_fetchrow($result_news_index)) 
		{
			$get_path_newindex = get_path($time);
			$path_upload_imgnewind = "$path_upload/news/$get_path_newindex";
			if($images !="" && file_exists("$path_upload_imgnewind/$images")) 
			{
				if(file_exists("$path_upload_imgnewind/thumb_".$images."")) 
				{
					$images = "thumb_".$images."";
				}
				$newspic = "<div style=\"float: ".$pic_align_cat."; margin-right: 5px; border: 1px solid #CCC; padding: 1px\"><a href=\"".url_sid("index.php?f=news&do=detail&id=$id")."\"><img border=\"0\" src=\"$path_upload_imgnewind/$images\"></a></div>";
			} 
			else 
			{
				$newspic = "";
			}
			temp_news_index($id, $title, $hometext, $newspic);
		}
	}
	CloseTab();
}
include_once("footer.php");
?>