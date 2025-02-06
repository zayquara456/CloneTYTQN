<?php
if (!defined('CMS_SYSTEM')) die();
global $Default_Temp, $time,$urlsite, $userInfo;
include_once("header.php");
//include("includes/resize-class.php");
//OpenTab(_HOMEPAGE);

//hien thi tin noi bat
?>



<?php
$result_catindex = $db->sql_query("SELECT catid, title, homelinks FROM {$prefix}_news_cat WHERE active='1' AND onhome=1 AND alanguage='$currentlang' ORDER BY weight");
	if($db->sql_numrows($result_catindex) > 0) 
	{
		$i=1;
		while(list($catid, $titlecat, $homelinks) = $db->sql_fetchrow($result_catindex)) 
		{
	?>
<div class="folder">
	<div class="folder-box of fl">
		<div class="folder-box-title">
			<div class="fl"><a  class="folder-link" href="<?php echo url_sid("index.php?f=news&do=categories&id=$catid")?>"><?php echo $titlecat?></a></div>
			<div class="folder-sub fl">
			<?php
			$result = $db->sql_query("SELECT catid, title, homelinks FROM {$prefix}_news_cat WHERE active='1' AND parent=$catid AND alanguage='$currentlang' ORDER BY weight");
			if($db->sql_numrows($result) > 0) 
			{
				$j=1;
				while(list($catid_sub, $titlecat_sub, $homelinks_sub) = $db->sql_fetchrow($result))
				{
					if($j!=1){echo " | ";}
					?>	
						<a class="link-subfolder" href="<?php echo url_sid("index.php?f=news&do=categories&id=$catid_sub")?>"><?php echo $titlecat_sub?></a>
			<?php
					$j++;
				}
			}
			?>
			</div>
			<div class="cl"></div>
		</div>	
		<?php
			if($i==8){$i=1;}
$result_lastnew = $db->sql_query("SELECT id, title, images, time, hometext FROM ".$prefix."_news WHERE active=1 AND (catid=$catid or catid in (SELECT catid FROM {$prefix}_news_cat WHERE active=1 AND parent=$catid)) AND  alanguage='$currentlang' ORDER BY time DESC LIMIT 8");
//die("SELECT id, title, images, time, hometext FROM ".$prefix."_news WHERE active=1 AND  catid=$catid or catid in (SELECT catid FROM {$prefix}_news_cat WHERE active=1 AND parent=$catid) AND  alanguage='$currentlang' ORDER BY time DESC LIMIT 8");
$numrows = $db->sql_numrows($result_lastnew);
if($numrows > 0) {
	$a=0;
	?>
	<div>
	<div class="folder-box-left fl">
	<?php
	while(list($idlast, $titlelast, $imageslast, $time, $hometext) = $db->sql_fetchrow($result_lastnew)) {
		$hometext = preg_replace("/<.*?>/", "", $hometext);
		if ($a<2)
		{
			$get_path = get_path($time);
			$path_upload_img = "$path_upload/news/$get_path";
			$path_upload_img2 = "$path_upload/news";
			if($imageslast !="" && file_exists("$path_upload_img/$imageslast")) 
			{
				$imageslast= resize_image($titlelast, $imageslast, $path_upload_img, $path_upload_img2, 140,120);
			}
			else
			{
				$imageslast= resize_image($titlelast, 'no_image.gif', 'images', $path_upload_img2, 140,120);
			}
			if($a==1) $style_padding_bottom='style="overflow: hidden;"';
			else $style_padding_bottom='style="padding-bottom:8px;overflow: hidden"';
		?>
		<div <?php echo $style_padding_bottom;?>><?php echo $imageslast?><h3><a href="<?php echo url_sid("index.php?f=news&do=detail&id=$idlast")?>"><?php echo CutString($titlelast,150)?></a></h3><div class="folder-content"><?php echo CutString($hometext,250)?></div></div>
		
		<?php
			if($a==1){
		?>
			</div>
			<div class="fl" style="width:260px;">
		<?php
			}
		}
		elseif($a==2){
			$get_path = get_path($time);
			$path_upload_img = "$path_upload/news/$get_path";
			$path_upload_img2 = "$path_upload/news";
			if($imageslast !="" && file_exists("$path_upload_img/$imageslast")) 
			{
				$imageslast= resize_image($titlelast, $imageslast, $path_upload_img, $path_upload_img2, 90,80);
			}
			else
			{
				$imageslast= resize_image($titlelast, 'no_image.gif', 'images', $path_upload_img2, 90,80);
			}
			?>
			<div class="folder-box-right"><?php echo $imageslast?><h3><a href="<?php echo url_sid("index.php?f=news&do=detail&id=$idlast")?>"><?php echo CutString($titlelast,150)?></a></h3><div class="cl"></div></div>
			<?php
		}
		else
		{
			if($a==2) $style_list_dot="";
			else $style_list_dot='style="border-top:1px dotted #EAEAEA"';
			
		?>
			<div class="folder-box-list" <?php echo $style_list_dot;?>><a href="<?php echo url_sid("index.php?f=news&do=detail&id=$idlast")?>" class="folder-title"><?php echo CutString($titlelast,110)?></a></div>	
			
		<?php
		}
		$a++;
	}
	?>
	</div>
	<div class="cl"></div>
	</div>
	<?php
	
}?>
</div>
	<div class="cl"></div>
	</div>
<?php
	$i++;
	}
	}
?>

<?php
//CloseTab();

include_once("footer.php");
?>

