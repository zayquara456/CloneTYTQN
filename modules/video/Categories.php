<?php
if (!defined('CMS_SYSTEM')) die();
global $db, $currentlang, $prefix, $home, $urlsite, $userInfo, $path_upload;
$where="";
$id = intval($_GET['id']);
$t = isset($_GET['t']) ? $_GET['t'] : "";
$page = isset($_GET['page']) ? intval($_GET['page']) : 0;
$perpage=16;
if($id!=0)
	$where.="catid=$id AND ";
if($t!="")
	$where.="permalink='$t' AND ";
//title
$resultmodule = $db->sql_query("SELECT catid, title, seo_title, seo_description, seo_keyword FROM {$prefix}_video_cat WHERE $where active=1 AND alanguage='$currentlang'");

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

<div class="module-video-content fl"><div class="module-video-title"><div class="video-breakcoup fl"><?php echo $mcustom_title ?><div class="cl"></div></div></div>

<div class="module-video-special">
<?php
$result_subcat_prd = $db->sql_query("SELECT catid, title FROM {$prefix}_video_cat WHERE parent='$mmid' AND active=1 AND alanguage='$currentlang' ORDER BY weight");
if($db->sql_numrows($result_subcat_prd) > 0) {
	echo "<div class=\"cat_list\"><ul>";
	while(list($subcatid, $subcatname) = $db->sql_fetchrow($result_subcat_prd)) {
		echo "<li><h2><a href=\"".url_sid("index.php?f=video&do=categories&id=".$subcatid."")."\">$subcatname</a></h2></li>";	
	}
	echo "</ul><div class=\"clearfix\"></div></div>";
}	
?>
	<div class="of fl">

		<?php
	$perpage = 16;
	$page = intval(isset($_GET['page']) ? $_GET['page'] : (isset($_POST['page']) ? $_POST['page']:1));
	$offset = ($page-1) * $perpage;
	$countf = $db->sql_fetchrow($db->sql_query("SELECT COUNT(*) FROM {$prefix}_video WHERE  catid='$mmid' AND active=1 AND alanguage='$currentlang'"));
	$total = ($countf[0]) ? $countf[0] : 1;
	$pageurl = "index.php?f=video&do=categories&id=$mmid";


$result_lastnew = $db->sql_query("SELECT id, title, guid, images, time FROM ".$prefix."_video WHERE alanguage='$currentlang' AND catid='$mmid' AND active=1 ORDER BY catid DESC LIMIT  $offset, $perpage");

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
	<div class="cl"></div><br>
	<?php
	if($total > $perpage) {
		echo paging($total,$pageurl,$perpage,$page);
	}
			}
	?>
</div>
	<div class="cl"></div>
	</div>
<?php

echo '<div class="cl"></div></div>';
include_once("blocks/Video_left.php");
echo '</div>';
include_once("footer.php");
?>