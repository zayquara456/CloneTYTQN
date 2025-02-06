<?php if (!defined('CMS_SYSTEM')) die(); ?>
<?php function temp_news_cat_index($catid, $titlecat, $idnewind, $titlenewind, $hometextind, $news_pic_index, $othersnewindex, $url_news_detail, $url_news_cat) { 
	OpenTab($titlecat,$url_news_cat); ?>

	<table border="0" width="100%" cellpadding="0" style="border-collapse: collapse">
	<tr>
	<td>
	<div style="margin-bottom: 3px" class="content">
		<?php if ($news_pic_index!=""){?>
		<img src="<?php echo $news_pic_index ?>" title="<?php echo $titlenewind?>" alt="<?php echo $titlenewind?>"/>
		<?php } ?>
		<a href="$url_news_detail" class="titlecat"><h3><?php echo $titlenewind ?></h3></a></div>
	<div align="justify" class="content"><?php echo $hometextind ?></div>
	<div class="viewmore" style="margin-top: 6px"><a  class="ui-state-default ui-corner-all" id="dialog_link" href="<?php echo $url_news_detail ?>"><span class="ui-icon ui-icon-newwin"></span><?php echo _READMORE ?></a></div>
	</td>
	</tr>
<?php	if($othersnewindex) {?>
		<tr><td style="padding-left: 8px; padding-top: 10px;"><?php echo $othersnewindex ?></td></tr>
<?php 	}?>
</table>
	
<?php CloseTab(); }?>
<?php
function temp_news_other_index($idother,$url_news_other,$titleother)
{
	$str="";
	 $str=" <div style=\"margin-bottom: 2px\"><span style=\"padding-right: 8px\"><img border=\"0\" src=\"images/bullet.gif\"  alt=\"bullet\"/></span><a href=\"$url_news_other\">$titleother</a></div>";
	 return $str;
}

?>

<?php
function temp_news_loop_cat_index($catid, $titlecat, $idnewind, $titlenewind, $hometextind, $news_pic_index, $othersnewindex, $url_news_detail, $url_news_cat) 
{ OpenTab($titlecat,$url_news_cat); ?>

	<div class="content">
		<?php if ($news_pic_index!=""){?>
		<img src="<?php echo $news_pic_index ?>" title="<?php echo $titlenewind?>" alt="<?php echo $titlenewind?>"/>
		<?php } ?>
		<a href="<?php echo $url_news_detail ?>" class="titlecat">
			<h2><?php echo $titlenewind ?></h2>
		</a>
		<?php echo $hometextind ?>
	</div>
	<div class="cl"></div>
	<div class="viewmore">
		<a  class="ui-state-default ui-corner-all" href="<?php echo $url_news_detail ?>">
			<span class="ui-icon ui-icon-newwin"></span><?php _READMORE ?>
		</a>
	</div>
	<?php if($othersnewindex) {
		echo $othersnewindex;
	} ?>
	
<?php CloseTab();}?>
<?php
function temp_newdetail($id, $title, $time, $hometext, $bodytext, $fattach, $othershow, $news_img, $imgtext, $new_others, $new_others2, $source, $news_tid, $title_seo, $description_seo, $keyword_seo, $tags_seo, $hits, $comment, $comment_content) {
	global $module_name, $adm_mods_ar, $admin_fold, $url ,$urlsite,$path_upload;
	$url_news_detail =url_sid("index.php?f=news&do=detail&id=$id");
?>
<div itemscope="" itemtype="http://schema.org/Recipe" style="z-index: -100; width:1px; height:1px; left: -1px; top: -1px; visibility: hidden;overflow:hidden; position: absolute;">
	<span itemprop="name"><?php echo $title?></span>
	<?php echo $news_img?>
	<div itemprop="aggregateRating" itemscope="" itemtype="http://schema.org/AggregateRating">
		<span itemprop="ratingValue">9</span>
		<span itemprop="bestRating">10</span>
		<span itemprop="ratingCount"><?php echo $hits?></span>
	</div>
</div>
	<div class="content">
		<h1 class="posttitle"><?php echo $title ?></h1>
		<!--<span class="time"><?php //echo NameDay($time).", ".ext_time($time,2) ?></span>-->
		<div style="padding: 8px">
		<div>
			<div align="justify">
				<div style="margin-bottom: 5px"><h2 class="postdesc"><?php echo $hometext ?></h2></div>
				<!--<div style="text-align: center"><?php //echo $news_img ?></div><div class="cl"></div>-->
				<span><?php echo $bodytext ?></span>
			</div>
		</div>
	<?php if($fattach !="") { ?>
		<div class="clearfix" style="padding: 4px; margin-bottom:0px; padding-left: 20px; border-bottom:1px solid #cccccc">
		<b><?php echo _FILE_ATTACH ?>:</b> <img border="0" alt="file" src="<?php echo $urlsite ?>/images/file.gif" align="absmiddle">&nbsp;<a href="<?php echo $urlsite."/$path_upload/news/attachs/".$fattach; ?>" style="font: bold 12px arial; color: #007dba; text-decoration: underline"><?php echo $fattach ?></a> (<?php echo ext_time($time,2) ?>)</div>
	<?php } ?>
	<?php if($source !="") { ?>
		<div><div align="right" style="margin-top: 20px"><i><b><?php echo $source ?></i></b></div></div>
	<?php }
	if(defined('iS_SADMIN') || defined('iS_RADMIN') || (defined('iS_ADMIN') && in_array($module_name,$adm_mods_ar))) { ?>
		<div align="right" style="margin-top: 3px">[<a href="<?php echo $urlsite ?>/admin/modules.php?f=news&do=edit_news&type=normal&id=<?php echo $id ?>" target="mainFrame"><?php echo _EDIT ?></a> | <a href="<?php echo $urlsite ?>/admin/modules.php?f=news&do=delete_news&type=normal&id=<?php echo $id ?>" target="mainFrame" onclick="return confirm('<?php echo _DELETEASK ?>');"><?php echo _DELETE ?></a>]</div>
	<?php }?>
	
	<div class="tags"><div class="title"><span class="icon-tags"></span> Tags </div><?php echo $tags_seo ?><div class="cl"></div></div>
	<!-- AddThis Button BEGIN -->
	<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/vi_VN/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<div class="fb-like" data-href="<?php echo $url_news_detail?>" data-layout="standard" data-action="like" data-show-faces="false" data-share="true"></div>
	<!-- AddThis Button END -->
	<p><span style="float:right"><a href="javascript:history.go(-1);">[<b><?php echo _BACK ?></b>]</a> <a href="#">[<b><?php echo _TOP?></b>]</a></span><a href="<?php echo url_sid("index.php?f=news&do=print&id=".$id."") ?>" target="_blank">
	<img border="0" src="<?php echo $urlsite ?>/images/print.gif" alt="<?php echo _PRINT ?>" title="<?php echo _PRINT ?>"/></a> <a href="javascript:void(0)" onclick="openNewWindow('<?php echo url_sid("index.php?f=news&do=email&id=".$id."") ?>',220,450)">
	<img border="0" src="<?php echo $urlsite ?>/images/email.gif" alt="<?php echo _SENDFRIEND ?>" title="<?php echo _SENDFRIEND ?>"/></a></p>
	<div class="cl"></div>
<?php
	if($comment_content!="")
	{
		echo $comment_content;	
	}
	echo "<iframe src=\"{$comment}\" scrolling=\"no\" width=\"665\" height=\"220\" frameborder=\"0\"> </iframe>";
?>
<?php if($othershow != 1)
	{
		if($new_others2) {?>
			<!--<p><b><?php echo _OTHERNEW1 ?>:</b><br/>
			<?php echo $new_others2 ?></p>-->
		<?php }
		if($new_others) {?>
			<div class="line-other"><span class="title-other"><?php echo _OTHERNEW ?>:</span></div><div style="line-height:18px">
			<?php echo $new_others ?></div>
		<?php }
	}?>
	<div class="footer-line"></div>
	</div>
	</div>
	
	
<?php }
?>

<?php function temp_newcat_start($id, $title, $hometext, $images, $url_news_detail) { ?>
	<div class="content"  align="justify">
		<?php if ($images!=""){?>
		<img src="<?php echo $images ?>" title="<?php echo $title?>" alt="<?php echo $title?>"/>
		<?php } ?>
		<a href="<?php echo $url_news_detail ?>"><h2 class="title2"><?php echo $title ?></h2></a>
		<?php echo $hometext ?>
	</div>
	<div class="viewmore">
		<a href="<?php echo $url_news_detail ?>" class="strong">&raquo; <?php echo _READMORE ?>...</a>
	</div>
<?php } ?>

<?php function temp_news_index($id, $title, $hometext, $newspic,$url_news_detail) { ?>
	<div class="boxde">
		<div class="boxde-img">
		<a href="<?php echo $url_news_detail ?>" title="<?php echo $title ?>"><?php echo $newspic ?></a>
		</div>
		<div class="boxde-title"><h2 class="title2"><a href="<?php echo $url_news_detail ?>" title="<?php echo $title ?>"><?php echo $title ?></a></h2></div>
		<?php if ($newspic!=""){?>
		
		<?php } ?>
		<div class="boxde-content">
			<span><?php echo $hometext ?></span>
		</div>
	</div>
<?php } ?>


<?php function temp_news_index_list($id, $title, $hometext, $newspic,$url_news_detail) { ?>
			<li class="list-news"><a href="<?php echo $url_news_detail ?>"><?php echo $title ?></a></li>
<?php } ?>