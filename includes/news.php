<?php
if ((!defined('CMS_SYSTEM')) AND (!defined('CMS_ADMIN'))) { die(); }
if(!defined('CMS_CONFIG')) { die(); }

function newsstart() {
	global $db, $prefix, $currentlang, $path_upload;
	include(DATAFOLD."/config_news.php");
	
	$result = $db->sql_query("SELECT id, title, link, time, images, description, imgtext FROM ".$prefix."_start_list WHERE active='1' AND alanguage='$currentlang' ORDER BY weight DESC LIMIT 6");
	if($db->sql_numrows($result) > 0) {
		?>
		<script language="javascript">
		window.onload = initTopStoryBox;

		function focusTopStory (n) {
		  for (i = 0; i < numTopStories; i++) {
		    if (i == n) {
		      topStoryTeasers[i].style.zIndex = '1';
		      topStoryIntros[i].style.zIndex = '1';
		      topStoryHeadlines[i].style.background = 'url(images/top-story-bg-hover.gif) left no-repeat';
		      topStoryHeadlines[i].style.color = 'white';
		    } else {
		      topStoryTeasers[i].style.zIndex = '0';
		      topStoryIntros[i].style.zIndex = '0';
		      topStoryHeadlines[i].style.background = 'url(images/top-story-bg.gif) left no-repeat';
		      topStoryHeadlines[i].style.color = '#2967c2';
		    }
		  }
		}
		
		function rotateTopStory () {
		  focusTopStory(currentTopStory);
		  currentTopStory = (currentTopStory + 1) % numTopStories;
		  topStoryTimer = setTimeout("rotateTopStory()", 3000);
		}
		
		function mouseoverTopStory (n) {
		  clearTimeout(topStoryTimer);
		  focusTopStory(n - 1);
		}
		
		function mouseoutTopStory (n) {
		  currentTopStory = n - 1;
		  clearTimeout(topStoryTimer);
		  topStoryTimer = setTimeout("rotateTopStory()", 3000)
		}
		
		function initTopStoryBox () {
		  var topStoryBox = document.getElementById('top-stories');
		  var topStoryContainer = topStoryBox.getElementsByTagName('ul');
		  topStoryTeasers = topStoryBox.getElementsByTagName('img');
		  topStoryIntros = topStoryBox.getElementsByTagName('p');
		  topStoryHeadlines = topStoryContainer[0].getElementsByTagName('a');
		  numTopStories = topStoryTeasers.length;
		  currentTopStory = 0;
		  rotateTopStory();
		}
		</script>
		<?php	
		$img_arr = $news_li = $news_p ="";
		$i =0;
		while(list($id, $title, $link, $time, $images, $hometext, $imgtext) = $db->sql_fetchrow($result)) {
			$i ++;
			$title = cutText($title,50);
			$hometext = strip_tags($hometext,"<br/>");
			$path_img = "$path_upload/start";
			$img_arr .= "<a href=\"".url_sid($link)."\"><img border=\"0\" src=\"$path_img/$images\" alt=\"$imgtext\" width=\"160\" height=\"200\" style=\"z-index: 1\" /></a>\n";	
			$news_li .= "<li><a href=\"".url_sid($link)."\" onmouseover=\"mouseoverTopStory($i)\" onmouseout=\"mouseoutTopStory($i)\">$title</a></li>\n";
			$news_p .= "<p><a href=\"".url_sid($link)."\" onmouseover=\"mouseoverTopStory($i)\" onmouseout=\"mouseoutTopStory($i)\">$hometext</a></p>\n";
		}	
		echo "<div id=\"top-stories\">\n";
		echo $img_arr;
		echo "	<div>\n";
		echo "		<ul>\n";
		echo $news_li;
		echo "		</ul>\n";
		echo $news_p;
		echo "	</div>\n";
		echo "</div>\n";
	}
}
?>