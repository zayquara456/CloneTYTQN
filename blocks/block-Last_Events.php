<?php
if (!defined('CMS_SYSTEM')) header("Location: index.php");

//if(file_exists("data/config_dichvu.php")) require("data/config_dichvu.php");

$content ="";

global $path_upload, $mod_name, $id, $Default_Temp, $urlsite;

if($mod_name == "news" && isset($id)) {
	$seld = "AND id!='$id'";
} else {
	$seld ="";
}
$bl_arr = array();
$bl_arr[] = $bl_l;
$bl_arr[] = $bl_r;
$basename = pathinfo(__FILE__, PATHINFO_BASENAME);
$correctArr = array();
for ($i = 0; $i < count($bl_arr); $i++) {
	for ($h = 0; $h < count($bl_arr[$i]); $h++) {
		$temp = explode("@", $bl_arr[$i][$h]);
		if (($temp[5] == $currentlang) && ($temp[6] == $basename)) {
			$correctArr = $temp;
			break;
		}
	}
}
?>
<div class="block-event">
<div class="style4" id="style4"><ul>
<?php
$result_lastnew = $db->sql_query("SELECT id, title, images, time, hometext FROM ".$prefix."_news WHERE special=1 AND active='1' AND alanguage='$currentlang' AND ( catid=22 or catid in(SELECT catid FROM {$prefix}_news_cat WHERE parent=22)) ORDER BY time DESC LIMIT 5");
$numrows = $db->sql_numrows($result_lastnew);
if($numrows > 0) {
	$a=0;
	?>
	<?php
	while(list($idlast, $titlelast, $imageslast, $time, $hometext) = $db->sql_fetchrow($result_lastnew)) 
	{
		$hometext = preg_replace("/<.*?>/", "", $hometext);
		$a=$idlast;
		$get_path = get_path($time);
		$path_upload_img = "$path_upload/news/$get_path";
		$path_upload_img2 = "$path_upload/news";
		if($imageslast !="" && file_exists("$path_upload_img/$imageslast")) 
		{
			$imageslast= resize_image($titlelast, $imageslast, $path_upload_img, $path_upload_img2, 300,173);
		}
		else
		{
			$imageslast= resize_image($titlelast, 'no_image.gif', 'images', $path_upload_img2, 300,173);
		}
		?>
			<li>
				<span><a href="<?php echo url_sid("index.php?f=news&do=categories&id=22");?>"><?php echo _EVENTS;?></a></span>
				
				<h4># <?php echo ext_time($time,1)?><br><a href="<?php echo url_sid("index.php?f=news&do=detail&id=$idlast")?>"><strong><?php echo $titlelast?><p></strong><?php echo CutString($hometext,250)?></p></a></h4>
			</li>
			
<?php
	}
}
?>
</ul></div></div>