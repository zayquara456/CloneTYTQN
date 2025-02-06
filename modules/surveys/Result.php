<?php
if (!defined('CMS_SYSTEM')) {
    die();
}

$timecheck = time() - $sv_timecorrect*3600;
$db->sql_query("DELETE FROM ".$prefix."_survey_check WHERE vottime<=$timecheck");

$id = intval($_GET['id']);
$result = $db->sql_query("SELECT question, votes, anwsers, options, totalvotes, time FROM ".$prefix."_survey WHERE active='1' AND id='$id' AND alanguage='$currentlang'");
if(empty($id) || $db->sql_numrows($result) != 1) {
	die();
}	

list($title, $votes, $anwsers, $options, $totalvotes, $time) = $db->sql_fetchrow($result);
$anwsers_ar = @explode("|",$anwsers);
$votes_ar = @explode("|",$votes);

if(isset($_GET['vote'])) { $vote = $_GET['vote']; } else { $vote =""; }
if($vote !="") {
	$vote = intval($vote);
	$clientgs = useragentrs();
	$check = $db->sql_numrows($db->sql_query("SELECT*FROM ".$prefix."_survey_check WHERE agent='$clientgs' AND vid='$id'"));
	if($check > 0) {
		header("Location: ".url_sid("index.php?f=$module_name&do=result&id=$id")."");
		exit;	
	} else {	
		$voteup_arr ="";
		for($i =0; $i < sizeof($anwsers_ar); $i ++) {
			if($i == $vote) {
				$voteup_arr[] = $votes_ar[$i]+1;
			} else {
				$voteup_arr[] = $votes_ar[$i];
			}	
		}
		$voteup = @implode("|",$voteup_arr);
		$db->sql_query("UPDATE ".$prefix."_survey SET votes='$voteup', totalvotes=totalvotes+1 WHERE id='$id'");
		$db->sql_query("INSERT INTO ".$prefix."_survey_check (vottime, vid, agent) VALUES ('".TIMENOW."', '$id', '$clientgs')");	
		header("Location: ".url_sid("index.php?f=$module_name&do=result&id=$id")."");
		exit;
	}
} else {
$db->sql_query("UPDATE ".$prefix."_survey SET hits=hits+1 WHERE id='$id'");	
echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">";
echo "<head>";
echo "<meta http-equiv=\"Content-Language\" content=\"en-us\">";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset="._CHARSET."\">";
echo "<title>$sitename - "._MODTITLE."</title>";
echo "<link rel=\"StyleSheet\" href=\"css/".$Default_Temp.".css\" type=\"text/css\">";
echo "<style>";
echo "<!--";
echo ".SvTitle { font-family: 'Times New Roman'; font-size: 14pt; font-weight: bold; margin-top: 0; color: #FFF }";
echo ".SvContent { font-family: 'Verdana'; font-size: 10pt; font-weight: bold; color: #000000; text-decoration: none }";
echo "-->";
echo "</style>";
echo "</head>";
echo "<body style=\"margin: 5px\" bgcolor=\"#00325C\">";	
echo "$vote<table width=\"100%\" cellspacing=0 cellpadding=0 border=0>";
echo "<tr><td align=right style=\"font-family: 'Verdana'; font-size: 8pt; color: #ffffff; text-decoration: none\">".NameDay($time).", ".ext_time($time,2)." GMT+7</td></tr>";
echo "<tr><td class=\"SvTitle\">$title</td></tr>";
echo "</table>";
echo "<table border=0 cellspacing=1 cellpadding=3 width=\"100%\">";
for($i =0; $i < $options; $i ++) {
	$percent = @round($votes_ar[$i]*100/$totalvotes);
	$culac1 = $percent*0.8;
	$widthvote = $percent+$culac1;
	$bgcolor = backgroundvote($percent);
	echo "<tr bgcolor=\"#FFFFFF\"><td width=\"50%\" class=\"SvContent\"><b>$anwsers_ar[$i]</b></td><td width=\"150\">";
	echo "<table cellspacing=0 cellpadding=0 border=0 align=left height=20><tr><td width=$widthvote bgcolor=\"$bgcolor\">&nbsp;</td><td class=SvContent>&nbsp;$percent%</td></tr></table>";
	echo "</td><td width=\"100\" align=\"right\" class=\"SvContent\">$votes_ar[$i] "._ANWSER."</td></tr>";
}
echo "<tr bgcolor=\"#FFFFFF\"><td align=\"right\" colspan=\"3\" class=\"SvContent\">"._TOTALVOTE.": $totalvotes "._ANWSER."</td></tr>";	
echo "</table>";		
echo "<table width=\"100%\" cellspacing=0 cellpadding=0 border=0>";
echo "<tr>";
echo "<td height=20 valign=bottom></td>";
echo "<td height=20 valign=bottom align=right><a href=\"JavaScript:window.close()\"><font color=\"#ffffff\">["._BACK."]</font></a></td>";
echo "</tr>";
echo "";
echo "";
echo "</table>";

echo "</body></html>";
}	

function backgroundvote($p) {
	if($p >= 90) { $bg = "#FF0000";}
	elseif ($p >= 50 && $p < 90) { $bg = "#FF3300"; }
	elseif ($p >= 30 && $p < 50) { $bg = "#0C186D"; }
	elseif ($p >= 20 && $p < 30) { $bg = "#2CC603"; }
	elseif ($p >= 10 && $p < 20) { $bg = "#9999FF"; }
	else{ $bg = "#FF0080"; }
	
	return $bg;
}		


?>