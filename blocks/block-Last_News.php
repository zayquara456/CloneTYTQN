<?php
if (!defined('CMS_SYSTEM')) header("Location: index.php");

//if(file_exists("data/config_dichvu.php")) require("data/config_dichvu.php");

$content ="";

global $path_upload, $mod_name, $id, $Default_Temp;

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
$result_lastnew = $db->sql_query("SELECT id, title, images, time, hometext FROM ".$prefix."_news WHERE active='1'  AND alanguage='$currentlang' $seld ORDER BY hits DESC LIMIT 6");
$numrows = $db->sql_numrows($result_lastnew);
if($numrows > 0) {
	$a=0;
	?>
	<div class="div-block">
	<div class="div-tblock"><?php echo $correctArr[1] ?></div>


	<div class="div-cblock" style="padding-top:0px; background: #fbfbfb">
		<ul style="list-style-type: none; margin: 0; padding: 0" class="newsblock">
	<?php
	while(list($idlast, $titlelast, $imageslast, $time, $hometext) = $db->sql_fetchrow($result_lastnew)) 
	{
		$a++;

		$url_news_titlelast =url_sid("index.php?f=news&do=detail&id=$idlast");
			if ($a < $numrows) $border_bottom="border-bottom: 1px dotted #EAEAEA";
			?>
			<li><a style="font-weight:bold;" href="<?php echo $url_news_titlelast?>" title="<?php echo  $titlelast?>"><div style="float: left; padding: 0;"><p style="margin:9px 6px; border-right:1px solid #e2e2e2;  height: 35px; width: 40px; font-size: 25px; text-align: center; color: #7B7B7B"><?php echo $a?></p></div><p style="color: #3E3E3E;  font-size: 12px;  margin: 0 0 0 58px; padding: 12px 10px 6px 0;"><?php echo  CutString($titlelast,110)?></p><div class="cl"></div></a></li>
	<?php } ?>
	</ul>
	</div></div>
<?php }
?>