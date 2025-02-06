<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html lang="en">
<head>
    <title>News crawler</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />	
</head>
<body>
<!-- Progress bar holder -->
<div id="progress" style="width:500px;border:1px solid #ccc; text-align:right; ">0%</div>
<!-- Progress information -->
<div id="information" style="width"></div>
<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");
include("class/Crawler.php");
include('class/class.autokeyword.php');
//include('../../includes/functions.php');
$id = isset($_GET["id"]) ? $_GET["id"] : 0;
$result = $db->sql_query("SELECT cron_name, cron_url, alanguage, cat_id, filter_id FROM {$prefix}_ngrab_cron WHERE id=$id");
if($db->sql_numrows($result) != 1) 
{
	echo"Not found!";
}
else
{
	list($cron_name, $cron_url, $alanguage, $cat_id, $filter_id) = $db->sql_fetchrow($result);
	$resultfilter = $db->sql_query("SELECT id, title, source, data, time, status FROM {$prefix}_ngrab_filter WHERE id=$filter_id");
	if($db->sql_numrows($resultfilter) != 1) 
	{
		echo"Not found!";
	}
	else
	{
		list($title, $alanguage, $source, $data, $time, $status ) = $db->sql_fetchrow($resultfilter);
		include("data/".$data);
		echo "source: ".$cron_url."";
		echo "<table border=1><tr><td>"._TITLE."</td><td>"._STATUS."</td></tr>";
		//foreach($url as $value)
		//{
			$get_path = get_path(time());
			$newscrawler = new Crawler("../files/news"."/".$get_path."/","jpg,bmp,jpeg,png",99999999999);	
			$html =	$newscrawler->runBrowser($cron_url);
			$html = preg_replace($begin,'',$html);// remove doan tren
			$html = preg_replace($end,'',$html);// Remove doan duoi
			if (!empty($html))
			{
				if(!preg_match_all($reg["page"], $html, $pages))
				{
					echo " page not fount!"; 
					exit();
				}
				//preg_match_all($reg["page"], $html, $pages);
			}
			else
			{
				echo "html ko ton tai";
			}
				//for ($i=0;$i<1;$i++) { 
				for ($i=0;$i<count($pages[0]);$i++) 
				{ 
					// Calculate the percentation
					$percent = intval($i/count($pages[0]) * 100)."%";
				 
					// Javascript for updating the progress bar and information
					echo '<script language="javascript">
		document.getElementById("progress").innerHTML="<div style=\"width:'.$percent.';background-color:#ddd; text-align:right; \">'.$percent.'&nbsp;</div>";
		document.getElementById("information").innerHTML="'.$i.' row(s) processed.";
		</script>';
					
				 
					// This is for the buffer achieve the minimum size in order to flush data
					echo str_repeat(' ',1024*64);
				 
					// Send output to browser immediately
					flush();
				 
					// Sleep one second so we can see the delay
					sleep(1);
					//echo $links[0][$i]."<br>";
					$tempcrawler = $newscrawler->getPost($newscrawler->chuanhoaUrl($pages[$page["link"]][$i],$source), $replace, $reg, $page);
					$_clink=$newscrawler->chuanhoaUrl($pages[$page["link"]][$i],$source);
					$resultck = $db->sql_query("SELECT cron_id, content_id, link_detail FROM {$prefix}_ngrab_usage WHERE link_detail='$_clink'");
					if($db->sql_numrows($resultck) != 1) 
					{
						//cap nhat bai viet
						$permalink=url_optimization(trim($pages[$page["title"]][$i]));
						$news_name=$escape_mysql_string(trim($pages[$page["title"]][$i]));
						$news_quote=$escape_mysql_string(trim($tempcrawler["description"]));
						$news_content=$escape_mysql_string(trim($tempcrawler["content"]));
						$images = $tempcrawler["images"];
						$query = "INSERT INTO {$prefix}_news (catid, title, permalink, alanguage, hometext, bodytext, seo_title, seo_description,  news_type, images, imgtext, active, source, imgshow, othershow, image_highlight, hits, nstart, special, time) VALUES ($cat_id, '$news_name', '$permalink','$currentlang', '$news_quote', '$news_content', '$news_name', '$news_quote', 'news_type', '$images', '', 0, '$source', 0, '0', 0, 0, 0, 0, ".time().")";
						$result = $db->sql_query($query);
						//die($query);
						list ($xid) = $db->sql_fetchrow($db->sql_query("SELECT MAX(id) AS id FROM ".$prefix."_news"));
						$guid="index.php?f=news&do=detail&id=$xid";
						$query = "UPDATE {$prefix}_news SET guid='$guid' WHERE id='$xid'";
						$db->sql_query($query);
						//cap nhat tin da lay
						$link_detail= $newscrawler->chuanhoaUrl($pages[$page["link"]][$i],$source);
						$query = "INSERT INTO {$prefix}_ngrab_usage (cron_id, cdate, link_detail, content_id, mdate) VALUES ($id,  ".time().", '".$link_detail."', $xid,  ".time().")";
						
						$result = $db->sql_query($query);
						$_status="<span style=\"color:#ff0000\">"._THANH_CONG."</span>";
					}
					else
					{
						$_status="<span style=\"color:#000000\">"._DA_TON_TAI."</span>";
					}
					
					echo "<tr><td>".$pages[$page["title"]][$i]."</td><td>".$_status."</td></tr>";
					//echo "<tr><td>link</td><td>".$newscrawler->chuanhoaUrl($pages[$page["link"]][$i],$source)."</td></tr>";
					//echo "<tr><td>desc</td><td>".$tempcrawler["description"]."</td></tr>";
					//echo "<tr><td>content</td><td>".$tempcrawler["content"]."</td></tr>";
	//				$params['content'] = $tempcrawler["content"]; //page content
//					//set the length of keywords you like
//					$params['min_word_length'] = 5;  //minimum length of single words
//					$params['min_word_occur'] = 100;  //minimum occur of single words
//					
//					$params['min_2words_length'] = 3;  //minimum length of words for 2 word phrases
//					$params['min_2words_phrase_length'] = 10; //minimum length of 2 word phrases
//					$params['min_2words_phrase_occur'] = 5; //minimum occur of 2 words phrase
//					
//					$params['min_3words_length'] = 3;  //minimum length of words for 3 word phrases
//					$params['min_3words_phrase_length'] = 10; //minimum length of 3 word phrases
//					$params['min_3words_phrase_occur'] = 2; //minimum occur of 3 words phrase
//					
//					$keyword = new autokeyword($params, "utf-8");
					//echo "<tr><td>tags</td><td>";
					
					
					//echo $keyword->get_keywords();
					//echo ", ".$tempcrawler["tag"]."</td></tr>";
				}
		//}
		echo "</table>";
		echo '<script language="javascript">
document.getElementById("information").innerHTML="Process completed";
document.getElementById("progress").innerHTML="<div style=\"width:100%;background-color:#ddd;text-align:right; \">100%&nbsp;</div>";
</script>';
	}
}
?> 
</body>
</html>