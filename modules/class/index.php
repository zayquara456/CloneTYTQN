<?php
if (!defined('CMS_SYSTEM')) die();
//title
$resultmodule = $db->sql_query("SELECT mid, custom_title, seo_title, seo_description, seo_keyword FROM {$prefix}_modules WHERE active=1 AND alanguage='$currentlang' AND title='$module_name'");
if($db->sql_numrows($resultmodule) > 0) 
{
	list($mmid, $mcustom_title, $mseo_title, $mseo_description, $mseo_keyword) = $db->sql_fetchrow($resultmodule);
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

/////////////////////////////////////////////////////////////
$result_lastnew = $db->sql_query("SELECT id, title, guid, images, time, description, content, status FROM ".$prefix."_class WHERE status=1 ORDER BY time DESC");

$numrows = $db->sql_numrows($result_lastnew);
if($numrows > 0) {
	$a=1;
	?>
	<div class="class-folder" id="class-folder">
		<div class="class-title">Danh sách lớp học</div>
		<div class="class-group">
	<?php
			while(list($id, $title, $guid, $images, $time, $description, $content, $status) = $db->sql_fetchrow($result_lastnew)) {
			$description = preg_replace("/<.*?>/", "", $description);
		
			$path_upload_img = "$path_upload/class";
			if($images !="" && file_exists("$path_upload_img/$images")) 
			{
				$images = resize_image($title,$images,$path_upload_img,$path_upload_img,325,227);
			}
			else
			{
				$images = resize_image($title,'no_image.gif','images',$path_upload_img,325,227);
			}
		?>
		
			<div class="class-item fl">
				<div><a href="<?php echo url_sid($guid)?>" class="document-link"><?php echo $images?></a>
				<p class="class-item-title"><a href="<?php echo url_sid($guid)?>"><strong><?php echo $title ;?></strong></a></p>
				</div>
			</div>
		
		
		<?php
		$a++;
		//<br><?php echo show_money($price) </a>
		}
	?>
		</div>
	</div>
	<div class="cl"></div>
	<?php
			}
			$i++;	
?>
<?php
//}
include_once("footer.php");
?>