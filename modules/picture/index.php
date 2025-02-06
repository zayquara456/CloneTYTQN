<?php
if (!defined('CMS_SYSTEM')) die();
include_once("header.php");
include("blocks/Menu_Left.php");
//begin check show menu
$faobucmenu = isset($_GET['f']) ? $_GET['f'] : "";
if($faobucmenu=="picture"){$idbuc=36;}
echo show_left_menu($idbuc);
//begin check show menu
OpenTab("<a href=\"".url_sid("index.php")."\" \" title=\""._HOMEPAGE."\">"._HOMEPAGE."</a> &gt; "._MODTITLE."");
	echo "<div class=\"content\">";
	$result = $db->sql_query("SELECT catid,title,images FROM ".$prefix."_picture_cat WHERE active=1 ORDER BY catid ASC ");
	$i=1;
	while(list($catid, $title,$images) = $db->sql_fetchrow($result)){
		echo "<div class=\"picture-pic\" style=\"margin-right:0px;\">";		
		$images = resizeImages("files/pictures/$images", "files/pictures/142x91_$images" ,142,91);	
			echo "<a href=\"".url_sid("index.php?f=picture&do=categories&catid=$catid",$title)."\"><img src=\"$images\"></a>";	
			echo "<div class=\"video-title\">";
				echo "<a href=\"".url_sid("index.php?f=picture&do=categories&catid=$catid",$title)."\">".$title."</a>";	
			echo "</div>";
		echo "</div>";
		$i++;
	}						
echo "</div>";
	echo "<div class=\"cl\"></div>";
CloseTab();

include_once("footer.php");
?>