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
function temp_document_detail($id, $code, $title, $time, $description, $content, $fattach, $othershow, $document_img, $imgtext, $new_others, $new_others2, $source, $document_tid, $title_seo, $description_seo, $keyword_seo, $tags_seo, $hits, $comment, $comment_content, $fattach_intro, $hits_download, $folder, $fullname, $fattach, $link_extend, $price) {
	global $module_name, $adm_mods_ar, $admin_fold, $url ,$urlsite,$path_upload;
	$url_document_detail =url_sid("index.php?f=document&do=detail&id=$id");
?>
<div itemscope itemtype="http://schema.org/Book" style="z-index: -100; width:1px; height:1px; left: -1px; top: -1px; visibility: hidden;overflow:hidden; position: absolute;">
<a itemprop="url" href="<?php echo $url_document_detail?>"><div itemprop="name"><strong><?php if($title_seo=="") echo $title; else echo $title_seo;?></strong></div>
</a>
<div itemprop="description"><?php if($description_seo=="") echo $description; else echo $description_seo;?></div>
<div itemprop="author" itemscope itemtype="http://schema.org/Person">
Written by: <span itemprop="name"><?php echo $fullname?></span></div>
<div><meta itemprop="datePublished" content="<?php echo ext_time($time,2)?>">Date published: <?php echo ext_time($time,2);?></div>
<div>Available in <link itemprop="bookFormat" href="http://schema.org/Ebook">Ebook </div>
</div>

	<div class="document-content">
		<div class="document-content-right fl">
			<div class="document-content-title"><h1 class="posttitle"><?php echo $title ?></h1></div>
			<div style="margin: 5px"><h2 class="postdesc"><?php echo $description ?></h2></div>
		</div>
		<div class="cl"></div>
		<!--<span class="time"><?php //echo NameDay($time).", ".ext_time($time,2) ?></span>-->
		<div>
		<div>
			<div>
				<ul id="countrytabs" class="shadetabs">
<li><a class="tab-desc" href="#" rel="country1" class="selected">Nội dung khóa học</a></li>
<li><a class="tab-error" href="#" rel="country4">Đăng ký tham gia</a></li>
</ul>

<div style="padding-top: 3px">

<div id="country1" class="tabcontent">
<div style="padding: 10px"><?php echo $content?></div>
</div>

<div id="country4" class="tabcontent">
<iframe style="border:0px solid #ccc;" width='675px' height='497px' src="<?php echo $urlsite?>/index.php?f=class&do=reg_class&id=<?php echo $id?>"></iframe>
</div>

</div>

<script type="text/javascript">

var countries=new ddtabcontent("countrytabs")
countries.setpersist(false)
countries.setselectedClassTarget("link") //"link" or "linkparent"
countries.init()

</script>

				

				
			</div>
		</div>
		
	<?php if($source !="") { ?>
		<div><div align="right" style="margin-top: 20px"><i><b><?php echo $source ?></i></b></div></div>
	<?php }
	if(defined('iS_SADMIN') || defined('iS_RADMIN') || (defined('iS_ADMIN') && in_array($module_name,$adm_mods_ar))) { ?>
		<div align="right" style="margin-top: 3px">[<a href="<?php echo $urlsite ?>/admin/modules.php?f=class&do=edit&type=normal&id=<?php echo $id ?>" target="mainFrame"><?php echo _EDIT ?></a> | <a href="<?php echo $urlsite ?>/admin/modules.php?f=class&do=delete&type=normal&id=<?php echo $id ?>" target="mainFrame" onclick="return confirm('<?php echo _DELETEASK ?>');"><?php echo _DELETE ?></a>]</div>
	<?php }?>
<div class="tags"><div class="title"><span class="icon-tags"></span> Tags </div><?php echo $tags_seo ?><div class="cl"></div></div>
<div class="cl"></div>
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
		<div class="money-view"><span><?php echo check_docdown($id)?></span></div>
		<!--<div  class="document-boxca-title">
			<p>
				<strong><a href="<?php echo $url_document_detail ?>" title="<?php echo $title ?>"><?php echo CutString($title,50) ;?></a></strong>
				</div>-->
                <!--<br><?php //echo show_money($price) ?></p>-->
		<?php if ($documentpic!=""){?>
		
		<?php } ?>
		
	</div>
<?php } ?>


<?php function temp_document_index_list($id, $title, $hometext, $documentpic,$url_document_detail) { ?>
			<li class="list-document"><a href="<?php echo $url_document_detail ?>"><?php echo $title ?></a></li>
<?php } ?>