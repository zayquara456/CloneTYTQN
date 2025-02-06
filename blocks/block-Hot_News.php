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
$result_lastnew = $db->sql_query("SELECT id, title, images, time, hometext FROM ".$prefix."_news WHERE active='1' AND special='0' AND alanguage='$currentlang' $seld ORDER BY time DESC LIMIT 10");
$numrows = $db->sql_numrows($result_lastnew);
if($numrows > 0) {
	$a=0;
	?>
	<div class="div-block">
	<div class="div-tblock"><?php echo $correctArr[1] ?></div>
	<div class="div-cblock" style="padding-top:5px; height:386px">
	<?php
	while(list($idlast, $titlelast, $imageslast, $time, $hometext) = $db->sql_fetchrow($result_lastnew)) 
	{
		$a++;

		$url_news_titlelast =url_sid("index.php?f=news&do=detail&id=$idlast");
			if ($a < $numrows) $border_bottom="border-bottom: 1px dotted #EAEAEA";
			?>
			<div style="<?php echo $border_bottom ?>; padding:4px"><a href="<?php echo $url_news_titlelast?>" class="newsothers"><?php echo  CutString($titlelast,110)?></a></div>
	<?php } ?>
	</div></div>
<?php }
?>