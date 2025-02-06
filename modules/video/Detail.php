<?php
//die("SELECT n.id, n.title, n.images, n.hometext, n.bodytext, n.links, n.link_extend, n.hits FROM ".$prefix."_video AS n, ".$prefix."_video_cat AS c WHERE $where n.active=1 AND n.catid=c.catid");
if (!defined('CMS_SYSTEM')) die();
global $db, $currentlang, $prefix, $home, $urlsite, $userInfo, $path_upload;
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
$where="";
$id = intval($_GET['id']);
$t = isset($_GET['t']) ? $_GET['t'] : "";
$c = isset($_GET['c']) ? $_GET['c'] : "";
if($id!=0)
	$where.="n.id=$id AND ";
if($t!="")
	$where.="n.permalink='$t' AND ";
if($c!="")
	$where.="c.permalink='$c' AND ";
//
$db->sql_query("UPDATE ".$prefix."_video SET hits=hits+1 WHERE id='$id'");
//
//$result = $db->sql_query("SELECT title, links, hits FROM {$prefix}_video WHERE id='$id'");
//list($title, $links, $hits) = $db->sql_fetchrow($result);
echo "<div class=\"module-video-content fl\"><div class=\"module-video-title\"><div class=\"video-breakcoup fl\">"._MODTITLE."</div><div class=\"cl\"></div></div>";
	echo "<div class=\"video-hot\">";
	$result = $db->sql_query("SELECT n.id, n.title, n.images, n.hometext, n.bodytext, n.links, n.link_extend, n.hits FROM ".$prefix."_video AS n, ".$prefix."_video_cat AS c WHERE $where n.active=1 AND n.catid=c.catid");
    
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
echo '<div class="module-video-special"><div class="title">Video khác</div>';
	$result = $db->sql_query("SELECT id,title,images,links FROM ".$prefix."_video WHERE active=1 ORDER BY id DESC LIMIT 4");
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

echo '<div class="cl"></div></div>';


include_once("blocks/Video_left.php");


echo '</div>';
include_once("footer.php");
?>

<?php
//if (!defined('CMS_SYSTEM')) die();
//
//$id = intval($_GET['id']);
//
//$db->sql_query("UPDATE ".$prefix."_video SET hits=hits+1 WHERE id='$id'");
//
//$result = $db->sql_query("SELECT title, links, hits FROM {$prefix}_video WHERE id='$id'");
//list($title, $links, $hits) = $db->sql_fetchrow($result);
//
//	$str1 = substr($links, 0, 15);
//	$str2 = substr($links, 15, 3);
//	$str2 = 550;
//	$str3 = substr($links, 18, 10);
//	$str4 = substr($links, 28, 3);
//	$str4 = 380;
//	$str5 = substr($links, 31, 200);
//	$str = $str1.$str2.$str3.$str4.$str5;
//
//$page_title = $title;
//$description_page = $title;
//$title_page = $title;
//include_once("header.php");
//
//echo "<div class=\"title_home\">".$title."</div>";
//echo "<div class=\"div-home\" style=\"padding-bottom:23px;\" >";
//echo "<div class=\"play-video\">";
//echo '<iframe width="658" height="404" src="//www.youtube.com/embed/'.$links.'?rel=0" frameborder="0" allowfullscreen></iframe>';
//echo "</div>";
//echo "<div class=\"play-hits\">"._HITS_VIDEO." : ".$hits."</div>";
//
//echo "</div>";
//
//include_once("footer.php");
?>