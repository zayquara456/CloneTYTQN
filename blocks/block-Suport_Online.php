<?php
if (!defined('CMS_SYSTEM')) exit;

global $yim_support, $skyim_support,$yim_support_en, $skyim_support_en, $Default_Temp, $urlsite, $currentlang;

$bl_arr = array();
$bl_arr[] = $bl_l;
$bl_arr[] = $bl_r;
$basename = pathinfo(__FILE__, PATHINFO_BASENAME);
$correctArr = array();
for ($i = 0; $i < count($bl_arr); $i++) 
{
	for ($h = 0; $h < count($bl_arr[$i]); $h++) 
	{
		$temp = explode("@", $bl_arr[$i][$h]);
		if (($temp[5] == $currentlang) && ($temp[6] == $basename)) 
		{
			$correctArr = $temp;
			break;
		}
	}
}

$content = "";
if($currentlang=="english")
{
$yim_support= $yim_support_en;
$skyim_support = $skyim_support_en;
}
?>
	<div>
<?php
if(!empty($yim_support)) 
{
	$yim_support_arr = @explode(",", $yim_support);
	//$yim_support_arr = str_replace(" ","",$yim_support_arr);
	
	for ($i = 0; $i < sizeof($yim_support_arr); $i++) 
	{
		$yim_support_arr2 = @explode("|", $yim_support_arr[$i]);
	?>
	<a href="ymsgr:SendIM?<?php echo trim($yim_support_arr2[0])?>" title="<?php echo $yim_support_arr2[1] ?>"><img src="<?php echo $urlsite?>/images/ymicon.jpg" alt="<?php echo $yim_support_arr2[1] ?>" title="<?php echo $yim_support_arr2[1] ?>" align="middle"/></a>&nbsp
	<?php }
	if (count($yim_support_arr) > 0){}
	
}
?>
<?php
if(!empty($skyim_support)) 
{
	$skyim_support_arr = @explode(",", $skyim_support);
	//$yim_support_arr = str_replace(" ","",$yim_support_arr);
	
	for ($i = 0; $i < sizeof($skyim_support_arr); $i++) 
	{
		$skyim_support_arr2 = @explode("|", $skyim_support_arr[$i]);
	?>
	<a href="skype:<?php echo trim($skyim_support_arr2[0])?>?chat" title="<?php echo $skyim_support_arr2[1] ?>"><img src="<?php echo $urlsite?>/images/skype.png" alt="<?php echo $skyim_support_arr2[1] ?>" title="<?php echo $yim_support_arr2[1] ?>" align="middle"/></a>
	<?php }
	if (count($skyim_support_arr) > 0){}
	
}
?>
</div>
<?php 
?>
