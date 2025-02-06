<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

$id = isset($_GET['id']) ? $_GET['id'] : $_POST['id'];
$result = $db->sql_query("SELECT question, votes, alanguage, anwsers, options FROM ".$prefix."_survey WHERE id='$id'");

if(empty($id) || $db->sql_numrows($result) != 1) die();

list($title, $votes1, $alanguage, $anwsers1, $numas) = $db->sql_fetchrow($result);	
if($numas > 10) { $numoption = 20; }else{ $numoption = 10; }
$anwsers = @explode("|",$anwsers1);
$votes = @explode("|",$votes1);

$stop = "";
$numqs ="";
if(isset($_POST['subup']) && $_POST['subup'] == 1) {
	$title = nospatags($_POST['title']);
	$anwsers = $_POST['anwsers'];
	
	if($title == "") {
		$stop .="- "._ERROR1."<br/>";
		$err = 1;
	}
	
	$votesup_arr ="";
	$totalvotes = 0;
	for($i =0; $i < sizeof($anwsers); $i ++) {
		if($anwsers[$i] !="") {
			$anwsers_vl[] = $anwsers[$i];	
			$votesup_arr[] = $votes[$i];
			$totalvotes = $totalvotes+$votes[$i];
		}	
	}
	
	if(sizeof($anwsers_vl) < 2) {
		$stop .="- "._ERROR2."<br/>";
		$err = 1;
	}	
	if(!$err) {	
		$anwsers_up = @implode("|",$anwsers_vl);
		$votes_up = @implode("|",$votesup_arr);
		$db->sql_query("UPDATE ".$prefix."_survey SET question='$title', votes='$votes_up', totalvotes='$totalvotes', anwsers='$anwsers_up', options='".sizeof($anwsers_vl)."', alanguage='$alanguage' WHERE id='$id'");
		header("Location: modules.php?f=".$adm_modname."");
		exit;
	}
}	

include("page_header.php");

if($stop) {
echo "<table align=\"center\" border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
echo "<tr><td class=\"header\">"._ERROR."</td></tr>";
echo "<tr><td class=\"row1\">$stop</td></tr>";
echo "</table><br/>";	
}


echo "<form action=\"modules.php?f=$adm_modname&do=$do&id=$id\" method=\"POST\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" class=\"tableborder\">\n";
echo "<tr><td class=\"header\" colspan=\"2\">"._SVEDIT."</td></tr>";	
echo "<tr>\n";
echo "<td width=\"30%\" align=\"right\" class=\"row1\"><b>"._QUESTION."</b></td>\n";
echo "<td class=\"row2\"><input type=\"text\" size=\"60\" name=\"title\" value=\"$title\"></td></tr>\n";
$a =0;
for($i = 0; $i < $numoption; $i ++) {
	$a = $i + 1;
	if(!isset($anwsers[$i])) { $anwsers[$i] =""; }
	echo "<tr>\n";
	echo "<td class=\"row1\" align=\"right\"><b>"._ANWSER." $a:</b></td>\n";
	echo "<td class=\"row2\"><input type=\"text\" size=\"60\" name=\"anwsers[]\" value=\"".$anwsers[$i]."\"></td>\n";
	echo "</tr>\n";
}	
echo "<input type=\"hidden\" name=\"numqs\" value=\"$numqs\">";
echo "<tr><td class=\"row3\">&nbsp;</td><td class=\"row3\"><input type=\"hidden\" name=\"subup\" value=\"1\"><input type=\"submit\" name=\"submit\" value=\""._SAVECHANGES."\" class=\"button2\"></td></tr>";
echo "</table></form>";

include_once("page_footer.php");
?>