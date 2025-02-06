<?php
if (!defined('CMS_SYSTEM')) die();

$title_home = "<a href=\"".url_sid("index.php")."\">"._HOMEPAGE."</a> &rsaquo; <a href=\"".url_sid("index.php?f=question")."\">"._HOI_DAP."</a>";
$sitelinkmap=$title_home;
include_once("header.php");
OpenTab($title_home);
?>
<?
$result_catindex = $db->sql_query("SELECT catid, title FROM {$prefix}_question_cat WHERE active=1 AND alanguage='$currentlang' ORDER BY weight");
	if($db->sql_numrows($result_catindex) > 0) 
	{
		$i=0;
		while(list($catid, $titlecat) = $db->sql_fetchrow($result_catindex)) 
		{
			$i++;
			$rwtitlecat = utf8_to_ascii(url_optimization($titlecat));
			$url_news_cat =url_sid("index.php?f=question&do=categories&id=$catid");
			echo"<div class=\"boxca\">\n";
			echo "<div class=\"boxca-title\" style=\"padding-left:10px\"><h2><a href=\"$url_news_cat\">$titlecat</a></h2></div>\n";
			echo "<div style=\"padding:10px\">";
			$result_newsindex = $db->sql_query("SELECT id, title, content, time, name, email,hits FROM {$prefix}_question WHERE active=1 AND ( catid=$catid or catid in(SELECT catid FROM {$prefix}_question_cat WHERE parent=$catid)) ORDER BY time DESC LIMIT 1");
			if($db->sql_numrows($result_newsindex) > 0) 
			{
				
				list($idnewind, $titlenewind, $contentind, $timeind, $nameind, $emailind, $hitsind) = $db->sql_fetchrow($result_newsindex);
				//$query = "SELECT COUNT(*) FROM {$prefix}_answer WHERE qid=$idnewind AND alanguage='$currentlang'";
//$result = $db->sql_query($query);
//list($total) = $db->sql_fetchrow($result);
				$rwtitlenewind = utf8_to_ascii(url_optimization($titlenewind));
				$url_news_detail =url_sid("index.php?f=question&do=detail&id=$idnewind");
				$contentind = strip_tags($contentind,"<a><u><i><b><strong><em>");
					echo"<div class=\"box-home-content\">";
					echo "<div class=\"box-home-item-desc\">";
					//echo "<h3><a href=\"$url_news_detail\">$titlenewind</a></h3>";
					
					//echo "<div class=\"qname\">"._NGUOI_GUI."<b>$nameind</b> - "._LUOT_XEM."[$hitsind]</div>";
					//echo "<div class=\"boxca-img\"><img title=\"$titlenewind\" alt=\"$titlenewind\" src=\"$urlsite/images/logo.gif\"/></div>";
					echo "<div class=\"boxcca-title\"><strong><a href=\"$url_news_detail\">$titlenewind</a></strong></div>";
					echo "<div>".CutString($contentind,300)."</div>
				<div class=\"cl\"></div>
				</div>
			</div>";
			$homelinks=4;

				
				$result_newsindex_others = $db->sql_query("SELECT id, title FROM {$prefix}_question WHERE active=1 AND id!=$idnewind AND (catid=$catid or catid in(SELECT catid FROM {$prefix}_question_cat WHERE parent=$catid)) ORDER BY time DESC LIMIT $homelinks");
				if($db->sql_numrows($result_newsindex_others) > 0) 
				{
					echo "<div class=\"box-home-more\"><ul>";
					while(list($idotherindex, $titleotherindex) = $db->sql_fetchrow($result_newsindex_others)) 
					{
						$rwtitleotherindex = utf8_to_ascii(url_optimization($titleotherindex));
						$url_news_other =url_sid("index.php?f=question&do=detail&id=$idotherindex");
						echo "<li><a title=\"$titleotherindex\" href=\"$url_news_other\">".CutString($titleotherindex,85)."</a></li>";
					}
					echo "</ul></div>";
				} 
				else 
				{
					echo"";
				}
				
			}
			echo "</div>";

				if($i==2)
				{
					echo "<div class=\"cl\"></div>";
					$i=0;
				}
				echo "</div>";
		}
	} 
	else 
	{
		OpenTable();
		echo "<center>"._NODATA."</center>";
		CloseTable();
	}
CloseTab();

include_once("footer.php");

?>