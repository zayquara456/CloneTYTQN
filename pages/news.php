<?php
$where=$idbuc="";
$catid = isset($_GET['id']) ? intval($_GET['id']) : 0;
$t = isset($_GET['c']) ? $_GET['c'] : "";
$n = isset($_GET['n']) ? $_GET['n'] : "";
$page = isset($_GET['page']) ? intval($_GET['page']) : 0;
$t=trim($t);
if($catid!=0)
	$where.="catid=$catid AND ";
if($n!=0)
	$where.="catid=$n AND ";
if($t!="")
	$where.="t.permalink='$t' AND ";
$result = $db->sql_query("SELECT catid, title, startid, parent, permalink FROM ".$prefix."_news_cat WHERE permalink='$t' AND alanguage='$currentlang'");
if($db->sql_numrows($result) != 1) {
	//header("Location: ".url_sid("index.php")."");	
	die('ssssssssssss');
}
list($ccatid, $ctitle, $cstartid, $cparent, $cpermalink) = $db->sql_fetchrow($result);
?>
<div class="main">
	<div class="main-content container clearfix">
		<div class="content-col fr clearfix">
			<h1 class="the-title indent"><?php echo $ctitle?></h1>
			<div class="view-mode clearfix">
				<span class="list fr"></span>
				<span class="grid current fr"></span>
			</div>
			<div id="news_list" class="news-list clearfix">
			<?php
			
				$result_lastnew = $db->sql_query("SELECT d.id, d.title, d.images, d.time, d.hometext FROM ".$prefix."_news AS d,".$prefix."_news_cat AS t WHERE $where d.catid = t.catid AND d.active=1 AND  d.alanguage='$currentlang' ORDER BY d.time DESC LIMIT 12");
				$numrows = $db->sql_numrows($result_lastnew);
				if($numrows > 0) {
				$a=0;
			?>
			<?php
			while(list($idlast, $titlelast, $imageslast, $time, $hometext) = $db->sql_fetchrow($result_lastnew)) {
			$hometext = preg_replace("/<.*?>/", "", $hometext);
			$get_path = get_path($time);
			$path_upload_img = "$path_upload/news/$get_path";
			$path_upload_img2 = "$path_upload/news";
			$css = 'attachment-banner size-banner wp-post-image';
			if($imageslast !="" && file_exists("$path_upload_img/$imageslast")) 
			{
				$imageslast= resize_image($titlelast, $imageslast, $path_upload_img, $path_upload_img2, $css, 540,270);
			}
			else
			{
				$imageslast= resize_image($titlelast, 'no_image.gif', 'images', $path_upload_img2, $css, 540,270);
			}
			if($a==1) $style_padding_bottom='style="overflow: hidden;"';
			else $style_padding_bottom='style="padding-bottom:8px;overflow: hidden"';
		?>
			<div class="item">
				<a href="<?php echo url_sid("index.php?f=news&do=detail&id=$idlast")?>"><?php echo $imageslast?></a>
				<h3><a title="<?php echo $titlelast?>" href="<?php echo url_sid("index.php?f=news&do=detail&id=$idlast")?>"><?php echo CutString($titlelast,120)?></a></h3>
				<p><?php echo CutString($hometext,250)?></p>
				<p><a href="<?php echo url_sid("index.php?f=news&do=detail&id=$idlast")?>">Xem chi tiết</a></p>
			</div><?php
		$a++;
	}
}?>			
		</div>
			<!--<ul class='page-numbers'>
				<li><span class='page-numbers current'>1</span></li>
				<li><a class='page-numbers' href='$cpermalink<?php echo $urlsite?>/news/page/2/'>2</a></li>
				<li><a class='page-numbers' href='<?php echo $urlsite?>/news/page/3/'>3</a></li>
				<li><span class="page-numbers dots">&hellip;</span></li>
				<li><a class='page-numbers' href='<?php echo $urlsite?>/news/page/18/'>18</a></li>
				<li><a class="next page-numbers" href="<?php echo $urlsite?>/news/page/2/">»</a></li>
			</ul>-->
		</div>
		<?php include('sidebar.php');?>
	</div>
</div>