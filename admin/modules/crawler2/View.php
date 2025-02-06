<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");
include("class/Crawler.php");
include('class/class.autokeyword.php');
$id = isset($_GET["id"]) ? $_GET["id"] : 0;
$result = $db->sql_query("SELECT id, title, source, data, time, status FROM {$prefix}_ngrab_filter WHERE id=$id");
if($db->sql_numrows($result) != 1) 
{
	echo"Not found!";
}
else
{
	list($title, $alanguage, $source, $data, $time, $status ) = $db->sql_fetchrow($result);
	include("data/".$data);
	echo "source: ".$source_view."";
	echo '
	<!-- Progress bar holder -->
<div id="progress" style="width:500px;border:1px solid #ccc; text-align:right; ">0%</div>
<!-- Progress information -->
<div id="information" style="width"></div>';
	echo "<table border=1>
			";
	$newscrawler = new Crawler("../files/news","jpg,bmp,jpeg,png",99999999999);	
	$html =	$newscrawler->runBrowser($source_view);
	$html = preg_replace($begin,'',$html);// remove doan tren
	$html = preg_replace($end,'',$html);// Remove doan duoi
	
	if (!empty($html)){
		if(preg_match_all($reg["page"], $html, $pages))
		{
		
		}
		//return $pages[0];
  		else 
		{
			echo "not fount!"; 
			exit();
		}
		
	}
	else
	{
		echo "html ko ton tai";
	}
		for ($i=0;$i<1;$i++) { 
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
		echo "<tr><td>title</td><td>".$pages[$page["title"]][$i]."</td></tr>";
		echo "<tr><td>link</td><td>".$newscrawler->chuanhoaUrl($pages[$page["link"]][$i],$source)."</td></tr>";
		echo "<tr><td>desc</td><td>".$tempcrawler["description"]."</td></tr>";
		echo "<tr><td>content</td><td>".$tempcrawler["content"]."</td></tr>";
	$params['content'] = $tempcrawler["content"]; //page content
	//set the length of keywords you like
	$params['min_word_length'] = 5;  //minimum length of single words
	$params['min_word_occur'] = 100;  //minimum occur of single words
	
	$params['min_2words_length'] = 3;  //minimum length of words for 2 word phrases
	$params['min_2words_phrase_length'] = 10; //minimum length of 2 word phrases
	$params['min_2words_phrase_occur'] = 5; //minimum occur of 2 words phrase
	
	$params['min_3words_length'] = 3;  //minimum length of words for 3 word phrases
	$params['min_3words_phrase_length'] = 10; //minimum length of 3 word phrases
	$params['min_3words_phrase_occur'] = 2; //minimum occur of 3 words phrase
	
	$keyword = new autokeyword($params, "utf-8");
	echo "<tr><td>tags</td><td>";
	
	
	echo $keyword->get_keywords();
	echo ", ".$tempcrawler["tag"]."</td></tr>";
	}
echo "</table>";
echo '<script language="javascript">
document.getElementById("information").innerHTML="Process completed";
document.getElementById("progress").innerHTML="<div style=\"width:100%;background-color:#ddd;text-align:right; \">100%&nbsp;</div>";
</script>';
}


//echo $begin;
?>