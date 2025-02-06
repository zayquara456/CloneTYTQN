<?php
if (!defined('CMS_SYSTEM')) die();

$tag = $_GET['tag'];
$tag=str_ireplace("-"," ",$tag);
$result = $db->sql_query("SELECT title FROM ".$prefix."_document where seo_tag LIKE '%$tag%'");
//if($db->sql_numrows($result) != 1) header("Location: index.php");

list($catname) = $db->sql_fetchrow($result);

$page_title .= "$tag";
//title
if($tag!="")
	$page_title = "$tag bài liên quan đến $tag, ".utf8_to_ascii($tag)."";

//keywords
if($tag!="")
	$keywords_site ="$tag, ".utf8_to_ascii($tag).", $tag ,".utf8_to_ascii($tag).",".utf8_to_ascii($tag)."";
else
	$header_page_keyword = $tag.",".utf8_to_ascii($tag)."";
//description
$description_site =" $tag, ".utf8_to_ascii($tag)." ".$description_site;

$rwcatname= utf8_to_ascii(url_optimization($catname));
include_once("header.php");
OpenTab("<a href=\"".url_sid("index.php")."\">"._HOMEPAGE."</a> &rsaquo; <a href=\"".url_sid("index.php?f=document&do=tags&tag=".$_GET['tag']."")."\">$tag</a>");

$page = isset($_GET['page']) ? intval($_GET['page']) : (isset($_POST['page']) ? intval($_POST['page']):1);
$offset = ($page-1) * $perpage;

$query = "SELECT COUNT(*) FROM {$prefix}_document WHERE alanguage='$currentlang' AND (";
$query .= "seo_tag LIKE '%$tag%' OR ";
$query = substr($query, 0, strlen($query) - 4);
$query .= ')';
$result = $db->sql_query($query);
list($total) = $db->sql_fetchrow($result);

$query = "SELECT n.id, n.title, n.images, n.hometext, n.time, n.price, u.fullname, u.folder FROM ".$prefix."_document AS n,".$prefix."_user AS u WHERE n.alanguage='$currentlang' AND n.active=1  AND n.user_id=u.id AND (";
$query .= "n.seo_tag LIKE '%$tag%' OR ";
$query = substr($query, 0, strlen($query) - 4);
$query .= ") ORDER BY n.time DESC LIMIT $offset, $perpage";
$resultn = $db->sql_query($query);
if($db->sql_numrows($resultn) > 0) {
	//OpenContent($tag);
	echo"<div class=\"document-boxca\"><div class=\"document-boxca-group\">\n";
	while(list($id, $title, $images, $hometext, $time, $price, $fullname, $folder) = $db->sql_fetchrow($resultn)) {
		$url_news_detail =url_sid("index.php?f=document&do=detail&id=$id");
		$hometext = strip_tags($hometext, '<a><b><u><i>');
		$get_path = get_path($time);
		$path_upload_img = "$path_upload/document/$folder";
		$path_upload_img2 = "$path_upload/document";
		if(file_exists("$path_upload_img/$images") && $images !="") {
			$news_img = resize_image($title,$images,$path_upload_img,$path_upload_img,122,177);
		} else {
			$news_img = resize_image($title,'no_image.gif','images',$path_upload_img2,122,177);
		}
		temp_document_index($id, $title, $hometext, $news_img, $url_news_detail);
	}
	if($total > $perpage) {
		$pageurl = "index.php?f=$module_name&do=tags&tag=$tag";
		echo paging($total,$pageurl,$perpage,$page);
	}
	echo "</div>";
	echo "</div>";
	//CloseContent();
}

CloseTab();

include_once("footer.php");

?>