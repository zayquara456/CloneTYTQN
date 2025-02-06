<?php
if (!defined('CMS_SYSTEM')) header("Location: index.php");

if(file_exists("data/config_news.php")) require("data/config_news.php");
if(file_exists(DATAFOLD."/config_surveys.php")) require(DATAFOLD."/config_surveys.php");
$content ="";
global $path_upload, $mod_name, $id, $default_temp;
global $db, $prefix, $currentlang, $module_name, $Default_Temp;
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
?>

<?php
$result_lastnew = $db->sql_query("SELECT id, title,content, time FROM ".$prefix."_question WHERE  active='1' ORDER BY time DESC LIMIT 3");
$numrows = $db->sql_numrows($result_lastnew);
if($numrows > 0) {
	$a=0;
?>
<div class="div-block">
<div class="div-tblock">
	<div class="fl"><?php echo $correctArr[1];?></div>
	<div class="fr">
		<!--<div class="nav"><a id="prev2" href="#">Prev</a> <a id="next2" href="#">Next</a></div>-->
	</div>
	<div class="cl"></div>
</div>
	<div class="div-cblock">
<?php
	//echo "<div id="s2"><div>";
	//
	while(list($idlast, $titlelast,$contentlast, $time) = $db->sql_fetchrow($result_lastnew)) {
		$rwtitlelast = utf8_to_ascii(url_optimization($titlelast));
		$url_question_detail =url_sid("index.php?f=question&do=detail&id=$idlast");
		//echo "<tr>";
		$a++;
		if($a%2==0){$background="background:#F9F1E5";}
		else{$background="background:#ffffff";}
		echo "<div class=\"question\" style=\"$background;padding: 5px\">\n";
		echo "<div class=\"question-title\"><a href=\"$url_question_detail\">$titlelast</a></div>\n";
		//echo "</tr>";
		//echo "<tr><td colspan=\"2\" height=\"4\"></td></tr>";
		//if ($a < $numrows) echo "<tr><td colspan=\"3\"><hr size=\"0\" style=\"margin:2px;border-top: 1px dotted #f3f3f3\"></td></tr>";
		$resul_answer = $db->sql_query("SELECT id, content, time, name, email FROM ".$prefix."_answer WHERE active=1 AND qid=$idlast  ORDER BY time DESC ");
if($db->sql_numrows($resul_answer) > 0) {
	
	list($idanswer,$contentanswer, $timeanswer, $nameanswer, $emailanswer) = $db->sql_fetchrow($resul_answer);
	$contentlast = preg_replace("/<.*?>/", "", $contentlast);
		echo "<div class=\"question-content\" align=\"justify\">".CutString($contentlast,255)."</div>";
	
}
		//echo "<tr><td class=\"question_content\">$contentlast</td></tr>";
		echo "</div>";
		//if($a==3 || $a==6){echo "</div><div>";}
		//elseif($a==9){echo "</div>";}
	}
	
	echo '	</div><div class="clearfix"></div>';
		//echo "</div>\n";
		echo "</div>\n";
	 echo "<div class=\"cl\"></div>\n";
	 
	//echo "<div><a href=\"index.php?f=question\" title=\"danh sách câu hỏi\"><img src=\"templates/$Default_Temp/images/danhsachcauhoi.jpg\"></a>&nbsp;<a href=\"index.php?f=question&do=create\" title=\"đăng câu hỏi\"><img src=\"templates/$Default_Temp/images/submit_faq.jpg\"></a></div>";
	
	
}
?>
