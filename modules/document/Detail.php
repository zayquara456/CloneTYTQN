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

$result = $db->sql_query("SELECT c.title, c.parent, n.id , n.code, n.catid, n.title, n.permalink, n.guid, n.alanguage, n.time, n.hometext, n.bodytext, n.seo_title, n.seo_description, n.seo_keyword, n.seo_tag, n.fattach, n.link_extend , n.fattach_intro, n.othershow, n.images, n.image_slide, n.imgtext, n.source, n.active, n.imgshow, n.image_highlight, n.hits, n.hits_download, n.nstart, n.news_type, n.special, n.user_id, u.folder, u.fullname FROM ".$prefix."_document AS n,".$prefix."_document_cat AS c,".$prefix."_user AS u  WHERE $where n.catid=c.catid AND n.user_id=u.id AND n.alanguage='$currentlang'");

if($db->sql_numrows($result) != 1) {
	header("Location: ".url_sid("index.php")."");	
}	

list($cattitle, $parent, $id, $code, $catid, $title, $permalink, $guid, $alanguage, $time, $hometext, $bodytext, $seo_title, $seo_description, $seo_keyword, $seo_tag, $fattach, $link_extend, $fattach_intro, $othershow, $images, $image_slide, $imgtext, $source, $active, $imgshow, $image_highlight, $hits, $hits_download, $nstart, $news_type, $special, $user_id, $folder, $fullname) = $db->sql_fetchrow($result);
$get_path = get_path($time);
$db->sql_query("UPDATE ".$prefix."_document SET hits=hits+1 WHERE id='$id'");
//title
if($seo_title!="")
	$page_title = "$seo_title";
else
	$page_title .= "$title - $cattitle";
//image
if($images!="")
	$siteimage = "$urlsite/$path_upload/document/$folder/$images";
else
	$siteimage .= "$urlsite/$path_upload/document/$folder/$images";
//keywords

if($seo_tag!="")
	$tag_seo_key =", ".$seo_tag;
else
	$tag_seo_key = $seo_tag;

if($seo_keyword!="")
	$keywords_site =$seo_keyword.$tag_seo_key;
else
	$header_page_keyword = $hometext.$tag_seo_key;
//description
if($seo_description!="") 
	$description_site =$seo_description;
else
	if($hometext!=""){ $description_site =$hometext;}

//link title

if($parent != 0) {
	$title_cat = page_tilecat($catid, $parent, $cattitle);
	$title_home = "<a href=\"".url_sid("index.php")."\"  title=\""._HOMEPAGE."\">"._HOMEPAGE."</a> &rsaquo; ".$title_cat."";
} else {
	$catname2 = "<a href=\"".url_sid("index.php?f=".$module_name."&do=categories&id=$catid")."\" >$cattitle</a>";
	$title_home = "<a href=\"".url_sid("index.php")."\" title=\""._HOMEPAGE."\">"._HOMEPAGE."</a> &rsaquo; ".$catname2."";
}

include_once("header.php");
OpenTab($title_home);

$hometext = preg_replace("/<.*?>/", "", $hometext);
$bodytext = preg_replace('/src="\/files/', "src=\"".$urlsite."/files", $bodytext);
$bodytext = preg_replace('/alt=""/', "alt=\"anh minh hoa\"", $bodytext);

$path_upload_img = "$path_upload/document/$get_path";

$path_upload_attach = "$path_upload/document/attachs";
$siteurl = "http://".$_SERVER['HTTP_HOST']."";
if($folder_site)  $url = !empty($fattach) ? "$siteurl/$folder_site/$path_upload_attach/$fattach" : '';
else $url = !empty($fattach) ? "$siteurl/$path_upload_attach/$fattach" : '';

if(file_exists("$path_upload_img/$images") && $images !="" && $imgshow == 1) {
	$document_img = "<img alt=\"$title\" src=\"$urlsite/".resizeImages("$path_upload_img/$images", "$path_upload_img/80x60_$images" ,80,60)."\">";
} else {
	$document_img ="";
}
$new_others = "";
$margin="";
$result_others = $db->sql_query("SELECT n.id, n.title, n.images, n.time, n.hits, n.price, u.fullname, u.folder FROM ".$prefix."_document AS n,".$prefix."_user AS u WHERE n.alanguage='$currentlang' AND n.active=1  AND n.user_id=u.id AND n.id<'$id' AND n.catid='$catid' ORDER BY n.time DESC LIMIT $document_ccd");
if($db->sql_numrows($result_others) > 0) {
	while(list($idot, $titleot, $imagesot, $timeot, $hitsot, $priceot, $fullnameot, $folderot) = $db->sql_fetchrow($result_others)) {
		//$new_others .= "&bull; <a href=\"".url_sid("index.php?f=$module_name&do=detail&id=$idot")."\" class=\"hometext\">$titleot</a><br/>";// <span class=\"documentothers\">(".ext_time($timeot,1).")</span>
		$rwtitlelast = utf8_to_ascii(url_optimization($titleot));
		$url_news_detail =url_sid("index.php?f=document&do=detail&id=$idot");
		$path_upload_img = "$path_upload/document/$folderot";
		//if($a%2==0){$margin="margin: 0px 0px 0px 0px";}
		//else{$margin="margin: 0px 8px 0px 0px";}
		if(file_exists("$path_upload_img/$imagesot") && $imagesot !="") {
			$imagesot = resize_image($titleot,$imagesot,$path_upload_img,$path_upload_img,60,80);
		}
		else
		{
			
		}
		$new_others .= "<div class=\"document-other-item\" style=\"$margin\"><div class=\"document-img fl\"><a href=\"$url_news_detail\">$imagesot</a></div>";
			$new_others .= "<div  class=\"document-title fl\"><a href=\"$url_news_detail\">".CutString($titleot,130)."</a>
			</div></div>";
	}
	$new_others .= "<div class=\"cl\"></div>";
}		
$new_others2 = "";
$result_others2 = $db->sql_query("SELECT id, title, time FROM ".$prefix."_document WHERE id>'$id' AND catid='$catid' ORDER BY time ASC LIMIT $document_ccd");
if($db->sql_numrows($result_others2) > 0) {
	while(list($idot2, $titleot2, $timeot2) = $db->sql_fetchrow($result_others2)) {
		$new_others2 .= "&raquo; <a href=\"".url_sid("index.php?f=$module_name&do=detail&id=$idot2")."\" class=\"hometext\">$titleot2</a> <span class=\"documentothers\"></span><br/>";//(".ext_time($timeot2,1).")
	}
} 

$document_tid ="";
if(!empty($tid)) {
	$result_tid = $db->sql_query("SELECT id, title FROM ".$prefix."_document WHERE tid='$tid' AND id!='$id' ORDER BY time DESC LIMIT $nums_tid");
	if($db->sql_numrows($result_tid) > 0) {
		$document_tid .= "<div style=\"border: 1px solid #CCCCCC; padding: 5px; background-color: #F0F0F0\">";
		$document_tid .= "<font color=\"red\"><b>"._DOCUMENTTID.":</b></font><br/>";
		while(list($idtid, $titletid) = $db->sql_fetchrow($result_tid)) {
			$rwtitletid=utf8_to_ascii(url_optimization($titletid));
			$document_tid .= "&raquo; <a href=\"../".url_sid($module_name.".php?do=detail&id=$idtid&t=$rwtitletid")."\" class=\"tittahom\">$titletid</a></br>";
		}	
		if($db->sql_numrows($db->sql_query("SELECT*FROM ".$prefix."_document WHERE tid='$tid' AND id!='$id'")) > $nums_tid) {
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
		$tags .= "<a class=\"tag_item\" href=\"".url_sid("index.php?f=document&do=tags&tag=$tagurl")."\">".$tag_seo_arr[$i]."</a>\n";
	}
}

$comment_content = "";
$result_comments = $db->sql_query("SELECT id, content, name, email, time FROM ".$prefix."_document_comments WHERE documentid='$id' AND status=1 AND alanguage='$currentlang' ORDER BY time ASC");
if($db->sql_numrows($result_comments) > 0) 
{
	$comment_content .= "<div style=\" max-height: 400px; padding: 0px 8px; overflow-y: auto; overflow-x: hidden;\"><img border=\"0\" title=\"\" src=\"templates/Adoosite/images/ykienbandoc.jpg\">";
	while(list($id_comment, $content_comment, $name_comment, $email_comment, $time_comment) = $db->sql_fetchrow($result_comments)) 
	{
		$comment_content .= "<div style=\" border-bottom:1px dotted #F2F2F2;padding:4px\"><div class=\"comment-content\">$content_comment</div><div class=\"comment-name\"><b>$name_comment</b> - <i>".ext_time($time_comment, 2)."</i></div></div>";
	}
	$comment_content .= "</div>";
} 
$comment="$urlsite/index.php?f=$module_name&do=comments&id=$id";
temp_document_detail($id, $code, $title, $time, $hometext, $bodytext, $fattach, $othershow, $document_img, $imgtext, $new_others, $new_others2, $source, $document_tid, $seo_title, $seo_description, $seo_keyword, $tags, $hits, $comment, $comment_content, $fattach_intro, $hits_download, $folder, $fullname, $link_extend);

CloseTab();
include_once("footer.php");
?>