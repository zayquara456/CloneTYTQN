<?php
if (!defined('CMS_SYSTEM')) header("Location: index.php");

if(file_exists("data/config_news.php")) require("data/config_news.php");

$content ="";

global $path_upload, $mod_name, $id, $Default_Temp,$urlsite;

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

$result = $db->sql_query("SELECT showtitle FROM ".$prefix."_blocks WHERE title='$correctArr[1]'   AND active=1");
list($showtitle) = $db->sql_fetchrow($result);

$result_lastnew = $db->sql_query("SELECT id, title, images, time, hometext FROM ".$prefix."_news WHERE active='1'  AND alanguage='$currentlang' AND (catid=84 OR catid in (SELECT catid FROM ".$prefix."_news_cat WHERE parent=84)) ORDER BY time DESC LIMIT 15");//AND special='1'
$numrows = $db->sql_numrows($result_lastnew);
if($numrows > 0) 
{
	$a=1;
	echo  "<div class=\"div-block\">";	
	if($showtitle==1)
	    {
		echo "<div class=\"div-tblock\">{$correctArr[1]}</div>";
    	}
	echo "<div class=\"div-cblock\" style=\"padding-top:5px\">";
	echo "<div id=\"last_news\">";
	while(list($idlast, $titlelast, $imageslast, $time, $hometext) = $db->sql_fetchrow($result_lastnew)) 
	   {
		$get_path = get_path($time);
		$path_upload_img = "$path_upload/news/$get_path";
		if($imageslast !="" && file_exists("$path_upload_img/thumb_".$imageslast."")) 
		    {
			$imageslast = $urlsite."/".$path_upload_img."/thumb_".$imageslast;
			$imageslast= "<img title=\"$titlelast\" alt=\"$titlelast\" src=\"$imageslast\" data-original=\"$imageslast\" />";			
		    }
		else{
			//$imageslast= "<img title=\"$titlelast\" alt=\"$titlelast\" src=\"$urlsite/images/no_image.gif\" data-original=\"$urlsite/images/no_image.gif\" />";
		    }		
		$url_news_titlelast =url_sid("index.php?f=news&do=detail&id=$idlast");
		$a++;
		$border_bottom = "";
		if($a > $numrows) {$border_bottom="border-bottom:0px;";}
		//echo "<div class=\"block-news\" > <a href=\"$url_news_titlelast\">".CutString($titlelast,100)." </a></div>";	
		echo "<div class=\"block-news\" align=\"justify\" > <a href=\"$url_news_titlelast\" class=\"newsothers\" style=\" font-weight:bold;\"><img src=\"$urlsite/templates/Adoosite/images/bullet-left.gif\">".CutString($titlelast,110)."</a>";		
	 
	    echo "<div  class=\"newsothers\" align=\"justify\">".CutString($hometext,125)."</div></div>";		

	 
	   }
	echo "</div>";
		echo "<div class=\"clearfix\"></div>";

	echo "</div>";
	echo "</div>";
}
?>