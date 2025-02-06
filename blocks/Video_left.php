<?php
echo '<div class="module-video-left fl">';
//block video moi nhat
echo '<div class="module-video-new"><div class="title">Video mới nhất</div>';
	$result = $db->sql_query("SELECT id,title,images,links FROM ".$prefix."_video WHERE active=1 ORDER BY id DESC LIMIT 6");
	while(list($id, $title,$images, $links_video) = $db->sql_fetchrow($result)){
		$path_upload_imgnewind = "$path_upload/video";
		if($images !="" && file_exists("$path_upload_imgnewind/$images")) 
		{
			$images= resize_image($title, $images, $path_upload_imgnewind, $path_upload_imgnewind, 120,70);
		}
		else
		{
			$images= resize_image($title, 'no_image.gif', 'images', $path_upload_imgnewind, 120,70);
		}
		echo "<div class=\"video-new-pic\">";	
			echo "<div class=\"img\">";
			echo "<a id=\"various\"  href=\"".url_sid("index.php?f=video&do=detail&id=$id")."\" title=\"$title\" >$images</a>";
			echo "</div>";	
			echo "<div class=\"video-new-title\">";
				echo "<a href=\"".url_sid("index.php?f=video&do=detail&id=$id")."\">".CutString($title,65)."</a>";	
			echo "</div>";
		echo '<div class="cl"></div></div>';
	}
echo '<div class="cl"></div></div>';		
//block video xem nhieu nhat
echo '<div class="module-video-view"><div class="title">Video xem nhiều nhất</div>';
	$result = $db->sql_query("SELECT id,title,images,links FROM ".$prefix."_video WHERE active=1 ORDER BY hits DESC LIMIT 6");
	while(list($id, $title,$images, $links_video) = $db->sql_fetchrow($result)){
		$path_upload_imgnewind = "$path_upload/video";
		if($images !="" && file_exists("$path_upload_imgnewind/$images")) 
		{
			$images= resize_image($title, $images, $path_upload_imgnewind, $path_upload_imgnewind, 120,70);
		}
		else
		{
			$images= resize_image($title, 'no_image.gif', 'images', $path_upload_imgnewind, 120,70);
		}
		echo "<div class=\"video-view-pic\">";	
			echo "<div class=\"img\">";
			echo "<a id=\"various\"  href=\"".url_sid("index.php?f=video&do=detail&id=$id")."\" title=\"$title\" >$images</a>";
			echo "</div>";	
			echo "<div class=\"video-view-title\">";
				echo "<a href=\"".url_sid("index.php?f=video&do=detail&id=$id")."\">".CutString($title,65)."</a>";	
			echo "</div>";
			
		echo '<div class="cl"></div></div>';
	}
echo '<div class="cl"></div></div>';		
// block facebook
echo '<div class="module-video-facebook"><div class="title">Tìm chúng tôi trên Facebook</div>';
echo '<div class="fb-like-box" data-href="https://www.facebook.com/acud.vn" data-colorscheme="light" data-show-faces="true" data-header="false" data-stream="false" data-show-border="false"></div>';
echo '<div class="cl"></div></div>';	

?>