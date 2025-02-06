<?php

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
$result = $db->sql_query("SELECT n.id, n.catid, c.title, c.parent, n.title, n.othershow, n.time, n.hometext, n.bodytext, n.seo_title, n.seo_description, n.seo_keyword, n.seo_tag, n.fattach, n.news_type, n.images, n.imgtext, n.source, n.imgshow, n.hits FROM ".$prefix."_news AS n,".$prefix."_news_cat AS c WHERE $where n.catid=c.catid AND n.alanguage='$currentlang'");

if($db->sql_numrows($result) != 1) {
	//header("Location: ".url_sid("index.php")."");	
	die('ssssssssssss');
}	

list($id, $catid, $catname, $parent, $title, $othershow, $time, $hometext, $bodytext, $title_seo, $description_seo, $keyword_seo, $tag_seo, $fattach, $news_type, $images, $imgtext, $source, $imgshow,$hits ) = $db->sql_fetchrow($result);
$db->sql_query("UPDATE ".$prefix."_news SET hits=hits+1 WHERE id='$id'");
$get_path = get_path($time);
//title
/*
if($title_seo!="")
	$page_title = "$title_seo";
else
	$page_title .= "$title - $catname";
//image
if($images!="")
	$siteimage = "$urlsite/$path_upload/news/$get_path/$images";
else
	$siteimage .= "$urlsite/$path_upload/news/$get_path/$images";
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
*/
$hometext = preg_replace("/<.*?>/", "", $hometext);
$bodytext = preg_replace('/src="\/files/', "src=\"".$urlsite."/files", $bodytext);
$bodytext = preg_replace('/alt=""/', "alt=\"anh minh hoa\"", $bodytext);
$path_upload_img = "$path_upload/news/$get_path";
$path_upload_img2 = "$path_upload/news";
$path_upload_attach = "$path_upload/news/attachs";
$siteurl = "http://".$_SERVER['HTTP_HOST']."";
if($folder_site)  $url = !empty($fattach) ? "$siteurl/$folder_site/$path_upload_attach/$fattach" : '';
else $url = !empty($fattach) ? "$siteurl/$path_upload_attach/$fattach" : '';

if(file_exists("$path_upload_img/$images") && $images !="" && $imgshow == 1) {
	$news_img = resize_image($title, $images, $path_upload_img, $path_upload_img2, 490,320);
} else {
	$news_img ="";
}
/*
$new_others = "";
$result_others = $db->sql_query("SELECT id, title, time FROM ".$prefix."_news WHERE id<'$id' AND catid='$catid' ORDER BY time DESC LIMIT $news_ccd");
if($db->sql_numrows($result_others) > 0) {
	while(list($idot, $titleot, $timeot) = $db->sql_fetchrow($result_others)) {
		$new_others .= "&bull; <a href=\"".url_sid("index.php?f=$module_name&do=detail&id=$idot")."\" class=\"hometext\">$titleot</a><br/>";// <span class=\"newsothers\">(".ext_time($timeot,1).")</span>
	}
}		
$new_others2 = "";
$result_others2 = $db->sql_query("SELECT id, title, time FROM ".$prefix."_news WHERE id>'$id' AND catid='$catid' ORDER BY time ASC LIMIT $news_ccd");
if($db->sql_numrows($result_others2) > 0) {
	while(list($idot2, $titleot2, $timeot2) = $db->sql_fetchrow($result_others2)) {
		$new_others2 .= "&raquo; <a href=\"".url_sid("index.php?f=$module_name&do=detail&id=$idot2")."\" class=\"hometext\">$titleot2</a> <span class=\"newsothers\"></span><br/>";//(".ext_time($timeot2,1).")
	}
} 

$news_tid ="";
if(!empty($tid)) {
	$result_tid = $db->sql_query("SELECT id, title FROM ".$prefix."_news WHERE tid='$tid' AND id!='$id' ORDER BY time DESC LIMIT $nums_tid");
	if($db->sql_numrows($result_tid) > 0) {
		$news_tid .= "<div style=\"border: 1px solid #CCCCCC; padding: 5px; background-color: #F0F0F0\">";
		$news_tid .= "<font color=\"red\"><b>"._NEWSTID.":</b></font><br/>";
		while(list($idtid, $titletid) = $db->sql_fetchrow($result_tid)) {
			$rwtitletid=utf8_to_ascii(url_optimization($titletid));
			$news_tid .= "&raquo; <a href=\"../".url_sid($module_name.".php?do=detail&id=$idtid&t=$rwtitletid")."\" class=\"tittahom\">$titletid</a></br>";
		}	
		if($db->sql_numrows($db->sql_query("SELECT*FROM ".$prefix."_news WHERE tid='$tid' AND id!='$id'")) > $nums_tid) {
			$news_tid .= "<div align=\"right\"><a href=\"../".url_sid("".$module_name.".php?do=topics&topic_id=$tid")."\"><b>["._VIEWALLNEWS."]</b></a></div>";
		}	
		$news_tid .= "</div>";
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
		$tags .= "<a class=\"tag_item\" href=\"".url_sid("index.php?f=news&do=tags&tag=$tagurl")."\">".$tag_seo_arr[$i]."</a>\n";
	}
}

$comment_content = "";
$result_comments = $db->sql_query("SELECT id, content, name, email, time FROM ".$prefix."_comments WHERE newsid='$id' AND status=1 AND alanguage='$currentlang' ORDER BY time ASC");
if($db->sql_numrows($result_comments) > 0) 
{
	$comment_content .= "<div style=\" max-height: 400px; padding: 0px 8px; overflow-y: auto; overflow-x: hidden;\"><img border=\"0\" title=\"\" src=\"templates/Adoosite/images/ykienbandoc.jpg\">";
	while(list($id_comment, $content_comment, $name_comment, $email_comment, $time_comment) = $db->sql_fetchrow($result_comments)) 
	{
		$comment_content .= "<div style=\" border-bottom:1px dotted #F2F2F2;padding:4px\"><div class=\"comment-content\">$content_comment</div><div class=\"comment-name\"><b>$name_comment</b> - <i>".ext_time($time_comment, 2)."</i></div></div>";
	}
	$comment_content .= "</div>";
} */
$comment="$urlsite/index.php?f=$module_name&do=comments&id=$id";
//temp_newdetail($id, $title, $time, $hometext, $bodytext, $fattach, $othershow, $news_img, $imgtext, $new_others, $new_others2, $source, $news_tid, $title_seo, $description_seo, $keyword_seo, $tags, $hits, $comment, $comment_content);
?>

<div class="main">
	<div class="main-content container clearfix">
		<div class="content-col fr">	
						<div class="news-padding clearfix">
								<h1 class="the-title"><?php echo $title?></h1>

				<time><?php echo date("d/m/Y",$time);?></time>

				<div class="fb-like" data-href="<?php echo url_sid("index.php?f=news&do=detail&id=$id")?>" data-layout="button_count" data-action="like" data-show-faces="true" data-share="true"></div>
				
				<div class="the-content clearfix">
					<p><strong><?php echo $hometext?></strong></p>
					<p><?php echo $bodytext?></p>
				</div>

				<div class="vehicle-share">
			        <div class="social-network">	            
						<a href="<?php echo url_sid("index.php?f=news&do=detail&id=$id")?>" class="facebook share-popup">Facebook</a> 
						<a href="https://plus.google.com/share?url=<?php echo url_sid("index.php?f=news&do=detail&id=$id")?>" class="google share-popup">Google+</a> 
						<a href="http://twitter.com/share?text=<?php echo $title?>" class="twitter share-popup">Twitter</a> 
						<a href="http://youtube.com/toyotavietnammotor" target="blank" class="youtube">YouTube</a>
			        </div>
			   	 	<a id="vehicle_share_button" class="button-toggle icon" href="javascript:void(0)"></a>
				</div>

							</div>
					</div>

		<?php include('sidebar.php');?>
	</div>
</div>