<?php
if (!defined('CMS_SYSTEM')) die();
global $urlsite;
$where="";
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$t = isset($_GET['t']) ? $_GET['t'] : "";
$c = isset($_GET['c']) ? $_GET['c'] : "";
if($id!=0)
	$where.="n.id=$id AND ";
if($t!="")
	$where.="n.permalink='$t' AND ";
if($c!="")
	$where.="c.permalink='$c' AND ";
$result = $db->sql_query("SELECT n.id, n.title, n.permalink, n.guid, n.description, n.content, n.parentid, n.images, n.seo_title, n.seo_description, n.seo_keyword, n.seo_tag, n.time, n.status FROM ".$prefix."_class AS n WHERE $where n.id<>0");
//die("SELECT n.id, n.code, n.title, n.permalink, n.guid, n.description, n.content, n.parentid, n.images, n.seo_title, n.seo_description, n.seo_keyword, n.seo_tag, n.time, n.status FROM ".$prefix."_class AS n WHERE $where n.id<>0");
if($db->sql_numrows($result) != 1) {
	//header("Location: ".url_sid("index.php")."");	
}	

list($id, $title, $permalink, $guid, $description, $content, $parentid, $images, $seo_title, $seo_description, $seo_keyword, $seo_tag, $time, $status) = $db->sql_fetchrow($result);
$get_path = get_path($time);
$db->sql_query("UPDATE ".$prefix."_document SET hits=hits+1 WHERE id='$id'");
//title
if($seo_title!="")
	$page_title = "$seo_title";
else
	$page_title .= "$title";
//image
if($images!="")
	$siteimage = "$urlsite/$path_upload/class/$images";
else
	$siteimage .= "$urlsite/$path_upload/class/$images";
//keywords

if($seo_tag!="")
	$tag_seo_key =", ".$seo_tag;
else
	$tag_seo_key = $seo_tag;

if($seo_keyword!="")
	$keywords_site =$seo_keyword.$tag_seo_key;
else
	$header_page_keyword =$tag_seo_key;
//description
if($seo_description!="") 
	$description_site =$seo_description;
else
	if($content!=""){ $description_site =$content;}

//link title

if($parentid != 0) {
	$title_cat = page_tilecat($id, $parentid, $title);
	$title_home = "eưewew<a href=\"".url_sid("index.php")."\"  title=\""._HOMEPAGE."\">"._HOMEPAGE."</a> &rsaquo; ".$title."";
} else {
	//$catname2 = "<a href=\"".url_sid("index.php?f=".$module_name."&do=categories&id=$catid")."\" >$cattitle</a>";
	$title_home = "<a href=\"".url_sid("index.php")."\" title=\""._HOMEPAGE."\">"._HOMEPAGE."</a> &rsaquo; ".$title."";
}

include_once("header.php");
OpenTab($title_home);

/*$hometext = preg_replace("/<.*?>/", "", $hometext);*/
$content = preg_replace('/src="\/files/', "src=\"".$urlsite."/files", $content);
$content = preg_replace('/alt=""/', "alt=\"anh minh hoa\"", $content);

$path_upload_img = "$path_upload/class";
$siteurl = "http://".$_SERVER['HTTP_HOST']."";
if($folder_site)  $url = !empty($fattach) ? "$siteurl/$folder_site/$path_upload_attach/$fattach" : '';
else $url = !empty($fattach) ? "$siteurl/$path_upload_attach/$fattach" : '';

if(file_exists("$path_upload_img/$images") && $images !="" && $imgshow == 1) {
	$document_img = resize_image($cattitle,$images,$path_upload_img,$path_upload_img,148,203);
} else {
	$document_img = resize_image($cattitle,'no_image.gif','images',$path_upload_img,148,203);
}
$new_others = "";
$margin="";
$result_others = $db->sql_query("SELECT n.id, n.title, n.images, n.time FROM ".$prefix."_class AS n WHERE n.status=1 AND n.id<'$id' ORDER BY n.time DESC LIMIT 6");
if($db->sql_numrows($result_others) > 0) {
	while(list($idot, $titleot, $imagesot, $timeot) = $db->sql_fetchrow($result_others)) {
		$rwtitlelast = utf8_to_ascii(url_optimization($titleot));
		$url_news_detail =url_sid("index.php?f=class&do=detail&id=$idot");
		$path_upload_img = "$path_upload/class";
		//if(file_exists("$path_upload_img/$imagesot") && $imagesot !="") {
		//	$imagesot = resize_image($titleot,$imagesot,$path_upload_img,$path_upload_img,60,80);
		//}
		//else
		//{
		//	$imagesot = resize_image($titleot,'no_image.gif','images',$path_upload_img,60,80);
		//}
		$new_others .= "<div class=\"document-other-item\" style=\"$margin\"><div class=\"document-img fl\"><a href=\"$url_news_detail\">$imagesot</a></div>";
			$new_others .= "<div  class=\"document-title fl\"><a href=\"$url_news_detail\">".CutString($titleot,130)."</a>
			</div></div>";
	}
	$new_others .= "<div class=\"cl\"></div>";
}
$new_others2 = "";
$result_others2 = $db->sql_query("SELECT id, title, time FROM ".$prefix."_class WHERE id>'$id' ORDER BY time ASC LIMIT 6");
if($db->sql_numrows($result_others2) > 0) {
	while(list($idot2, $titleot2, $timeot2) = $db->sql_fetchrow($result_others2)) {
		$new_others2 .= "&raquo; <a href=\"".url_sid("index.php?f=$module_name&do=detail&id=$idot2")."\" class=\"hometext\">$titleot2</a> <span class=\"documentothers\"></span><br/>";//(".ext_time($timeot2,1).")
	}
} 

$document_tid ="";
if(!empty($tid)) {
	$result_tid = $db->sql_query("SELECT id, title FROM ".$prefix."_class WHERE tid='$tid' AND id!='$id' ORDER BY time DESC LIMIT $nums_tid");
	if($db->sql_numrows($result_tid) > 0) {
		$document_tid .= "<div style=\"border: 1px solid #CCCCCC; padding: 5px; background-color: #F0F0F0\">";
		$document_tid .= "<font color=\"red\"><b>"._DOCUMENTTID.":</b></font><br/>";
		while(list($idtid, $titletid) = $db->sql_fetchrow($result_tid)) {
			$rwtitletid=utf8_to_ascii(url_optimization($titletid));
			$document_tid .= "&raquo; <a href=\"../".url_sid($module_name.".php?do=detail&id=$idtid&t=$rwtitletid")."\" class=\"tittahom\">$titletid</a></br>";
		}	
		if($db->sql_numrows($db->sql_query("SELECT*FROM ".$prefix."_class WHERE tid='$tid' AND id!='$id'")) > $nums_tid) {
			$document_tid .= "<div align=\"right\"><a href=\"../".url_sid("".$module_name.".php?do=topics&topic_id=$tid")."\"><b>["._VIEWALLDOCUMENT."]</b></a></div>";
		}	
		$document_tid .= "</div>";
	}
}
$tags="";
if(!empty($seo_tag)) 
{
	$tag_seo_arr = @explode(",", $seo_tag);
	for ($i = 0; $i < sizeof($tag_seo_arr); $i++) 
	{
		$tagurl=trim($tag_seo_arr[$i]);
		$tagurl=str_ireplace(" ","-",$tagurl);
		$tags .= "<a class=\"tag_item\" href=\"".url_sid("index.php?f=class&do=tags&tag=$tagurl")."\">".$tag_seo_arr[$i]."</a>\n";
	}
}

$comment_content = "";
temp_document_detail($id, $code, $title, $time, $description, $content, $fattach, $othershow, $document_img, $imgtext, $new_others, $new_others2, $source, $document_tid, $seo_title, $seo_description, $seo_keyword, $tags, $hits, $comment, $comment_content, $fattach_intro, $hits_download, $folder, $fullname, $fattach, $link_extend, $price);

CloseTab();
include_once("footer.php");
?>