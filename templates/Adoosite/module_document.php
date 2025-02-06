<?php if (!defined('CMS_SYSTEM')) die(); ?>
<?php function temp_document_cat_index($catid, $titlecat, $idnewind, $titlenewind, $hometextind, $document_pic_index, $othersnewindex, $url_document_detail, $url_document_cat) { 
	OpenTab($titlecat,$url_document_cat); ?>

	<table border="0" width="100%" cellpadding="0" style="border-collapse: collapse">
	<tr>
	<td>
	<div style="margin-bottom: 3px" class="content">
		<?php if ($document_pic_index!=""){?>
		<img src="<?php echo $document_pic_index ?>" title="<?php echo $titlenewind?>" alt="<?php echo $titlenewind?>"/>
		<?php } ?>
		<a href="$url_document_detail" class="titlecat"><h3><?php echo $titlenewind ?></h3></a></div>
	<div align="justify" class="content"><?php echo $hometextind ?></div>
	<div class="viewmore" style="margin-top: 6px"><a  class="ui-state-default ui-corner-all" id="dialog_link" href="<?php echo $url_document_detail ?>"><span class="ui-icon ui-icon-newwin"></span><?php echo _READMORE ?></a></div>
	</td>
	</tr>
<?php	if($othersnewindex) {?>
		<tr><td style="padding-left: 8px; padding-top: 10px;"><?php echo $othersnewindex ?></td></tr>
<?php 	}?>
</table>
	
<?php CloseTab(); }?>
<?php
function temp_document_other_index($idother,$url_document_other,$titleother)
{
	$str="";
	 $str=" <div style=\"margin-bottom: 2px\"><span style=\"padding-right: 8px\"><img border=\"0\" src=\"images/bullet.gif\"  alt=\"bullet\"/></span><a href=\"$url_document_other\">$titleother</a></div>";
	 return $str;
}

?>

<?php
function temp_document_loop_cat_index($catid, $titlecat, $idnewind, $titlenewind, $hometextind, $document_pic_index, $othersnewindex, $url_document_detail, $url_document_cat) 
{ OpenTab($titlecat,$url_document_cat); ?>

	<div class="content">
		<?php if ($document_pic_index!=""){?>
		<img src="<?php echo $document_pic_index ?>" title="<?php echo $titlenewind?>" alt="<?php echo $titlenewind?>"/>
		<?php } ?>
		<a href="<?php echo $url_document_detail ?>" class="titlecat">
			<h2><?php echo $titlenewind ?></h2>
		</a>
		<?php echo $hometextind ?>
	</div>
	<div class="cl"></div>
	<div class="viewmore">
		<a  class="ui-state-default ui-corner-all" href="<?php echo $url_document_detail ?>">
			<span class="ui-icon ui-icon-newwin"></span><?php _READMORE ?>
		</a>
	</div>
	<?php if($othersnewindex) {
		echo $othersnewindex;
	} ?>
	
<?php CloseTab();}?>
<?php
function temp_document_detail($id, $code, $title, $time, $hometext, $bodytext, $fattach, $othershow, $document_img, $imgtext, $new_others, $new_others2, $source, $document_tid, $title_seo, $description_seo, $keyword_seo, $tags_seo, $hits, $comment, $comment_content, $fattach_intro, $hits_download, $folder, $fullname, $link_extend) {
	global $module_name, $adm_mods_ar, $admin_fold, $url ,$urlsite,$path_upload;
	$url_document_detail =url_sid("index.php?f=document&do=detail&id=$id");
?>
<div itemscope="" itemtype="http://schema.org/Recipe" style="z-index: -100; width:1px; height:1px; left: -1px; top: -1px; visibility: hidden;overflow:hidden; position: absolute;">
	<span itemprop="name"><?php echo $title?></span>
	<?php echo $document_img?>
	<div itemprop="aggregateRating" itemscope="" itemtype="http://schema.org/AggregateRating">
		<span itemprop="ratingValue">9</span>
		<span itemprop="bestRating">10</span>
		<span itemprop="ratingCount"><?php echo $hits?></span>
	</div>
</div>
	<div class="document-content">
		<h1 class="posttitle"><?php echo $title ?></h1>
		<!--<span class="time"><?php //echo NameDay($time).", ".ext_time($time,2) ?></span>-->
		<div>
		<div><?php echo $document_img ?>
			<div align="justify">
				<div style="margin: 5px"><h2 class="postdesc"><?php echo $bodytext ?></h2></div>
				<?php
				//echo "<div  class=\"document-title fl\"><p><strong>"._SEND_BY.":</strong> ".CutString($fullname,12)." | ".ext_time($time,2)." | <strong>"._DOWNLOAD.":</strong> ".$hits_download." | <strong>"._VIEW.":</strong> ".$hits." | <strong>"._COMMENT.":</strong> 0</p></div>";
				?>
				<?php if($fattach !="") { ?>
					<div class="fr" style="padding: 4px; margin-bottom:0px; padding-left: 20px; text-align: right">
					<a target="_blank" href="<?php echo url_sid("index.php?f=document&do=download&u=".$code); ?>" style="font: bold 12px arial; color: #007dba; text-decoration: underline"><img border="0" alt="file" src="<?php echo $urlsite ?>/images/download.png" align="absmiddle"></a></div>
					<div class=\"cl\"></div>
				<?php } ?>
				<?php if($link_extend !="") { ?>
					<div class="fr" style="padding: 4px; margin-bottom:0px; padding-left: 20px; text-align: right">
					<a target="_blank" href="<?php echo url_sid("index.php?f=document&do=download_extend&u=".$code); ?>" style="font: bold 12px arial; color: #007dba; text-decoration: underline"><img border="0" alt="file" src="<?php echo $urlsite ?>/images/download.png" align="absmiddle"></a></div>
					<div class=\"cl\"></div>
				<?php } ?>
				
				<?php if(!empty($fattach_intro)){?>
				<span><iframe style="border:1px solid #ccc;" width='657px' height='560px' src="https://docs.google.com/viewer?url=http://acud.vn/<?php echo $path_upload?>/document/<?php echo $folder?>/<?php echo $fattach_intro?>&embedded=true"></iframe>
</span>
				<?php }?>
			</div>
		</div>
		
	<?php if($source !="") { ?>
		<div><div align="right" style="margin-top: 20px"><i><b><?php echo $source ?></i></b></div></div>
	<?php }
	if(defined('iS_SADMIN') || defined('iS_RADMIN') || (defined('iS_ADMIN') && in_array($module_name,$adm_mods_ar))) { ?>
		<div align="right" style="margin-top: 3px">[<a href="<?php echo $urlsite ?>/admin/modules.php?f=document&do=edit_document&type=normal&id=<?php echo $id ?>" target="mainFrame"><?php echo _EDIT ?></a> | <a href="<?php echo $urlsite ?>/admin/modules.php?f=document&do=delete_document&type=normal&id=<?php echo $id ?>" target="mainFrame" onclick="return confirm('<?php echo _DELETEASK ?>');"><?php echo _DELETE ?></a>]</div>
	<?php }?>
	<!-- AddThis Button BEGIN -->
	
<div class="fb-like" data-href="<?php echo $url_document_detail?>" data-layout="standard" data-action="like" data-show-faces="false" data-share="true"></div>
	<!-- AddThis Button END -->
	<div class="tags"><div class="title"><span class="icon-tags"></span> Tags </div><?php echo $tags_seo ?><div class="cl"></div></div>
	<p><span style="float:right"><a href="javascript:history.go(-1);">[<b><?php echo _BACK ?></b>]</a> <a href="#">[<b><?php echo _TOP?></b>]</a></span><a href="<?php echo url_sid("index.php?f=document&do=print&id=".$id."") ?>" target="_blank">
	<img border="0" src="<?php echo $urlsite ?>/images/print.gif" alt="<?php echo _PRINT ?>" title="<?php echo _PRINT ?>"/></a> <a href="javascript:void(0)" onclick="openNewWindow('<?php echo url_sid("index.php?f=document&do=email&id=".$id."") ?>',220,450)">
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

<?php function temp_documentcat_start($id, $title, $hometext, $images, $url_document_detail) { ?>
	<div class="content"  align="justify">
		<?php if ($images!=""){?>
		<img src="<?php echo $images ?>" title="<?php echo $title?>" alt="<?php echo $title?>"/>
		<?php } ?>
		<a href="<?php echo $url_document_detail ?>"><h2 class="title2"><?php echo $title ?></h2></a>
		<?php echo $hometext ?>
	</div>
	<div class="viewmore">
		<a href="<?php echo $url_document_detail ?>" class="strong">&raquo; <?php echo _READMORE ?>...</a>
	</div>
<?php } ?>

<?php function temp_document_index($id, $title, $price, $documentpic,$url_document_detail) { ?>
	<div class="document-item fl">
		<div class="document-boxde-img">
		<a href="<?php echo $url_document_detail ?>" title="<?php echo $title ?>"><?php echo $documentpic ?></a>
		</div>
		<div  class="document-boxca-title">
			<p>
				<strong><a href="<?php echo $url_document_detail ?>" title="<?php echo $title ?>"><?php echo CutString($title,50) ;?></a></strong>
				</div>
                <!--<br><?php //echo show_money($price) ?></p>-->
		<?php if ($documentpic!=""){?>
		
		<?php } ?>
		
	</div>
<?php } ?>


<?php function temp_document_index_list($id, $title, $hometext, $documentpic,$url_document_detail) { ?>
			<li class="list-document"><a href="<?php echo $url_document_detail ?>"><?php echo $title ?></a></li>
<?php } ?>