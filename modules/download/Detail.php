<?php
if (!defined('CMS_SYSTEM')) die();

 global $sitelinkmap;
$where="";
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$t = isset($_GET['t']) ? $_GET['t'] : "";

if($id!=0)
	$where.="n.id=$id AND ";
if($t!="")
	$where.="n.permalink='$t' AND ";
$result = $db->sql_query("SELECT n.id, n.catid, c.title, c.parent, n.title, n.othershow, n.time, n.hometext, n.bodytext, n.seo_title, n.seo_description, n.seo_keyword, n.seo_tag, n.fattach, n.download_type, n.images, n.imgtext, n.source, n.imgshow, n.hits FROM ".$prefix."_download AS n,".$prefix."_download_cat AS c WHERE $where n.catid=c.catid AND n.alanguage='$currentlang'");

if($db->sql_numrows($result) != 1) {
	//header("Location: ".url_sid("index.php")."");	
}	

list($id, $catid, $catname, $parent, $title, $othershow, $time, $hometext, $bodytext, $title_seo, $description_seo, $keyword_seo, $tag_seo, $fattach, $download_type, $images, $imgtext, $source, $imgshow,$hits ) = $db->sql_fetchrow($result);
$db->sql_query("UPDATE ".$prefix."_download SET hits=hits+1 WHERE id='$id'");
//title
if($title_seo!="")
	$page_title = "$title_seo";
else
	$page_title .= "$title - $catname";
//keywords

if($tag_seo!="")
	$tag_seo_key =", ".$tag_seo;
else
	$tag_seo_key = $tag_seo;

if($keyword_seo!="")
	$keywords_site =$keyword_seo.$tag_seo_key;
else
	$header_page_keyword = $hometext.$tag_seo_key;
//description
if($description_seo!="") 
	$description_site =$description_seo;
else
	if($hometext!=""){ $description_site =$hometext;}

//link title

if($parent != 0) {
	$title_cat = page_tilecat($catid, $parent, $catname);
	$title_home = "<a href=\"".url_sid("index.php")."\" \" title=\""._HOMEPAGE."\">"._HOMEPAGE."</a> &rsaquo; ".$title_cat."";
} else {
	$catname2 = "<a href=\"".url_sid("index.php?f=".$module_name."&do=categories&id=$catid")."\" >$catname</a>";
	$title_home = "<a href=\"".url_sid("index.php")."\" \" title=\""._HOMEPAGE."\">"._HOMEPAGE."</a> &rsaquo; ".$catname2."";
}
$sitelinkmap=$title_home;

include_once("header.php");
OpenTab("");

$hometext = strip_tags($hometext, '<a><b><u><i><strong><em>');
$path_upload_img = "$path_upload/download";

$path_upload_attach = "$path_upload/download/attachs";
$siteurl = "http://".$_SERVER['HTTP_HOST']."";
if($folder_site)  $url = !empty($fattach) ? "$siteurl/$folder_site/$path_upload_attach/$fattach" : '';
else $url = !empty($fattach) ? "$siteurl/$path_upload_attach/$fattach" : '';

if(file_exists("$path_upload_img/$images") && $images !="" && $imgshow == 1) {
	$dichvu_img = $urlsite."/".resizeImages("$path_upload_img/$images", "$path_upload_img/80x60_$images" ,80,60);
} else {
	$dichvu_img ="";
}
$new_others = "";
$result_others = $db->sql_query("SELECT id, title, time FROM ".$prefix."_download WHERE id<'$id' AND catid='$catid' ORDER BY time DESC LIMIT 10");
if($db->sql_numrows($result_others) > 0) {
	while(list($idot, $titleot, $timeot) = $db->sql_fetchrow($result_others)) {
		$new_others .= "&bull; <a href=\"".url_sid("index.php?f=$module_name&do=detail&id=$idot")."\" class=\"hometext\">$titleot</a><br/>";// <span class=\"dichvuothers\">(".ext_time($timeot,1).")</span>
	}
}		
$new_others2 = "";
$result_others2 = $db->sql_query("SELECT id, title, time FROM ".$prefix."_download WHERE id>'$id' AND catid='$catid' ORDER BY time ASC LIMIT 10");
if($db->sql_numrows($result_others2) > 0) {
	while(list($idot2, $titleot2, $timeot2) = $db->sql_fetchrow($result_others2)) {
		$new_others2 .= "&bull; <a href=\"".url_sid("index.php?f=$module_name&do=detail&id=$idot2")."\" class=\"hometext\">$titleot2</a> <span class=\"dichvuothers\"></span><br/>";//(".ext_time($timeot2,1).")
	}
} 

$dichvu_tid ="";
if(!empty($tid)) {
	$result_tid = $db->sql_query("SELECT id, title FROM ".$prefix."_download WHERE tid='$tid' AND id!='$id' ORDER BY time DESC LIMIT $nums_tid");
	if($db->sql_numrows($result_tid) > 0) {
		$dichvu_tid .= "<div style=\"border: 1px solid #CCCCCC; padding: 5px; background-color: #F0F0F0\">";
		$dichvu_tid .= "<font color=\"red\"><b>"._NEWSTID.":</b></font><br/>";
		while(list($idtid, $titletid) = $db->sql_fetchrow($result_tid)) {
			$rwtitletid=utf8_to_ascii(url_optimization($titletid));
			$dichvu_tid .= "&raquo; <a href=\"../".url_sid($module_name.".php?do=detail&id=$idtid&t=$rwtitletid")."\" class=\"tittahom\">$titletid</a></br>";
		}	
		if($db->sql_numrows($db->sql_query("SELECT*FROM ".$prefix."_download WHERE tid='$tid' AND id!='$id'")) > $nums_tid) {
			$dichvu_tid .= "<div align=\"right\"><a href=\"../".url_sid("".$module_name.".php?do=topics&topic_id=$tid")."\"><b>["._VIEWALLNEWS."]</b></a></div>";
		}	
		$dichvu_tid .= "</div>";
	}		
}	
$tags="";	
if(!empty($tag_seo)) 
{
	$tag_seo_arr = @explode(",", $tag_seo);
	for ($i = 0; $i < sizeof($tag_seo_arr); $i++) 
	{
		$tagurl=trim($tag_seo_arr[$i]);
		$tagurl=str_ireplace(" ","-",$tagurl);
		$tags .= "<a href=\"".url_sid("index.php?f=dichvu&do=tags&tag=$tagurl")."\">".$tag_seo_arr[$i]."</a>,\n";
	}
}

temp_newdetail($id, $title, $time, $hometext, $bodytext, $fattach, $othershow, $dichvu_img, $imgtext, $new_others, $new_others2, $source, $dichvu_tid, $title_seo, $description_seo, $keyword_seo, $tags, $hits);

CloseTab();
include_once("footer.php");
?>