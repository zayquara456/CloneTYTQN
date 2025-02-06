<?php
if (!defined('CMS_SYSTEM')) die();
global $db, $currentlang, $prefix, $home, $urlsite, $userInfo, $path_upload;
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
?>
<script type="text/javascript" src="<?php echo $urlsite?>/js/switchcontent.js" ></script>
<style type="text/css">
.handcursor{
cursor:hand;
cursor:pointer;
}

</style>
<?php
echo "<div class=\"module-video-content fl\"><div class=\"module-video-title\"><div class=\"video-breakcoup fl\">"._MODTITLE."</div><div class=\"cl\"></div></div>";
	echo "<div class=\"video-hot\">";
	$result = $db->sql_query("SELECT id, title, images, hometext, bodytext, links, link_extend, hits FROM ".$prefix."_video WHERE active=1 AND special=1 ORDER BY id DESC LIMIT 1");
	list($id, $title,$images, $hometext, $bodytext, $links_video, $link_extend, $hits) = $db->sql_fetchrow($result);
	$top1id= $id;
	echo '<div class=""><iframe width="628" height="364" src="//www.youtube.com/embed/'.$links_video.'?rel=0" frameborder="0" allowfullscreen></iframe></div>';
	echo '<div class="video-hot-tool"><div class="fl"><div class="fb-like" data-href="'.$urlsite.'/'.$_SERVER['REQUEST_URI'].'" data-layout="button" data-action="like" data-show-faces="true" data-share="true"></div></div><div class="fr"><a class="btn_download" target="_blank" href='.$link_extend.'>Tải bản video đầy đủ</a></div><div class="video-hot-view fr"><span class="icon-eye-open"></span> '.$hits.'</div></div>';
	echo '<div class="video-hot-title">'.$title.'</div>';
	echo '<div class="video-hot-detail">'.$hometext.'</div>';
	?>
<div class="video-hot-content">
<div id="bobcontent1" class="switchgroup1"><?php echo $bodytext;?>
</div>
<h3 id="bobcontent1-title" class="handcursor"></h3>


<script type="text/javascript">
// MAIN FUNCTION: new switchcontent("class name", "[optional_element_type_to_scan_for]") REQUIRED
// Call Instance.init() at the very end. REQUIRED

var bobexample=new switchcontent("switchgroup1", "div") //Limit scanning of switch contents to just "div" elements
bobexample.setStatus('Đóng nội dung', 'Xem Nội dung')
bobexample.setColor('darkred', 'black')
bobexample.setPersist(true)
bobexample.collapsePrevious(true) //Only one content open at any given time
bobexample.init()
</script>
</div>
<?
	echo '<div class="cl"></div></div>';

	
//video noi bat
echo '<div class="module-video-special"><div class="title">Video nổi bật</div>';
	$result = $db->sql_query("SELECT id,title,images,links FROM ".$prefix."_video WHERE active=1 AND id<>$top1id AND special=1 ORDER BY id DESC LIMIT 4");
	while(list($id, $title,$images, $links_video) = $db->sql_fetchrow($result)){
		$path_upload_imgnewind = "$path_upload/video";
		if($images !="" && file_exists("$path_upload_imgnewind/$images")) 
		{
			$images= resize_image($title, $images, $path_upload_imgnewind, $path_upload_imgnewind, 146,86);
		}
		else
		{
			$images= resize_image($title, 'no_image.gif', 'images', $path_upload_imgnewind, 146,86);
		}
		echo "<div class=\"video-special-pic\">";	
			echo "<div class=\"img\">";
			echo "<a id=\"various\"  href=\"".url_sid("index.php?f=video&do=detail&id=$id")."\" title=\"$title\" >$images</a>";
			echo "</div>";	
			echo "<div class=\"video-special-title\">";
				echo "<a href=\"".url_sid("index.php?f=video&do=detail&id=$id")."\">".CutString($title,65)."</a>";
			echo "</div>";
			
		echo '<div class="cl"></div></div>';
	}
echo '<div class="cl"></div></div>';

/////////////////////////////////////////////////////////////
$result_catindex = $db->sql_query("SELECT catid, title, guid, homelinks FROM {$prefix}_video_cat WHERE active=1  AND onhome=1 AND alanguage='$currentlang' ORDER BY weight");
if($db->sql_numrows($result_catindex) > 0) {
	$i=2;
	while(list($catid, $titlecat, $catguid, $homelinks) = $db->sql_fetchrow($result_catindex)){
?>
<div class="module-video-special">
	<div class="of fl">
		<div class="title">
			<div><a  class="folder-link" href="<?php echo url_sid($catguid)?>"><?php echo $titlecat?></a></div>
			
			<div class="cl"></div>
		</div>	
		<?php
$result_lastnew = $db->sql_query("SELECT id, title, guid, images, time FROM ".$prefix."_video WHERE alanguage='$currentlang' AND active=1 AND catid=$catid ".query_muticat("catid","parent",$catid,"".$prefix."_video_cat")." ORDER BY id DESC LIMIT 4");

$numrows = $db->sql_numrows($result_lastnew);
if($numrows > 0) {
	$a=1;
	?>
	<div class="">
	<?php
			while(list($idlast, $titlelast,$guidlast, $imageslast, $time, $price, $fullname, $folder) = $db->sql_fetchrow($result_lastnew)) {
			$hometext = preg_replace("/<.*?>/", "", $hometext);
		
			$path_upload_imgnewind = "$path_upload/video";
			if($imageslast !="" && file_exists("$path_upload_imgnewind/$imageslast")) 
			{
				$imageslast= resize_image($titlelast, $imageslast, $path_upload_imgnewind, $path_upload_imgnewind, 146,86);
			}
			else
			{
				$imageslast= resize_image($titlelast, 'no_image.gif', 'images', $path_upload_imgnewind, 146,86);
			}
			?>
			
			<div class="video-special-pic"><div class="img"><a href="<?php echo url_sid($guidlast)?>" class="document-link"><?php echo $imageslast?></a></div>
				<div  class="video-special-title"><a href="<?php echo url_sid($guidlast)?>"><?php echo CutString($titlelast,45) ;?></a></div>
			</div>
		<?php
		}
	?>
	</div>
	<div class="cl"></div>
	<?php
			}
	?>
</div>
	<div class="cl"></div>
	</div>
<?php
			$i++;
		}
	}





echo '<div class="cl"></div></div>';


include_once("blocks/Video_left.php");


echo '</div>';
include_once("footer.php");
?>