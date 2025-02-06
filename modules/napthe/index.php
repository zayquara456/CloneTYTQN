<?php
if (!defined('CMS_SYSTEM')) die();

$title_home = "<a href=\"".url_sid("index.php")."\">"._HOMEPAGE."</a> &rsaquo; <a href=\"".url_sid("index.php?f=question")."\">"._HOI_DAP."</a>";
$sitelinkmap=$title_home;
include_once("header.php");
OpenTab($title_home);
?>
<style type="text/css">
.tabbox{width:455px; float:left}
</style>
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
			echo "<div class=\"boxca-title\"><h2><a href=\"$url_news_cat\">$titlecat</a></h2></div>\n";
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
					echo "<div class=\"boxca-img\"><img title=\"$titlenewind\" alt=\"$titlenewind\" src=\"$urlsite/images/logo.gif\"/></div>";
					echo "<div class=\"boxcca-title\"><a href=\"$url_news_detail\">$titlenewind</a></div>";
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

?>
<style>

#advfixedfloat { /* required to avoid jumping */
  width:210px;
  float:left;
  margin-left:10px;
}

#advright {

  top: 0;
  margin-top: 0px;
}


#advright ol li {
  border-top: 1px solid purple;
}

#advright ol li:first-child {
  border-top: 0;
}

#advright.fixed {
  position: fixed;
  top: 0;
}
</style>

<script>
$(function () {
  
  var msie6 = $.browser == 'msie' && $.browser.version < 7;
  
  if (!msie6) {
    var top = $('#advright').offset().top - parseFloat($('#advright').css('margin-top').replace(/auto/, 0));
    $(window).scroll(function (event) {
      // what the y position of the scroll is
      var y = $(this).scrollTop();
      
      // whether that's below the form
      if (y >= top) {
        // if so, ad the fixed class
        $('#advright').addClass('fixed');
      } else {
        // otherwise remove it
        $('#advright').removeClass('fixed');
      }
    });
  }  
});
</script>
<div id="advfixedfloat">
      <div id="advright">
<?
echo "<div class=\"adv-right\">";
echo advertising(10);
echo "</div>      </div>
    </div>";
include_once("footer.php");

?>